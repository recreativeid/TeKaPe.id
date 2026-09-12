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
        $price = (int) ($settings['premium_price'] ?? 149000);
        $duration = (int) ($settings['premium_duration_days'] ?? 30);
        $clientKey = $settings['midtrans_client_key'] ?? 'SB-Mid-client-sample-key';
        $env = $settings['midtrans_environment'] ?? 'sandbox';
        $isActive = ($settings['midtrans_is_active'] ?? '1') === '1';

        return view('murid/payment/index', [
            'title'     => 'Berlangganan Premium - TeKaPe.id',
            'activeNav' => 'soal',
            'price'     => $price,
            'duration'  => $duration,
            'clientKey' => $clientKey,
            'env'       => $env,
            'isActive'  => $isActive,
        ]);
    }

    // Midtrans Snap Token Generator (AJAX)
    public function getSnapToken()
    {
        $userId   = session()->get('user_id');
        $user     = $this->userModel->find($userId);
        $settings = $this->settingModel->getMap();
        $price    = (int) ($settings['premium_price'] ?? 149000);
        $duration = (int) ($settings['premium_duration_days'] ?? 30);
        $serverKey= trim($settings['midtrans_server_key'] ?? '');
        $env      = $settings['midtrans_environment'] ?? 'sandbox';

        $orderId  = 'TKP-' . strtoupper(uniqid());
        $now      = date('Y-m-d H:i:s');
        $expiry   = date('Y-m-d H:i:s', strtotime("+{$duration} days"));

        // Save pending subscription
        $this->subscriptionModel->insert([
            'user_id'        => $userId,
            'order_id'       => $orderId,
            'amount'         => $price,
            'payment_method' => 'Midtrans Snap',
            'status'         => 'pending',
            'created_at'     => $now,
            'expired_at'     => $expiry,
        ]);

        // If Server Key is configured and not default dummy, call Midtrans Snap API
        if (!empty($serverKey) && strpos($serverKey, 'sample') === false && strpos($serverKey, '•••') === false) {
            $snapUrl = ($env === 'production')
                ? 'https://app.midtrans.com/snap/v1/transactions'
                : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

            $payload = [
                'transaction_details' => [
                    'order_id'     => $orderId,
                    'gross_amount' => $price,
                ],
                'customer_details' => [
                    'first_name' => $user['name'] ?? 'Siswa TeKaPe',
                    'email'      => ($user['username'] ?? 'siswa') . '@tekape.id',
                    'phone'      => $user['phone_whatsapp'] ?? '08123456789',
                ],
                'item_details' => [
                    [
                        'id'       => 'ALL_ACCESS_KEDINASAN',
                        'price'    => $price,
                        'quantity' => 1,
                        'name'     => "Langganan All-Access Kedinasan ({$duration} Hari)",
                    ]
                ]
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $snapUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: Basic ' . base64_encode($serverKey . ':')
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            $res = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 201 || $httpCode === 200) {
                $json = json_decode($res, true);
                if (!empty($json['token'])) {
                    return $this->response->setJSON([
                        'status'     => 'success',
                        'snap_token' => $json['token'],
                        'order_id'   => $orderId,
                    ]);
                }
            }
        }

        // Fallback: Sandbox / Simulation token for testing
        return $this->response->setJSON([
            'status'     => 'simulate',
            'order_id'   => $orderId,
            'amount'     => $price,
            'message'    => 'Mode simulasi sandbox aktif.',
        ]);
    }

    // Called when Snap payment succeeds on frontend or fallback simulation
    public function finish()
    {
        $orderId = $this->request->getPost('order_id') ?? $this->request->getGet('order_id');
        $paymentType = $this->request->getPost('payment_type') ?? 'Midtrans Snap (QRIS/Bank)';
        
        $this->activateSubscription($orderId, $paymentType);

        return redirect()->to(base_url("murid/payment/success?order={$orderId}"));
    }

    // Direct 1-Click Simulation for local testing
    public function simulateSuccess()
    {
        $userId   = session()->get('user_id');
        $settings = $this->settingModel->getMap();
        $price    = (int) ($settings['premium_price'] ?? 149000);
        $duration = (int) ($settings['premium_duration_days'] ?? 30);
        $method   = $this->request->getPost('payment_method') ?? 'Midtrans QRIS (Simulasi)';

        $orderId = 'TKP-' . strtoupper(uniqid());
        $now = date('Y-m-d H:i:s');
        $expiry = date('Y-m-d H:i:s', strtotime("+{$duration} days"));

        $this->subscriptionModel->insert([
            'user_id'        => $userId,
            'order_id'       => $orderId,
            'amount'         => $price,
            'payment_method' => $method,
            'status'         => 'paid',
            'created_at'     => $now,
            'expired_at'     => $expiry,
        ]);

        $this->activateSubscription($orderId, $method);

        return redirect()->to(base_url("murid/payment/success?order={$orderId}"));
    }

    // Process legacy or direct submission
    public function process()
    {
        return $this->simulateSuccess();
    }

    // Midtrans Webhook / HTTP Notification Handler
    public function midtransNotification()
    {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);

        if (!$data || empty($data['order_id'])) {
            return $this->response->setStatusCode(400)->setBody('Bad Request');
        }

        $orderId = $data['order_id'];
        $statusCode = $data['status_code'] ?? '';
        $grossAmount = $data['gross_amount'] ?? '';
        $signature = $data['signature_key'] ?? '';
        $transactionStatus = $data['transaction_status'] ?? '';
        $fraudStatus = $data['fraud_status'] ?? 'accept';

        $settings = $this->settingModel->getMap();
        $serverKey = trim($settings['midtrans_server_key'] ?? '');

        // Verify Signature Hash
        if (!empty($serverKey) && !empty($signature)) {
            $expected = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
            if ($signature !== $expected) {
                return $this->response->setStatusCode(403)->setBody('Invalid Signature');
            }
        }

        if ($transactionStatus === 'settlement' || ($transactionStatus === 'capture' && $fraudStatus === 'accept')) {
            $this->activateSubscription($orderId, $data['payment_type'] ?? 'Midtrans Webhook');
        } elseif (in_array($transactionStatus, ['cancel', 'expire', 'deny'])) {
            $this->subscriptionModel->where('order_id', $orderId)->set(['status' => 'failed'])->update();
        }

        return $this->response->setStatusCode(200)->setBody('OK');
    }

    // Helper: Activate Student Premium Status and Unlock All Packages
    protected function activateSubscription($orderId, $paymentMethod = 'Midtrans')
    {
        $sub = $this->subscriptionModel->where('order_id', $orderId)->first();
        $userId = $sub ? $sub['user_id'] : session()->get('user_id');

        $settings = $this->settingModel->getMap();
        $duration = (int) ($settings['premium_duration_days'] ?? 30);
        $expiryDate = date('Y-m-d', strtotime("+{$duration} days"));

        // Mark subscription paid
        if ($sub) {
            $this->subscriptionModel->where('order_id', $orderId)->set([
                'status'         => 'paid',
                'payment_method' => $paymentMethod,
                'expired_at'     => date('Y-m-d H:i:s', strtotime("+{$duration} days")),
            ])->update();
        }

        // Unlock All Premium Packages for Student
        $db = \Config\Database::connect();
        $meta = $db->table('students_meta')->where('user_id', $userId)->get()->getRowArray();
        if ($meta) {
            $db->table('students_meta')->where('user_id', $userId)->update([
                'is_premium'     => 1,
                'premium_start'  => date('Y-m-d'),
                'premium_expiry' => $expiryDate,
            ]);
        } else {
            $db->table('students_meta')->insert([
                'user_id'         => $userId,
                'is_premium'      => 1,
                'premium_start'   => date('Y-m-d'),
                'premium_expiry'  => $expiryDate,
                'attendance_rate' => 100,
            ]);
        }
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
