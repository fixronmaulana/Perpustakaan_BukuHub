<?php

namespace App\Models;

use CodeIgniter\Model;

class WaTemplateModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'wa_templates';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'type',
        'template_name',
        'message_template',
        'is_active',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Ambil template aktif berdasarkan tipe
     * $type: 'before_due' atau 'overdue'
     */
    public function getActiveTemplate(string $type): ?array
    {
        return $this->where('type', $type)
            ->where('is_active', 1)
            ->orderBy('id', 'DESC')
            ->first();
    }
}