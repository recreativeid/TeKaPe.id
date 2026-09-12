<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ScheduleModel;

class Jadwal extends BaseController
{
    protected $scheduleModel;

    public function __construct()
    {
        $this->scheduleModel = new ScheduleModel();
    }

    // Prompt 18 — Menu Utama Kelola Jadwal Bimbel
    public function index()
    {
        $todayDay = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ][date('l')] ?? 'Senin';

        $todayClasses = $this->scheduleModel->getSchedulesWithTentor($todayDay);

        return view('admin/jadwal/index', [
            'title'        => 'Kelola Jadwal Bimbel - TeKaPe.id',
            'activeNav'    => 'jadwal',
            'todayDay'     => $todayDay,
            'todayClasses' => $todayClasses,
        ]);
    }

    // Prompt 19 — Lihat Jadwal Mingguan
    public function lihat()
    {
        $selectedDay = $this->request->getGet('day') ?? 'Senin';
        $filterSubj  = $this->request->getGet('subject') ?? 'Semua';
        $tentorId    = $this->request->getGet('tentor_id') ? (int) $this->request->getGet('tentor_id') : null;

        $schedules = $this->scheduleModel->getSchedulesWithTentor($selectedDay, $tentorId);
        if ($filterSubj !== 'Semua') {
            $schedules = array_filter($schedules, fn($s) => $s['subject'] === $filterSubj);
        }
        $tentors = (new \App\Models\UserModel())->getTentors();

        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $allSchedules = $this->scheduleModel->getSchedulesWithTentor(null, $tentorId);
        $weeklySchedules = [];
        foreach ($days as $d) {
            $weeklySchedules[$d] = [];
        }
        foreach ($allSchedules as $sc) {
            if (isset($weeklySchedules[$sc['day']])) {
                $weeklySchedules[$sc['day']][] = $sc;
            }
        }

        return view('admin/jadwal/lihat', [
            'title'            => 'Jadwal Mingguan - TeKaPe.id',
            'activeNav'        => 'jadwal',
            'selectedDay'      => $selectedDay,
            'filterSubj'       => $filterSubj,
            'schedules'        => $schedules,
            'tentors'          => $tentors,
            'selectedTentorId' => $tentorId,
            'days'             => $days,
            'weeklySchedules'  => $weeklySchedules,
        ]);
    }

    // Prompt 20 — Tambah Jadwal dengan Conflict Validation
    public function tambah()
    {
        $existing = $this->scheduleModel->findAll();
        $tentors  = (new \App\Models\UserModel())->getTentors();

        return view('admin/jadwal/tambah', [
            'title'     => 'Tambah Jadwal - TeKaPe.id',
            'activeNav' => 'jadwal',
            'existing'  => $existing,
            'tentors'   => $tentors,
        ]);
    }

    public function saveJadwal()
    {
        $subject   = $this->request->getPost('subject');
        $tentorId  = $this->request->getPost('tentor_id') ? (int) $this->request->getPost('tentor_id') : null;
        $day       = $this->request->getPost('day');
        $startTime = $this->request->getPost('start_time');
        $endTime   = $this->request->getPost('end_time');
        $platform  = $this->request->getPost('platform');
        $link      = trim($this->request->getPost('meeting_link') ?? '');
        $status    = $this->request->getPost('status') ?? 'active';

        // Check Conflict for the same tentor or room
        $conflict = $this->scheduleModel->checkConflict($day, $startTime, $endTime, null, $tentorId);
        if ($conflict) {
            return redirect()->back()->withInput()->with('error', "Jadwal bentrok dengan kelas {$conflict['subject']} ({$conflict['start_time']}–{$conflict['end_time']}) pada hari {$day}.");
        }

        $this->scheduleModel->insert([
            'subject'      => $subject,
            'tentor_id'    => $tentorId,
            'day'          => $day,
            'start_time'   => $startTime,
            'end_time'     => $endTime,
            'platform'     => $platform,
            'meeting_link' => $link,
            'status'       => $status,
        ]);

        return redirect()->to(base_url('admin/jadwal/lihat?day=' . urlencode($day)))->with('success', 'Jadwal bimbingan baru berhasil disimpan!');
    }

    // Prompt 21 — Edit Jadwal
    public function edit($id = null)
    {
        $schedules = $this->scheduleModel->orderBy('day', 'ASC')->orderBy('start_time', 'ASC')->findAll();
        $selectedSchedule = $id ? $this->scheduleModel->find($id) : null;

        return view('admin/jadwal/edit', [
            'title'            => 'Edit Jadwal - TeKaPe.id',
            'activeNav'        => 'jadwal',
            'schedules'        => $schedules,
            'selectedSchedule' => $selectedSchedule,
        ]);
    }

    public function updateJadwal($id)
    {
        $subject   = $this->request->getPost('subject');
        $day       = $this->request->getPost('day');
        $startTime = $this->request->getPost('start_time');
        $endTime   = $this->request->getPost('end_time');
        $platform  = $this->request->getPost('platform');
        $link      = trim($this->request->getPost('meeting_link') ?? '');
        $status    = $this->request->getPost('status') ?? 'active';

        // Check Conflict ignoring current schedule ID
        $conflict = $this->scheduleModel->checkConflict($day, $startTime, $endTime, $id);
        if ($conflict) {
            return redirect()->back()->withInput()->with('error', "Jadwal bentrok dengan kelas {$conflict['subject']} ({$conflict['start_time']}–{$conflict['end_time']}) pada hari {$day}.");
        }

        $this->scheduleModel->update($id, [
            'subject'      => $subject,
            'day'          => $day,
            'start_time'   => $startTime,
            'end_time'     => $endTime,
            'platform'     => $platform,
            'meeting_link' => $link,
            'status'       => $status,
        ]);

        return redirect()->to(base_url('admin/jadwal/edit'))->with('success', 'Perubahan jadwal berhasil disimpan!');
    }

    public function deleteJadwal($id)
    {
        $this->scheduleModel->delete($id);
        return redirect()->to(base_url('admin/jadwal/edit'))->with('success', 'Jadwal berhasil dihapus.');
    }

    // Prompt 22 — Kelola Link Zoom / Google Meet
    public function link()
    {
        $schedules = $this->scheduleModel->orderBy('day', 'ASC')->orderBy('start_time', 'ASC')->findAll();

        return view('admin/jadwal/link', [
            'title'     => 'Kelola Link Kelas - TeKaPe.id',
            'activeNav' => 'jadwal',
            'schedules' => $schedules,
        ]);
    }

    public function updateLink($id)
    {
        $platform = $this->request->getPost('platform');
        $link     = trim($this->request->getPost('meeting_link') ?? '');

        $this->scheduleModel->update($id, [
            'platform'     => $platform,
            'meeting_link' => $link,
        ]);

        return redirect()->back()->with('success', 'Tautan meeting kelas berhasil diperbarui!');
    }
}
