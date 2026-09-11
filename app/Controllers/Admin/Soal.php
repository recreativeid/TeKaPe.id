<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PackageModel;
use App\Models\CategoryModel;
use App\Models\QuestionModel;
use App\Models\OptionModel;
use App\Models\ScoringModel;
use App\Models\SettingModel;

class Soal extends BaseController
{
    protected $packageModel;
    protected $categoryModel;
    protected $questionModel;
    protected $optionModel;
    protected $scoringModel;
    protected $settingModel;

    public function __construct()
    {
        $this->packageModel  = new PackageModel();
        $this->categoryModel = new CategoryModel();
        $this->questionModel = new QuestionModel();
        $this->optionModel   = new OptionModel();
        $this->scoringModel  = new ScoringModel();
        $this->settingModel  = new SettingModel();
    }

    // Prompt 03 — Main Kelola Soal Gate
    public function index()
    {
        return view('admin/soal/index', [
            'title'     => 'Kelola Soal - TeKaPe.id',
            'activeNav' => 'soal',
            'role'      => session()->get('user_role'),
        ]);
    }

    // ==========================================
    // PAKET FREE
    // ==========================================

    // Prompt 04 — Paket Free Action Selector
    public function free()
    {
        $packages = $this->packageModel->getPackagesWithType('free');

        return view('admin/soal/free_index', [
            'title'     => 'Paket Soal Free - TeKaPe.id',
            'activeNav' => 'soal',
            'packages'  => $packages,
            'role'      => session()->get('user_role'),
        ]);
    }

    // Prompt 05 — Tambah Paket Free
    public function freeTambah()
    {
        return view('admin/soal/free_tambah', [
            'title'     => 'Tambah Paket Free - TeKaPe.id',
            'activeNav' => 'soal',
            'type'      => 'free',
            'role'      => session()->get('user_role'),
        ]);
    }

    // Prompt 06 — Edit Paket Free
    public function freeEdit($packageId = null)
    {
        $search = $this->request->getGet('q');
        $packages = $this->packageModel->getPackagesWithType('free');

        if ($search) {
            $packages = array_filter($packages, function($p) use ($search) {
                return stripos($p['title'], $search) !== false;
            });
        }

        $selectedPackage = null;
        if ($packageId) {
            $selectedPackage = $this->packageModel->getPackageWithQuestions($packageId);
        }

        return view('admin/soal/free_edit', [
            'title'           => 'Edit Paket Free - TeKaPe.id',
            'activeNav'       => 'soal',
            'packages'        => $packages,
            'selectedPackage' => $selectedPackage,
            'search'          => $search,
            'type'            => 'free',
            'role'            => session()->get('user_role'),
        ]);
    }

    // Prompt 07 — Sistem Penilaian Free
    public function freePenilaian($packageId = null)
    {
        $packages = $this->packageModel->getPackagesWithType('free');
        $activePackage = null;

        if ($packageId) {
            $activePackage = $this->packageModel->find($packageId);
        } elseif (!empty($packages)) {
            $activePackage = $packages[0];
        }

        $scoring = null;
        if ($activePackage) {
            $scoring = $this->scoringModel->where('package_id', $activePackage['id'])->first();
        }

        return view('admin/soal/free_penilaian', [
            'title'         => 'Sistem Penilaian Paket Free - TeKaPe.id',
            'activeNav'     => 'soal',
            'packages'      => $packages,
            'activePackage' => $activePackage,
            'scoring'       => $scoring,
            'type'          => 'free',
            'role'          => session()->get('user_role'),
        ]);
    }

    // ==========================================
    // PAKET PREMIUM
    // ==========================================

    // Prompt 08 — Paket Premium Action Selector
    public function premium()
    {
        $packages = $this->packageModel->getPackagesWithType('premium');
        $settings = $this->settingModel->getMap();

        return view('admin/soal/premium_index', [
            'title'           => 'Paket Soal Premium - TeKaPe.id',
            'activeNav'       => 'soal',
            'packages'        => $packages,
            'premiumPrice'    => $settings['premium_price'] ?? '149000',
            'premiumDuration' => $settings['premium_duration_days'] ?? '30',
            'activeCount'     => count($packages),
            'role'            => session()->get('user_role'),
        ]);
    }

    // Prompt 09 — Tambah Paket Premium
    public function premiumTambah()
    {
        $settings = $this->settingModel->getMap();
        return view('admin/soal/premium_tambah', [
            'title'           => 'Tambah Paket Premium - TeKaPe.id',
            'activeNav'       => 'soal',
            'defaultPrice'    => $settings['premium_price'] ?? '149000',
            'defaultDuration' => $settings['premium_duration_days'] ?? '30',
            'type'            => 'premium',
            'role'            => session()->get('user_role'),
        ]);
    }

