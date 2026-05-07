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

<!-- Kartu Statistik -->
<!-- Kartu Statistik -->
<div class="grid-stat" style="grid-template-columns:repeat(3,1fr);margin-bottom:1.25rem">

  <!-- Poin Bulan Ini -->
  <div class="kartu-stat-admin">
    <div class="ksa-body">
      <div class="ksa-icon">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
          <polyline points="17 6 23 6 23 12"/>
        </svg>
      </div>
      <div class="ksa-angka <?= $totalBulanIni < 0 ? 'tgl-terlambat' : '' ?>">
        <?= $totalBulanIni ?>
      </div>
      <div class="ksa-label">Poin Bulan Ini</div>
    </div>
  </div>

  <!-- Total Poin -->
  <div class="kartu-stat-admin">
    <div class="ksa-body">
      <div class="ksa-icon">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"/>
          <polyline points="12 6 12 12 16 14"/>
        </svg>
      </div>
      <div class="ksa-angka"><?= $totalAllTime ?></div>
      <div class="ksa-label">Total Poin</div>
    </div>
  </div>

  <!-- Peringkat -->
  <div class="kartu-stat-admin">
    <div class="ksa-body">
      <div class="ksa-icon">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <line x1="18" y1="20" x2="18" y2="10"/>
          <line x1="12" y1="20" x2="12" y2="4"/>
          <line x1="6"  y1="20" x2="6"  y2="14"/>
        </svg>
      </div>
      <div class="ksa-angka"><?= $rankBulanIni > 0 ? $rankBulanIni : '—' ?></div>
      <div class="ksa-label">Peringkat Bulan Ini</div>
    </div>
  </div>

</div>

<!-- Grid: Chart + Breakdown -->
<div class="grid-poin-atas">

  <!-- Chart Poin per Bulan -->
  <div class="kotak-konten tanpa-margin">
    <div class="kepala-kotak">
      <h3>Grafik Poin Keaktifan</h3>
    </div>
    <div style="padding:1rem 1.25rem 1.25rem">
      <canvas id="chartPoin" height="180"></canvas>
    </div>
  </div>

  <!-- Breakdown per Aktivitas -->
  <div class="kotak-konten tanpa-margin">
    <div class="kepala-kotak">
      <h3>Breakdown Aktivitas</h3>
    </div>
    <div style="padding:0.5rem 0">
      <?php
        $breakdown = [
          'visit'         => ['label' => 'Kunjungan',    'icon' => 'ti-door-enter',        'warna' => '#16a34a', 'bg' => '#f0fdf4'],
          'loan'          => ['label' => 'Peminjaman',   'icon' => 'ti-book',              'warna' => '#2563eb', 'bg' => '#eff6ff'],
          'return_ontime' => ['label' => 'Tepat Waktu',  'icon' => 'ti-check',             'warna' => '#16a34a', 'bg' => '#f0fdf4'],
          'return_late'   => ['label' => 'Terlambat',    'icon' => 'ti-clock-exclamation', 'warna' => '#dc2626', 'bg' => '#fef2f2'],
          'quiz'          => ['label' => 'Kuis',         'icon' => 'ti-help-circle',       'warna' => '#7c3aed', 'bg' => '#f5f3ff'],
        ];
        foreach ($breakdown as $type => $info):
          $poin = $poinPerAktivitas[$type] ?? 0;
          $isPos = $poin >= 0;
      ?>
        <div style="display:flex;align-items:center;gap:12px;padding:0.75rem 1.25rem;border-bottom:1px solid #f1f5f9">
          <div style="width:34px;height:34px;border-radius:8px;background:<?= $info['bg'] ?>;
                      display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <i class="ti <?= $info['icon'] ?>" style="color:<?= $info['warna'] ?>;font-size:1rem"></i>
          </div>
          <div style="flex:1">
            <div style="font-size:0.82rem;font-weight:600;color:#1a2340"><?= $info['label'] ?></div>
          </div>
          <div style="font-size:0.9rem;font-weight:700;color:<?= $isPos ? '#16a34a' : '#dc2626' ?>">
            <?= ($isPos ? '+' : '') . $poin ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

