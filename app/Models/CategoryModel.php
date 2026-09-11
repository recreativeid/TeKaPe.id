<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table            = 'categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['package_id', 'code', 'name', 'is_active', 'max_score', 'scoring_rule'];
    protected $useTimestamps    = false;
}
