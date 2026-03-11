<!-- ══ NAVBAR — app/Views/layouts/navbar.php ══ -->
<nav class="bilah-navigasi">

  <a href="<?= base_url('/') ?>" class="merek-perpus">
  <img src="<?= base_url('assets/images/logo-smk.png') ?>" alt="Logo SMK" class="logo-smk-nav">
  <div class="teks-merek">
    <span class="teks-atas">Perpustakaan</span>
    <span class="teks-bawah">SMK Al-Munawwir IIBS</span>
  </div>
</a>

  <ul class="daftar-menu">
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
      <a href="<?= base_url('layanan') ?>"
        <?= (isset($activeNav) && $activeNav === 'layanan') ? 'class="aktif"' : '' ?>>
        Layanan
      </a>
    </li>
    <li>
      <a href="<?= base_url('leaderboard') ?>"
        <?= (isset($activeNav) && $activeNav === 'leaderboard') ? 'class="aktif"' : '' ?>>
        Leaderboard
      </a>
    </li>
    <li>
      <a href="<?= base_url('kontak') ?>"
        <?= (isset($activeNav) && $activeNav === 'kontak') ? 'class="aktif"' : '' ?>>
        Kontak
      </a>
    </li>
  </ul>

  <a href="<?= base_url('login') ?>" class="tombol-masuk">Login</a>

</nav>