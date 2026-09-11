<?php

namespace App\Controllers\Murid;

use App\Controllers\BaseController;
use App\Models\PackageModel;
use App\Models\UserModel;
use App\Models\TryoutModel;
use App\Models\QuestionModel;
use App\Models\OptionModel;

class Soal extends BaseController
{
    protected $packageModel;
    protected $userModel;
    protected $tryoutModel;
    protected $questionModel;
    protected $optionModel;

    public function __construct()
    {
        $this->packageModel  = new PackageModel();
        $this->userModel     = new UserModel();
        $this->tryoutModel   = new TryoutModel();
        $this->questionModel = new QuestionModel();
        $this->optionModel   = new OptionModel();
    }

    // Prompt 25 — Menu Paket Soal (Gateway to Free & Premium)
    public function index()
    {
        $userId = session()->get('user_id');
        $student = $this->userModel->getStudentDetail($userId);

        return view('murid/soal/index', [
            'title'     => 'Paket Soal - TeKaPe.id',
            'activeNav' => 'soal',
            'isPremium' => $student['is_premium'] ?? 0,
        ]);
    }

    // Prompt 26 — Murid Paket Free
    public function free()
    {
        $userId = session()->get('user_id');
        $search = $this->request->getGet('q');
        $filter = $this->request->getGet('category');

        $packages = $this->packageModel->getPackagesWithType('free');

        if ($search) {
            $packages = array_filter($packages, fn($p) => stripos($p['title'], $search) !== false);
        }

        // Attach completion and latest score
        foreach ($packages as &$p) {
            $lastTryout = $this->tryoutModel->where('user_id', $userId)->where('package_id', $p['id'])->orderBy('id', 'DESC')->first();
            $p['completed'] = !empty($lastTryout);
            $p['latest_score'] = $lastTryout ? $lastTryout['final_score'] : null;
        }

        return view('murid/soal/free', [
            'title'     => 'Paket Free - TeKaPe.id',
            'activeNav' => 'soal',
            'packages'  => $packages,
            'search'    => $search,
            'filter'    => $filter,
        ]);
    }

    // Prompt 27 — Murid Paket Premium
    public function premium()
    {
        $userId = session()->get('user_id');
        $student = $this->userModel->getStudentDetail($userId);
        $isPremium = $student['is_premium'] ?? 0;
        $expiryDate = $student['premium_expiry'] ?? null;

        $packages = $this->packageModel->getPackagesWithType('premium');

        foreach ($packages as &$p) {
            $lastTryout = $this->tryoutModel->where('user_id', $userId)->where('package_id', $p['id'])->orderBy('id', 'DESC')->first();
            $p['completed'] = !empty($lastTryout);
            $p['latest_score'] = $lastTryout ? $lastTryout['final_score'] : null;
        }

        return view('murid/soal/premium', [
            'title'      => 'Paket Premium - TeKaPe.id',
            'activeNav'  => 'soal',
            'packages'   => $packages,
            'isPremium'  => $isPremium,
            'expiryDate' => $expiryDate,
        ]);
    }

    // Prompt 30 — Mengerjakan Soal (CBT Exam Interface)
    public function kerjakan($packageId)
    {
        $userId = session()->get('user_id');
        $package = $this->packageModel->getPackageWithQuestions($packageId);

        if (!$package) {
            return redirect()->to(base_url('murid/soal'))->with('error', 'Paket soal tidak ditemukan.');
        }

        // Check premium gate
        if ($package['type'] === 'premium') {
            $student = $this->userModel->getStudentDetail($userId);
            if (empty($student['is_premium'])) {
                return redirect()->to(base_url('murid/payment'))->with('error', 'Paket ini khusus member Premium. Silakan berlangganan terlebih dahulu.');
            }
        }

        if (empty($package['questions'])) {
            return redirect()->back()->with('error', 'Paket soal ini belum memiliki butir soal.');
        }

        return view('murid/soal/kerjakan', [
            'title'         => 'Try Out: ' . esc($package['title']),
            'package'       => $package,
            'hideHeader'    => true,
            'hideBottomNav' => true,
        ]);
    }

