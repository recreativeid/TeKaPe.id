<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\TryoutModel;
use App\Models\AttendanceModel;
use App\Models\PackageModel;

class Murid extends BaseController
{
    protected $userModel;
    protected $tryoutModel;
    protected $attendanceModel;
    protected $packageModel;

    public function __construct()
    {
        $this->userModel       = new UserModel();
        $this->tryoutModel     = new TryoutModel();
        $this->attendanceModel = new AttendanceModel();
        $this->packageModel    = new PackageModel();
    }

    // Prompt 12 — Main Data Murid Navigation Screen
    public function index()
    {
        $db = \Config\Database::connect();
        $totalMurid = $this->userModel->where('role', 'murid')->countAllResults();
        $premiumMurid = $db->table('students_meta')->where('is_premium', 1)->countAllResults();
        $freeMurid = $totalMurid - $premiumMurid;
        $aktifMurid = $totalMurid;

        return view('admin/murid/index', [
            'title'        => 'Data Murid - TeKaPe.id',
            'activeNav'    => 'murid',
            'totalMurid'   => $totalMurid,
            'premiumMurid' => $premiumMurid,
            'freeMurid'    => $freeMurid,
            'aktifMurid'   => $aktifMurid,
        ]);
    }

    // Prompt 13 — Database Murid
    public function database()
    {
        $search = $this->request->getGet('q');
        $filter = $this->request->getGet('filter') ?? 'semua';

        $students = $this->userModel->getStudents();

        // Apply filters
        if ($filter === 'premium') {
            $students = array_filter($students, fn($s) => $s['is_premium'] == 1);
        } elseif ($filter === 'free') {
            $students = array_filter($students, fn($s) => $s['is_premium'] == 0);
        }

        if ($search) {
            $students = array_filter($students, function($s) use ($search) {
                return stripos($s['name'], $search) !== false || stripos($s['username'], $search) !== false;
            });
        }

        // Attach latest score for each student
        foreach ($students as &$st) {
            $latest = $this->tryoutModel->where('user_id', $st['id'])->orderBy('id', 'DESC')->first();
            $st['latest_score'] = $latest ? $latest['final_score'] : null;
        }

        return view('admin/murid/database', [
            'title'     => 'Database Murid - TeKaPe.id',
            'activeNav' => 'murid',
            'students'  => $students,
            'search'    => $search,
            'filter'    => $filter,
        ]);
    }

    // Prompt 14 — Detail Murid
    public function detail($id)
    {
        $student = $this->userModel->getStudentDetail($id);
        if (!$student) {
            return redirect()->to(base_url('admin/murid/database'))->with('error', 'Murid tidak ditemukan.');
        }

        // Scores summary
        $scores = $this->tryoutModel->where('user_id', $id)->findAll();
        $latestScore = !empty($scores) ? end($scores)['final_score'] : null;
        $highestScore = !empty($scores) ? max(array_column($scores, 'final_score')) : null;
        $avgScore = !empty($scores) ? round(array_sum(array_column($scores, 'final_score')) / count($scores), 2) : null;

        // Recent tryout history
        $tryoutHistory = $this->tryoutModel->select('tryout_sessions.*, packages.title as package_title')
            ->join('packages', 'packages.id = tryout_sessions.package_id')
            ->where('tryout_sessions.user_id', $id)
            ->orderBy('tryout_sessions.id', 'DESC')
            ->findAll(10);

        // Attendance records
        $attendances = $this->attendanceModel->where('user_id', $id)->orderBy('date', 'DESC')->findAll();

        return view('admin/murid/detail', [
            'title'         => 'Detail Murid - ' . esc($student['name']),
            'activeNav'     => 'murid',
            'student'       => $student,
            'latestScore'   => $latestScore,
            'highestScore'  => $highestScore,
            'avgScore'      => $avgScore,
            'tryoutHistory' => $tryoutHistory,
            'attendances'   => $attendances,
        ]);
    }

    // Prompt 15 — Nilai Try Out
    public function nilai()
    {
        $search = $this->request->getGet('q');
        $type = $this->request->getGet('type');

        $results = $this->tryoutModel->getTryoutResultsWithDetails($type, $search);

        return view('admin/murid/nilai', [
            'title'     => 'Nilai Try Out - TeKaPe.id',
            'activeNav' => 'murid',
            'results'   => $results,
            'search'    => $search,
            'type'      => $type,
        ]);
    }

