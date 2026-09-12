<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\SettingModel;

class Auth extends BaseController
{
    protected $userModel;
    protected $settingModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->settingModel = new SettingModel();
    }

    public function index()
    {
        return redirect()->to(base_url('auth/login'));
    }

    public function login()
    {
        if (session()->get('is_logged_in')) {
            $role = session()->get('user_role');
            if ($role === 'admin') return redirect()->to(base_url('admin/dashboard'));
            if ($role === 'tentor') return redirect()->to(base_url('tentor/soal'));
            return redirect()->to(base_url('murid/dashboard'));
        }

        $settings = $this->settingModel->getMap();
        $adminWa = $settings['admin_whatsapp'] ?? '6281234567890';
        $selectedRole = $this->request->getGet('role') ?? 'murid';

        return view('auth/login', [
            'title'        => 'Masuk ke TeKaPe.id',
            'adminWa'      => $adminWa,
            'selectedRole' => $selectedRole,
            'hideHeader'   => true,
            'hideBottomNav'=> true,
        ]);
    }

    public function processLogin()
    {
        $role     = $this->request->getPost('role') ?? 'murid';
        $username = trim($this->request->getPost('username') ?? '');
        $password = $this->request->getPost('password') ?? '';

        if (empty($username) || empty($password)) {
            return redirect()->back()->withInput()->with('error', 'Silakan masukkan username dan password.');
        }

        $user = $this->userModel->where('username', $username)->first();

        if (!$user) {
            return redirect()->back()->withInput()->with('error', 'Akun tidak ditemukan. Pastikan username sudah benar.');
        }

        // Validate role matches
        if ($user['role'] !== $role) {
            $roleName = $user['role'] === 'admin' ? 'Admin' : ($user['role'] === 'tentor' ? 'Guru/Tentor' : 'Murid');
            return redirect()->back()->withInput()->with('error', "Username ini terdaftar sebagai {$roleName}. Silakan pilih tab peran yang sesuai.");
        }

        if (!password_verify($password, $user['password_hash'])) {
            return redirect()->back()->withInput()->with('error', 'Password yang Anda masukkan salah.');
        }

        // Set session
        session()->set([
            'user_id'       => $user['id'],
            'user_name'     => $user['name'],
            'user_username' => $user['username'],
            'user_role'     => $user['role'],
            'is_logged_in'  => true,
        ]);

        if ($user['role'] === 'admin') {
            return redirect()->to(base_url('admin/dashboard'))->with('success', 'Selamat datang kembali, Admin!');
        } elseif ($user['role'] === 'tentor') {
            return redirect()->to(base_url('tentor/dashboard'))->with('success', 'Selamat datang, ' . $user['name'] . '!');
        } else {
            return redirect()->to(base_url('murid/dashboard'))->with('success', 'Selamat belajar, ' . $user['name'] . '!');
        }
    }

    public function register()
    {
        return view('auth/register', [
            'title'        => 'Daftar sebagai Murid - TeKaPe.id',
            'hideHeader'   => true,
            'hideBottomNav'=> true,
        ]);
    }

    public function processRegister()
    {
        $name     = trim($this->request->getPost('name') ?? '');
        $username = trim($this->request->getPost('username') ?? '');
        $phone    = trim($this->request->getPost('phone_whatsapp') ?? '');
        $password = $this->request->getPost('password') ?? '';
        $confirm  = $this->request->getPost('confirm_password') ?? '';

        if (empty($name) || empty($username) || empty($password)) {
            return redirect()->back()->withInput()->with('error', 'Semua kolom wajib diisi.');
        }

        if (strlen($username) < 4) {
            return redirect()->back()->withInput()->with('error', 'Username minimal 4 karakter.');
        }

        if ($password !== $confirm) {
            return redirect()->back()->withInput()->with('error', 'Konfirmasi password tidak cocok.');
        }

        $existing = $this->userModel->where('username', $username)->first();
        if ($existing) {
            return redirect()->back()->withInput()->with('error', 'Username sudah digunakan. Silakan gunakan username lain.');
        }

        $userId = $this->userModel->insert([
            'name'           => $name,
            'username'       => $username,
            'password_hash'  => password_hash($password, PASSWORD_BCRYPT),
            'role'           => 'murid',
            'phone_whatsapp' => $phone,
            'created_at'     => date('Y-m-d H:i:s'),
            'updated_at'     => date('Y-m-d H:i:s'),
        ]);

        $db = \Config\Database::connect();
        $db->table('students_meta')->insert([
            'user_id'         => $userId,
            'is_premium'      => 0,
            'premium_start'   => null,
            'premium_expiry'  => null,
            'attendance_rate' => 100,
        ]);

        return redirect()->to(base_url('auth/login?role=murid'))->with('success', 'Pendaftaran berhasil! Silakan masuk dengan akun Anda.');
    }

    public function verifyPassword()
    {
        if (!session()->get('is_logged_in')) {
            return redirect()->to(base_url('auth/login'));
        }

        $userRole = session()->get('user_role');
        if (!in_array($userRole, ['admin', 'tentor'])) {
            return redirect()->to(base_url('/'))->with('error', 'Akses ditolak.');
        }

        $redirectTarget = $this->request->getGet('next') ?? ($userRole === 'admin' ? 'admin/soal' : 'tentor/soal');

        return view('auth/verify_password', [
            'title'          => 'Konfirmasi Password - TeKaPe.id',
            'redirectTarget' => $redirectTarget,
            'hideBottomNav'  => true,
        ]);
    }

    public function processVerifyPassword()
    {
        $password = $this->request->getPost('password') ?? '';
        $redirect = $this->request->getPost('redirect_target') ?? 'admin/soal';
        $userId   = session()->get('user_id');

        $db   = \Config\Database::connect();
        $user = $db->table('users')->where('id', $userId)->get()->getRowArray();
        if (!$user || !isset($user['password_hash'])) {
            return redirect()->to(base_url('auth/login'));
        }

        if (password_verify($password, $user['password_hash'])) {
            session()->set('soal_auth_verified', true);
            session()->set('soal_auth_time', time());
            return redirect()->to(base_url($redirect));
        }

        return redirect()->back()->with('error', 'Password salah. Silakan coba kembali.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(base_url('auth/login'))->with('success', 'Anda telah berhasil keluar.');
    }
}
