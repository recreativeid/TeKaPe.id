<?php

namespace App\Models;

use CodeIgniter\Model;

class TryoutModel extends Model
{
    protected $table            = 'tryout_sessions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'user_id', 'package_id', 'status', 'start_time', 'end_time', 'duration_seconds',
        'final_score', 'twk_score', 'tiu_score', 'tkp_score', 'correct_count', 'incorrect_count',
        'is_manual_edited', 'edited_by', 'edited_at', 'created_at'
    ];
    protected $useTimestamps    = false;

    public function getTryoutResultsWithDetails($packageType = null, $search = null)
    {
        $builder = $this->db->table('tryout_sessions ts');
        $builder->select('ts.*, u.name as student_name, u.username as student_username, p.title as package_title, p.type as package_type');
        $builder->join('users u', 'u.id = ts.user_id', 'inner');
        $builder->join('packages p', 'p.id = ts.package_id', 'inner');

        if ($packageType) {
            $builder->where('p.type', $packageType);
        }
        if ($search) {
            $builder->groupStart()
                ->like('u.name', $search)
                ->orLike('u.username', $search)
                ->orLike('p.title', $search)
                ->groupEnd();
        }

        $builder->orderBy('ts.id', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function getSessionWithAnswers($sessionId)
    {
        $session = $this->select('tryout_sessions.*, users.name as student_name, users.username as student_username, packages.title as package_title, packages.type as package_type')
            ->join('users', 'users.id = tryout_sessions.user_id')
            ->join('packages', 'packages.id = tryout_sessions.package_id')
            ->where('tryout_sessions.id', $sessionId)
            ->first();

        if (!$session) return null;

        $db = \Config\Database::connect();
        $answers = $db->table('tryout_answers ta')
            ->select('ta.*, q.narrative, q.type as question_type, q.question_number, q.image_url, q.discussion, q.expected_answer, c.code as category_code')
            ->join('questions q', 'q.id = ta.question_id', 'left')
            ->join('categories c', 'c.id = q.category_id', 'left')
            ->where('ta.session_id', $sessionId)
            ->orderBy('q.question_number', 'ASC')
            ->get()->getResultArray();

        foreach ($answers as &$a) {
            $a['options'] = $db->table('question_options')
                ->where('question_id', $a['question_id'])
                ->orderBy('option_label', 'ASC')
                ->get()->getResultArray();
        }

        $session['answers'] = $answers;
        return $session;
    }
}
