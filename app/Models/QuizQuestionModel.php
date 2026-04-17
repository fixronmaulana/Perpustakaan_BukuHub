<?php

namespace App\Models;

use CodeIgniter\Model;

class QuizQuestionModel extends Model
{
    protected $table            = 'quiz_questions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'quiz_id',
        'question',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_answer',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}