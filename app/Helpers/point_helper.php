<?php

/**
 * Point Helper
 * Dipanggil dari controller setelah setiap aktivitas yang menghasilkan poin
 *
 * Cara pakai:
 * catat_poin($memberId, 'visit', 5, 'Kunjungan perpustakaan', $visitId, 'visit');
 */

if (!function_exists('catat_poin')) {
    function catat_poin(
        int    $memberId,
        string $activityType,
        int    $points,
        string $description,
        ?int   $referenceId   = null,
        ?string $referenceType = null
    ): void {
        $model = new \App\Models\PointTransactionModel();
        $model->insert([
            'member_id'      => $memberId,
            'activity_type'  => $activityType,
            'points'         => $points,
            'description'    => $description,
            'reference_id'   => $referenceId,
            'reference_type' => $referenceType,
        ]);
    }
}

if (!function_exists('get_poin_setting')) {
    function get_poin_setting(string $activityType): int
    {
        $model = new \App\Models\PointSettingModel();
        return $model->getPoints($activityType);
    }
}