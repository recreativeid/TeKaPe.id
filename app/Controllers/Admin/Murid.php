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
        $tentorId = $this->request->getGet('tentor_id') ? (int) $this->request->getGet('tentor_id') : null;

        $students = $this->userModel->getStudents($tentorId);
        $tentors  = $this->userModel->getTentors();

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
            'title'            => 'Database Murid - TeKaPe.id',
            'activeNav'        => 'murid',
            'students'         => $students,
            'tentors'          => $tentors,
            'selectedTentorId' => $tentorId,
            'search'           => $search,
            'filter'           => $filter,
        ]);
    }

    // Prompt 14 — Detail Murid
    public function detail($id)
    {
        $student = $this->userModel->getStudentDetail($id);
        if (!$student) {
            return redirect()->to(base_url('admin/murid/database'))->with('error', 'Murid tidak ditemukan.');
        }

        $tentors = $this->userModel->getTentors();

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
            'tentors'       => $tentors,
            'latestScore'   => $latestScore,
            'highestScore'  => $highestScore,
            'avgScore'      => $avgScore,
            'tryoutHistory' => $tryoutHistory,
            'attendances'   => $attendances,
        ]);
    }

    // Assign Tentor Pembimbing
    public function assignTentor($studentId)
    {
        $tentorId = $this->request->getPost('assigned_tentor_id');
        $tentorId = !empty($tentorId) ? (int) $tentorId : null;

        $db = \Config\Database::connect();
        $exists = $db->table('students_meta')->where('user_id', $studentId)->get()->getRowArray();
        if ($exists) {
            $db->table('students_meta')->where('user_id', $studentId)->update([
                'assigned_tentor_id' => $tentorId,
            ]);
        } else {
            $db->table('students_meta')->insert([
                'user_id'            => $studentId,
                'assigned_tentor_id' => $tentorId,
                'attendance_rate'    => 90,
            ]);
        }

        return redirect()->back()->with('success', 'Tentor pembimbing berhasil diperbarui!');
    }

    // Prompt 15 — Nilai Try Out
    public function nilai()
    {
        $search   = $this->request->getGet('q');
        $type     = $this->request->getGet('type');
        $tentorId = $this->request->getGet('tentor_id') ? (int) $this->request->getGet('tentor_id') : null;

        $results = $this->tryoutModel->getTryoutResultsWithDetails($type, $search, $tentorId);
        $tentors = $this->userModel->getTentors();

        return view('admin/murid/nilai', [
            'title'            => 'Nilai Try Out - TeKaPe.id',
            'activeNav'        => 'murid',
            'results'          => $results,
            'tentors'          => $tentors,
            'selectedTentorId' => $tentorId,
            'search'           => $search,
            'type'             => $type,
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

    // Prompt 17 — Kelola Akun Siswa (Renamed from Kelola Login Siswa)
    public function loginSiswa()
    {
        return $this->akunSiswa();
    }

    public function akunSiswa()
    {
        $search   = $this->request->getGet('q');
        $status   = $this->request->getGet('status') ?? 'semua';
        $tentorId = $this->request->getGet('tentor_id') ? (int) $this->request->getGet('tentor_id') : null;

        $students = $this->userModel->getStudents($tentorId);
        $tentors  = $this->userModel->getTentors();

        // Apply status filter
        if ($status === 'premium') {
            $students = array_filter($students, fn($s) => !empty($s['is_premium']) && $s['is_premium'] == 1);
        } elseif ($status === 'free') {
            $students = array_filter($students, fn($s) => empty($s['is_premium']) || $s['is_premium'] == 0);
        }

        // Apply search query
        if ($search) {
            $students = array_filter($students, function($s) use ($search) {
                return stripos($s['name'], $search) !== false 
                    || stripos($s['username'], $search) !== false 
                    || stripos($s['phone_whatsapp'] ?? '', $search) !== false;
            });
        }

        return view('admin/murid/akun_siswa', [
            'title'            => 'Kelola Akun Siswa - TeKaPe.id',
            'activeNav'        => 'murid',
            'students'         => $students,
            'tentors'          => $tentors,
            'selectedTentorId' => $tentorId,
            'status'           => $status,
            'search'           => $search,
        ]);
    }

    // Buat Akun Siswa Baru
    public function createStudent()
    {
        $name          = trim($this->request->getPost('name') ?? '');
        $username      = trim($this->request->getPost('username') ?? '');
        $password      = $this->request->getPost('password') ?? '';
        $phoneWhatsapp = trim($this->request->getPost('phone_whatsapp') ?? '');
        $isPremium     = (int) ($this->request->getPost('is_premium') ?? 0);
        $tentorId      = $this->request->getPost('assigned_tentor_id');
        $tentorId      = !empty($tentorId) ? (int) $tentorId : null;

        if (empty($name)) {
            return redirect()->back()->withInput()->with('error', 'Nama lengkap murid wajib diisi.');
        }

        if (empty($username) || strlen($username) < 3) {
            return redirect()->back()->withInput()->with('error', 'Username minimal 3 karakter tanpa spasi.');
        }

        // Validate username format (alphanumeric and underscore/dot)
        if (!preg_match('/^[a-zA-Z0-9._-]+$/', $username)) {
            return redirect()->back()->withInput()->with('error', 'Username hanya boleh mengandung huruf, angka, titik, strip, atau underscore.');
        }

        // Check if username already taken
        $existing = $this->userModel->where('username', $username)->first();
        if ($existing) {
            return redirect()->back()->withInput()->with('error', "Username '{$username}' sudah digunakan. Silakan pilih username lain.");
        }

        if (empty($password) || strlen($password) < 6) {
            return redirect()->back()->withInput()->with('error', 'Password minimal 6 karakter.');
        }

        // Normalize phone number for WhatsApp
        $cleanPhone = preg_replace('/[^0-9]/', '', $phoneWhatsapp);
        if (substr($cleanPhone, 0, 1) === '0') {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        }

        $userId = $this->userModel->insert([
            'name'           => $name,
            'username'       => $username,
            'password_hash'  => password_hash($password, PASSWORD_BCRYPT),
            'role'           => 'murid',
            'phone_whatsapp' => $phoneWhatsapp,
            'created_at'     => date('Y-m-d H:i:s'),
            'updated_at'     => date('Y-m-d H:i:s'),
        ]);

        if ($userId) {
            $db = \Config\Database::connect();
            $metaData = [
                'user_id'            => $userId,
                'is_premium'         => $isPremium,
                'attendance_rate'    => 100,
                'assigned_tentor_id' => $tentorId,
            ];

            if ($isPremium == 1) {
                $metaData['premium_start']  = date('Y-m-d');
                $metaData['premium_expiry'] = date('Y-m-d', strtotime('+30 days'));
            }

            $db->table('students_meta')->insert($metaData);

            // Flash info so admin can immediately copy WA message with credentials
            session()->setFlashdata('new_student_created', [
                'id'         => $userId,
                'name'       => $name,
                'username'   => $username,
                'password'   => $password,
                'phone'      => $cleanPhone,
                'is_premium' => $isPremium,
            ]);

            return redirect()->to(base_url('admin/murid/akunSiswa'))->with('success', "Akun siswa {$name} (@{$username}) berhasil dibuat!");
        }

        return redirect()->back()->withInput()->with('error', 'Gagal membuat akun siswa. Silakan coba lagi.');
    }

    // Ubah Username Siswa
    public function updateStudentUsername($id)
    {
        $newUsername = trim($this->request->getPost('new_username') ?? '');
        if (empty($newUsername) || strlen($newUsername) < 3) {
            return redirect()->back()->with('error', 'Username baru minimal 3 karakter.');
        }

        if (!preg_match('/^[a-zA-Z0-9._-]+$/', $newUsername)) {
            return redirect()->back()->with('error', 'Username hanya boleh mengandung huruf, angka, titik, strip, atau underscore.');
        }

        $student = $this->userModel->find($id);
        if (!$student) {
            return redirect()->back()->with('error', 'Siswa tidak ditemukan.');
        }

        $existing = $this->userModel->where('username', $newUsername)->where('id !=', $id)->first();
        if ($existing) {
            return redirect()->back()->with('error', "Username '{$newUsername}' sudah digunakan oleh akun lain.");
        }

        $this->userModel->update($id, [
            'username'   => $newUsername,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $cleanPhone = preg_replace('/[^0-9]/', '', $student['phone_whatsapp'] ?? '');
        if (substr($cleanPhone, 0, 1) === '0') {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        }

        session()->setFlashdata('username_changed_success', [
            'id'           => $id,
            'name'         => $student['name'],
            'old_username' => $student['username'],
            'new_username' => $newUsername,
            'phone'        => $cleanPhone,
        ]);

        return redirect()->back()->with('success', "Username siswa {$student['name']} berhasil diubah menjadi @{$newUsername}.");
    }

    // Reset Password Siswa
    public function resetStudentPassword($id)
    {
        $newPassword = $this->request->getPost('new_password') ?? '';
        if (empty($newPassword) || strlen($newPassword) < 6) {
            return redirect()->back()->with('error', 'Password baru minimal 6 karakter.');
        }

        $student = $this->userModel->find($id);
        if (!$student) {
            return redirect()->back()->with('error', 'Siswa tidak ditemukan.');
        }

        $this->userModel->update($id, [
            'password_hash' => password_hash($newPassword, PASSWORD_BCRYPT),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

        $cleanPhone = preg_replace('/[^0-9]/', '', $student['phone_whatsapp'] ?? '');
        if (substr($cleanPhone, 0, 1) === '0') {
            $cleanPhone = '62' . substr($cleanPhone, 1);
        }

        session()->setFlashdata('password_reset_success', [
            'id'       => $id,
            'name'     => $student['name'],
            'username' => $student['username'],
            'password' => $newPassword,
            'phone'    => $cleanPhone,
        ]);

        return redirect()->back()->with('success', "Password akun siswa {$student['name']} (@{$student['username']}) berhasil direset.");
    }
}
