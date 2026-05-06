<?= $this->extend('layouts/home_layout') ?>

<?= $this->section('head') ?>
<title>Koleksi Buku — Perpustakaan SMK</title>
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
  <div class="grid-populer">

    <?php if (empty($books)) : ?>
      <div class="kondisi-kosong">
        <div class="ikon-kosong">📭</div>
        <h3>Buku tidak ditemukan</h3>
        <p>Coba kata kunci lain atau telusuri semua koleksi kami.</p>
      </div>
    <?php endif; ?>

    <?php foreach ($books as $i => $book) : ?>
      <?php
        $coverPath = BOOK_COVER_PATH . ($book['book_cover'] ?? '');
        $adaCover  = !empty($book['book_cover']) && file_exists($coverPath);
        $coverUrl  = base_url(
          $adaCover
            ? BOOK_COVER_URI . $book['book_cover']
            : BOOK_COVER_URI . DEFAULT_BOOK_COVER
        );
        $tunda = ($i % 8) * 60;
      ?>

      <a href="<?= base_url("book/{$book['slug']}") ?>"
         class="kartu-populer"
         style="animation-delay: <?= $tunda ?>ms">

        <!-- Sampul -->
        <div class="bungkus-sampul-populer">
          <div class="sampul-populer"
               style="background-image: url('<?= $coverUrl ?>');">
          </div>
        </div>

        <!-- Info -->
        <div class="isi-kartu-populer">
          <?php if (!empty($book['category'])) : ?>
            <span class="label-kategori"><?= esc($book['category']) ?></span>
          <?php endif; ?>
          <p class="judul-kartu">
            <?= esc(substr($book['title'], 0, 60) . (strlen($book['title']) > 60 ? '…' : '')) ?>
          </p>
          <p class="penulis-kartu"><?= esc($book['author']) ?></p>
          <div class="tombol-detail-koleksi">
            Lihat Detail →
          </div>
        </div>

      </a>

    <?php endforeach; ?>

  </div><!-- /grid-populer -->

  <!-- Pagination -->
  <div class="bungkus-pager">
    <?= $pager->links('books', 'my_pager') ?>
  </div>

</div><!-- /bungkus-halaman-buku -->

<?= $this->endSection() ?>