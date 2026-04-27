<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Leaderboard — Admin</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
$namaBulan = [
    1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',
    5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',
    9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
];
$bulanLabel = ($namaBulan[$bulan] ?? $bulan) . ' ' . $tahun;
$isRealtime = ($bulan === $bulanIni && $tahun === $tahunIni);
?>

<!-- Header -->
<div class="card mb-3">
  <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div class="d-flex align-items-center gap-2">
      <h5 class="fw-semibold mb-0">Leaderboard</h5>
      <span class="badge bg-primary"><?= $bulanLabel ?></span>
      <?php if ($isRealtime): ?>
        <span class="badge bg-success">Live</span>
      <?php endif; ?>
    </div>
    <form method="get" action="" class="d-flex align-items-center gap-2">
      <label class="text-muted small mb-0">Periode:</label>
      <select name="bulan" class="form-select form-select-sm" style="width:auto" id="selectBulan">
        <?php foreach ($daftarBulan as $db): ?>
          <option value="<?= $db['bulan'] ?>"
                  data-tahun="<?= $db['tahun'] ?>"
                  <?= ($db['bulan'] == $bulan && $db['tahun'] == $tahun) ? 'selected' : '' ?>>
            <?= esc($db['label']) ?>
          </option>
        <?php endforeach; ?>
      </select>
      <input type="hidden" name="tahun" id="tahunInput" value="<?= $tahun ?>">
    </form>
  </div>
</div>