    // Edit Nilai Manual (Audited)
    public function updateNilaiManual($sessionId)
    {
        $session = $this->tryoutModel->find($sessionId);
        if (!$session) {
            return redirect()->back()->with('error', 'Sesi try out tidak ditemukan.');
        }

        $finalScore = (float) $this->request->getPost('final_score');
        $twkScore   = (float) $this->request->getPost('twk_score');
        $tiuScore   = (float) $this->request->getPost('tiu_score');
        $tkpScore   = (float) $this->request->getPost('tkp_score');

        $this->tryoutModel->update($sessionId, [
            'final_score'      => $finalScore,
            'twk_score'        => $twkScore,
            'tiu_score'        => $tiuScore,
            'tkp_score'        => $tkpScore,
            'is_manual_edited' => 1,
            'edited_by'        => session()->get('user_name') ?? 'Admin',
            'edited_at'        => date('Y-m-d H:i:s'),
        ]);

        return redirect()->back()->with('success', 'Nilai try out berhasil disesuaikan secara manual!');
    }

    // Prompt 16 — Absensi
    public function absensi()
    {
        $filterDate    = $this->request->getGet('date');
        $filterSubject = $this->request->getGet('subject');
        $filterStudent = $this->request->getGet('q');

        $attendances = $this->attendanceModel->getAttendanceWithStudent($filterDate, $filterSubject, $filterStudent);

        $totalHadir = $this->attendanceModel->where('status', 'hadir')->countAllResults();
        $totalTidak = $this->attendanceModel->where('status', 'tidak_hadir')->countAllResults();
        $totalAll = $totalHadir + $totalTidak;
        $pct = $totalAll > 0 ? round(($totalHadir / $totalAll) * 100) . '%' : '92%';

        return view('admin/murid/absensi', [
            'title'         => 'Absensi Siswa - TeKaPe.id',
            'activeNav'     => 'murid',
            'attendances'   => $attendances,
            'totalHadir'    => $totalHadir,
            'totalTidak'    => $totalTidak,
            'pctKehadiran'  => $pct,
            'filterDate'    => $filterDate,
            'filterSubject' => $filterSubject,
            'filterStudent' => $filterStudent,
        ]);
    }

    // Prompt 17 — Kelola Login Siswa
    public function loginSiswa()
    {
        $search = $this->request->getGet('q');
        $students = $this->userModel->getStudents();

        if ($search) {
            $students = array_filter($students, function($s) use ($search) {
                return stripos($s['name'], $search) !== false || stripos($s['username'], $search) !== false;
            });
        }

        return view('admin/murid/login_siswa', [
            'title'     => 'Kelola Login Siswa - TeKaPe.id',
            'activeNav' => 'murid',
            'students'  => $students,
            'search'    => $search,
        ]);
    }

    // Ubah Username Siswa
    public function updateStudentUsername($id)
    {
        $newUsername = trim($this->request->getPost('new_username') ?? '');
        if (empty($newUsername) || strlen($newUsername) < 4) {
            return redirect()->back()->with('error', 'Username baru minimal 4 karakter.');
        }

        $existing = $this->userModel->where('username', $newUsername)->where('id !=', $id)->first();
        if ($existing) {
            return redirect()->back()->with('error', 'Username tersebut sudah digunakan oleh akun lain.');
        }

        $this->userModel->update($id, [
            'username'   => $newUsername,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->back()->with('success', "Username berhasil diubah menjadi {$newUsername}.");
    }

    // Reset Password Siswa
    public function resetStudentPassword($id)
    {
        $newPassword = $this->request->getPost('new_password') ?? '';
        if (empty($newPassword) || strlen($newPassword) < 6) {
            return redirect()->back()->with('error', 'Password baru minimal 6 karakter.');
        }

        $this->userModel->update($id, [
            'password_hash' => password_hash($newPassword, PASSWORD_BCRYPT),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

        return redirect()->back()->with('success', 'Password akun siswa berhasil direset.');
    }
}
