<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SettingModel;
use App\Models\UserModel;

class Pengaturan extends BaseController
{
    protected $settingModel;
    protected $userModel;

    public function __construct()
    {
        $this->settingModel = new SettingModel();
        $this->userModel    = new UserModel();
    }

    // Prompt 23 — Main Settings Menu (5 separate cards)
    public function index()
    {
        return view('admin/pengaturan/index', [
            'title'     => 'Pengaturan - TeKaPe.id',
            'activeNav' => '',
        ]);
    }

    // Dedicated Page 1: Pengaturan Website
    public function website()
    {
        $settings = $this->settingModel->getMap();
        return view('admin/pengaturan/website', [
            'title'    => 'Pengaturan Website - TeKaPe.id',
            'settings' => $settings,
        ]);
    }

    // Dedicated Page 2: Pengaturan WhatsApp
    public function whatsapp()
    {
        $settings = $this->settingModel->getMap();
        return view('admin/pengaturan/whatsapp', [
            'title'    => 'Pengaturan WhatsApp - TeKaPe.id',
            'settings' => $settings,
        ]);
    }

    // Dedicated Page 3: Payment Gateway
    public function payment()
    {
        $settings = $this->settingModel->getMap();
        return view('admin/pengaturan/payment', [
            'title'    => 'Pengaturan Payment Gateway - TeKaPe.id',
            'settings' => $settings,
        ]);
    }

    // Dedicated Page 4: Pengaturan Premium
    public function premium()
    {
        $settings = $this->settingModel->getMap();
        return view('admin/pengaturan/premium', [
            'title'    => 'Pengaturan Layanan Premium - TeKaPe.id',
            'settings' => $settings,
        ]);
    }

    // Dedicated Page 5: Keamanan Akun
    public function keamanan()
    {
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        return view('admin/pengaturan/keamanan', [
            'title' => 'Keamanan Akun - TeKaPe.id',
            'user'  => $user,
        ]);
    }

    // Processing Settings Updates
    public function saveSettings()
    {
        $fields = $this->request->getPost();
        foreach ($fields as $key => $val) {
            if ($key !== 'csrf_test_name') {
                $this->settingModel->setVal($key, trim($val));
            }
        }
        return redirect()->back()->with('success', 'Pengaturan berhasil disimpan!');
    }

    // Update Admin Account Security
    public function updateSecurity()
    {
        $userId   = session()->get('user_id');
        $username = trim($this->request->getPost('username') ?? '');
        $oldPass  = $this->request->getPost('old_password') ?? '';
        $newPass  = $this->request->getPost('new_password') ?? '';
        $secPass  = $this->request->getPost('secondary_password') ?? '';

        $user = $this->userModel->find($userId);
        if (!$user) return redirect()->to(base_url('auth/login'));

        if (!empty($newPass)) {
            if (empty($oldPass) || !password_verify($oldPass, $user['password_hash'])) {
                return redirect()->back()->with('error', 'Password lama tidak cocok.');
            }
            $this->userModel->update($userId, [
                'username'      => $username ?: $user['username'],
                'password_hash' => password_hash($newPass, PASSWORD_BCRYPT),
            ]);
        } else {
            $this->userModel->update($userId, [
                'username' => $username ?: $user['username'],
            ]);
        }

        // Secondary Password update
        if (!empty($secPass)) {
            $this->settingModel->setVal('secondary_password_hash', password_hash($secPass, PASSWORD_BCRYPT));
        }

        session()->set('user_username', $username ?: $user['username']);
        return redirect()->back()->with('success', 'Keamanan akun berhasil diperbarui!');
    }
}
