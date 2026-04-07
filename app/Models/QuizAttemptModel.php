<?php

namespace App\Models;

use CodeIgniter\Model;

class QuizAttemptModel extends Model
{
    protected $table            = 'quiz_attempts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'quiz_id',
        'member_id',
        'score',
        'total',
        'started_at',
        'finished_at',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}