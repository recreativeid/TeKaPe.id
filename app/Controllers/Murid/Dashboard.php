<?php

namespace App\Controllers\Murid;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\TryoutModel;
use App\Models\ScheduleModel;
use App\Models\SettingModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $userId = session()->get('user_id');
        $userModel = new UserModel();
        $tryoutModel = new TryoutModel();
        $scheduleModel = new ScheduleModel();

        $student = $userModel->getStudentDetail($userId);

        // Scores calculation
        $scores = $tryoutModel->where('user_id', $userId)->where('status', 'completed')->orderBy('id', 'ASC')->findAll();
        $latestScore = !empty($scores) ? end($scores)['final_score'] : 0;
        $highestScore = !empty($scores) ? max(array_column($scores, 'final_score')) : 0;
        $avgScore = !empty($scores) ? round(array_sum(array_column($scores, 'final_score')) / count($scores), 2) : 0;

        // Score trend for line chart visual
        $trendData = array_map(function($s) {
            return [
                'score' => (float)$s['final_score'],
                'date'  => date('d/m', strtotime($s['created_at'] ?? 'now')),
            ];
        }, array_slice($scores, -6));

        // Next bimbingan session
        $todayDay = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ][date('l')] ?? 'Senin';

        $nextClass = $scheduleModel->where('day', $todayDay)->where('status', 'active')->first();
        if (!$nextClass) {
            $nextClass = $scheduleModel->where('status', 'active')->first();
        }

        return view('murid/dashboard', [
            'title'        => 'Dashboard Siswa - TeKaPe.id',
            'activeNav'    => 'dashboard',
            'student'      => $student,
            'latestScore'  => $latestScore,
            'highestScore' => $highestScore,
            'avgScore'     => $avgScore,
            'trendData'    => $trendData,
            'nextClass'    => $nextClass,
        ]);
    }
}
