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

        $selectedDay = $this->request->getGet('day') ?? $todayDay;

        // Today's first active class
        $todayClass = $scheduleModel->where('day', $todayDay)->where('status', 'active')->first();
        if (!$todayClass) {
            $todayClass = $scheduleModel->where('status', 'active')->first();
        }

        // Schedules for selected day
        $daySchedules = $scheduleModel->where('day', $selectedDay)->where('status', 'active')->orderBy('start_time', 'ASC')->findAll();

        return view('murid/jadwal/index', [
            'title'        => 'Jadwal & Pembelajaran - TeKaPe.id',
            'activeNav'    => 'jadwal',
            'todayDay'     => $todayDay,
            'selectedDay'  => $selectedDay,
            'todayClass'   => $todayClass,
            'daySchedules' => $daySchedules,
        ]);
    }
}
