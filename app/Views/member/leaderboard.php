<?= $this->extend('layouts/member_layout') ?>

<?= $this->section('head') ?>
<title>Leaderboard — Portal Anggota</title>
<?= $this->endSection() ?>

<?= $this->section('pageTitle') ?>Leaderboard<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
$dummy = [
  ['rank'=>1, 'nama'=>'Aditya Abdi',    'kelas'=>'XII TKJ', 'poin'=>900,'kunjungan'=>35,'peminjaman'=>22,'tepat'=>22,'terlambat'=>0],
  ['rank'=>2, 'nama'=>'Eko Patrio',     'kelas'=>'XI RPL',  'poin'=>840,'kunjungan'=>30,'peminjaman'=>20,'tepat'=>19,'terlambat'=>1],
  ['rank'=>3, 'nama'=>'Gilang Ramadan', 'kelas'=>'X TKJ',   'poin'=>780,'kunjungan'=>28,'peminjaman'=>18,'tepat'=>16,'terlambat'=>2],
  ['rank'=>4, 'nama'=>'Siti Nurhaliza', 'kelas'=>'XII AKL', 'poin'=>720,'kunjungan'=>25,'peminjaman'=>16,'tepat'=>15,'terlambat'=>1],
  ['rank'=>5, 'nama'=>'Budi Santoso',   'kelas'=>'XI TKJ',  'poin'=>680,'kunjungan'=>22,'peminjaman'=>14,'tepat'=>13,'terlambat'=>1],
  ['rank'=>6, 'nama'=>'Dewi Rahayu',    'kelas'=>'X RPL',   'poin'=>650,'kunjungan'=>20,'peminjaman'=>13,'tepat'=>12,'terlambat'=>1],
  ['rank'=>7, 'nama'=>'Rizki Pratama',  'kelas'=>'XII RPL', 'poin'=>610,'kunjungan'=>18,'peminjaman'=>12,'tepat'=>11,'terlambat'=>1],
  ['rank'=>8, 'nama'=>'Lina Marlina',   'kelas'=>'XI AKL',  'poin'=>580,'kunjungan'=>16,'peminjaman'=>11,'tepat'=>10,'terlambat'=>1],
  ['rank'=>9, 'nama'=>'Hendra Wahyu',   'kelas'=>'X AKL',   'poin'=>560,'kunjungan'=>15,'peminjaman'=>10,'tepat'=>9, 'terlambat'=>1],
  ['rank'=>10,'nama'=>'Ahmad Ade',      'kelas'=>'XII TKJ', 'poin'=>540,'kunjungan'=>14,'peminjaman'=>9, 'tepat'=>9, 'terlambat'=>0,'saya'=>true],
];
$top3    = array_slice($dummy, 0, 3);
$lainnya = array_slice($dummy, 3);
?>

<!-- ── Header ── -->
<div class="header-leaderboard">
  <h1>Leaderboard Perpustakaan</h1>
  <p>Siswa Teraktif SMK Al-Munawwir IIBS</p>
  <div class="filter-bulan-lb">
    <button class="pil-lb aktif">Januari 2026</button>
    <button class="pil-lb">Februari 2026</button>
    <button class="pil-lb">Maret 2026</button>
  </div>
</div>

<!-- ── Podium ── -->
<div class="area-podium">

  <!-- Rank 2 -->
  <div class="podium-item rank-2">
    <div class="avatar-podium perak"><?= strtoupper(substr($top3[1]['nama'],0,1)) ?></div>
    <div class="nama-podium"><?= esc($top3[1]['nama']) ?></div>
    <div class="kelas-podium"><?= esc($top3[1]['kelas']) ?></div>
    <div class="poin-podium"><?= number_format($top3[1]['poin']) ?> poin</div>
    <div class="tiang-podium perak"><span class="no-podium">2</span></div>
  </div>

  <!-- Rank 1 -->
  <div class="podium-item rank-1">
    <div class="mahkota">👑</div>
    <div class="avatar-podium emas"><?= strtoupper(substr($top3[0]['nama'],0,1)) ?></div>
    <div class="nama-podium"><?= esc($top3[0]['nama']) ?></div>
    <div class="kelas-podium"><?= esc($top3[0]['kelas']) ?></div>
    <div class="poin-podium"><?= number_format($top3[0]['poin']) ?> poin</div>
    <div class="tiang-podium emas"><span class="no-podium">1</span></div>
  </div>

  <!-- Rank 3 -->
  <div class="podium-item rank-3">
    <div class="avatar-podium perunggu"><?= strtoupper(substr($top3[2]['nama'],0,1)) ?></div>
    <div class="nama-podium"><?= esc($top3[2]['nama']) ?></div>
    <div class="kelas-podium"><?= esc($top3[2]['kelas']) ?></div>
    <div class="poin-podium"><?= number_format($top3[2]['poin']) ?> poin</div>
    <div class="tiang-podium perunggu"><span class="no-podium">3</span></div>
  </div>

</div>

<!-- ── Tabel peringkat 4+ ── -->
<div class="kotak-konten">
  <div class="kepala-kotak">
    <h3>Peringkat 4 – 50</h3>
  </div>
  <div class="bungkus-tabel">
    <table class="tabel-member">
      <thead>
        <tr>
          <th style="width:50px">Rank</th>
          <th style="width:44px">Foto</th>
          <th>Nama</th>
          <th>Kelas</th>
          <th>Total Poin</th>
          <th>Kunjungan</th>
          <th>Peminjaman</th>
          <th>Tepat Waktu</th>
          <th>Terlambat</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($lainnya as $row): ?>
          <tr class="<?= !empty($row['saya']) ? 'baris-saya' : '' ?>">
            <td><span class="badge-rank"><?= $row['rank'] ?></span></td>
            <td>
              <div class="avatar-tabel-lb">
                <?= strtoupper(substr($row['nama'], 0, 1)) ?>
              </div>
            </td>
            <td>
              <span class="nama-lb">
                <?= esc($row['nama']) ?>
                <?php if (!empty($row['saya'])): ?>
                  <span class="badge-saya">Anda</span>
                <?php endif; ?>
              </span>
            </td>
            <td class="teks-redup-sm"><?= esc($row['kelas']) ?></td>
            <td><strong><?= number_format($row['poin']) ?></strong></td>
            <td class="teks-redup-sm"><?= $row['kunjungan'] ?></td>
            <td class="teks-redup-sm"><?= $row['peminjaman'] ?></td>
            <td class="teks-redup-sm"><?= $row['tepat'] ?></td>
            <td class="teks-redup-sm"><?= $row['terlambat'] ?: '—' ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.querySelectorAll('.pil-lb').forEach(btn => {
  btn.addEventListener('click', function() {
    document.querySelectorAll('.pil-lb').forEach(b => b.classList.remove('aktif'));
    this.classList.add('aktif');
  });
});
</script>
<?= $this->endSection() ?>