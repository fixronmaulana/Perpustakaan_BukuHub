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
$warnaBorder = [
    'visit'         => '#16a34a',
    'loan'          => '#2563eb',
    'return_ontime' => '#16a34a',
    'return_late'   => '#dc2626',
    'quiz'          => '#7c3aed',
];
?>

<!-- Kartu statistik -->
<div class="grid-stat" style="grid-template-columns:repeat(3,1fr);margin-bottom:1.25rem">

  <div class="kartu-stat-admin">
    <div class="ksa-body">
      <div class="ksa-icon">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
          <path d="M12 2l2.9 6.3L22 9.2l-5 4.9L18.2 22 12 18.6 5.8 22 7 14.1 2 9.2l7.1-.9L12 2z"/>
        </svg>
      </div>
      <div class="ksa-angka <?= $totalBulanIni < 0 ? 'tgl-terlambat' : '' ?>">
        <?= ($totalBulanIni >= 0 ? '+' : '') . $totalBulanIni ?>
      </div>
      <div class="ksa-label">Poin Bulan Ini</div>
    </div>
  </div>

  <div class="kartu-stat-admin">
    <div class="ksa-body">
      <div class="ksa-icon">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
          <path d="M12 2l2.9 6.3L22 9.2l-5 4.9L18.2 22 12 18.6 5.8 22 7 14.1 2 9.2l7.1-.9L12 2z"/>
        </svg>
      </div>
      <div class="ksa-angka"><?= $totalAllTime ?></div>
      <div class="ksa-label">Total Poin</div>
    </div>
  </div>

  <div class="kartu-stat-admin">
    <div class="ksa-body">
      <div class="ksa-icon">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
          <path d="M18 2h-2V1H8v1H6a1 1 0 00-1 1v3a5 5 0 004 4.9V13H7v2h10v-2h-2v-2.1A5 5 0 0019 6V3a1 1 0 00-1-1zm-1 4a3 3 0 01-2 2.83V4h2v2zm-10 0V4h2v4.83A3 3 0 017 6z"/>
        </svg>
      </div>
      <div class="ksa-angka"><?= $rankBulanIni > 0 ? '#' . $rankBulanIni : '—' ?></div>
      <div class="ksa-label">Peringkat Bulan Ini</div>
    </div>
  </div>

</div>

<!-- Riwayat Poin -->
<div class="kotak-konten">
  <div class="kepala-kotak">
    <h3>Riwayat Poin</h3>
  </div>
  <div class="bungkus-tabel">
    <table class="tabel-admin-member">
      <thead>
        <tr>
          <th>Aktivitas</th>
          <th>Keterangan</th>
          <th>Tanggal</th>
          <th class="teks-center">Poin</th>
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
            $isPositif   = $item['points'] >= 0;
            $label       = $labelAktivitas[$item['activity_type']] ?? $item['activity_type'];
            $warnaBorderKiri = $warnaBorder[$item['activity_type']] ?? ($isPositif ? '#16a34a' : '#dc2626');
          ?>
            <tr style="border-left:3px solid <?= $warnaBorderKiri ?>">
              <td>
                <div class="judul-tabel"><?= esc($label) ?></div>
              </td>
              <td class="penulis-tabel"><?= esc($item['description'] ?? '—') ?></td>
              <td class="tgl-normal">
                <?= Time::parse($item['created_at'])->format('d/m/Y') ?>
                <br><span class="penulis-tabel"><?= Time::parse($item['created_at'])->format('H:i') ?></span>
              </td>
              <td class="teks-center">
                <span style="font-weight:700;font-size:.92rem;
                             color:<?= $isPositif ? '#16a34a' : '#dc2626' ?>">
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