<?php

namespace App\Models;

use CodeIgniter\Model;

class PointSettingModel extends Model
{
    protected $table         = 'point_settings';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $protectFields = true;
    protected $allowedFields = ['activity_type', 'label', 'points'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Ambil poin berdasarkan activity_type
    public function getPoints(string $activityType): int
    {
        $row = $this->where('activity_type', $activityType)->first();
        return $row ? (int) $row['points'] : 0;
    }

    // Ambil semua sebagai array key => row
    public function getAllAsMap(): array
    {
        $rows = $this->findAll();
        $map  = [];
        foreach ($rows as $row) {
            $map[$row['activity_type']] = $row;
        }
        return $map;
    }
}