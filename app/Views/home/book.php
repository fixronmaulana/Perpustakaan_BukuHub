<?= $this->extend('layouts/home_layout') ?>

<?= $this->section('head') ?>
<title>Koleksi Buku — Perpustakaan SMK</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  :root {
    --navy: #0d1b3e;
    --navy-mid: #142150;
    --blue-accent: #1e3a8a;
    --gold: #c9a84c;
    --white: #ffffff;
    --bg: #f5f7fc;
    --text: #1a2340;
    --text-muted: #6b7a9d;
    --border: #e2e8f5;
    --card-shadow: 0 4px 24px rgba(13,27,62,0.08);
    --card-hover-shadow: 0 12px 40px rgba(13,27,62,0.18);
  }

  * { box-sizing: border-box; }

  body {
    background: var(--bg);
    font-family: 'DM Sans', sans-serif;
    color: var(--text);
    margin: 0;
    padding: 0;
  }

  /* ── NAVBAR ── */
  .lib-navbar {
    position: sticky;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000;
    background: rgba(13, 27, 62, 0.97);
    backdrop-filter: blur(12px);
    border-bottom: 1px solid rgba(201, 168, 76, 0.15);
    padding: 0 2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 64px;
  }

  .lib-brand {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    color: var(--white);
    font-weight: 600;
    font-size: 1rem;
  }

  .lib-brand .brand-icon {
    width: 36px;
    height: 36px;
    background: var(--gold);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
  }

  .lib-nav-links {
    display: flex;
    gap: 2rem;
    list-style: none;
    margin: 0;
    padding: 0;
  }

  .lib-nav-links a {
    color: rgba(255,255,255,0.65);
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    transition: color 0.2s;
  }

  .lib-nav-links a:hover,
  .lib-nav-links a.active {
    color: var(--white);
  }

  .lib-nav-links a.active {
    border-bottom: 2px solid var(--gold);
    padding-bottom: 2px;
  }

  .lib-nav-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
  }

  .btn-home-nav {
    display: flex;
    align-items: center;
    gap: 6px;
    color: rgba(255,255,255,0.75);
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    padding: 7px 14px;
    border-radius: 6px;
    border: 1px solid rgba(255,255,255,0.2);
    transition: all 0.2s;
  }

  .btn-home-nav:hover {
    background: rgba(255,255,255,0.1);
    color: var(--white);
    border-color: rgba(255,255,255,0.4);
  }

  .btn-login-nav {
    background: var(--blue-accent);
    color: var(--white);
    border: none;
    padding: 8px 22px;
    border-radius: 6px;
    font-size: 0.875rem;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: background 0.2s;
  }

  .btn-login-nav:hover {
    background: #2450b5;
    color: var(--white);
  }

  /* ── PAGE HEADER ── */
  .page-header {
    background: linear-gradient(135deg, var(--navy) 0%, #1a2f6a 100%);
    padding: 3rem 0 2.5rem;
    text-align: center;
    position: relative;
    overflow: hidden;
  }

  .page-header::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
  }

  .page-header h1 {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.8rem, 4vw, 2.6rem);
    color: var(--white);
    margin: 0 0 0.5rem;
    position: relative;
  }

  .page-header p {
    color: rgba(255,255,255,0.6);
    font-size: 0.95rem;
    margin: 0;
    position: relative;
  }

  .page-header .gold-line {
    width: 48px;
    height: 3px;
    background: var(--gold);
    margin: 1rem auto;
    border-radius: 2px;
  }

  /* ── MAIN CONTENT AREA ── */
  .book-page-wrap {
    max-width: 1280px;
    margin: 0 auto;
    padding: 2.5rem 2rem 4rem;
  }

  /* ── TOOLBAR (search + count) ── */
  .books-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
  }

  .books-count {
    font-size: 0.9rem;
    color: var(--text-muted);
    font-weight: 500;
  }

  .books-count span {
    color: var(--navy);
    font-weight: 700;
  }

  .search-form {
    display: flex;
    background: var(--white);
    border-radius: 10px;
    overflow: hidden;
    border: 1.5px solid var(--border);
    box-shadow: 0 2px 10px rgba(0,0,0,0.06);
    transition: border-color 0.2s, box-shadow 0.2s;
  }

  .search-form:focus-within {
    border-color: var(--blue-accent);
    box-shadow: 0 2px 16px rgba(30,58,138,0.12);
  }

  .search-form input {
    border: none;
    outline: none;
    padding: 11px 18px;
    font-size: 0.9rem;
    font-family: 'DM Sans', sans-serif;
    color: var(--text);
    width: 260px;
    background: transparent;
  }

  .search-form input::placeholder {
    color: #a0aec0;
  }

  .search-form button {
    background: var(--navy);
    color: var(--white);
    border: none;
    padding: 0 20px;
    font-size: 0.875rem;
    font-weight: 600;
    font-family: 'DM Sans', sans-serif;
    cursor: pointer;
    transition: background 0.2s;
    display: flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
  }

  .search-form button:hover {
    background: var(--blue-accent);
  }

  /* ── BOOK GRID ── */
  .books-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
    gap: 1.75rem;
  }

  /* ── BOOK CARD ── */
  .book-card {
    background: var(--white);
    border-radius: 14px;
    overflow: hidden;
    box-shadow: var(--card-shadow);
    border: 1px solid var(--border);
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    display: flex;
    flex-direction: column;
    animation: fadeUp 0.4s ease both;
  }

  .book-card:hover {
    transform: translateY(-6px);
    box-shadow: var(--card-hover-shadow);
  }

  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  .book-cover-link {
    display: block;
    position: relative;
    overflow: hidden;
  }

  .book-cover {
    width: 100%;
    height: 240px;
    background-size: cover;
    background-position: top center;
    background-repeat: no-repeat;
    background-color: #dde4f4;
    transition: transform 0.35s ease;
  }

  .book-cover-link:hover .book-cover {
    transform: scale(1.04);
  }

  /* overlay gradient on cover */
  .book-cover::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 60px;
    background: linear-gradient(to top, rgba(10,16,40,0.35), transparent);
    pointer-events: none;
  }

  .book-info {
    padding: 1rem 1.1rem 1.2rem;
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }

  .book-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--text);
    line-height: 1.45;
    margin: 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  .book-year {
    font-size: 0.78rem;
    color: var(--text-muted);
    font-weight: 500;
    margin: 0;
  }

  .book-view-btn {
    margin-top: auto;
    display: inline-block;
    padding: 7px 0;
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--blue-accent);
    text-decoration: none;
    letter-spacing: 0.3px;
    border-top: 1px solid var(--border);
    transition: color 0.2s;
  }

  .book-view-btn:hover {
    color: var(--navy);
  }

  .book-view-btn::after {
    content: ' →';
  }

  /* ── EMPTY STATE ── */
  .empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 5rem 2rem;
    color: var(--text-muted);
  }

  .empty-state .empty-icon {
    font-size: 3.5rem;
    margin-bottom: 1rem;
    opacity: 0.5;
  }

  .empty-state h3 {
    font-size: 1.2rem;
    color: var(--text);
    margin-bottom: 0.5rem;
  }

  .empty-state p {
    font-size: 0.9rem;
  }

  /* ── PAGER OVERRIDE ── */
  .pager-wrap {
    margin-top: 3rem;
    display: flex;
    justify-content: center;
  }

  .pager-wrap nav ul {
    display: flex;
    list-style: none;
    gap: 6px;
    margin: 0;
    padding: 0;
  }

  .pager-wrap nav ul li a,
  .pager-wrap nav ul li span {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 38px;
    height: 38px;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    text-decoration: none;
    border: 1.5px solid var(--border);
    color: var(--text);
    background: var(--white);
    transition: all 0.2s;
  }

  .pager-wrap nav ul li a:hover {
    background: var(--navy);
    color: var(--white);
    border-color: var(--navy);
  }

  .pager-wrap nav ul li.active a,
  .pager-wrap nav ul li span.active {
    background: var(--navy);
    color: var(--white);
    border-color: var(--navy);
  }

  /* ── RESPONSIVE ── */
  @media (max-width: 768px) {
    .lib-nav-links { display: none; }
    .books-toolbar { flex-direction: column; align-items: stretch; }
    .search-form input { width: 100%; }
    .books-grid { grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 1rem; }
    .book-cover { height: 200px; }
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('back') ?>
<?php /* back section kosong — tombol home sudah ada di navbar */ ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- ── NAVBAR ── -->
<nav class="lib-navbar">
  <a href="<?= base_url('/') ?>" class="lib-brand">
    <div class="brand-icon">📚</div>
    Perpustakaan SMK
  </a>

  <ul class="lib-nav-links">
    <li><a href="<?= base_url('/') ?>">Beranda</a></li>
    <li><a href="<?= base_url('book') ?>" class="active">Koleksi</a></li>
    <li><a href="#">Layanan</a></li>
    <li><a href="#">Leaderboard</a></li>
    <li><a href="#">Kontak</a></li>
  </ul>

  <div class="lib-nav-actions">
    <a href="<?= base_url('/') ?>" class="btn-home-nav">
      ← Beranda
    </a>
    <a href="<?= base_url('login') ?>" class="btn-login-nav">Login</a>
  </div>
</nav>

<!-- ── PAGE HEADER ── -->
<div class="page-header">
  <h1>Koleksi Buku</h1>
  <div class="gold-line"></div>
  <p>Temukan ribuan judul pilihan untuk mendukung perjalanan belajarmu</p>
</div>

<!-- ── MAIN CONTENT ── -->
<div class="book-page-wrap">

  <!-- Toolbar -->
  <div class="books-toolbar">
    <div class="books-count">
      Menampilkan <span><?= count($books) ?></span> buku
      <?php if (!empty($search)) : ?>
        untuk pencarian "<strong><?= esc($search) ?></strong>"
      <?php endif; ?>
    </div>

    <form action="" method="get" class="search-form">
      <input
        type="text"
        name="search"
        value="<?= $search ?? ''; ?>"
        placeholder="Cari judul, penulis, kategori…"
        aria-label="Cari buku"
      >
      <button type="submit">
        🔍 Cari
      </button>
    </form>
  </div>

  <!-- Grid Buku -->
  <div class="books-grid">
    <?php if (empty($books)) : ?>
      <div class="empty-state">
        <div class="empty-icon">📭</div>
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
        $delay = ($i % 8) * 60; // stagger animasi per baris
      ?>

      <div class="book-card" style="animation-delay: <?= $delay ?>ms">
        <a href="<?= base_url("admin/books/{$book['slug']}"); ?>" class="book-cover-link">
          <div
            class="book-cover"
            style="background-image: url('<?= $coverUrl ?>');"
            role="img"
            aria-label="Cover buku <?= esc($book['title']) ?>"
          ></div>
        </a>

        <div class="book-info">
          <p class="book-title">
            <?= esc(substr($book['title'], 0, 80) . (strlen($book['title']) > 80 ? '…' : '')) ?>
          </p>
          <p class="book-year"><?= esc($book['year']) ?></p>
          <a href="<?= base_url("admin/books/{$book['slug']}"); ?>" class="book-view-btn">
            Lihat Detail
          </a>
        </div>
      </div>

    <?php endforeach; ?>
  </div>

  <!-- Pagination -->
  <div class="pager-wrap">
    <?= $pager->links('books', 'my_pager'); ?>
  </div>

</div>

<?= $this->endSection() ?>