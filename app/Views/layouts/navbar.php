<!-- ══ NAVBAR — app/Views/layouts/navbar.php ══ -->
<nav class="bilah-navigasi">

  <a href="<?= base_url('/') ?>" class="merek-perpus">
    <div class="ikon-merek">📚</div>
    Perpustakaan SMK
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
      <a href="#"
        <?= (isset($activeNav) && $activeNav === 'kontak') ? 'class="aktif"' : '' ?>>
        Kontak
      </a>
    </li>
  </ul>

  <a href="<?= base_url('login') ?>" class="tombol-masuk">Login</a>

</nav>