<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PointSettingModel;
use App\Models\RewardModel;

class PointSettingsController extends BaseController
{
    protected PointSettingModel $pointSettingModel;
    protected RewardModel       $rewardModel;

    public function __construct()
    {
        $this->pointSettingModel = new PointSettingModel();
        $this->rewardModel       = new RewardModel();
        helper('upload');
    }

    public function index()
    {
        $settings = $this->pointSettingModel->getAllAsMap();

        $bulanIni = (int) date('n');
        $tahunIni = (int) date('Y');

        $hadiahBulanIni = $this->rewardModel->getHadiahBulan($bulanIni, $tahunIni);

        // Riwayat hadiah bulan-bulan sebelumnya
        $riwayatHadiah = $this->rewardModel
            ->orderBy('tahun', 'DESC')
            ->orderBy('bulan', 'DESC')
            ->orderBy('rank',  'ASC')
            ->findAll();

        return view('point_settings/index', [
            'settings'       => $settings,
            'hadiahBulanIni' => $hadiahBulanIni,
            'bulanIni'       => $bulanIni,
            'tahunIni'       => $tahunIni,
            'riwayatHadiah'  => $riwayatHadiah,
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
            $row = $this->pointSettingModel->where('activity_type', $activityType)->first();
            if ($row) {
                $this->pointSettingModel->update($row['id'], ['points' => (int) $points]);
            }
        }

        session()->setFlashdata(['msg' => 'Pengaturan poin berhasil disimpan.']);
        return redirect()->to('admin/pengaturan-poin');
    }

    // ── Simpan / update hadiah
    public function storeHadiah()
    {
        if (!$this->validate([
            'rank'        => 'required|in_list[1,2,3]',
            'nama_hadiah' => 'required|max_length[150]',
            'deskripsi'   => 'permit_empty|max_length[500]',
            'bulan'       => 'required|integer',
            'tahun'       => 'required|integer',
        ])) {
            session()->setFlashdata(['msg' => implode(' ', $this->validator->getErrors()), 'error' => true]);
            return redirect()->back();
        }

        $rank  = (int) $this->request->getPost('rank');
        $bulan = (int) $this->request->getPost('bulan');
        $tahun = (int) $this->request->getPost('tahun');

        // Cek apakah sudah ada hadiah untuk rank + bulan + tahun ini
        $existing = $this->rewardModel
            ->where('rank',  $rank)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->first();

        // Handle upload foto
        $foto     = null;
        $fileFoto = $this->request->getFile('foto');
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $namaFile = $fileFoto->getRandomName();
            $fileFoto->move(FCPATH . 'uploads/hadiah/', $namaFile);
            $foto = $namaFile;

            // Hapus foto lama jika update
            if ($existing && !empty($existing['foto'])) {
                $pathLama = FCPATH . 'uploads/hadiah/' . $existing['foto'];
                if (file_exists($pathLama)) unlink($pathLama);
            }
        } elseif ($existing) {
            $foto = $existing['foto']; // pertahankan foto lama
        }

        $data = [
            'rank'        => $rank,
            'bulan'       => $bulan,
            'tahun'       => $tahun,
            'nama_hadiah' => $this->request->getPost('nama_hadiah'),
            'deskripsi'   => $this->request->getPost('deskripsi') ?: null,
            'foto'        => $foto,
            'is_active'   => 1,
        ];

        if ($existing) {
            $this->rewardModel->update($existing['id'], $data);
            session()->setFlashdata(['msg' => 'Hadiah peringkat ' . $rank . ' berhasil diperbarui.']);
        } else {
            $this->rewardModel->insert($data);
            session()->setFlashdata(['msg' => 'Hadiah peringkat ' . $rank . ' berhasil ditambahkan.']);
        }

        return redirect()->to('admin/pengaturan-poin');
    }

    // ── Hapus hadiah ───────────────────────────────────────
    public function deleteHadiah($id = null)
    {
        $hadiah = $this->rewardModel->find($id);
        if (empty($hadiah)) {
            session()->setFlashdata(['msg' => 'Hadiah tidak ditemukan.', 'error' => true]);
            return redirect()->back();
        }

        if (!empty($hadiah['foto'])) {
            $path = FCPATH . 'uploads/hadiah/' . $hadiah['foto'];
            if (file_exists($path)) unlink($path);
        }

        $this->rewardModel->delete($id);
        session()->setFlashdata(['msg' => 'Hadiah berhasil dihapus.']);
        return redirect()->to('admin/pengaturan-poin');
    }

    // ── Toggle aktif/nonaktif hadiah ──────────────────────
    public function toggleHadiah($id = null)
    {
        $hadiah = $this->rewardModel->find($id);
        if (empty($hadiah)) {
            return redirect()->back();
        }
        $this->rewardModel->update($id, ['is_active' => $hadiah['is_active'] ? 0 : 1]);
        session()->setFlashdata(['msg' => 'Status hadiah berhasil diubah.']);
        return redirect()->to('admin/pengaturan-poin');
    }
}