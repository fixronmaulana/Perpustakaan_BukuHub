<?= $this->extend('layouts/member_layout') ?>

<?= $this->section('head') ?>
<title>Daftar Buku — Portal Anggota</title>
<?= $this->endSection() ?>

<?= $this->section('pageTitle') ?>Daftar Buku<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- ── Toolbar: search + filter kategori ── -->
<div class="toolbar-buku">

  <!-- Search -->
  <form method="get" action="<?= base_url('member/daftarbuku') ?>" class="form-cari-buku">
    <div class="input-cari-buku">
      <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input
        type="text"
        name="search"
        placeholder="Cari judul, pengarang, penerbit..."
        value="<?= esc($search ?? '') ?>"
        autocomplete="off">
      <?php if (!empty($categoryId)): ?>
        <input type="hidden" name="category" value="<?= esc($categoryId) ?>">
      <?php endif; ?>
    </div>
    <button type="submit" class="tombol-cari-buku">Cari</button>
  </form>

  <!-- Info hasil -->
  <?php if (!empty($search)): ?>
    <div class="info-hasil-cari">
      Hasil pencarian untuk <strong>"<?= esc($search) ?>"</strong>
      — <a href="<?= base_url('member/daftarbuku') ?>" class="tautan-reset-cari">Hapus pencarian</a>
    </div>
  <?php endif; ?>

</div>

<!-- ── Filter kategori (pill) ── -->
<div class="filter-kategori-buku">
  <a href="<?= base_url('member/daftarbuku' . ($search ? '?search=' . urlencode($search) : '')) ?>"
     class="pil-kategori <?= empty($categoryId) ? 'aktif' : '' ?>">
    Semua
  </a>
  <?php foreach ($categories as $cat): ?>
    <?php
      $url = base_url('member/daftarbuku?category=' . $cat['id']);
      if (!empty($search)) $url .= '&search=' . urlencode($search);
    ?>
    <a href="<?= $url ?>"
       class="pil-kategori <?= ($categoryId == $cat['id']) ? 'aktif' : '' ?>">
      <?= esc($cat['name']) ?>
    </a>
  <?php endforeach; ?>
</div>

<!-- ── Grid Buku ── -->
<?php if (!empty($books)): ?>

  <div class="grid-buku-member">
    <?php foreach ($books as $book): ?>

      <?php
        // Tentukan cover
        $coverPath = BOOK_COVER_PATH . ($book['book_cover'] ?? '');
        $adaCover  = !empty($book['book_cover']) && file_exists($coverPath);

        // Warna gradient fallback berdasarkan huruf pertama judul
        $huruf  = strtoupper(substr($book['title'], 0, 1));
        $warna  = [
          'A'=>'#1e3a8a,#3b82f6','B'=>'#065f46,#10b981','C'=>'#6d28d9,#a78bfa',
          'D'=>'#b45309,#fbbf24','E'=>'#be123c,#fb7185','F'=>'#0e7490,#22d3ee',
          'G'=>'#1e3a8a,#60a5fa','H'=>'#065f46,#34d399','I'=>'#7c3aed,#c4b5fd',
          'J'=>'#92400e,#fcd34d','K'=>'#0d1b3e,#3b82f6','L'=>'#14532d,#22c55e',
          'M'=>'#4c1d95,#8b5cf6','N'=>'#7f1d1d,#f87171','O'=>'#164e63,#38bdf8',
          'P'=>'#1e1b4b,#818cf8','Q'=>'#713f12,#fb923c','R'=>'#0f172a,#64748b',
          'S'=>'#134e4a,#2dd4bf','T'=>'#1c1917,#a8a29e','U'=>'#0c4a6e,#0ea5e9',
          'V'=>'#2e1065,#d946ef','W'=>'#0f2027,#2c5364','X'=>'#3d0000,#ff6b6b',
          'Y'=>'#1a1a2e,#e94560','Z'=>'#0d1b3e,#c9a84c',
        ];
        $grad = $warna[$huruf] ?? '0d1b3e,#3b82f6';

        // Stok
        $stok = (int)($book['quantity'] ?? 0);
      ?>

      <div class="kartu-buku-member">

        <!-- Sampul -->
        <div class="sampul-buku-member">
          <?php if ($adaCover): ?>
            <img src="<?= base_url(BOOK_COVER_URI . $book['book_cover']) ?>"
                 alt="<?= esc($book['title']) ?>"
                 loading="lazy">
          <?php else: ?>
            <div class="sampul-fallback" style="background: linear-gradient(160deg, <?= $grad ?>)">
              <span class="inisial-sampul"><?= $huruf ?></span>
            </div>
          <?php endif; ?>

          <!-- Badge stok -->
          <?php if ($stok <= 0): ?>
            <div class="badge-stok habis">Habis</div>
          <?php elseif ($stok <= 2): ?>
            <div class="badge-stok sedikit">Sisa <?= $stok ?></div>
          <?php endif; ?>

          <!-- Badge kategori -->
          <?php if (!empty($book['category'])): ?>
            <div class="badge-kategori-buku"><?= esc($book['category']) ?></div>
          <?php endif; ?>
        </div>

        <!-- Info buku -->
        <div class="info-buku-member">
          <div class="judul-buku-member"><?= esc($book['title']) ?></div>
          <div class="penulis-buku-member"><?= esc($book['author']) ?></div>
          <div class="meta-buku-member">
            <span><?= esc($book['publisher']) ?></span>
            <span class="titik-pemisah">·</span>
            <span><?= esc($book['year']) ?></span>
          </div>
        </div>

        <!-- Footer kartu -->
        <div class="footer-kartu-buku">
          <div class="stok-buku-member <?= $stok <= 0 ? 'habis' : '' ?>">
            <svg viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
            <?= $stok > 0 ? $stok . ' tersedia' : 'Tidak tersedia' ?>
          </div>
          <a href="<?= base_url('member/daftarbuku/' . $book['slug']) ?>" class="tombol-detail-buku">
            Detail
          </a>
        </div>

      </div>

    <?php endforeach; ?>
  </div>

  <!-- Paginasi -->
  <?php if ($pager): ?>
    <div class="bungkus-pager-member">
      <?= $pager->links('books', 'member_pager') ?>
    </div>
  <?php endif; ?>

<?php else: ?>

  <!-- Kosong -->
  <div class="kondisi-kosong-buku">
    <svg viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
    <h3>Buku tidak ditemukan</h3>
    <p>Coba kata kunci lain atau pilih kategori yang berbeda.</p>
    <a href="<?= base_url('member/daftarbuku') ?>" class="tombol-kuis">Lihat Semua Buku</a>
  </div>

<?php endif; ?>

<?= $this->endSection() ?>