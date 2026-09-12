<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['name', 'username', 'password_hash', 'role', 'phone_whatsapp', 'created_at', 'updated_at'];
    protected $useTimestamps    = false;

    public function getStudents($tentorId = null)
    {
        $builder = $this->select('users.*, students_meta.is_premium, students_meta.premium_start, students_meta.premium_expiry, students_meta.attendance_rate, students_meta.assigned_tentor_id, tentor.name as assigned_tentor_name')
            ->join('students_meta', 'students_meta.user_id = users.id', 'left')
            ->join('users tentor', 'tentor.id = students_meta.assigned_tentor_id', 'left')
            ->where('users.role', 'murid');

        if ($tentorId !== null) {
            $db = \Config\Database::connect();
            // Get student IDs who took tryouts for packages created by this tentor
            $tryoutUserIds = $db->table('tryout_sessions ts')
                ->select('ts.user_id')
                ->join('packages p', 'p.id = ts.package_id')
                ->where('p.created_by', $tentorId)
                ->get()->getResultArray();
            $userIds = array_unique(array_column($tryoutUserIds, 'user_id'));

            $builder->groupStart()
                ->where('students_meta.assigned_tentor_id', $tentorId);
            if (!empty($userIds)) {
                $builder->orWhereIn('users.id', $userIds);
            }
            $builder->groupEnd();
        }

        return $builder->orderBy('users.id', 'ASC')->findAll();
    }

    public function getStudentDetail($id)
    {
        return $this->select('users.*, students_meta.is_premium, students_meta.premium_start, students_meta.premium_expiry, students_meta.attendance_rate, students_meta.assigned_tentor_id, tentor.name as assigned_tentor_name')
            ->join('students_meta', 'students_meta.user_id = users.id', 'left')
            ->join('users tentor', 'tentor.id = students_meta.assigned_tentor_id', 'left')
            ->where('users.id', $id)
            ->where('users.role', 'murid')
            ->first();
    }

    public function getTentors()
    {
        return $this->where('role', 'tentor')->orderBy('id', 'ASC')->findAll();
    }
}
