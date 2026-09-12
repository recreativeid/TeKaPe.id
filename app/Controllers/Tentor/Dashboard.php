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
        $tentorId = (int) session()->get('user_id');
        $tentor   = $this->userModel->find($tentorId);

        // Filter packages by this tentor
        $myPackages = $this->packageModel->where('created_by', $tentorId)->findAll();
        $totalPackages = count($myPackages);

        $packageIds = array_column($myPackages, 'id');
        $totalQuestions = 0;
        if (!empty($packageIds)) {
            $totalQuestions = $this->questionModel->whereIn('package_id', $packageIds)->countAllResults();
        }

        // Schedules for this tentor
        $db = \Config\Database::connect();
        $totalSchedules = $db->table('schedules')->where('tentor_id', $tentorId)->countAllResults();

        // Students mentored or taking tryouts
        $myStudents = $this->userModel->getStudents($tentorId);
        $totalStudents = count($myStudents);

        $recentPackages = $this->packageModel
            ->where('created_by', $tentorId)
            ->orderBy('created_at', 'DESC')
            ->findAll(3);

        return view('tentor/dashboard', [
            'title'          => 'Dashboard Tentor - TeKaPe.id',
            'activeNav'      => 'dashboard',
            'tentor'         => $tentor,
            'totalPackages'  => $totalPackages,
            'totalQuestions' => $totalQuestions,
            'totalSchedules' => $totalSchedules,
            'totalStudents'  => $totalStudents,
            'recentPackages' => $recentPackages,
        ]);
    }
}
