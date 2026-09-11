<?php

namespace App\Models;

use CodeIgniter\Model;

class ScheduleModel extends Model
{
    protected $table            = 'schedules';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['subject', 'day', 'date', 'start_time', 'end_time', 'platform', 'meeting_link', 'status'];
    protected $useTimestamps    = false;

    public function checkConflict($day, $startTime, $endTime, $ignoreId = null)
    {
        $builder = $this->where('day', $day)
            ->where('status', 'active');

        if ($ignoreId) {
            $builder->where('id !=', $ignoreId);
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
