<?= $this->extend('layouts/member_layout') ?>

<?= $this->section('head') ?>
<title>Poin Saya — Portal Anggota</title>
<?= $this->endSection() ?>

<?= $this->section('pageTitle') ?>Poin Saya<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php use CodeIgniter\I18n\Time; ?>

<?php
$labelAktivitas = [
    'visit'         => 'Kunjungan Perpustakaan',
    'loan'          => 'Peminjaman Buku',
    'return_ontime' => 'Pengembalian Tepat Waktu',
    'return_late'   => 'Pengembalian Terlambat',
    'quiz'          => 'Kuis Buku',
];
$ikonAktivitas = [
    'visit'         => 'ti-door-enter',
    'loan'          => 'ti-book',
    'return_ontime' => 'ti-check',
    'return_late'   => 'ti-clock-exclamation',
    'quiz'          => 'ti-help-circle',
];
$warnaBorder = [
    'visit'         => '#16a34a',
    'loan'          => '#2563eb',
    'return_ontime' => '#16a34a',
    'return_late'   => '#dc2626',
    'quiz'          => '#7c3aed',
];
?>

<div class="grid-stat">
  <div class="kartu-stat-admin">
    <div class="ksa-body">
      <div class="ksa-icon">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
      </div>
      <div class="ksa-angka <?= $totalBulanIni < 0 ? 'tgl-terlambat' : '' ?>"><?= $totalBulanIni ?></div>
      <div class="ksa-label">Poin Bulan Ini</div>
    </div>
  </div>

  <div class="kartu-stat-admin">
    <div class="ksa-body">
      <div class="ksa-icon">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      </div>
      <div class="ksa-angka"><?= $totalAllTime ?></div>
      <div class="ksa-label">Total Poin</div>
    </div>
  </div>

  <div class="kartu-stat-admin">
    <div class="ksa-body">
      <div class="ksa-icon">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6"  y1="20" x2="6"  y2="14"/></svg>
      </div>
      <div class="ksa-angka"><?= $rankBulanIni > 0 ? $rankBulanIni : '—' ?></div>
      <div class="ksa-label">Peringkat Bulan Ini</div>
    </div>
  </div>
</div>

<div class="grid-poin-atas">
  <div class="kotak-konten">
    <div class="kepala-kotak">
      <h3 style="margin:0">Grafik Poin Keaktifan</h3>
    </div>
    <div class="chart-container">
      <canvas style="padding: 1rem;" id="chartPoin"></canvas>
    </div>
  </div>
</div>

<div class="kotak-konten">
  <div class="kepala-kotak">
    <h3 style="margin:0">Riwayat Poin</h3>
    <div class="filter-status">
      <button class="pil-filter aktif" onclick="filterPoin(this,'semua')">Semua</button>
      <button class="pil-filter" onclick="filterPoin(this,'visit')">Kunjungan</button>
      <button class="pil-filter" onclick="filterPoin(this,'loan')">Pinjam</button>
      <button class="pil-filter" onclick="filterPoin(this,'return_ontime')">Tepat Waktu</button>
      <button class="pil-filter" onclick="filterPoin(this,'return_late')">Terlambat</button>
      <button class="pil-filter" onclick="filterPoin(this,'quiz')">Kuis</button>
    </div>
  </div>

  <div class="table-responsive-custom">
    <table class="table table-hover mb-0" id="tabel-poin">
      <thead>
        <tr>
          <th>Aktivitas</th>
          <th>Keterangan</th>
          <th>Tanggal</th>
          <th class="text-center">Poin</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($riwayat)): ?>
          <tr><td colspan="4" class="text-center py-5">Belum ada riwayat poin</td></tr>
        <?php else: ?>
          <?php foreach ($riwayat as $item):
            $isPositif = $item['points'] >= 0;
            $label     = $labelAktivitas[$item['activity_type']] ?? $item['activity_type'];
            $ikon      = $ikonAktivitas[$item['activity_type']]  ?? 'ti-star';
            $warna     = $warnaBorder[$item['activity_type']]    ?? '#1e3a8a';
            $bg        = $isPositif ? '#f0fdf4' : '#fef2f2';
          ?>
            <tr data-tipe="<?= $item['activity_type'] ?>">
              <td>
                <div style="display:inline-flex;align-items:center;gap:10px">
                  <span style="width:32px;height:32px;border-radius:8px;background:<?= $bg ?>;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="ti <?= $ikon ?>" style="color:<?= $warna ?>;font-size:.9rem"></i>
                  </span>
                  <span class="judul-tabel" style="font-size:0.85rem"><?= esc($label) ?></span>
                </div>
              </td>
              <td class="penulis-tabel"><?= esc($item['description'] ?? '—') ?></td>
              <td>
                <b style="font-size:0.85rem"><?= Time::parse($item['created_at'])->format('d/m/Y') ?></b><br>
                <small class="penulis-tabel"><?= Time::parse($item['created_at'])->format('H:i') ?></small>
              </td>
              <td class="text-center">
                <span style="font-weight:700;color:<?= $isPositif ? '#16a34a' : '#dc2626' ?>">
                  <?= ($isPositif ? '+' : '−') . abs($item['points']) ?>
                </span>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('chartPoin').getContext('2d');
const chartData = <?= json_encode($chartPoin ?? []) ?>;
const labels = chartData.map(d => d.bulan);
const data   = chartData.map(d => d.total);

new Chart(ctx, {
  type: 'bar',
  data: {
    labels,
    datasets: [{
      label: 'Poin',
      data,
      backgroundColor: data.map(v => v >= 0 ? 'rgba(30,58,138,0.15)' : 'rgba(220,38,38,0.15)'),
      borderColor:     data.map(v => v >= 0 ? '#1e3a8a' : '#dc2626'),
      borderWidth: 2,
      borderRadius: 5,
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false, // WAJIB agar chart mengikuti tinggi container CSS
    plugins: {
      legend: { display: false },
      tooltip: { 
          backgroundColor: '#1e293b',
          callbacks: { label: ctx => (ctx.raw >= 0 ? '+' : '') + ctx.raw + ' poin' } 
      }
    },
    scales: {
      y: { 
          beginAtZero: true, 
          grid: { color: '#f1f5f9' }, 
          ticks: { font: { size: 11, family: 'Inter' }, color: '#94a3b8' } 
      },
      x: { 
          grid: { display: false }, 
          ticks: { font: { size: 11, family: 'Inter' }, color: '#94a3b8' } 
      }
    }
  }
});

function filterPoin(btn, tipe) {
  document.querySelectorAll('.pil-filter').forEach(b => b.classList.remove('aktif'));
  btn.classList.add('aktif');
  document.querySelectorAll('#tabel-poin tbody tr').forEach(tr => {
    tr.style.display = tipe === 'semua' || tr.dataset.tipe === tipe ? '' : 'none';
  });
}
</script>
<?= $this->endSection() ?>