    // Submit CBT Exam & Calculate Results
    public function submitUjian($packageId)
    {
        $userId = session()->get('user_id');
        $package = $this->packageModel->getPackageWithQuestions($packageId);
        if (!$package) return redirect()->to(base_url('murid/soal'));

        $postAnswers = $this->request->getPost('answers') ?? [];
        $startTime = $this->request->getPost('start_time') ?? date('Y-m-d H:i:s');
        $endTime = date('Y-m-d H:i:s');
        $duration = max(60, strtotime($endTime) - strtotime($startTime));

        $db = \Config\Database::connect();

        // Calculate category scores
        $categoryScores = ['TWK' => 0, 'TIU' => 0, 'TKP' => 0];
        $correctCount = 0;
        $incorrectCount = 0;

        $answersToSave = [];

        foreach ($package['questions'] as $q) {
            $qId = $q['id'];
            $catCode = $q['category_code'] ?? 'TWK';
            $userAns = $postAnswers[$qId] ?? '';

            $scoreEarned = 0;
            $isCorrect = 0;

            if ($q['type'] === 'pilihan_ganda') {
                foreach ($q['options'] as $opt) {
                    if ($opt['option_label'] === $userAns) {
                        $scoreEarned = (float) $opt['score'];
                        if (!empty($opt['is_correct']) || ($catCode === 'TKP' && $scoreEarned >= 4)) {
                            $isCorrect = 1;
                        }
                        break;
                    }
                }
            } else {
                // Isian singkat
                if (strcasecmp(trim($userAns), trim($q['expected_answer'] ?? '')) === 0) {
                    $scoreEarned = 5.0;
                    $isCorrect = 1;
                }
            }

            if ($isCorrect) {
                $correctCount++;
            } else {
                $incorrectCount++;
            }

            if (isset($categoryScores[$catCode])) {
                $categoryScores[$catCode] += $scoreEarned;
            }

            $answersToSave[] = [
                'question_id'  => $qId,
                'user_answer'  => $userAns,
                'is_correct'   => $isCorrect,
                'score_earned' => $scoreEarned,
            ];
        }

        // Apply Formula: (Sum of active category scores) / active category count
        $activeCats = 0;
        $sumScore = 0;
        foreach ($categoryScores as $cCode => $val) {
            $activeCats++;
            $sumScore += $val;
        }
        $finalScore = $activeCats > 0 ? round($sumScore / $activeCats, 2) : 0;

        // Save session
        $sessionId = $this->tryoutModel->insert([
            'user_id'          => $userId,
            'package_id'       => $packageId,
            'status'           => 'completed',
            'start_time'       => $startTime,
            'end_time'         => $endTime,
            'duration_seconds' => $duration,
            'final_score'      => $finalScore,
            'twk_score'        => $categoryScores['TWK'] ?? 0,
            'tiu_score'        => $categoryScores['TIU'] ?? 0,
            'tkp_score'        => $categoryScores['TKP'] ?? 0,
            'correct_count'    => $correctCount,
            'incorrect_count'  => $incorrectCount,
            'is_manual_edited' => 0,
            'created_at'       => $endTime,
        ]);

        // Save answer items
        foreach ($answersToSave as $ans) {
            $ans['session_id'] = $sessionId;
            $db->table('tryout_answers')->insert($ans);
        }

        return redirect()->to(base_url("murid/soal/hasil/{$sessionId}"));
    }

    // Prompt 31 — Hasil Try Out
    public function hasil($sessionId)
    {
        $session = $this->tryoutModel->getSessionWithAnswers($sessionId);
        if (!$session) {
            return redirect()->to(base_url('murid/soal'))->with('error', 'Hasil pengerjaan tidak ditemukan.');
        }

        $durationMin = round($session['duration_seconds'] / 60);

        return view('murid/soal/hasil', [
            'title'       => 'Hasil Try Out - TeKaPe.id',
            'activeNav'   => 'soal',
            'session'     => $session,
            'durationMin' => $durationMin,
        ]);
    }
}
