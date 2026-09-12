<?php

namespace App\Controllers\Tentor;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\TryoutModel;

class Murid extends BaseController
{
    protected $userModel;
    protected $tryoutModel;

    public function __construct()
    {
        $this->userModel   = new UserModel();
        $this->tryoutModel = new TryoutModel();
    }

    public function index()
    {
        $students = $this->userModel->getStudents();
        $tryoutResults = $this->tryoutModel->getLeaderboard(null, 30);

        return view('tentor/murid/index', [
            'title'         => 'Database Murid & Nilai - TeKaPe.id',
            'activeNav'     => 'murid',
            'students'      => $students,
            'tryoutResults' => $tryoutResults,
            'role'          => 'tentor',
        ]);
    }

    public function nilai()
    {
        return $this->index();
    }
}
