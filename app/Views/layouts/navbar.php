<!-- ══ NAVBAR — app/Views/layouts/navbar.php ══ -->
<nav class="bilah-navigasi">

  <a href="<?= base_url('/') ?>" class="merek-perpus">
    <img src="<?= base_url('assets/images/logo-perpus3.png') ?>" alt="Logo SMK" class="logo-smk-nav">
    <div class="teks-merek">
      <span class="teks-atas">Perpustakaan</span>
      <span class="teks-bawah">SMK Al-Munawwir IIBS</span>
    </div> 
  </a>

  <ul class="daftar-menu" id="daftarMenu">
    <li>
      <a href="<?= base_url('/') ?>"
        <?= (isset($activeNav) && $activeNav === 'beranda') ? 'class="aktif"' : '' ?>>
        Beranda
      </a>
    </li>
    <li>
      <a href="<?= base_url('book') ?>"
        <?= (isset($activeNav) && $activeNav === 'koleksi') ? 'class="aktif"' : '' ?>>
        Koleksi
      </a>
    </li>
    <li>
      <a href="<?= base_url('leaderboard') ?>"
        <?= (isset($activeNav) && $activeNav === 'leaderboard') ? 'class="aktif"' : '' ?>>
        Leaderboard
      </a>
    </li>
    <li>
      <a href="<?= base_url('layanan') ?>"
        <?= (isset($activeNav) && $activeNav === 'layanan') ? 'class="aktif"' : '' ?>>
        Layanan
      </a>
    </li>
    <li>
      <a href="<?= base_url('kontak') ?>"
        <?= (isset($activeNav) && $activeNav === 'kontak') ? 'class="aktif"' : '' ?>>
        Kontak
      </a>
    </li>
    <!-- Login masuk ke menu saat mobile -->
    <li class="menu-login-mobile">
      <a href="<?= base_url('login') ?>">Login</a>
    </li>
  </ul>

  <div class="kanan-nav">
    <!-- Login tetap tampil di desktop -->
    <a href="<?= base_url('login') ?>" class="tombol-masuk">Login</a>
    <button class="tombol-burger" id="tombolBurger" onclick="toggleMenu()" aria-label="Toggle Menu">
      <span></span>
      <span></span>
      <span></span>
    </button>
  </div>

</nav>