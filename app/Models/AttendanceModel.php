<?php

namespace App\Models;

use CodeIgniter\Model;

class AttendanceModel extends Model
{
    protected $table            = 'attendances';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['user_id', 'schedule_id', 'subject', 'date', 'status', 'created_at'];
    protected $useTimestamps    = false;

    public function getAttendanceWithStudent($filterDate = null, $filterSubject = null, $filterStudent = null)
    {
        $builder = $this->db->table('attendances a');
        $builder->select('a.*, u.name as student_name, u.username as student_username');
        $builder->join('users u', 'u.id = a.user_id', 'inner');

        if ($filterDate) {
            $builder->where('a.date', $filterDate);
        }
        if ($filterSubject) {
            $builder->where('a.subject', $filterSubject);
        }
        if ($filterStudent) {
            $builder->groupStart()
                ->like('u.name', $filterStudent)
                ->orLike('u.username', $filterStudent)
                ->groupEnd();
        }

        $builder->orderBy('a.date', 'DESC');
        return $builder->get()->getResultArray();
    }
}
