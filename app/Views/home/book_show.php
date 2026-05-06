<?= $this->extend('layouts/home_layout') ?>

<?= $this->section('head') ?>
<title><?= esc($book['title']) ?> — Perpustakaan SMK</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?= $this->include('layouts/navbar') ?>

<!-- ── HEADER ── -->
<div class="header-halaman">
  <h1>Detail Buku</h1>
  <div class="garis-emas"></div>
  <p>Informasi lengkap tentang buku ini</p>
</div>

<!-- ── KONTEN DETAIL ── -->
<div class="bungkus-detail-buku">

  <!-- Tombol kembali -->
  <a href="<?= base_url('book') ?>" class="tombol-kembali">
    ← Kembali ke Koleksi
  </a>

  <div class="layout-detail-buku">

    <!-- Kolom Kiri: Cover -->
    <div class="kolom-cover-buku">
      <?php
        $coverPath = BOOK_COVER_PATH . ($book['book_cover'] ?? '');
        $adaCover  = !empty($book['book_cover']) && file_exists($coverPath);
        $coverUrl  = base_url(
          $adaCover
            ? BOOK_COVER_URI . $book['book_cover']
            : BOOK_COVER_URI . DEFAULT_BOOK_COVER
        );
      ?>
      <div class="cover-detail" style="background-image: url('<?= $coverUrl ?>')"></div>

      <!-- Badge stok -->
      <?php $stok = (int)($book['quantity'] ?? 0); ?>
      <div class="badge-stok-detail <?= $stok > 0 ? 'tersedia' : 'habis' ?>">
        <?= $stok > 0 ? "✓ {$stok} Buku Tersedia" : '✗ Stok Habis' ?>
      </div>

      <!-- E-Book dummy -->
      <div class="kotak-ebook">
        <div class="ikon-ebook">📄</div>
        <div>
          <div class="label-ebook">E-Book Tersedia</div>
          <div class="sub-ebook">Format PDF · 2.4 MB</div>
        </div>
        <a href="<?= base_url('login') ?>" class="tombol-ebook">Unduh</a>
      </div>
    </div>

    <!-- Kolom Kanan: Info -->
    <div class="kolom-info-buku">

      <!-- Kategori -->
      <?php if (!empty($book['category'])) : ?>
        <span class="label-kategori"><?= esc($book['category']) ?></span>
      <?php endif; ?>

      <!-- Judul -->
      <h1 class="judul-detail"><?= esc($book['title']) ?></h1>

      <!-- Penulis -->
      <p class="penulis-detail">oleh <strong><?= esc($book['author']) ?></strong></p>

      <!-- Garis -->
      <div class="garis-detail"></div>

      <!-- Deskripsi dummy -->
      <div class="deskripsi-detail">
        <h3>Deskripsi Buku</h3>
        <p>
          Buku ini merupakan salah satu koleksi unggulan perpustakaan SMK Al-Munawwir IIBS.
          Karya dari <strong><?= esc($book['author']) ?></strong> ini hadir untuk memperkaya
          wawasan dan pengetahuan para pembaca, khususnya di bidang
          <strong><?= esc($book['category'] ?? 'ilmu pengetahuan') ?></strong>.
        </p>
        <p>
          Diterbitkan pada tahun <strong><?= esc($book['year']) ?></strong> oleh
          <strong><?= esc($book['publisher']) ?></strong>, buku ini telah menjadi
          referensi penting bagi siswa dan guru di lingkungan sekolah.
          Dengan gaya penulisan yang mudah dipahami, buku ini cocok dibaca oleh
          semua kalangan yang ingin mendalami topik ini lebih jauh.
        </p>
        <p>
          Tersedia di perpustakaan kami dan dapat dipinjam oleh anggota aktif
          perpustakaan SMK Al-Munawwir IIBS.
        </p>
      </div>

      <!-- Garis -->
      <div class="garis-detail"></div>

      <!-- Info detail -->
      <div class="tabel-info-buku">
        <div class="baris-info">
          <span class="label-info">Penerbit</span>
          <span class="nilai-info"><?= esc($book['publisher']) ?></span>
        </div>
        <div class="baris-info">
          <span class="label-info">Tahun Terbit</span>
          <span class="nilai-info"><?= esc($book['year']) ?></span>
        </div>
        <div class="baris-info">
          <span class="label-info">ISBN</span>
          <span class="nilai-info"><?= esc($book['isbn']) ?></span>
        </div>
        <div class="baris-info">
          <span class="label-info">Kategori</span>
          <span class="nilai-info"><?= esc($book['category'] ?? '-') ?></span>
        </div>
        <div class="baris-info">
          <span class="label-info">Lokasi Rak</span>
          <span class="nilai-info">
            Rak <?= esc($book['rack'] ?? '-') ?>
            <?= !empty($book['floor']) ? '· Lantai ' . esc($book['floor']) : '' ?>
          </span>
        </div>
        <div class="baris-info">
          <span class="label-info">Stok</span>
          <span class="nilai-info <?= $stok > 0 ? 'teks-hijau' : 'teks-merah' ?>">
            <?= $stok > 0 ? "{$stok} tersedia" : 'Habis' ?>
          </span>
        </div>
      </div>

    </div>
  </div>

</div>

<?= $this->include('layouts/home_footer') ?>

<?= $this->endSection() ?>