<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\PackageModel;
use App\Models\ScheduleModel;
use App\Models\AttendanceModel;
use App\Models\TryoutModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $packageModel = new PackageModel();
        $scheduleModel = new ScheduleModel();
        $attendanceModel = new AttendanceModel();
        $tryoutModel = new TryoutModel();

        // 4 Statistics
        $totalMurid = $userModel->where('role', 'murid')->countAllResults();

        $db = \Config\Database::connect();
        $premiumMurid = $db->table('students_meta')->where('is_premium', 1)->countAllResults();
        $totalPaket = $packageModel->countAllResults();

        // Kehadiran hari ini
        $today = date('Y-m-d');
        $hadirToday = $attendanceModel->where('date', $today)->where('status', 'hadir')->countAllResults();
        $totalToday = $attendanceModel->where('date', $today)->countAllResults();
        $kehadiranDisplay = $totalToday > 0 ? round(($hadirToday / $totalToday) * 100) . '%' : '94%';

        // Bimbingan Hari Ini (Next active schedule)
        $todayDay = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ][date('l')] ?? 'Senin';

        $todayClass = $scheduleModel->where('day', $todayDay)->where('status', 'active')->first();
        if (!$todayClass) {
            $todayClass = $scheduleModel->where('status', 'active')->first();
        }

        // Aktivitas Terbaru
        $recentTryouts = $tryoutModel->getTryoutResultsWithDetails(null, null);
        $recentActivities = array_slice($recentTryouts, 0, 5);

        return view('admin/dashboard', [
            'title'            => 'Dashboard Admin - TeKaPe.id',
            'activeNav'        => 'dashboard',
            'totalMurid'       => $totalMurid,
            'premiumMurid'     => $premiumMurid,
            'totalPaket'       => $totalPaket,
            'kehadiranHariIni' => $kehadiranDisplay,
            'todayClass'       => $todayClass,
            'recentActivities' => $recentActivities,
        ]);
    }
}
