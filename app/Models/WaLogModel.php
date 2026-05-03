<?php

namespace App\Models;

use CodeIgniter\Model;

class WaLogModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'wa_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'loan_id',
        'member_name',
        'phone',
        'book_title',
        'type',
        'status',
        'message',
        'note',
        'sent_at',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
}