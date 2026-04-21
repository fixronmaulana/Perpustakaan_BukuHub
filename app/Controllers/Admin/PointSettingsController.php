<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PointSettingModel;

class PointSettingsController extends BaseController
{
    protected PointSettingModel $pointSettingModel;

    public function __construct()
    {
        $this->pointSettingModel = new PointSettingModel();
    }

    public function index()
    {
        $settings = $this->pointSettingModel->getAllAsMap();

        return view('point_settings/index', [
            'settings' => $settings,
        ]);
    }

    public function update()
    {
        $data = $this->request->getPost('points');

        if (empty($data)) {
            session()->setFlashdata(['msg' => 'Tidak ada data yang diupdate.', 'error' => true]);
            return redirect()->back();
        }

        foreach ($data as $activityType => $points) {
            $row = $this->pointSettingModel
                ->where('activity_type', $activityType)
                ->first();

            if ($row) {
                $this->pointSettingModel->update($row['id'], [
                    'points' => (int) $points,
                ]);
            }
        }

        session()->setFlashdata(['msg' => 'Pengaturan poin berhasil disimpan.']);
        return redirect()->to('admin/pengaturan-poin');
    }
}