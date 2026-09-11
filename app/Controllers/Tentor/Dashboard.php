<?php

namespace App\Controllers\Tentor;

use App\Controllers\BaseController;
use App\Models\PackageModel;
use App\Models\QuestionModel;
use App\Models\UserModel;

class Dashboard extends BaseController
{
    protected $packageModel;
    protected $questionModel;
    protected $userModel;

    public function __construct()
    {
        $this->packageModel  = new PackageModel();
        $this->questionModel = new QuestionModel();
        $this->userModel     = new UserModel();
    }

    // Prompt 37 — Dashboard Guru / Tentor
    public function index()
    {
        $tentorId = session()->get('user_id');
        $tentor   = $this->userModel->find($tentorId);

        $totalPackages  = $this->packageModel->countAllResults();
        $totalQuestions = $this->questionModel->countAllResults();
        $recentPackages = $this->packageModel->orderBy('created_at', 'DESC')->findAll(3);

        return view('tentor/dashboard', [
            'title'          => 'Dashboard Tentor - TeKaPe.id',
            'activeNav'      => 'dashboard',
            'tentor'         => $tentor,
            'totalPackages'  => $totalPackages,
            'totalQuestions' => $totalQuestions,
            'recentPackages' => $recentPackages,
        ]);
    }
}