</div>

<!-- Tabel Riwayat Poin -->
<div class="kotak-konten" style="margin-top:1.25rem">
  <div class="kepala-kotak" style="flex-wrap:wrap;gap:0.5rem">
    <h3>Riwayat Poin</h3>
    <div class="filter-status">
      <button class="pil-filter aktif" onclick="filterPoin(this,'semua')">Semua</button>
      <button class="pil-filter" onclick="filterPoin(this,'visit')">Kunjungan</button>
      <button class="pil-filter" onclick="filterPoin(this,'loan')">Peminjaman</button>
      <button class="pil-filter" onclick="filterPoin(this,'return_ontime')">Tepat Waktu</button>
      <button class="pil-filter" onclick="filterPoin(this,'return_late')">Terlambat</button>
      <button class="pil-filter" onclick="filterPoin(this,'quiz')">Kuis</button>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-hover table-sm mb-0" id="tabel-poin">
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
          <tr>
            <td colspan="4">
              <div class="kondisi-kosong">
                <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                <p>Belum ada riwayat poin</p>
              </div>
            </td>
          </tr>
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
                <div style="display:inline-flex;align-items:center;gap:8px">
                  <span style="width:32px;height:32px;border-radius:8px;background:<?= $bg ?>;
                               display:flex;align-items:center;justify-content:center;flex-shrink:0">
                    <i class="ti <?= $ikon ?>" style="color:<?= $warna ?>;font-size:.9rem"></i>
                  </span>
                  <span class="judul-tabel"><?= esc($label) ?></span>
                </div>
              </td>
              <td class="penulis-tabel"><?= esc($item['description'] ?? '—') ?></td>
              <td>
                <b><?= Time::parse($item['created_at'])->format('d/m/Y') ?></b>
                <br><span class="penulis-tabel"><?= Time::parse($item['created_at'])->format('H:i') ?></span>
              </td>
              <td class="text-center">
                <span style="font-weight:700;font-size:.92rem;color:<?= $isPositif ? '#16a34a' : '#dc2626' ?>">
                  <?= ($isPositif ? '+' : '−') . abs($item['points']) ?>
                </span>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  <?php if ($pager): ?>
    <div style="padding:1rem 1.25rem;border-top:1px solid #f1f5f9">
      <?= $pager->links('poin', 'member_pager') ?>
    </div>
  <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// ── Chart Poin per Bulan ──
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
      borderRadius: 6,
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { display: false },
      tooltip: {
        callbacks: {
          label: ctx => (ctx.raw >= 0 ? '+' : '') + ctx.raw + ' poin'
        }
      }
    },
    scales: {
      y: {
        beginAtZero: true,
        grid: { color: '#f1f5f9' },
        ticks: { font: { size: 11 } }
      },
      x: {
        grid: { display: false },
        ticks: { font: { size: 11 } }
      }
    }
  }
});

// ── Filter Tabel ──
function filterPoin(btn, tipe) {
  document.querySelectorAll('.pil-filter').forEach(b => b.classList.remove('aktif'));
  btn.classList.add('aktif');
  document.querySelectorAll('#tabel-poin tbody tr').forEach(tr => {
    tr.style.display = tipe === 'semua' || tr.dataset.tipe === tipe ? '' : 'none';
  });
}
</script>

<style>
.grid-poin-atas {
  display: grid;
  grid-template-columns: 1.5fr 1fr;
  gap: 1.25rem;
  margin-bottom: 0;
}

@media (max-width: 768px) {
  .grid-poin-atas {
    grid-template-columns: 1fr;
  }
}
</style>
<?= $this->endSection() ?>