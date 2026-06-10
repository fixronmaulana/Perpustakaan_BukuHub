<?= $this->extend('layouts/home_layout') ?>

<?= $this->section('head') ?>
<title>Perpustakaan SMK — Jelajahi Dunia Pengetahuan</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?= $this->include('layouts/navbar') ?>

<!-- ══ HERO ══ -->
<section class="seksi-hero">
  <div class="latar-hero"></div>
  <div class="lapisan-hero"></div>

  <div class="konten-hero">
    <h1>Perpustkaan Digital<br>SMK Al-Munawwir IIBS</h1>
    <p>Akses ribuan koleksi buku fisik dengan mudah. Cari, temukan
      dan kunjungi perpustakaan kami untuk meminjam buku yang
      anda butuhkan</p>

    <div class="kotak-cari-hero">
      <input type="text" placeholder="Cari judul buku, penulis, atau kategori…">
      <button type="button" onclick="window.location='<?= base_url('book') ?>'">Cari Buku</button>
    </div>

    <div class="statistik-hero">
      <div class="item-statistik">
        <div class="ikon-statistik">📖</div>
        <div>
          <div class="angka-statistik"><?= number_format($totalBooks, 0, ',', '.') ?>+</div>
          <div class="label-statistik">Koleksi Buku</div>
        </div>
      </div>
      
      <div class="item-statistik">
        <div class="ikon-statistik">👥</div>
        <div>
          <div class="angka-statistik"><?= number_format($totalMembers, 0, ',', '.') ?>+</div>
          <div class="label-statistik">Anggota Aktif</div>
        </div>
      </div>
    </div>
  </div>

  <div class="petunjuk-gulir">↓</div>
</section>

<section class="seksi-populer">
  <div class="dalam-populer">

    <div class="judul-seksi">
      <h2>Koleksi Populer</h2>
      <p>Temukan buku-buku favorit yang paling banyak dipinjam oleh siswa dan guru</p>
    </div>

    <div class="pil-kategori">
      <a href="#" class="pil aktif">Semua</a>
      <a href="#" class="pil">Fiksi</a>
      <a href="#" class="pil">Sejarah</a>
      <a href="#" class="pil">Self-Help</a>
      <a href="#" class="pil">Pengembangan Diri</a>
      <a href="#" class="pil">Sains</a>
      <a href="#" class="pil">Agama</a>
    </div>

    <div class="grid-populer">

      <?php if (empty($books)) : ?>
        <div class="kondisi-kosong" style="grid-column: 1/-1; text-align: center; padding: 2rem; color: #888;">
          <div class="ikon-kosong" style="font-size: 2.5rem; margin-bottom: 0.5rem;">📭</div>
          <h3>Buku belum tersedia</h3>
          <p>Koleksi populer saat ini belum dapat ditampilkan.</p>
        </div>
      <?php endif; ?>

      <?php foreach ($books as $i => $book) : ?>
        <?php
          // Mengikuti standar pengecekan cover dari menu koleksi Anda
          $coverPath = BOOK_COVER_PATH . ($book['book_cover'] ?? '');
          $adaCover  = !empty($book['book_cover']) && file_exists($coverPath);
          $coverUrl  = base_url(
            $adaCover
              ? BOOK_COVER_URI . $book['book_cover']
              : BOOK_COVER_URI . DEFAULT_BOOK_COVER
          );
          
          // Mengikuti rumus delay animasi beranda Anda sebelumnya (0ms, 80ms, 160ms, dst)
          $tunda = $i * 80; 
        ?>

        <a href="<?= base_url("book/{$book['slug']}") ?>"
           class="kartu-populer"
           style="animation-delay: <?= $tunda ?>ms">

          <div class="bungkus-sampul-populer">
            <div class="sampul-populer"
                 style="background-image: url('<?= $coverUrl ?>');">
            </div>
            
            <?php if (isset($book['created_at']) && (strtotime($book['created_at']) > strtotime('-30 days'))): ?>
              <span class="lencana-baru">Baru</span>
            <?php endif; ?>
          </div>

          <div class="isi-kartu-populer">
            <?php if (!empty($book['category'])) : ?>
              <span class="label-kategori"><?= esc($book['category']) ?></span>
            <?php endif; ?>
            
            <p class="judul-kartu">
              <?= esc(substr($book['title'], 0, 60) . (strlen($book['title']) > 60 ? '…' : '')) ?>
            </p>
            
            <p class="penulis-kartu"><?= esc($book['author']) ?></p>
            
            <div class="tombol-detail-koleksi" style="margin-top: 12px; font-size: 0.85rem; font-weight: 600; color: #0d1b3e;">
              Lihat Detail →
            </div>
          </div>

        </a>

      <?php endforeach; ?>

    </div><div class="ajakan-populer">
      <a href="<?= base_url('book') ?>" class="tombol-lihat-semua">Lihat Semua Koleksi →</a>
    </div>

  </div>
</section>

<!-- ══ LAYANAN ══ -->
<section class="seksi-layanan" id="layanan">
  <div class="dalam-layanan">

    <div class="judul-seksi">
      <h2>Layanan Kami</h2>
      <p>Berbagai fasilitas dan layanan untuk mendukung kegiatan belajar mengajar</p>
    </div>

    <div class="grid-layanan">
      <div class="kartu-layanan" style="animation-delay:0ms">
        <div class="ikon-layanan">
          <svg viewBox="0 0 24 24"><path d="M2 6a2 2 0 012-2h7l2 2h7a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/><path d="M8 11h8M8 15h5"/></svg>
        </div>
        <h3>Peminjaman Buku</h3>
        <p>Pinjam hingga 5 buku selama 14 hari dengan perpanjangan online</p>
      </div>
      <div class="kartu-layanan" style="animation-delay:80ms">
        <div class="ikon-layanan">
          <svg viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
        </div>
        <h3>Akses E-Library</h3>
        <p>Ribuan e-book dan jurnal ilmiah tersedia untuk diakses kapan saja</p>
      </div>
      <div class="kartu-layanan" style="animation-delay:160ms">
        <div class="ikon-layanan">
          <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
        </div>
        <h3>Ruang Diskusi</h3>
        <p>Ruang diskusi kelompok yang nyaman untuk belajar bersama</p>
      </div>
      <div class="kartu-layanan" style="animation-delay:240ms">
        <div class="ikon-layanan">
          <svg viewBox="0 0 24 24"><path d="M18 8h1a4 4 0 010 8h-1"/><path d="M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>
        </div>
        <h3>Reading Corner</h3>
        <p>Sudut baca yang cozy dengan fasilitas charging station</p>
      </div>
      <div class="kartu-layanan" style="animation-delay:320ms">
        <div class="ikon-layanan">
          <svg viewBox="0 0 24 24"><path d="M3 18v-6a9 9 0 0118 0v6"/><path d="M21 19a2 2 0 01-2 2h-1a2 2 0 01-2-2v-3a2 2 0 012-2h3zM3 19a2 2 0 002 2h1a2 2 0 002-2v-3a2 2 0 00-2-2H3z"/></svg>
        </div>
        <h3>Audiobook</h3>
        <p>Koleksi audiobook untuk pengalaman belajar yang berbeda</p>
      </div>
      <div class="kartu-layanan" style="animation-delay:400ms">
        <div class="ikon-layanan">
          <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <h3>Buka Setiap Hari</h3>
        <p>Perpustakaan buka Senin–Sabtu pukul 07:00 – 17:00 WIB</p>
      </div>
    </div>

  </div>
</section>

<?= $this->include('layouts/home_footer') ?>

<?= $this->endSection() ?>