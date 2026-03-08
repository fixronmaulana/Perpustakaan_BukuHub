<!-- ══ FOOTER — app/Views/layouts/footer.php ══ -->
<!-- Cara pakai: <?= $this->include('layouts/footer') ?> -->

<footer class="kaki-halaman">
  <div class="dalam-kaki">

    <!-- Kolom 1: Brand + Kontak -->
    <div>
      <div class="nama-merek-kaki">
        <div class="ikon-merek">📚</div>
        <span>Perpustakaan SMK</span>
      </div>
      <p class="deskripsi-kaki">
        Pusat sumber belajar yang mendukung prestasi akademik siswa dan guru
        dengan koleksi lengkap dan layanan terbaik.
      </p>
      <div class="kontak-kaki">📍 Jl. Pendidikan No. 1, Kota Anda</div>
      <div class="kontak-kaki">📞 (0xx) 1234-5678</div>
      <div class="kontak-kaki">✉️ perpustakaan@smk.sch.id</div>
    </div>

    <!-- Kolom 2: Navigasi -->
    <div class="kolom-kaki">
      <h4>Navigasi</h4>
      <ul>
        <li><a href="<?= base_url('/') ?>">Beranda</a></li>
        <li><a href="<?= base_url('book') ?>">Koleksi Buku</a></li>
        <li><a href="#">Layanan</a></li>
        <li><a href="#">Leaderboard</a></li>
        <li><a href="#">Kontak</a></li>
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
        <span>07:00 – 17:00 WIB</span>
      </div>
      <div class="jam-operasional" style="margin-top:0.75rem">
        Sabtu
        <span>07:00 – 14:00 WIB</span>
      </div>
      <div class="jam-operasional" style="margin-top:0.75rem">
        Minggu
        <span>Tutup</span>
      </div>
    </div>

  </div>

  <!-- Baris bawah copyright -->
  <div class="bawah-kaki">
    <p>© <?= date('Y') ?> <span class="teks-emas">Perpustakaan SMK</span>. Semua hak cipta dilindungi.</p>
    <p>Dibuat dengan ❤️ untuk mendukung literasi</p>
  </div>
</footer>