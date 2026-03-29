<?= $this->extend('layouts/member_layout') ?>

<?= $this->section('head') ?>
<title>Pengembalian — Portal Anggota</title>
<?= $this->endSection() ?>

<?= $this->section('pageTitle') ?>Pengembalian<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php use CodeIgniter\I18n\Time; ?>

<!-- Statistik -->
<div class="grid-stat" style="margin-bottom:1.25rem">
  <div class="kartu-stat-admin">
    <div class="ksa-body">
      <div class="ksa-icon">
        <svg viewBox="0 0 24 24" width="22" height="22"><polyline points="9 14 4 9 9 4"/><path d="M20 20v-7a4 4 0 00-4-4H4"/></svg>
      </div>
      <div class="ksa-angka"><?= $totalKembali ?></div>
      <div class="ksa-label">Total Dikembalikan</div>
    </div>
  </div>
  <div class="kartu-stat-admin">
    <div class="ksa-body">
      <div class="ksa-icon">
        <svg viewBox="0 0 24 24" width="22" height="22"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
      </div>
      <div class="ksa-angka"><?= $tepatWaktu ?></div>
      <div class="ksa-label">Tepat Waktu</div>
    </div>
  </div>
  <div class="kartu-stat-admin">
    <div class="ksa-body">
      <div class="ksa-icon">
        <svg viewBox="0 0 24 24" width="22" height="22"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      </div>
      <div class="ksa-angka"><?= $terlambat ?></div>
      <div class="ksa-label">Terlambat</div>
    </div>
  </div>
</div>

<!-- Tabel -->
<div class="kotak-konten">
  <div class="kepala-kotak">
    <h3>Riwayat Pengembalian</h3>
    <div class="filter-status">
      <button class="pil-filter aktif" onclick="filterKembali(this,'semua')">Semua</button>
      <button class="pil-filter" onclick="filterKembali(this,'tepat-waktu')">Tepat Waktu</button>
      <button class="pil-filter" onclick="filterKembali(this,'terlambat')">Terlambat</button>
    </div>
  </div>

  <div class="bungkus-tabel">
    <table class="tabel-admin-member" id="tabel-pengembalian">
      <thead>
        <tr>
          <th style="width:40px">#</th>
          <th>Judul Buku</th>
          <th>Tgl Pinjam</th>
          <th>Tenggat</th>
          <th>Tgl Kembali</th>
          <th>Keterlambatan</th>
          <th class="teks-center">Status</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($returns)): ?>
          <tr>
            <td colspan="7">
              <div class="kondisi-kosong">
                <svg viewBox="0 0 24 24"><polyline points="9 14 4 9 9 4"/><path d="M20 20v-7a4 4 0 00-4-4H4"/></svg>
                <p>Belum ada riwayat pengembalian</p>
              </div>
            </td>
          </tr>
        <?php else: ?>
          <?php $i = 1; foreach ($returns as $ret):
            $isLate   = $ret['is_late'];
            $daysLate = $ret['days_late'];
            $statusKey = $isLate ? 'terlambat' : 'tepat-waktu';
          ?>
            <tr data-status="<?= $statusKey ?>">
              <td class="teks-redup-sm"><?= $i++ ?></td>
              <td>
                <div class="judul-tabel"><?= esc($ret['title']) ?> (<?= esc($ret['year']) ?>)</div>
                <div class="penulis-tabel">Author: <?= esc($ret['author']) ?></div>
              </td>
              <td><b><?= Time::parse($ret['loan_date'])->format('d/m/Y') ?></b></td>
              <td class="<?= $isLate ? 'tgl-terlambat' : '' ?>">
                <b><?= Time::parse($ret['due_date'])->format('d/m/Y') ?></b>
              </td>
              <td><b><?= Time::parse($ret['return_date'])->format('d/m/Y') ?></b></td>
              <td>
                <?php if ($isLate): ?>
                  <span class="tgl-terlambat">+<?= $daysLate ?> hari</span>
                <?php else: ?>
                  <span class="teks-redup-sm">—</span>
                <?php endif; ?>
              </td>
              <td class="teks-center">
                <?php if ($isLate): ?>
                  <span class="badge-admin merah">Terlambat</span>
                <?php else: ?>
                  <span class="badge-admin hijau">Tepat Waktu</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="kondisi-kosong" id="kondisi-kosong-kembali" style="display:none">
    <svg viewBox="0 0 24 24"><polyline points="9 14 4 9 9 4"/><path d="M20 20v-7a4 4 0 00-4-4H4"/></svg>
    <p>Tidak ada data pengembalian</p>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function filterKembali(btn, status) {
  document.querySelectorAll('.pil-filter').forEach(b => b.classList.remove('aktif'));
  btn.classList.add('aktif');
  const baris = document.querySelectorAll('#tabel-pengembalian tbody tr');
  let terlihat = 0;
  baris.forEach(tr => {
    const cocok = status === 'semua' || tr.dataset.status === status;
    tr.style.display = cocok ? '' : 'none';
    if (cocok) terlihat++;
  });
  document.getElementById('kondisi-kosong-kembali').style.display = terlihat === 0 ? 'flex' : 'none';
}
</script>
<?= $this->endSection() ?>