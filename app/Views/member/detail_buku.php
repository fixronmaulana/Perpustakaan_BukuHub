<?= $this->extend('layouts/member_layout') ?>

<?= $this->section('head') ?>
<title><?= esc($book['title']) ?> — Detail Buku</title>
<?= $this->endSection() ?>

<?= $this->section('pageTitle') ?>Detail Buku<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
  $coverPath = BOOK_COVER_PATH . ($book['book_cover'] ?? '');
  $adaCover  = !empty($book['book_cover']) && file_exists($coverPath);
  $huruf     = strtoupper(substr($book['title'], 0, 1));
  $stok      = (int)($book['quantity'] ?? 0);

  // 🔥 LOGIKA STATUS STOK (LEBIH LENGKAP)
  if ($stok <= 0) {
    $status = 'Tidak tersedia';
    $class  = 'habis';
  } elseif ($stok <= 3) {
    $status = 'Sisa ' . $stok . ' buku';
    $class  = 'sedikit';
  } else {
    $status = 'Tersedia (' . $stok . ')';
    $class  = 'tersedia';
  }
?>

<div class="detail-buku-wrapper">

  <!-- ── COVER ── -->
  <div class="detail-cover">

    <?php if ($adaCover): ?>
      <img src="<?= base_url(BOOK_COVER_URI . $book['book_cover']) ?>" alt="<?= esc($book['title']) ?>">
    <?php else: ?>
      <div class="sampul-fallback-detail">
        <span><?= $huruf ?></span>
      </div>
    <?php endif; ?>

  </div>

  <!-- ── INFO ── -->
  <div class="detail-info">

    <h2 class="judul-detail"><?= esc($book['title']) ?></h2>
    <p class="penulis-detail">oleh <?= esc($book['author']) ?></p>

    <!-- META DETAIL -->
    <div class="meta-detail">

      <div><b>Penerbit</b></div>
      <div>: <?= esc($book['publisher'] ?? '-') ?></div>

      <div><b>Tahun</b></div>
      <div>: <?= esc($book['year'] ?? '-') ?></div>

      <div><b>Kategori</b></div>
      <div>: <?= esc($book['category'] ?? '-') ?></div>

      <div><b>Rak</b></div>
      <div>: <?= esc($book['rack'] ?? '-') ?></div>

      <div><b>ISBN</b></div>
      <div>: <?= esc($book['isbn'] ?? '-') ?></div>

      <!-- 🔥 STOK LEBIH JELAS -->
      <div><b>Stok Buku</b></div>
      <div>
        <span class="status-stok <?= $class ?>">
          <?= $status ?>
        </span>
      </div>

    </div>

    <!-- AKSI -->
    <div class="aksi-detail">
      <a href="<?= base_url('member/daftarbuku') ?>" class="btn-kembali">← Kembali</a>
    </div>

  </div>

</div>

<!-- ── DESKRIPSI ── -->
<div class="detail-deskripsi">
  <h3>Deskripsi Buku</h3>
  <p>
    <?= !empty($book['description']) 
        ? nl2br(esc($book['description'])) 
        : 'Tidak ada deskripsi tersedia.' ?>
  </p>
</div>

<?= $this->endSection() ?>