<?php

namespace App\Models;

use CodeIgniter\Model;

class ScheduleModel extends Model
{
    protected $table            = 'schedules';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['subject', 'tentor_id', 'day', 'date', 'start_time', 'end_time', 'platform', 'meeting_link', 'status'];
    protected $useTimestamps    = false;

    public function getSchedulesWithTentor($day = null, $tentorId = null)
    {
        $builder = $this->db->table('schedules s')
            ->select('s.*, u.name as tentor_name, u.username as tentor_username')
            ->join('users u', 'u.id = s.tentor_id', 'left');

        if ($day) {
            $builder->where('s.day', $day);
        }
        if ($tentorId) {
            $builder->where('s.tentor_id', $tentorId);
        }

        return $builder->orderBy('s.start_time', 'ASC')->get()->getResultArray();
    }

    public function checkConflict($day, $startTime, $endTime, $ignoreId = null, $tentorId = null)
    {
        $builder = $this->where('day', $day)
            ->where('status', 'active');

        if ($ignoreId) {
            $builder->where('id !=', $ignoreId);
        }
        if ($tentorId) {
            $builder->where('tentor_id', $tentorId);
        }

        // Time overlap: existing start < new end AND existing end > new start
        $existing = $builder->findAll();
        foreach ($existing as $sc) {
            if ($sc['start_time'] < $endTime && $sc['end_time'] > $startTime) {
                return $sc; // Conflicting schedule
            }
        }
        return null;
    }
}
