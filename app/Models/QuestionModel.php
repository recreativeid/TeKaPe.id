<?php

namespace App\Models;

use CodeIgniter\Model;

class QuestionModel extends Model
{
    protected $table            = 'questions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['package_id', 'category_id', 'question_number', 'type', 'narrative', 'image_url', 'expected_answer', 'discussion', 'created_at', 'updated_at'];
    protected $useTimestamps    = false;
}