<!-- Tabel -->
<div class="card">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="card-title fw-semibold mb-0">Semua Peringkat</h5>
      <span class="text-muted small"><?= count($leaderboard) ?> anggota</span>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0" style="min-width:800px">
        <thead class="table-light">
          <tr>
            <th style="width:56px" class="text-center">#</th>
            <th>Anggota</th>
            <th class="text-center border-start" title="Poin dari kunjungan">
              <span class="d-block" style="font-size:.65rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:.4px">Kunjungan</span>
            </th>
            <th class="text-center" title="Poin dari peminjaman buku">
              <span class="d-block" style="font-size:.65rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:.4px">Peminjaman</span>
            </th>
            <th class="text-center" title="Poin pengembalian tepat waktu">
              <span class="d-block" style="font-size:.65rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:.4px">Tepat Waktu</span>
            </th>
            <th class="text-center" title="Pengurangan poin terlambat">
              <span class="d-block" style="font-size:.65rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:.4px">Terlambat</span>
            </th>
            <th class="text-center" title="Poin dari kuis buku">
              <span class="d-block" style="font-size:.65rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:.4px">Kuis</span>
            </th>
            <th class="text-center border-start" style="width:110px">
              <span class="d-block" style="font-size:.65rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:.4px">Total Poin</span>
            </th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($leaderboard)): ?>
            <tr>
              <td colspan="8" class="text-center py-5 text-muted">
                <i class="ti ti-chart-bar" style="font-size:2.5rem;display:block;margin-bottom:.5rem;opacity:.3"></i>
                Belum ada data leaderboard bulan ini
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($leaderboard as $i => $row):
              $rank           = $i + 1;
              $nama           = ucwords(strtolower(trim($row['first_name'] . ' ' . ($row['last_name'] ?? ''))));
              $inisial        = strtoupper(substr($row['first_name'], 0, 1) . substr($row['last_name'] ?? '', 0, 1));
              $adaFoto        = !empty($row['foto_profil']) && file_exists(FCPATH . 'uploads/foto_profil/' . $row['foto_profil']);
              $poinKunjungan  = (int) ($row['poin_kunjungan']  ?? 0);
              $poinPeminjaman = (int) ($row['poin_peminjaman'] ?? 0);
              $poinTepat      = (int) ($row['poin_tepat']      ?? 0);
              $poinTerlambat  = (int) ($row['poin_terlambat']  ?? 0);
              $poinKuis       = (int) ($row['poin_kuis']       ?? 0);
              // Warna baris top 3
              $bgBaris = '';
              if ($rank === 1) $bgBaris = 'background:#fffbeb';
              elseif ($rank === 2) $bgBaris = 'background:#f8fafc';
              elseif ($rank === 3) $bgBaris = 'background:#fff7ed';
            ?>
              <tr style="<?= $bgBaris ?>">
                <!-- Rank -->
                <td class="text-center">
                  <?php if ($rank === 1): ?>
                    <span style="font-size:1.15rem">🥇</span>
                  <?php elseif ($rank === 2): ?>
                    <span style="font-size:1.15rem">🥈</span>
                  <?php elseif ($rank === 3): ?>
                    <span style="font-size:1.15rem">🥉</span>
                  <?php else: ?>
                    <span class="text-muted small"><?= $rank ?></span>
                  <?php endif; ?>
                </td>

                <!-- Anggota -->
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <div style="width:34px;height:34px;border-radius:50%;flex-shrink:0;overflow:hidden;
                                background:#e2e8f0;display:flex;align-items:center;justify-content:center;
                                font-size:.7rem;font-weight:700;color:#64748b;border:2px solid #f1f5f9">
                      <?php if ($adaFoto): ?>
                        <img src="<?= base_url('uploads/foto_profil/' . $row['foto_profil']) ?>"
                             style="width:100%;height:100%;object-fit:cover" alt="">
                      <?php else: ?>
                        <?= esc($inisial) ?>
                      <?php endif; ?>
                    </div>
                    <div>
                      <div class="fw-semibold small"><?= esc($nama) ?></div>
                      <div class="text-muted" style="font-size:.72rem">
                        <?= esc($row['no_identitas']) ?>
                        <span class="badge bg-light text-secondary border ms-1" style="font-size:.6rem">
                          <?= esc($row['tipe_anggota']) ?>
                        </span>
                      </div>
                    </div>
                  </div>
                </td>

                <!-- Kunjungan -->
                <td class="text-center border-start">
                  <?php if ($poinKunjungan > 0): ?>
                    <span class="badge" style="background:#f0fdf4;color:#16a34a;font-size:.72rem;font-weight:600">
                      +<?= $poinKunjungan ?>
                    </span>
                  <?php else: ?>
                    <span class="text-muted" style="font-size:.8rem">—</span>
                  <?php endif; ?>
                </td>

                <!-- Peminjaman -->
                <td class="text-center">
                  <?php if ($poinPeminjaman > 0): ?>
                    <span class="badge" style="background:#f0fdf4;color:#16a34a;font-size:.72rem;font-weight:600">
                      +<?= $poinPeminjaman ?>
                    </span>
                  <?php else: ?>
                    <span class="text-muted" style="font-size:.8rem">—</span>
                  <?php endif; ?>
                </td>

                <!-- Tepat Waktu -->
                <td class="text-center">
                  <?php if ($poinTepat > 0): ?>
                    <span class="badge" style="background:#f0fdf4;color:#16a34a;font-size:.72rem;font-weight:600">
                      +<?= $poinTepat ?>
                    </span>
                  <?php else: ?>
                    <span class="text-muted" style="font-size:.8rem">—</span>
                  <?php endif; ?>
                </td>

                <!-- Terlambat -->
                <td class="text-center">
                  <?php if ($poinTerlambat < 0): ?>
                    <span class="badge" style="background:#fef2f2;color:#dc2626;font-size:.72rem;font-weight:600">
                      <?= $poinTerlambat ?>
                    </span>
                  <?php else: ?>
                    <span class="text-muted" style="font-size:.8rem">—</span>
                  <?php endif; ?>
                </td>

                <!-- Kuis -->
                <td class="text-center">
                  <?php if ($poinKuis > 0): ?>
                    <span class="badge" style="background:#f0fdf4;color:#16a34a;font-size:.72rem;font-weight:600">
                      +<?= $poinKuis ?>
                    </span>
                  <?php else: ?>
                    <span class="text-muted" style="font-size:.8rem">—</span>
                  <?php endif; ?>
                </td>

                <!-- Total -->
                <td class="text-center border-start">
                  <span class="fw-bold" style="color:<?= $row['total_points'] >= 0 ? '#16a34a' : '#dc2626' ?>;font-size:.9rem">
                    <?= ($row['total_points'] >= 0 ? '+' : '') . number_format($row['total_points']) ?>
                  </span>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
document.getElementById('selectBulan').addEventListener('change', function() {
  document.getElementById('tahunInput').value = this.options[this.selectedIndex].dataset.tahun;
  this.form.submit();
});
</script>

<?= $this->endSection() ?>