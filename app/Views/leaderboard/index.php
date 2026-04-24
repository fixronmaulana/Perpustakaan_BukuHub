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
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th style="width:60px" class="text-center">#</th>
            <th>Anggota</th>
            <th>Tipe</th>
            <th class="text-center">Poin</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($leaderboard)): ?>
            <tr>
              <td colspan="4" class="text-center py-5 text-muted">
                <i class="ti ti-chart-bar" style="font-size:2.5rem;display:block;margin-bottom:.5rem;opacity:.3"></i>
                Belum ada data leaderboard bulan ini
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($leaderboard as $i => $row):
              $rank    = $i + 1;
              $nama    = ucwords(strtolower(trim($row['first_name'] . ' ' . ($row['last_name'] ?? ''))));
              $inisial = strtoupper(substr($row['first_name'], 0, 1) . substr($row['last_name'] ?? '', 0, 1));
              $adaFoto = !empty($row['foto_profil']) && file_exists(FCPATH . 'uploads/foto_profil/' . $row['foto_profil']);
            ?>
              <tr>
                <td class="text-center fw-semibold">
                  <?php if ($rank === 1): ?>
                    <span style="font-size:1.2rem">🥇</span>
                  <?php elseif ($rank === 2): ?>
                    <span style="font-size:1.2rem">🥈</span>
                  <?php elseif ($rank === 3): ?>
                    <span style="font-size:1.2rem">🥉</span>
                  <?php else: ?>
                    <span class="text-muted"><?= $rank ?></span>
                  <?php endif; ?>
                </td>
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <!-- Avatar -->
                    <div style="width:36px;height:36px;border-radius:50%;flex-shrink:0;overflow:hidden;
                                background:#e2e8f0;display:flex;align-items:center;justify-content:center;
                                font-size:.72rem;font-weight:700;color:#64748b;border:2px solid #f1f5f9">
                      <?php if ($adaFoto): ?>
                        <img src="<?= base_url('uploads/foto_profil/' . $row['foto_profil']) ?>"
                             style="width:100%;height:100%;object-fit:cover" alt="">
                      <?php else: ?>
                        <?= esc($inisial) ?>
                      <?php endif; ?>
                    </div>
                    <div>
                      <div class="fw-semibold small"><?= esc($nama) ?></div>
                      <div class="text-muted" style="font-size:.75rem"><?= esc($row['no_identitas']) ?></div>
                    </div>
                  </div>
                </td>
                <td>
                  <span class="badge bg-light text-secondary border">
                    <?= esc($row['tipe_anggota']) ?>
                  </span>
                </td>
                <td class="text-center">
                  <span class="fw-bold" style="color:<?= $row['total_points'] >= 0 ? '#16a34a' : '#dc2626' ?>">
                    <?= ($row['total_points'] >= 0 ? '+' : '') . $row['total_points'] ?>
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
  const opt = this.options[this.selectedIndex];
  document.getElementById('tahunInput').value = opt.dataset.tahun;
  this.form.submit();
});
</script>

<?= $this->endSection() ?>