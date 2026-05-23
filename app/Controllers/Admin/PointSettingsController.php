<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PointSettingModel;
use App\Models\RewardModel;

class PointSettingsController extends BaseController
{
    protected PointSettingModel $pointSettingModel;
    protected RewardModel       $rewardModel;

    /**
     * Aktivitas yang nilainya HARUS positif (> 0)
     */
    private array $positiveActivities = ['visit', 'loan', 'return_ontime'];

    /**
     * Aktivitas yang nilainya HARUS negatif (< 0)
     * User input angka positif → otomatis dikonversi negatif
     */
    private array $negativeActivities = ['return_late'];

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

        $errors = [];

        foreach ($data as $activityType => $rawValue) {
            $value = (int) $rawValue;

            // ── Validasi aktivitas positif ──────────────────────────
            if (in_array($activityType, $this->positiveActivities)) {
                if ($value <= 0) {
                    $label = $this->getLabelByType($activityType);
                    $errors[] = "Poin <b>{$label}</b> harus bernilai positif (lebih dari 0).";
                }
            }

            // ── Aktivitas negatif: konversi otomatis ke negatif ─────
            if (in_array($activityType, $this->negativeActivities)) {
                // Jika user input positif, jadikan negatif
                if ($value > 0) {
                    $data[$activityType] = -$value;
                }
                // Jika user input 0, tolak
                if ($value === 0) {
                    $label = $this->getLabelByType($activityType);
                    $errors[] = "Poin <b>{$label}</b> tidak boleh bernilai 0.";
                }
            }
        }

        if (!empty($errors)) {
            session()->setFlashdata([
                'msg'   => implode('<br>', $errors),
                'error' => true,
            ]);
            return redirect()->back()->withInput();
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

    /**
     * Ambil label aktivitas untuk pesan error  
     */
    private function getLabelByType(string $type): string
    {
        $labels = [
            'visit'         => 'Kunjungan',
            'loan'          => 'Peminjaman',
            'return_ontime' => 'Pengembalian Tepat Waktu',
            'return_late'   => 'Pengembalian Terlambat',
        ];
        return $labels[$type] ?? $type;
    }

    // ── Simpan / update hadiah ─────────────────────────────
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

        $existing = $this->rewardModel
            ->where('rank',  $rank)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->first();

        $foto     = null;
        $fileFoto = $this->request->getFile('foto');
        if ($fileFoto && $fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $namaFile = $fileFoto->getRandomName();
            $fileFoto->move(FCPATH . 'uploads/hadiah/', $namaFile);
            $foto = $namaFile;

            if ($existing && !empty($existing['foto'])) {
                $pathLama = FCPATH . 'uploads/hadiah/' . $existing['foto'];
                if (file_exists($pathLama)) unlink($pathLama);
            }
        } elseif ($existing) {
            $foto = $existing['foto'];
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