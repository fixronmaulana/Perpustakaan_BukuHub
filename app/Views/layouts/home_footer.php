<!-- ══ FOOTER — app/Views/layouts/footer.php ══ -->
<!-- Cara pakai: <?= $this->include('layouts/footer') ?> -->

<footer class="kaki-halaman">
  <div class="dalam-kaki">

    <!-- Kolom 1: Brand + Kontak -->
    <div>
      <div class="nama-merek-kaki">
      <img src="<?= base_url('assets/images/logo-smk.jpg') ?>" alt="Logo SMK" class="logo-smk-nav">
      <img src="<?= base_url('assets/images/logo-perpus3.png') ?>" alt="Logo SMK" class="logo-smk-nav">
        <!-- <span>Perpustakaan SMK Al-Munawwir IIBS</span> -->
      </div>
      <p class="deskripsi-kaki">
        Pusat sumber belajar yang mendukung prestasi akademik siswa dan guru
        dengan koleksi lengkap dan layanan terbaik.
      </p>
      <div class="kontak-kaki">📍 Jl. Kedungliwung No.35, Kemiri, Singojuruh, Banyuwangi</div>
      <div class="kontak-kaki">📞 (0xx) 1234-5678</div>
      <div class="kontak-kaki">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
            style="vertical-align: middle;">
            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
            <circle cx="12" cy="12" r="4"/>
            <circle cx="17.5" cy="6.5" r="0.5" fill="currentColor" stroke="none"/>
          </svg>
        <a href="https://www.instagram.com/smkalmunawwiriibs/" target="_blank" class="link-ig">smkalmunawwiriibs
        </a>
      </div>
      <div class="kontak-kaki">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
          style="vertical-align: middle;">
          <rect x="2" y="4" width="20" height="16" rx="2"/>
          <polyline points="2,4 12,13 22,4"/>
        </svg>perpustakaan@smk.sch.id
      </div>
    </div>

    <!-- Kolom 2: Navigasi -->
    <div class="kolom-kaki">
      <h4>Navigasi</h4>
      <ul>
        <li><a href="<?= base_url('/') ?>">Beranda</a></li>
        <li><a href="<?= base_url('book') ?>">Koleksi Buku</a></li>
        <li><a href="<?= base_url('layanan') ?>">Layanan</a></li>
        <li><a href="<?= base_url('leaderboard') ?>">Leaderboard</a></li>
        <li><a href="<?= base_url('kontak') ?>">Kontak</a></li>
      </ul>
    </div>

    <!-- Kolom 3: Layanan -->
    <div class="kolom-kaki">
      <h4>Layanan</h4>
      <ul>
        <li><a href="#">Peminjaman Buku</a></li>
        <li><a href="#">E-Library</a></li>
        <li><a href="#">Ruang Diskusi</a></li>
        <li><a href="#">Reading Corner</a></li>
        <li><a href="#">Audiobook</a></li>
      </ul>
    </div>

    <!-- Kolom 4: Jam Operasional -->
    <div class="kolom-kaki">
      <h4>Jam Operasional</h4>
      <div class="jam-operasional">
        Senin – Jumat
        <span>07:00 – 14:00 WIB</span>
      </div>
      <div class="jam-operasional" style="margin-top:0.75rem">
        Sabtu
        <span>07:00 – 13:00 WIB</span>
      </div>
      <div class="jam-operasional" style="margin-top:0.75rem">
        Minggu
        <span>Tutup</span>
      </div>
    </div>

  </div>

  <!-- Baris bawah copyright -->
  <div class="bawah-kaki">
    <p>
      <a href="https://github.com/ikhsan3adi/sistem-perpustakaan-qr-code" target="_blank" class="teks-emas">BukuHub</a> © 2026.
      Perpustakaan Al-Munawwir IIBS
    </p>
    <p>
      Dibuat oleh
      <a href="https://github.com/gilangsetia" target="_blank" class="teks-emas">Gilang Setia Adi Saputra</a>
      atas bimbingan TRPL Poliwangi
      <img src="<?= base_url('assets/images/logo-poliwangi.png') ?>" alt="Logo Poliwangi"
        style="height: 1.5rem; width: auto; vertical-align: middle; margin: 0 3px;">
    </p>
  </div>
</footer>