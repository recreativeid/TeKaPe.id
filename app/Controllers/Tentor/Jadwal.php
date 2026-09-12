<?php

namespace App\Controllers\Tentor;

use App\Controllers\BaseController;
use App\Models\ScheduleModel;

class Jadwal extends BaseController
{
    protected $scheduleModel;

    public function __construct()
    {
        $this->scheduleModel = new ScheduleModel();
    }

    public function index()
    {
        $tentorId = (int) session()->get('user_id');
        $schedules = $this->scheduleModel
            ->where('tentor_id', $tentorId)
            ->orderBy('date', 'ASC')
            ->orderBy('start_time', 'ASC')
            ->findAll();

        return view('tentor/jadwal/index', [
            'title'     => 'Jadwal Bimbel Saya - TeKaPe.id',
            'activeNav' => 'jadwal',
            'schedules' => $schedules,
            'role'      => 'tentor',
        ]);
    }

    public function save()
    {
        $tentorId = (int) session()->get('user_id');
        $subject  = $this->request->getPost('subject') ?? 'TWK';
        $day      = $this->request->getPost('day') ?? 'Senin';
        $date     = $this->request->getPost('date') ?: null;
        $start    = $this->request->getPost('start_time') ?? '19:00';
        $end      = $this->request->getPost('end_time') ?? '20:30';
        $platform = $this->request->getPost('platform') ?? 'Google Meet';
        $link     = trim($this->request->getPost('meeting_link') ?? '');

        if (empty($link)) {
            $link = $platform === 'Zoom' ? 'https://zoom.us/j/bimbel-' . strtolower($subject) : 'https://meet.google.com/tek-' . strtolower($subject) . '-live';
        }

        $this->scheduleModel->insert([
            'subject'      => $subject,
            'tentor_id'    => $tentorId,
            'day'          => $day,
            'date'         => $date,
            'start_time'   => $start,
            'end_time'     => $end,
            'platform'     => $platform,
            'meeting_link' => $link,
            'status'       => 'active',
        ]);

        return redirect()->to(base_url('tentor/jadwal'))->with('success', "Jadwal Bimbel {$subject} berhasil ditambahkan!");
    }

    public function update($id)
    {
        $tentorId = (int) session()->get('user_id');
        $schedule = $this->scheduleModel->find($id);

        if (!$schedule || (int)$schedule['tentor_id'] !== $tentorId) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $subject  = $this->request->getPost('subject') ?? $schedule['subject'];
        $day      = $this->request->getPost('day') ?? $schedule['day'];
        $date     = $this->request->getPost('date') ?: null;
        $start    = $this->request->getPost('start_time') ?? $schedule['start_time'];
        $end      = $this->request->getPost('end_time') ?? $schedule['end_time'];
        $platform = $this->request->getPost('platform') ?? $schedule['platform'];
        $link     = trim($this->request->getPost('meeting_link') ?? $schedule['meeting_link']);

        $this->scheduleModel->update($id, [
            'subject'      => $subject,
            'day'          => $day,
            'date'         => $date,
            'start_time'   => $start,
            'end_time'     => $end,
            'platform'     => $platform,
            'meeting_link' => $link,
        ]);

        return redirect()->to(base_url('tentor/jadwal'))->with('success', 'Jadwal berhasil diperbarui!');
    }

    public function delete($id)
    {
        $tentorId = (int) session()->get('user_id');
        $schedule = $this->scheduleModel->find($id);

        if (!$schedule || (int)$schedule['tentor_id'] !== $tentorId) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $this->scheduleModel->delete($id);
        return redirect()->to(base_url('tentor/jadwal'))->with('success', 'Jadwal berhasil dihapus.');
    }
}
