<?php

namespace App\Controllers\Murid;

use App\Controllers\BaseController;
use App\Models\ScheduleModel;

class Jadwal extends BaseController
{
    public function index()
    {
        $scheduleModel = new ScheduleModel();

        $todayDay = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ][date('l')] ?? 'Senin';

        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $allSchedules = $scheduleModel->getSchedulesWithTentor();

        $weeklySchedules = [];
        foreach ($days as $d) {
            $weeklySchedules[$d] = [];
        }
        foreach ($allSchedules as $sc) {
            if (isset($weeklySchedules[$sc['day']])) {
                $weeklySchedules[$sc['day']][] = $sc;
            }
        }

        // Today's first active class
        $todayClass = null;
        if (!empty($weeklySchedules[$todayDay])) {
            $todayClass = $weeklySchedules[$todayDay][0];
        } elseif (!empty($allSchedules)) {
            $todayClass = $allSchedules[0];
        }

        return view('murid/jadwal/index', [
            'title'           => 'Jadwal & Pembelajaran - TeKaPe.id',
            'activeNav'       => 'jadwal',
            'todayDay'        => $todayDay,
            'days'            => $days,
            'weeklySchedules' => $weeklySchedules,
            'allSchedules'    => $allSchedules,
            'todayClass'      => $todayClass,
        ]);
    }
}
