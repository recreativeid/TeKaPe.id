<?php

namespace App\Models;

use CodeIgniter\Model;

class ScoringModel extends Model
{
    protected $table            = 'scoring_settings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['package_id', 'formula_type', 'twk_enabled', 'tiu_enabled', 'tkp_enabled', 'twk_score_rule', 'tiu_score_rule', 'tkp_score_rule'];
    protected $useTimestamps    = false;
}
