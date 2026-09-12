<?php

namespace App\Models;

use CodeIgniter\Model;

class PackageModel extends Model
{
    protected $table            = 'packages';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['title', 'description', 'type', 'price', 'duration_days', 'status', 'created_by', 'created_at', 'updated_at'];
    protected $useTimestamps    = false;

    public function getPackagesWithType($type = null, $createdBy = null)
    {
        $builder = $this->db->table('packages p');
        $builder->select('p.*, COUNT(q.id) as question_count, u.name as author_name');
        $builder->join('questions q', 'q.package_id = p.id', 'left');
        $builder->join('users u', 'u.id = p.created_by', 'left');
        if ($type !== null) {
            $builder->where('p.type', $type);
        }
        if ($createdBy !== null) {
            $builder->where('p.created_by', $createdBy);
        }
        $builder->groupBy('p.id');
        $builder->orderBy('p.id', 'DESC');
        return $builder->get()->getResultArray();
    }

    public function getPackageWithQuestions($packageId)
    {
        $package = $this->find($packageId);
        if (!$package) return null;

        $db = \Config\Database::connect();
        $questions = $db->table('questions q')
            ->select('q.*, c.code as category_code, c.name as category_name')
            ->join('categories c', 'c.id = q.category_id', 'left')
            ->where('q.package_id', $packageId)
            ->orderBy('q.question_number', 'ASC')
            ->get()->getResultArray();

        foreach ($questions as &$q) {
            $q['options'] = $db->table('question_options')
                ->where('question_id', $q['id'])
                ->orderBy('option_label', 'ASC')
                ->get()->getResultArray();
        }

        $package['questions'] = $questions;
        $package['categories'] = $db->table('categories')
            ->where('package_id', $packageId)
            ->get()->getResultArray();

        $package['scoring'] = $db->table('scoring_settings')
            ->where('package_id', $packageId)
            ->get()->getRowArray();

        return $package;
    }
}
