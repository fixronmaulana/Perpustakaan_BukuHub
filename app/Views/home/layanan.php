<?= $this->extend('layouts/home_layout') ?>

<?= $this->section('head') ?>
<title>Layanan — Perpustakaan SMK</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?= $this->include('layouts/navbar') ?>

<!-- ══ HEADER HALAMAN ══ -->
<div class="header-halaman">
  <h1>Layanan Kami</h1>
  <div class="garis-emas"></div>
  <p>Berbagai fasilitas dan layanan untuk mendukung kegiatan belajar mengajar</p>
</div>

<!-- ══ KONTEN LAYANAN ══ -->
<div class="bungkus-layanan">

  <!-- ── KARTU LAYANAN UTAMA ── -->
  <div class="grid-layanan-besar">

    <!-- Peminjaman Buku -->
    <div class="kartu-layanan-besar">
      <div class="ikon-layanan-besar">
        <svg viewBox="0 0 24 24"><path d="M2 6a2 2 0 012-2h7l2 2h7a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/><path d="M8 11h8M8 15h5"/></svg>
      </div>
      <div class="isi-layanan-besar">
        <h3>Peminjaman Buku</h3>
        <p>Nikmati kemudahan meminjam koleksi buku perpustakaan dengan prosedur yang mudah dan cepat.</p>
        <ul class="daftar-fitur">
          <li>Maksimal 5 buku per peminjaman</li>
          <li>Durasi pinjam 14 hari</li>
          <li>Perpanjangan bisa dilakukan secara online</li>
          <li>Denda Rp500/hari jika terlambat</li>
        </ul>
        <div class="label-status tersedia">Tersedia</div>
      </div>
    </div>

    <!-- Akses E-Library -->
    <div class="kartu-layanan-besar">
      <div class="ikon-layanan-besar">
        <svg viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
      </div>
      <div class="isi-layanan-besar">
        <h3>Akses E-Library</h3>
        <p>Ribuan koleksi e-book dan jurnal ilmiah tersedia secara digital yang bisa diakses kapan saja dan di mana saja.</p>
        <ul class="daftar-fitur">
          <li>Akses 24 jam tanpa batas</li>
          <li>Koleksi e-book lebih dari 5.000 judul</li>
          <li>Jurnal ilmiah nasional dan internasional</li>
          <li>Bisa diakses dari smartphone maupun laptop</li>
        </ul>
        <div class="label-status tersedia">Tersedia</div>
      </div>
    </div>

    <!-- Ruang Diskusi -->
    <div class="kartu-layanan-besar">
      <div class="ikon-layanan-besar">
        <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
      </div>
      <div class="isi-layanan-besar">
        <h3>Ruang Diskusi</h3>
        <p>Ruang khusus untuk belajar kelompok yang nyaman, kondusif, dan dilengkapi fasilitas pendukung.</p>
        <ul class="daftar-fitur">
          <li>Kapasitas 6–10 orang per ruangan</li>
          <li>Dilengkapi whiteboard dan proyektor</li>
          <li>Tersedia 3 ruangan diskusi</li>
          <li>Reservasi via petugas perpustakaan</li>
        </ul>
        <div class="label-status tersedia">Tersedia</div>
      </div>
    </div>

    <!-- Reading Corner -->
    <div class="kartu-layanan-besar">
      <div class="ikon-layanan-besar">
        <svg viewBox="0 0 24 24"><path d="M18 8h1a4 4 0 010 8h-1"/><path d="M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>
      </div>
      <div class="isi-layanan-besar">
        <h3>Reading Corner</h3>
        <p>Sudut baca yang cozy dan nyaman dengan suasana tenang untuk membaca dan belajar mandiri.</p>
        <ul class="daftar-fitur">
          <li>Kursi dan meja baca yang ergonomis</li>
          <li>Tersedia colokan dan charging station</li>
          <li>Koneksi WiFi gratis</li>
          <li>Suasana tenang dan kondusif</li>
        </ul>
        <div class="label-status tersedia">Tersedia</div>
      </div>
    </div>

    <!-- Audiobook -->
    <div class="kartu-layanan-besar">
      <div class="ikon-layanan-besar">
        <svg viewBox="0 0 24 24"><path d="M3 18v-6a9 9 0 0118 0v6"/><path d="M21 19a2 2 0 01-2 2h-1a2 2 0 01-2-2v-3a2 2 0 012-2h3zM3 19a2 2 0 002 2h1a2 2 0 002-2v-3a2 2 0 00-2-2H3z"/></svg>
      </div>
      <div class="isi-layanan-besar">
        <h3>Audiobook</h3>
        <p>Nikmati pengalaman membaca yang berbeda dengan koleksi audiobook pilihan yang bisa didengarkan kapan saja.</p>
        <ul class="daftar-fitur">
          <li>Koleksi 500+ judul audiobook</li>
          <li>Genre beragam: fiksi, sains, sejarah</li>
          <li>Bisa didengar via aplikasi atau website</li>
          <li>Gratis untuk anggota aktif</li>
        </ul>
        <div class="label-status segera">Segera Hadir</div>
      </div>
    </div>

    <!-- Jam Layanan -->
    <div class="kartu-layanan-besar">
      <div class="ikon-layanan-besar">
        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
      </div>
      <div class="isi-layanan-besar">
        <h3>Jam Operasional</h3>
        <p>Perpustakaan buka setiap hari kerja untuk melayani seluruh siswa dan guru SMK AL-Munawwir.</p>
        <ul class="daftar-jam">
          <li>
            <span class="hari">Senin – Jumat</span>
            <span class="waktu">07:00 – 17:00 WIB</span>
          </li>
          <li>
            <span class="hari">Sabtu</span>
            <span class="waktu">07:00 – 14:00 WIB</span>
          </li>
          <li>
            <span class="hari">Minggu</span>
            <span class="waktu tutup">Tutup</span>
          </li>
        </ul>
        <div class="label-status tersedia">Buka Hari Ini</div>
      </div>
    </div>

  </div><!-- /grid-layanan-besar -->

  <!-- ── BANNER KONTAK ── -->
  <div class="banner-kontak">
    <div class="isi-banner-kontak">
      <h2>Butuh Bantuan?</h2>
      <p>Hubungi petugas perpustakaan kami untuk informasi lebih lanjut mengenai layanan yang tersedia.</p>
      <div class="daftar-kontak-banner">
        <div class="item-kontak-banner">📍 Jl. Pendidikan No. 1, Kota Anda</div>
        <div class="item-kontak-banner">📞 (0xx) 1234-5678</div>
        <div class="item-kontak-banner">✉️ perpustakaan@smk.sch.id</div>
      </div>
    </div>
  </div>

</div><!-- /bungkus-layanan -->

<?= $this->include('layouts/home_footer') ?>

<?= $this->endSection() ?>