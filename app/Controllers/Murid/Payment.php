<?php

namespace App\Controllers\Murid;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\SettingModel;
use App\Models\SubscriptionModel;

class Payment extends BaseController
{
    protected $userModel;
    protected $settingModel;
    protected $subscriptionModel;

    public function __construct()
    {
        $this->userModel         = new UserModel();
        $this->settingModel      = new SettingModel();
        $this->subscriptionModel = new SubscriptionModel();
    }

    // Prompt 28 — Checkout Page
    public function index()
    {
        $settings = $this->settingModel->getMap();
        $price = $settings['premium_price'] ?? 149000;
        $duration = $settings['premium_duration_days'] ?? 30;

        return view('murid/payment/index', [
            'title'     => 'Berlangganan Premium - TeKaPe.id',
            'activeNav' => 'soal',
            'price'     => $price,
            'duration'  => $duration,
        ]);
    }

    // Process Payment Simulation / Gateway
    public function process()
    {
        $userId   = session()->get('user_id');
        $settings = $this->settingModel->getMap();
        $price    = $settings['premium_price'] ?? 149000;
        $duration = $settings['premium_duration_days'] ?? 30;
        $method   = $this->request->getPost('payment_method') ?? 'QRIS Otomatis';

        $orderId = 'TKP-' . strtoupper(uniqid());
        $now = date('Y-m-d H:i:s');
        $expiry = date('Y-m-d H:i:s', strtotime("+{$duration} days"));

        // Save Subscription
        $this->subscriptionModel->insert([
            'user_id'        => $userId,
            'order_id'       => $orderId,
            'amount'         => $price,
            'payment_method' => $method,
            'status'         => 'paid',
            'created_at'     => $now,
            'expired_at'     => $expiry,
        ]);

        // Activate Student Premium Status
        $db = \Config\Database::connect();
        $meta = $db->table('students_meta')->where('user_id', $userId)->get()->getRowArray();
        if ($meta) {
            $db->table('students_meta')->where('user_id', $userId)->update([
                'is_premium'     => 1,
                'premium_start'  => date('Y-m-d'),
                'premium_expiry' => date('Y-m-d', strtotime("+{$duration} days")),
            ]);
        } else {
            $db->table('students_meta')->insert([
                'user_id'         => $userId,
                'is_premium'      => 1,
                'premium_start'   => date('Y-m-d'),
                'premium_expiry'  => date('Y-m-d', strtotime("+{$duration} days")),
                'attendance_rate' => 100,
            ]);
        }

        return redirect()->to(base_url("murid/payment/success?order={$orderId}"));
    }

    // Prompt 29 — Payment Success Page
    public function success()
    {
        $orderId = $this->request->getGet('order');
        $sub = $this->subscriptionModel->where('order_id', $orderId)->first();

        $settings = $this->settingModel->getMap();
        $duration = $settings['premium_duration_days'] ?? 30;

        return view('murid/payment/success', [
            'title'     => 'Pembayaran Berhasil - TeKaPe.id',
            'activeNav' => 'soal',
            'orderId'   => $orderId,
            'sub'       => $sub,
            'duration'  => $duration,
        ]);
    }
}