    // Prompt 10 — Edit Paket Premium
    public function premiumEdit($packageId = null)
    {
        $search = $this->request->getGet('q');
        $packages = $this->packageModel->getPackagesWithType('premium');

        if ($search) {
            $packages = array_filter($packages, function($p) use ($search) {
                return stripos($p['title'], $search) !== false;
            });
        }

        $selectedPackage = null;
        if ($packageId) {
            $selectedPackage = $this->packageModel->getPackageWithQuestions($packageId);
        }

        return view('admin/soal/premium_edit', [
            'title'           => 'Edit Paket Premium - TeKaPe.id',
            'activeNav'       => 'soal',
            'packages'        => $packages,
            'selectedPackage' => $selectedPackage,
            'search'          => $search,
            'type'            => 'premium',
            'role'            => session()->get('user_role'),
        ]);
    }

    // Prompt 11 — Sistem Penilaian Premium
    public function premiumPenilaian($packageId = null)
    {
        $packages = $this->packageModel->getPackagesWithType('premium');
        $activePackage = null;

        if ($packageId) {
            $activePackage = $this->packageModel->find($packageId);
        } elseif (!empty($packages)) {
            $activePackage = $packages[0];
        }

        $scoring = null;
        if ($activePackage) {
            $scoring = $this->scoringModel->where('package_id', $activePackage['id'])->first();
        }

        return view('admin/soal/premium_penilaian', [
            'title'         => 'Sistem Penilaian Paket Premium - TeKaPe.id',
            'activeNav'     => 'soal',
            'packages'      => $packages,
            'activePackage' => $activePackage,
            'scoring'       => $scoring,
            'type'          => 'premium',
            'role'          => session()->get('user_role'),
        ]);
    }

    // ==========================================
    // ACTIONS & CRUD PROCESSING
    // ==========================================

    // Save New Package
    public function savePackage()
    {
        $type     = $this->request->getPost('type') ?? 'free';
        $title    = trim($this->request->getPost('title') ?? '');
        $desc     = trim($this->request->getPost('description') ?? '');
        $status   = $this->request->getPost('status') ?? 'active';
        $price    = (int) ($this->request->getPost('price') ?? 0);
        $duration = (int) ($this->request->getPost('duration_days') ?? 30);

        if (empty($title)) {
            return redirect()->back()->withInput()->with('error', 'Nama paket soal wajib diisi.');
        }

        $now = date('Y-m-d H:i:s');
        $packageId = $this->packageModel->insert([
            'title'         => $title,
            'description'   => $desc,
            'type'          => $type,
            'price'         => $price,
            'duration_days' => $duration,
            'status'        => $status,
            'created_by'    => session()->get('user_id'),
            'created_at'    => $now,
            'updated_at'    => $now,
        ]);

        // Create Default Categories
        $categories = [
            ['package_id' => $packageId, 'code' => 'TWK', 'name' => 'Tes Wawasan Kebangsaan', 'is_active' => 1, 'max_score' => 100, 'scoring_rule' => '5 per benar, 0 salah'],
            ['package_id' => $packageId, 'code' => 'TIU', 'name' => 'Tes Inteligensia Umum', 'is_active' => 1, 'max_score' => 100, 'scoring_rule' => '5 per benar, 0 salah'],
            ['package_id' => $packageId, 'code' => 'TKP', 'name' => 'Tes Karakteristik Pribadi', 'is_active' => 1, 'max_score' => 100, 'scoring_rule' => 'Skala 1 - 5'],
        ];
        foreach ($categories as $cat) {
            $this->categoryModel->insert($cat);
        }

        // Create Default Scoring Settings
        $this->scoringModel->insert([
            'package_id'     => $packageId,
            'formula_type'   => 'average_category',
            'twk_enabled'    => 1,
            'tiu_enabled'    => 1,
            'tkp_enabled'    => 1,
            'twk_score_rule' => '5 per benar',
            'tiu_score_rule' => '5 per benar',
            'tkp_score_rule' => 'Skala 1 - 5',
        ]);

        // Check if there are questions submitted with the package
        $questionJson = $this->request->getPost('questions_json');
        if ($questionJson) {
            $decodedQuestions = json_decode($questionJson, true);
            if (is_array($decodedQuestions)) {
                $db = \Config\Database::connect();
                $catMap = [];
                $cats = $this->categoryModel->where('package_id', $packageId)->findAll();
                foreach ($cats as $c) {
                    $catMap[$c['code']] = $c['id'];
                }

                foreach ($decodedQuestions as $num => $qData) {
                    $catCode = $qData['category'] ?? 'TWK';
                    $catId = $catMap[$catCode] ?? $cats[0]['id'];

                    $qId = $this->questionModel->insert([
                        'package_id'      => $packageId,
                        'category_id'     => $catId,
                        'question_number' => $num + 1,
                        'type'            => $qData['type'] ?? 'pilihan_ganda',
                        'narrative'       => $qData['narrative'] ?? '',
                        'image_url'       => $qData['image_url'] ?? null,
                        'expected_answer' => $qData['expected_answer'] ?? null,
                        'discussion'      => $qData['discussion'] ?? '',
                        'created_at'      => $now,
                        'updated_at'      => $now,
                    ]);

                    if (($qData['type'] ?? 'pilihan_ganda') === 'pilihan_ganda' && !empty($qData['options'])) {
                        foreach ($qData['options'] as $opt) {
                            $this->optionModel->insert([
                                'question_id'  => $qId,
                                'option_label' => $opt['label'],
                                'option_text'  => $opt['text'],
                                'score'        => (float)($opt['score'] ?? 0),
                                'is_correct'   => !empty($opt['is_correct']) ? 1 : 0,
                            ]);
                        }
                    }
                }
            }
        }

        $rolePrefix = session()->get('user_role') === 'tentor' ? 'tentor' : 'admin';
        $redirectUrl = $type === 'premium' ? "{$rolePrefix}/soal/premium" : "{$rolePrefix}/soal/free";
        return redirect()->to(base_url($redirectUrl))->with('success', "Paket {$title} berhasil disimpan!");
    }

