<?php

namespace App\Controllers\Tentor;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Profil extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // Prompt 40 — Profil Guru / Tentor
    public function index()
    {
        $tentorId = session()->get('user_id');
        $tentor   = $this->userModel->find($tentorId);

        return view('tentor/profil/index', [
            'title'     => 'Profil Tentor - TeKaPe.id',
            'activeNav' => 'profil',
            'tentor'    => $tentor,
        ]);
    }

    public function update()
    {
        $tentorId  = session()->get('user_id');
        $fullName  = trim($this->request->getPost('full_name') ?? '');
        $email     = trim($this->request->getPost('email') ?? '');
        $phone     = trim($this->request->getPost('phone_number') ?? '');

        if (empty($fullName)) {
            return redirect()->back()->with('error', 'Nama lengkap wajib diisi.');
        }

        $this->userModel->update($tentorId, [
            'full_name'    => $fullName,
            'email'        => $email,
            'phone_number' => $phone,
            'updated_at'   => date('Y-m-d H:i:s'),
        ]);

        session()->set('user_name', $fullName);
        return redirect()->to(base_url('tentor/profil'))->with('success', 'Profil berhasil diperbarui!');
    }

    // Prompt 41 — Ubah Password Tentor
    public function password()
    {
        return view('tentor/profil/password', [
            'title'     => 'Ubah Password Tentor - TeKaPe.id',
            'activeNav' => 'profil',
        ]);
    }

    public function updatePassword()
    {
        $tentorId = session()->get('user_id');
        $oldPass  = $this->request->getPost('old_password') ?? '';
        $newPass  = $this->request->getPost('new_password') ?? '';
        $cfmPass  = $this->request->getPost('confirm_password') ?? '';

        $user = $this->userModel->find($tentorId);
        if (!password_verify($oldPass, $user['password_hash'])) {
            return redirect()->back()->with('error', 'Password lama tidak cocok.');
        }

        if (strlen($newPass) < 6) {
            return redirect()->back()->with('error', 'Password baru minimal 6 karakter.');
        }

        if ($newPass !== $cfmPass) {
            return redirect()->back()->with('error', 'Konfirmasi password baru tidak cocok.');
        }

        $this->userModel->update($tentorId, [
            'password_hash' => password_hash($newPass, PASSWORD_BCRYPT),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(base_url('tentor/profil'))->with('success', 'Password berhasil diubah!');
    }
}
