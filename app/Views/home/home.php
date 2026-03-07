<?= $this->extend('layouts/home_layout') ?>

<?= $this->section('head') ?>
<title>Perpustakaan SMK - Jelajahi Dunia Pengetahuan</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  :root {
    --navy: #0d1b3e;
    --blue-accent: #1e3a8a;
    --gold: #c9a84c;
    --white: #ffffff;
    --bg: #f5f7fc;
    --bg-warm: #f0ede8;
    --text: #1a2340;
    --text-muted: #6b7a9d;
    --border: #e2e8f5;
  }

  * { box-sizing: border-box; }

  body {
    margin: 0;
    font-family: 'DM Sans', sans-serif;
    background: var(--bg);
    color: var(--text);
  }

  /* ══ NAVBAR ══ */
  .lib-navbar {
    position: fixed;
    top: 0; left: 0; width: 100%;
    z-index: 1000;
    background: rgba(13,27,62,0.94);
    backdrop-filter: blur(14px);
    border-bottom: 1px solid rgba(201,168,76,0.15);
    padding: 0 2.5rem;
    display: flex; align-items: center; justify-content: space-between;
    height: 64px;
  }

  .lib-brand {
    display: flex; align-items: center; gap: 10px;
    text-decoration: none; color: var(--white);
    font-weight: 600; font-size: 1rem;
  }

  .lib-brand .brand-icon {
    width: 36px; height: 36px;
    background: var(--gold); border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; flex-shrink: 0;
  }

  .lib-nav-links {
    display: flex; gap: 2rem;
    list-style: none; margin: 0; padding: 0;
  }

  .lib-nav-links a {
    color: rgba(255,255,255,0.7);
    text-decoration: none; font-size: 0.9rem; font-weight: 500;
    transition: color 0.2s;
  }
  .lib-nav-links a:hover { color: var(--white); }

  .btn-login-nav {
    background: var(--blue-accent); color: var(--white);
    padding: 8px 22px; border-radius: 6px;
    font-size: 0.875rem; font-weight: 600;
    text-decoration: none; transition: background 0.2s;
  }
  .btn-login-nav:hover { background: #2450b5; color: var(--white); }

  /* ══ HERO ══ */
  .lib-hero {
    position: relative;
    min-height: 100vh;
    display: flex; align-items: center; justify-content: center;
    overflow: hidden; text-align: center;
  }

  .lib-hero-bg {
    position: absolute; inset: 0;
    background-image: url('https://images.unsplash.com/photo-1521587760476-6c12a4b040da?w=1600&q=80');
    background-size: cover; background-position: center 30%;
    filter: brightness(0.28) saturate(0.5);
  }

  .lib-hero-overlay {
    position: absolute; inset: 0;
    background: radial-gradient(ellipse at center, rgba(10,18,50,0.45) 0%, rgba(6,12,30,0.88) 100%);
  }

  .lib-hero-content {
    position: relative; z-index: 2;
    max-width: 720px; padding: 0 2rem;
  }

  .lib-hero-content h1 {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2.2rem, 5vw, 3.5rem);
    font-weight: 800; color: var(--white);
    line-height: 1.15; margin-bottom: 1.1rem; letter-spacing: -0.5px;
  }

  .lib-hero-content p {
    color: rgba(255,255,255,0.72);
    font-size: 1.05rem; line-height: 1.75;
    margin: 0 auto 2.4rem; max-width: 560px;
  }

  .lib-search-wrap {
    display: flex;
    background: var(--white); border-radius: 12px; overflow: hidden;
    box-shadow: 0 8px 36px rgba(0,0,0,0.35);
    max-width: 600px; margin: 0 auto 2.5rem;
  }

  .lib-search-wrap input {
    flex: 1; border: none; outline: none;
    padding: 17px 22px;
    font-size: 0.95rem; font-family: 'DM Sans', sans-serif;
    color: var(--text); background: transparent;
  }
  .lib-search-wrap input::placeholder { color: #a0aec0; }

  .lib-search-wrap button {
    background: var(--blue-accent); color: var(--white);
    border: none; padding: 0 28px;
    font-size: 0.95rem; font-weight: 600; font-family: 'DM Sans', sans-serif;
    cursor: pointer; transition: background 0.2s; white-space: nowrap;
  }
  .lib-search-wrap button:hover { background: #2450b5; }

  .lib-stats {
    display: flex; gap: 2rem;
    justify-content: center; flex-wrap: wrap;
  }

  .lib-stat { display: flex; align-items: center; gap: 10px; color: var(--white); }

  .lib-stat .stat-icon {
    width: 38px; height: 38px;
    border: 1.5px solid rgba(255,255,255,0.25); border-radius: 8px;
    display: flex; align-items: center; justify-content: center; font-size: 1rem;
  }

  .lib-stat .stat-num { font-size: 1.25rem; font-weight: 700; line-height: 1.1; }
  .lib-stat .stat-label { font-size: 0.78rem; color: rgba(255,255,255,0.55); font-weight: 500; }

  .scroll-hint {
    position: absolute; bottom: 2rem; left: 50%;
    transform: translateX(-50%); z-index: 2;
    color: rgba(255,255,255,0.35); font-size: 1.2rem;
    animation: bounce 2s infinite;
  }

  @keyframes bounce {
    0%,100% { transform: translateX(-50%) translateY(0); }
    50%      { transform: translateX(-50%) translateY(9px); }
  }

  /* ══ SHARED SECTION STYLES ══ */
  .section-header {
    text-align: center; margin-bottom: 2.2rem;
  }
  .section-header h2 {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.6rem, 3vw, 2.1rem);
    font-weight: 700; color: var(--text); margin: 0 0 0.5rem;
  }
  .section-header p {
    color: var(--text-muted); font-size: 0.95rem; margin: 0;
  }

  /* ══ KOLEKSI POPULER ══ */
  .popular-section {
    background: var(--white);
    padding: 5rem 0;
  }

  .popular-inner {
    max-width: 1200px; margin: 0 auto; padding: 0 2rem;
  }

  .cat-pills {
    display: flex; gap: 0.5rem;
    justify-content: center; flex-wrap: wrap;
    margin-bottom: 2.5rem;
  }

  .cat-pill {
    padding: 7px 20px; border-radius: 999px;
    font-size: 0.875rem; font-weight: 500;
    border: 1.5px solid var(--border);
    background: var(--white); color: var(--text-muted);
    cursor: pointer; transition: all 0.18s; text-decoration: none;
  }
  .cat-pill:hover { border-color: var(--navy); color: var(--navy); background: var(--bg); }
  .cat-pill.active { background: var(--navy); color: var(--white); border-color: var(--navy); }

  .popular-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
    gap: 1.5rem;
  }

  .pop-card {
    background: var(--white);
    border: 1px solid var(--border); border-radius: 14px; overflow: hidden;
    box-shadow: 0 2px 12px rgba(13,27,62,0.07);
    transition: transform 0.25s, box-shadow 0.25s;
    text-decoration: none; color: inherit; display: block;
    animation: fadeUp 0.45s ease both;
  }
  .pop-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 14px 40px rgba(13,27,62,0.15);
    color: inherit; text-decoration: none;
  }

  @keyframes fadeUp {
    from { opacity:0; transform: translateY(22px); }
    to   { opacity:1; transform: translateY(0); }
  }

  .pop-card-cover-wrap { position: relative; overflow: hidden; }

  .pop-card-cover-bg {
    width: 100%; height: 260px;
    background-size: cover; background-position: top center;
    background-color: #dde4f4; transition: transform 0.35s;
  }
  .pop-card:hover .pop-card-cover-bg { transform: scale(1.04); }

  .pop-card-badge {
    position: absolute; top: 12px; right: 12px;
    background: var(--gold); color: #fff;
    font-size: 0.72rem; font-weight: 700;
    padding: 3px 10px; border-radius: 999px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
  }

  .pop-card-body { padding: 1rem 1.1rem 1.25rem; }

  .pop-card-cat {
    display: inline-block;
    font-size: 0.75rem; font-weight: 600;
    color: var(--text-muted); background: var(--bg);
    border: 1px solid var(--border); border-radius: 6px;
    padding: 2px 10px; margin-bottom: 0.6rem;
  }

  .pop-card-title {
    font-size: 1rem; font-weight: 700;
    color: var(--text); margin: 0 0 0.3rem; line-height: 1.35;
  }

  .pop-card-author { font-size: 0.82rem; color: var(--text-muted); margin: 0 0 0.6rem; }

  .pop-card-rating {
    display: flex; align-items: center; gap: 5px;
    font-size: 0.85rem; font-weight: 600; color: var(--text);
  }
  .pop-card-rating .star { color: #f5a623; }

  .popular-cta { text-align: center; margin-top: 3rem; }

  .btn-see-all {
    display: inline-block;
    padding: 13px 36px; border-radius: 10px;
    background: var(--navy); color: var(--white);
    font-size: 0.95rem; font-weight: 600;
    text-decoration: none; transition: background 0.2s, transform 0.2s;
  }
  .btn-see-all:hover { background: var(--blue-accent); color: var(--white); transform: translateY(-2px); }

  /* ══ LAYANAN SECTION ══ */
  .layanan-section {
    background: var(--bg-warm);
    padding: 5rem 0;
  }

  .layanan-inner {
    max-width: 1100px; margin: 0 auto; padding: 0 2rem;
  }

  .layanan-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.25rem;
  }

  .layanan-card {
    background: var(--white);
    border: 1px solid #e8e4dc;
    border-radius: 14px;
    padding: 1.75rem 1.75rem 1.5rem;
    transition: transform 0.22s, box-shadow 0.22s;
    animation: fadeUp 0.45s ease both;
  }

  .layanan-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 32px rgba(13,27,62,0.1);
  }

  .layanan-icon {
    width: 52px; height: 52px;
    background: #eef1f8;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 1.1rem;
  }

  .layanan-icon svg {
    width: 26px; height: 26px;
    stroke: var(--navy); fill: none;
    stroke-width: 1.6; stroke-linecap: round; stroke-linejoin: round;
  }

  .layanan-card h3 {
    font-size: 1rem; font-weight: 700;
    color: var(--text); margin: 0 0 0.5rem;
  }

  .layanan-card p {
    font-size: 0.875rem; color: var(--text-muted);
    line-height: 1.6; margin: 0;
  }

  /* ══ FOOTER ══ */
  .lib-footer {
    background: var(--navy);
    color: rgba(255,255,255,0.75);
    padding: 4rem 0 0;
  }

  .footer-inner {
    max-width: 1200px; margin: 0 auto; padding: 0 2rem;
    display: grid;
    grid-template-columns: 1.8fr 1fr 1fr 1fr;
    gap: 3rem;
  }

  .footer-brand-name {
    display: flex; align-items: center; gap: 10px;
    margin-bottom: 1rem;
  }

  .footer-brand-name .brand-icon {
    width: 36px; height: 36px;
    background: var(--gold); border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; flex-shrink: 0;
  }

  .footer-brand-name span {
    font-size: 1rem; font-weight: 700; color: var(--white);
  }

  .footer-desc {
    font-size: 0.875rem; line-height: 1.7;
    margin: 0 0 1.5rem;
    color: rgba(255,255,255,0.55);
  }

  .footer-contact-item {
    display: flex; align-items: center; gap: 8px;
    font-size: 0.85rem; color: rgba(255,255,255,0.6);
    margin-bottom: 0.5rem;
  }

  .footer-col h4 {
    font-size: 0.8rem; font-weight: 700;
    color: rgba(255,255,255,0.45);
    text-transform: uppercase; letter-spacing: 1px;
    margin: 0 0 1.1rem;
  }

  .footer-col ul {
    list-style: none; margin: 0; padding: 0;
    display: flex; flex-direction: column; gap: 0.6rem;
  }

  .footer-col ul li a {
    color: rgba(255,255,255,0.65);
    text-decoration: none; font-size: 0.875rem;
    transition: color 0.2s;
  }
  .footer-col ul li a:hover { color: var(--white); }

  .footer-jam {
    font-size: 0.85rem;
    color: rgba(255,255,255,0.6);
    line-height: 1.8;
  }

  .footer-jam span {
    display: block;
    color: rgba(255,255,255,0.35);
    font-size: 0.78rem; margin-top: 0.2rem;
  }

  .footer-bottom {
    margin-top: 3.5rem;
    border-top: 1px solid rgba(255,255,255,0.08);
    padding: 1.25rem 2rem;
    max-width: 1200px; margin-left: auto; margin-right: auto;
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 0.5rem;
  }

  .footer-bottom p {
    font-size: 0.8rem; color: rgba(255,255,255,0.35); margin: 0;
  }

  .footer-gold { color: var(--gold); }

  /* ══ RESPONSIVE ══ */
  @media (max-width: 900px) {
    .layanan-grid { grid-template-columns: repeat(2, 1fr); }
    .footer-inner { grid-template-columns: 1fr 1fr; gap: 2rem; }
  }

  @media (max-width: 640px) {
    .lib-nav-links { display: none; }
    .popular-grid { grid-template-columns: repeat(2, 1fr); }
    .pop-card-cover-bg { height: 180px; }
    .layanan-grid { grid-template-columns: 1fr; }
    .footer-inner { grid-template-columns: 1fr; gap: 1.75rem; }
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- ══ NAVBAR ══ -->
<nav class="lib-navbar">
  <a href="<?= base_url('/') ?>" class="lib-brand">
    <div class="brand-icon">📚</div>
    Perpustakaan SMK
  </a>

  <ul class="lib-nav-links">
    <li><a href="<?= base_url('/') ?>">Beranda</a></li>
    <li><a href="<?= base_url('book') ?>">Koleksi</a></li>
    <li><a href="#">Layanan</a></li>
    <li><a href="#">Leaderboard</a></li>
    <li><a href="#">Kontak</a></li>
  </ul>

  <a href="<?= base_url('login') ?>" class="btn-login-nav">Login</a>
</nav>

<!-- ══ HERO ══ -->
<section class="lib-hero">
  <div class="lib-hero-bg"></div>
  <div class="lib-hero-overlay"></div>

  <div class="lib-hero-content">
    <h1>Jelajahi Dunia Pengetahuan<br>di Perpustakaan Kami</h1>
    <p>Temukan ribuan koleksi buku, sumber belajar digital, dan ruang baca yang nyaman untuk mendukung perjalanan akademikmu.</p>

    <div class="lib-search-wrap">
      <input type="text" placeholder="Cari judul buku, penulis, atau kategori…">
      <button type="button" onclick="window.location='<?= base_url('book') ?>'">Cari Buku</button>
    </div>

    <div class="lib-stats">
      <div class="lib-stat">
        <div class="stat-icon">📖</div>
        <div>
          <div class="stat-num">15,000+</div>
          <div class="stat-label">Koleksi Buku</div>
        </div>
      </div>
      <div class="lib-stat">
        <div class="stat-icon">👥</div>
        <div>
          <div class="stat-num">2,500+</div>
          <div class="stat-label">Anggota Aktif</div>
        </div>
      </div>
    </div>
  </div>

  <div class="scroll-hint">↓</div>
</section>

<!-- ══ KOLEKSI POPULER ══ -->
<section class="popular-section">
  <div class="popular-inner">
    <div class="section-header">
      <h2>Koleksi Populer</h2>
      <p>Temukan buku-buku favorit yang paling banyak dipinjam oleh siswa dan guru</p>
    </div>

    <div class="cat-pills">
      <a href="#" class="cat-pill active">Semua</a>
      <a href="#" class="cat-pill">Fiksi</a>
      <a href="#" class="cat-pill">Sejarah</a>
      <a href="#" class="cat-pill">Self-Help</a>
      <a href="#" class="cat-pill">Pengembangan Diri</a>
      <a href="#" class="cat-pill">Sains</a>
      <a href="#" class="cat-pill">Agama</a>
    </div>

    <div class="popular-grid">
      <a href="<?= base_url('book') ?>" class="pop-card" style="animation-delay:0ms">
        <div class="pop-card-cover-wrap">
          <div class="pop-card-cover-bg" style="background-image:url('https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=400&q=80')"></div>
          <span class="pop-card-badge">Baru</span>
        </div>
        <div class="pop-card-body">
          <span class="pop-card-cat">Fiksi</span>
          <p class="pop-card-title">Laskar Pelangi</p>
          <p class="pop-card-author">Andrea Hirata</p>
          <div class="pop-card-rating"><span class="star">★</span> 4.8</div>
        </div>
      </a>

      <a href="<?= base_url('book') ?>" class="pop-card" style="animation-delay:80ms">
        <div class="pop-card-cover-wrap">
          <div class="pop-card-cover-bg" style="background-image:url('https://images.unsplash.com/photo-1512820790803-83ca734da794?w=400&q=80')"></div>
        </div>
        <div class="pop-card-body">
          <span class="pop-card-cat">Sejarah</span>
          <p class="pop-card-title">Bumi Manusia</p>
          <p class="pop-card-author">Pramoedya Ananta Toer</p>
          <div class="pop-card-rating"><span class="star">★</span> 4.9</div>
        </div>
      </a>

      <a href="<?= base_url('book') ?>" class="pop-card" style="animation-delay:160ms">
        <div class="pop-card-cover-wrap">
          <div class="pop-card-cover-bg" style="background-image:url('https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=400&q=80')"></div>
          <span class="pop-card-badge">Baru</span>
        </div>
        <div class="pop-card-body">
          <span class="pop-card-cat">Self-Help</span>
          <p class="pop-card-title">Filosofi Teras</p>
          <p class="pop-card-author">Henry Manampiring</p>
          <div class="pop-card-rating"><span class="star">★</span> 4.7</div>
        </div>
      </a>

      <a href="<?= base_url('book') ?>" class="pop-card" style="animation-delay:240ms">
        <div class="pop-card-cover-wrap">
          <div class="pop-card-cover-bg" style="background-image:url('https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=400&q=80')"></div>
        </div>
        <div class="pop-card-body">
          <span class="pop-card-cat">Pengembangan Diri</span>
          <p class="pop-card-title">Atomic Habits</p>
          <p class="pop-card-author">James Clear</p>
          <div class="pop-card-rating"><span class="star">★</span> 4.9</div>
        </div>
      </a>
    </div>

    <div class="popular-cta">
      <a href="<?= base_url('book') ?>" class="btn-see-all">Lihat Semua Koleksi →</a>
    </div>
  </div>
</section>

<!-- ══ LAYANAN KAMI ══ -->
<section class="layanan-section" id="layanan">
  <div class="layanan-inner">
    <div class="section-header">
      <h2>Layanan Kami</h2>
      <p>Berbagai fasilitas dan layanan untuk mendukung kegiatan belajar mengajar</p>
    </div>

    <div class="layanan-grid">

      <!-- Peminjaman Buku -->
      <div class="layanan-card" style="animation-delay:0ms">
        <div class="layanan-icon">
          <svg viewBox="0 0 24 24"><path d="M2 6a2 2 0 012-2h7l2 2h7a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/><path d="M8 11h8M8 15h5"/></svg>
        </div>
        <h3>Peminjaman Buku</h3>
        <p>Pinjam hingga 5 buku selama 14 hari dengan perpanjangan online</p>
      </div>

      <!-- Akses E-Library -->
      <div class="layanan-card" style="animation-delay:80ms">
        <div class="layanan-icon">
          <svg viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
        </div>
        <h3>Akses E-Library</h3>
        <p>Ribuan e-book dan jurnal ilmiah tersedia untuk diakses kapan saja</p>
      </div>

      <!-- Ruang Diskusi -->
      <div class="layanan-card" style="animation-delay:160ms">
        <div class="layanan-icon">
          <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
        </div>
        <h3>Ruang Diskusi</h3>
        <p>Ruang diskusi kelompok yang nyaman untuk belajar bersama</p>
      </div>

      <!-- Reading Corner -->
      <div class="layanan-card" style="animation-delay:240ms">
        <div class="layanan-icon">
          <svg viewBox="0 0 24 24"><path d="M18 8h1a4 4 0 010 8h-1"/><path d="M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>
        </div>
        <h3>Reading Corner</h3>
        <p>Sudut baca yang cozy dengan fasilitas charging station</p>
      </div>

      <!-- Audiobook -->
      <div class="layanan-card" style="animation-delay:320ms">
        <div class="layanan-icon">
          <svg viewBox="0 0 24 24"><path d="M3 18v-6a9 9 0 0118 0v6"/><path d="M21 19a2 2 0 01-2 2h-1a2 2 0 01-2-2v-3a2 2 0 012-2h3zM3 19a2 2 0 002 2h1a2 2 0 002-2v-3a2 2 0 00-2-2H3z"/></svg>
        </div>
        <h3>Audiobook</h3>
        <p>Koleksi audiobook untuk pengalaman belajar yang berbeda</p>
      </div>

      <!-- Jam Buka -->
      <div class="layanan-card" style="animation-delay:400ms">
        <div class="layanan-icon">
          <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <h3>Buka Setiap Hari</h3>
        <p>Perpustakaan buka Senin–Sabtu pukul 07:00 – 17:00 WIB</p>
      </div>

    </div>
  </div>
</section>

<!-- ══ FOOTER ══ -->
<footer class="lib-footer">
  <div class="footer-inner">

    <!-- Brand col -->
    <div>
      <div class="footer-brand-name">
        <div class="brand-icon">📚</div>
        <span>Perpustakaan SMK</span>
      </div>
      <p class="footer-desc">Pusat sumber belajar yang mendukung prestasi akademik siswa dan guru dengan koleksi lengkap dan layanan terbaik.</p>
      <div class="footer-contact-item">📍 Jl. Pendidikan No. 1, Kota Anda</div>
      <div class="footer-contact-item">📞 (0xx) 1234-5678</div>
      <div class="footer-contact-item">✉️ perpustakaan@smk.sch.id</div>
    </div>

    <!-- Navigasi -->
    <div class="footer-col">
      <h4>Navigasi</h4>
      <ul>
        <li><a href="<?= base_url('/') ?>">Beranda</a></li>
        <li><a href="<?= base_url('book') ?>">Koleksi Buku</a></li>
        <li><a href="#">Layanan</a></li>
        <li><a href="#">Leaderboard</a></li>
        <li><a href="#">Kontak</a></li>
      </ul>
    </div>

    <!-- Layanan -->
    <div class="footer-col">
      <h4>Layanan</h4>
      <ul>
        <li><a href="#">Peminjaman Buku</a></li>
        <li><a href="#">E-Library</a></li>
        <li><a href="#">Ruang Diskusi</a></li>
        <li><a href="#">Reading Corner</a></li>
        <li><a href="#">Audiobook</a></li>
      </ul>
    </div>

    <!-- Jam Operasional -->
    <div class="footer-col">
      <h4>Jam Operasional</h4>
      <div class="footer-jam">
        Senin – Jumat
        <span>07:00 – 17:00 WIB</span>
      </div>
      <div class="footer-jam" style="margin-top:0.75rem">
        Sabtu
        <span>07:00 – 14:00 WIB</span>
      </div>
      <div class="footer-jam" style="margin-top:0.75rem">
        Minggu
        <span>Tutup</span>
      </div>
    </div>

  </div>

  <div class="footer-bottom">
    <p>© <?= date('Y') ?> <span class="footer-gold">Perpustakaan SMK</span>. Semua hak cipta dilindungi.</p>
    <p>Dibuat dengan ❤️ untuk mendukung literasi</p>
  </div>
</footer>

<?= $this->endSection() ?>