    // Save Updated Package Details
    public function updatePackage($id)
    {
        $package = $this->packageModel->find($id);
        if (!$package) {
            return redirect()->back()->with('error', 'Paket tidak ditemukan.');
        }

        $title    = trim($this->request->getPost('title') ?? '');
        $desc     = trim($this->request->getPost('description') ?? '');
        $status   = $this->request->getPost('status') ?? 'active';
        $price    = (int) ($this->request->getPost('price') ?? $package['price']);
        $duration = (int) ($this->request->getPost('duration_days') ?? $package['duration_days']);

        $this->packageModel->update($id, [
            'title'         => $title,
            'description'   => $desc,
            'status'        => $status,
            'price'         => $price,
            'duration_days' => $duration,
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

        return redirect()->back()->with('success', 'Informasi paket berhasil diperbarui!');
    }

    // Add / Update a Single Question in Package
    public function saveQuestion()
    {
        $packageId  = $this->request->getPost('package_id');
        $questionId = $this->request->getPost('question_id');
        $catCode    = $this->request->getPost('category_code') ?? 'TWK';
        $type       = $this->request->getPost('type') ?? 'pilihan_ganda';
        $narrative  = trim($this->request->getPost('narrative') ?? '');
        $discussion = trim($this->request->getPost('discussion') ?? '');
        $expected   = trim($this->request->getPost('expected_answer') ?? '');

        // Find or create category for this package
        $category = $this->categoryModel->where('package_id', $packageId)->where('code', $catCode)->first();
        if (!$category) {
            $catId = $this->categoryModel->insert([
                'package_id'   => $packageId,
                'code'         => $catCode,
                'name'         => $catCode === 'TWK' ? 'Tes Wawasan Kebangsaan' : ($catCode === 'TIU' ? 'Tes Inteligensia Umum' : 'Tes Karakteristik Pribadi'),
                'is_active'    => 1,
                'max_score'    => 100,
                'scoring_rule' => 'Standard',
            ]);
        } else {
            $catId = $category['id'];
        }

        // Handle Image Upload if any
        $imageUrl = null;
        $img = $this->request->getFile('image');
        if ($img && $img->isValid() && !$img->hasMoved()) {
            $newName = $img->getRandomName();
            $img->move(FCPATH . 'uploads/questions', $newName);
            $imageUrl = base_url('uploads/questions/' . $newName);
        }

        $now = date('Y-m-d H:i:s');

        if ($questionId) {
            $dataUpdate = [
                'category_id'     => $catId,
                'type'            => $type,
                'narrative'       => $narrative,
                'expected_answer' => $expected,
                'discussion'      => $discussion,
                'updated_at'      => $now,
            ];
            if ($imageUrl) {
                $dataUpdate['image_url'] = $imageUrl;
            }
            $this->questionModel->update($questionId, $dataUpdate);

            // Update options if multiple choice
            if ($type === 'pilihan_ganda') {
                $this->optionModel->where('question_id', $questionId)->delete();
                $labels = ['A', 'B', 'C', 'D', 'E'];
                $correctLabel = $this->request->getPost('correct_option');
                foreach ($labels as $label) {
                    $optText  = $this->request->getPost("option_{$label}_text");
                    $optScore = (float) ($this->request->getPost("option_{$label}_score") ?? 0);
                    if ($optText !== null && $optText !== '') {
                        $this->optionModel->insert([
                            'question_id'  => $questionId,
                            'option_label' => $label,
                            'option_text'  => $optText,
                            'score'        => $optScore,
                            'is_correct'   => ($correctLabel === $label) ? 1 : 0,
                        ]);
                    }
                }
            }
            return redirect()->back()->with('success', 'Soal berhasil diperbarui!');
        } else {
            $highestNum = $this->questionModel->where('package_id', $packageId)->selectMax('question_number')->first();
            $qNum = ($highestNum['question_number'] ?? 0) + 1;

            $newQId = $this->questionModel->insert([
                'package_id'      => $packageId,
                'category_id'     => $catId,
                'question_number' => $qNum,
                'type'            => $type,
                'narrative'       => $narrative,
                'image_url'       => $imageUrl,
                'expected_answer' => $expected,
                'discussion'      => $discussion,
                'created_at'      => $now,
                'updated_at'      => $now,
            ]);

            if ($type === 'pilihan_ganda') {
                $labels = ['A', 'B', 'C', 'D', 'E'];
                $correctLabel = $this->request->getPost('correct_option');
                foreach ($labels as $label) {
                    $optText  = $this->request->getPost("option_{$label}_text");
                    $optScore = (float) ($this->request->getPost("option_{$label}_score") ?? 0);
                    if ($optText !== null && $optText !== '') {
                        $this->optionModel->insert([
                            'question_id'  => $newQId,
                            'option_label' => $label,
                            'option_text'  => $optText,
                            'score'        => $optScore,
                            'is_correct'   => ($correctLabel === $label) ? 1 : 0,
                        ]);
                    }
                }
            }
            return redirect()->back()->with('success', 'Soal baru berhasil ditambahkan!');
        }
    }

    // Delete Question
    public function deleteQuestion($id)
    {
        $question = $this->questionModel->find($id);
        if ($question) {
            $this->optionModel->where('question_id', $id)->delete();
            $this->questionModel->delete($id);
            return redirect()->back()->with('success', 'Soal berhasil dihapus.');
        }
        return redirect()->back()->with('error', 'Soal tidak ditemukan.');
    }

    // Move Question to Another Category
    public function moveQuestionCategory($questionId)
    {
        $targetCatCode = $this->request->getPost('target_category');
        $question = $this->questionModel->find($questionId);
        if (!$question) {
            return redirect()->back()->with('error', 'Soal tidak ditemukan.');
        }

        $category = $this->categoryModel->where('package_id', $question['package_id'])->where('code', $targetCatCode)->first();
        if ($category) {
            $this->questionModel->update($questionId, ['category_id' => $category['id']]);
            return redirect()->back()->with('success', "Soal berhasil dipindahkan ke kategori {$targetCatCode}!");
        }

        return redirect()->back()->with('error', 'Kategori tujuan tidak valid.');
    }

    // Save Scoring Settings (Formula & Weights)
    public function saveScoring()
    {
        $packageId   = $this->request->getPost('package_id');
        $twkEnabled  = $this->request->getPost('twk_enabled') ? 1 : 0;
        $tiuEnabled  = $this->request->getPost('tiu_enabled') ? 1 : 0;
        $tkpEnabled  = $this->request->getPost('tkp_enabled') ? 1 : 0;
        $twkRule     = $this->request->getPost('twk_rule') ?? '5 per benar';
        $tiuRule     = $this->request->getPost('tiu_rule') ?? '5 per benar';
        $tkpRule     = $this->request->getPost('tkp_rule') ?? 'Skala 1 - 5';

        $existing = $this->scoringModel->where('package_id', $packageId)->first();
        if ($existing) {
            $this->scoringModel->update($existing['id'], [
                'twk_enabled'    => $twkEnabled,
                'tiu_enabled'    => $tiuEnabled,
                'tkp_enabled'    => $tkpEnabled,
                'twk_score_rule' => $twkRule,
                'tiu_score_rule' => $tiuRule,
                'tkp_score_rule' => $tkpRule,
            ]);
        } else {
            $this->scoringModel->insert([
                'package_id'     => $packageId,
                'formula_type'   => 'average_category',
                'twk_enabled'    => $twkEnabled,
                'tiu_enabled'    => $tiuEnabled,
                'tkp_enabled'    => $tkpEnabled,
                'twk_score_rule' => $twkRule,
                'tiu_score_rule' => $tiuRule,
                'tkp_score_rule' => $tkpRule,
            ]);
        }

        return redirect()->back()->with('success', 'Sistem penilaian berhasil diperbarui!');
    }
}
