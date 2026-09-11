<?php

namespace App\Controllers\Murid;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\SettingModel;

class Profil extends BaseController
{
    protected $userModel;
    protected $settingModel;

    public function __construct()
    {
        $this->userModel    = new UserModel();
        $this->settingModel = new SettingModel();
    }

    // Prompt 33 — Profil Murid Main Page
    public function index()
    {
        $userId = session()->get('user_id');
        $student = $this->userModel->getStudentDetail($userId);

        return view('murid/profil/index', [
            'title'     => 'Profil Siswa - TeKaPe.id',
            'activeNav' => 'profil',
            'student'   => $student,
        ]);
    }

    // Prompt 34 — Ubah Username
    public function username()
    {
        $userId = session()->get('user_id');
        $student = $this->userModel->find($userId);

        return view('murid/profil/username', [
            'title'     => 'Ubah Username - TeKaPe.id',
            'activeNav' => 'profil',
            'student'   => $student,
        ]);
    }

    public function updateUsername()
    {
        $userId   = session()->get('user_id');
        $username = trim($this->request->getPost('new_username') ?? '');

        if (empty($username) || strlen($username) < 4) {
            return redirect()->back()->with('error', 'Username baru minimal 4 karakter.');
        }

        $existing = $this->userModel->where('username', $username)->where('id !=', $userId)->first();
        if ($existing) {
            return redirect()->back()->with('error', 'Username tersebut sudah terdaftar. Silakan pilih username lain.');
        }

        $this->userModel->update($userId, [
            'username'   => $username,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        session()->set('user_username', $username);
        return redirect()->to(base_url('murid/profil'))->with('success', 'Username berhasil diperbarui!');
    }

    // Prompt 35 — Ubah Password
    public function password()
    {
        return view('murid/profil/password', [
            'title'     => 'Ubah Password - TeKaPe.id',
            'activeNav' => 'profil',
        ]);
    }

    public function updatePassword()
    {
        $userId  = session()->get('user_id');
        $oldPass = $this->request->getPost('old_password') ?? '';
        $newPass = $this->request->getPost('new_password') ?? '';
        $cfmPass = $this->request->getPost('confirm_password') ?? '';

        $user = $this->userModel->find($userId);
        if (!password_verify($oldPass, $user['password_hash'])) {
            return redirect()->back()->with('error', 'Password lama tidak cocok.');
        }

        if (strlen($newPass) < 6) {
            return redirect()->back()->with('error', 'Password baru minimal 6 karakter.');
        }

        if ($newPass !== $cfmPass) {
            return redirect()->back()->with('error', 'Konfirmasi password baru tidak cocok.');
        }

        $this->userModel->update($userId, [
            'password_hash' => password_hash($newPass, PASSWORD_BCRYPT),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(base_url('murid/profil'))->with('success', 'Password berhasil diubah!');
    }

    // Prompt 36 — Hubungi Tentor
    public function bantuan()
    {
        $settings = $this->settingModel->getMap();
        $tentorName = $settings['tentor_name'] ?? 'Tentor Bima Satria, M.Pd.';
        $tentorWa   = $settings['tentor_whatsapp'] ?? '6289876543210';

        return view('murid/profil/bantuan', [
            'title'      => 'Hubungi Tentor - TeKaPe.id',
            'activeNav'  => 'profil',
            'tentorName' => $tentorName,
            'tentorWa'   => $tentorWa,
        ]);
    }
}
