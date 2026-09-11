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

    public function getStudents()
    {
        return $this->select('users.*, students_meta.is_premium, students_meta.premium_start, students_meta.premium_expiry, students_meta.attendance_rate')
            ->join('students_meta', 'students_meta.user_id = users.id', 'left')
            ->where('users.role', 'murid')
            ->orderBy('users.id', 'ASC')
            ->findAll();
    }

    public function getStudentDetail($id)
    {
        return $this->select('users.*, students_meta.is_premium, students_meta.premium_start, students_meta.premium_expiry, students_meta.attendance_rate')
            ->join('students_meta', 'students_meta.user_id = users.id', 'left')
            ->where('users.id', $id)
            ->where('users.role', 'murid')
            ->first();
    }
}
