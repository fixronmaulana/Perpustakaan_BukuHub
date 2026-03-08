<?= $this->extend('layouts/home_layout') ?>

<?= $this->section('head') ?>
<title>Koleksi Buku — Perpustakaan SMK</title>
<?= $this->endSection() ?>

<?= $this->section('back') ?>
<?php /* kosong — navigasi sudah ada di navbar */ ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?= $this->include('layouts/navbar') ?>

<!-- ── HEADER HALAMAN ── -->
<div class="header-halaman">
  <h1>Koleksi Buku</h1>
  <div class="garis-emas"></div>
  <p>Temukan ribuan judul pilihan untuk mendukung perjalanan belajarmu</p>
</div>

<!-- ── AREA KONTEN UTAMA ── -->
<div class="bungkus-halaman-buku">

  <!-- Bilah alat: hitungan + pencarian -->
  <div class="bilah-alat-buku">
    <div class="jumlah-buku">
      Menampilkan <span><?= count($books) ?></span> buku
      <?php if (!empty($search)) : ?>
        untuk pencarian "<strong><?= esc($search) ?></strong>"
      <?php endif; ?>
    </div>

    <form action="" method="get" class="form-cari">
      <input
        type="text"
        name="search"
        value="<?= $search ?? '' ?>"
        placeholder="Cari judul, penulis, kategori…"
        aria-label="Cari buku"
      >
      <button type="submit">🔍 Cari</button>
    </form>
  </div>

  <!-- Grid kartu buku -->
  <div class="grid-buku">

    <?php if (empty($books)) : ?>
      <div class="kondisi-kosong">
        <div class="ikon-kosong">📭</div>
        <h3>Buku tidak ditemukan</h3>
        <p>Coba kata kunci lain atau telusuri semua koleksi kami.</p>
      </div>
    <?php endif; ?>

    <?php foreach ($books as $i => $book) : ?>
      <?php
        $coverImageFilePath = BOOK_COVER_URI . $book['book_cover'];
        $coverUrl = base_url(
          (!empty($book['book_cover']) && file_exists($coverImageFilePath))
            ? $coverImageFilePath
            : BOOK_COVER_URI . DEFAULT_BOOK_COVER
        );
        $tunda = ($i % 8) * 60;
      ?>

      <div class="kartu-buku" style="animation-delay: <?= $tunda ?>ms">

        <a href="<?= base_url("admin/books/{$book['slug']}") ?>" class="tautan-sampul">
          <div
            class="sampul-buku"
            style="background-image: url('<?= $coverUrl ?>');"
            role="img"
            aria-label="Sampul buku <?= esc($book['title']) ?>"
          ></div>
        </a>

        <div class="info-buku">
          <p class="judul-buku">
            <?= esc(substr($book['title'], 0, 80) . (strlen($book['title']) > 80 ? '…' : '')) ?>
          </p>
          <p class="tahun-buku"><?= esc($book['year']) ?></p>
          <a href="<?= base_url("admin/books/{$book['slug']}") ?>" class="tombol-detail">
            Lihat Detail
          </a>
        </div>

      </div>

    <?php endforeach; ?>

  </div><!-- /grid-buku -->

  <!-- Pagination -->
  <div class="bungkus-pager">
    <?= $pager->links('books', 'my_pager') ?>
  </div>

</div><!-- /bungkus-halaman-buku -->

<?= $this->endSection() ?>