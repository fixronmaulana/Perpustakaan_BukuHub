<?= $this->extend('layouts/home_layout') ?>

<?= $this->section('head') ?>
<title>Kontak — Perpustakaan SMK</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?= $this->include('layouts/navbar') ?>

<!-- ══ HEADER HALAMAN ══ -->
<div class="header-halaman">
  <h1>Hubungi Kami</h1>
  <div class="garis-emas"></div>
  <p>Ada pertanyaan, saran, atau butuh bantuan? Kami siap membantu kamu</p>
</div>

<!-- ══ KONTEN KONTAK ══ -->
<div class="bungkus-kontak">

  <!-- ── BARIS ATAS: INFO + FORM ── -->
  <div class="grid-kontak-utama">

    <!-- Kolom kiri: Informasi kontak -->
    <div class="kolom-info-kontak">

      <h2 class="judul-kolom-kontak">Informasi Kontak</h2>
      <p class="deskripsi-kolom-kontak">
        Kunjungi perpustakaan kami atau hubungi melalui saluran di bawah ini.
        Petugas kami siap melayani pada jam operasional.
      </p>

      <!-- Kartu info kontak -->
      <div class="daftar-info-kontak">

        <div class="kartu-info-kontak">
          <div class="ikon-info-kontak">
            <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
          </div>
          <div class="isi-info-kontak">
            <div class="label-info-kontak">Alamat</div>
            <div class="nilai-info-kontak">Jl. Pendidikan No. 1<br>Kota Anda, Provinsi Anda 12345</div>
          </div>
        </div>

        <div class="kartu-info-kontak">
          <div class="ikon-info-kontak">
            <svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.8 19.79 19.79 0 0115 3.18 2 2 0 0117 5.36v3a2 2 0 01-1.41 1.94 12 12 0 01-5.37 5.37A2 2 0 0122 16.92z"/></svg>
          </div>
          <div class="isi-info-kontak">
            <div class="label-info-kontak">Telepon</div>
            <div class="nilai-info-kontak">(0xx) 1234-5678</div>
          </div>
        </div>

        <div class="kartu-info-kontak">
          <div class="ikon-info-kontak">
            <svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
          </div>
          <div class="isi-info-kontak">
            <div class="label-info-kontak">Email</div>
            <div class="nilai-info-kontak">perpustakaan@smk.sch.id</div>
          </div>
        </div>

        <div class="kartu-info-kontak">
          <div class="ikon-info-kontak">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          </div>
          <div class="isi-info-kontak">
            <div class="label-info-kontak">Jam Operasional</div>
            <div class="nilai-info-kontak">
              Senin – Jumat: 07.00 – 17.00 WIB<br>
              Sabtu: 07.00 – 14.00 WIB<br>
              <span class="teks-tutup">Minggu: Tutup</span>
            </div>
          </div>
        </div>

      </div><!-- /daftar-info-kontak -->

      <!-- Sosial media -->
      <div class="area-sosmed">
        <div class="label-sosmed">Ikuti Kami</div>
        <div class="daftar-sosmed">
          <a href="#" class="tombol-sosmed" title="Instagram">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>
            </svg>
            Instagram
          </a>
          <a href="#" class="tombol-sosmed" title="Facebook">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>
            </svg>
            Facebook
          </a>
          <a href="#" class="tombol-sosmed" title="YouTube">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M22.54 6.42a2.78 2.78 0 00-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46A2.78 2.78 0 001.46 6.42 29 29 0 001 12a29 29 0 00.46 5.58 2.78 2.78 0 001.95 1.95C5.12 20 12 20 12 20s6.88 0 8.59-.47a2.78 2.78 0 001.95-1.95A29 29 0 0023 12a29 29 0 00-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"/>
            </svg>
            YouTube
          </a>
        </div>
      </div>

    </div><!-- /kolom-info-kontak -->

    <!-- Kolom kanan: Form pesan -->
    <div class="kolom-form-kontak">

      <h2 class="judul-kolom-kontak">Kirim Pesan</h2>
      <p class="deskripsi-kolom-kontak">
        Punya pertanyaan atau saran? Isi form di bawah dan kami akan membalas
        secepatnya pada jam kerja.
      </p>

      <!-- Flash sukses -->
      <?php if (session('sukses')) : ?>
        <div class="pesan-form sukses">
          ✅ <?= session('sukses') ?>
        </div>
      <?php endif ?>

      <!-- Flash error -->
      <?php if (session('errors')) : ?>
        <div class="pesan-form gagal">
          ⚠️ Harap lengkapi semua field yang wajib diisi.
        </div>
      <?php endif ?>

      <form action="<?= base_url('kontak/kirim') ?>" method="post" class="form-kontak">
        <?= csrf_field() ?>

        <div class="baris-form-kontak">
          <div class="grup-form-kontak">
            <label for="nama">Nama Lengkap <span class="wajib">*</span></label>
            <input type="text" id="nama" name="nama"
              placeholder="Masukkan nama lengkap"
              value="<?= old('nama') ?>" required>
          </div>
          <div class="grup-form-kontak">
            <label for="email_kontak">Email <span class="wajib">*</span></label>
            <input type="email" id="email_kontak" name="email"
              placeholder="contoh@email.com"
              value="<?= old('email') ?>" required>
          </div>
        </div>

        <div class="grup-form-kontak">
          <label for="subjek">Subjek <span class="wajib">*</span></label>
          <select id="subjek" name="subjek" required>
            <option value="" disabled <?= !old('subjek') ? 'selected' : '' ?>>Pilih topik pesan…</option>
            <option value="pertanyaan"  <?= old('subjek') === 'pertanyaan'  ? 'selected' : '' ?>>Pertanyaan Umum</option>
            <option value="peminjaman"  <?= old('subjek') === 'peminjaman'  ? 'selected' : '' ?>>Peminjaman & Pengembalian</option>
            <option value="koleksi"     <?= old('subjek') === 'koleksi'     ? 'selected' : '' ?>>Saran Koleksi Buku</option>
            <option value="fasilitas"   <?= old('subjek') === 'fasilitas'   ? 'selected' : '' ?>>Fasilitas & Layanan</option>
            <option value="keanggotaan" <?= old('subjek') === 'keanggotaan' ? 'selected' : '' ?>>Keanggotaan</option>
            <option value="lainnya"     <?= old('subjek') === 'lainnya'     ? 'selected' : '' ?>>Lainnya</option>
          </select>
        </div>

        <div class="grup-form-kontak">
          <label for="pesan">Pesan <span class="wajib">*</span></label>
          <textarea id="pesan" name="pesan" rows="5"
            placeholder="Tuliskan pertanyaan atau saran kamu di sini…"
            required><?= old('pesan') ?></textarea>
        </div>

        <button type="submit" class="tombol-kirim-kontak">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" width="16" height="16">
            <line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/>
          </svg>
          Kirim Pesan
        </button>

        <p class="catatan-form">
          <span class="wajib">*</span> Wajib diisi. Kami menghargai privasi kamu dan tidak akan membagikan data ke pihak lain.
        </p>

      </form>

    </div><!-- /kolom-form-kontak -->

  </div><!-- /grid-kontak-utama -->

  <!-- ── PETA LOKASI ── -->
  <div class="area-peta">
    <div class="kepala-area-peta">
      <h2>Lokasi Kami</h2>
      <p>Perpustakaan berada di dalam kompleks SMK AL-Munawwir, gedung B lantai 1</p>
    </div>
    <div class="bingkai-peta">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3295.2419846812086!2d114.2046592741292!3d-8.29071138337786!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd15300689df32d%3A0xf4d28a0ff76d227c!2sSMK%20AL%20MUNAWWIR%20IIBS!5e1!3m2!1sid!2sid!4v1772965150579!5m2!1sid!2sid" 
          width="600" height="450" 
          style="border:0;" allowfullscreen="" loading="lazy"
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div>
  </div>

  <!-- ── FAQ SINGKAT ── -->
  <div class="area-faq">
    <h2 class="judul-faq">Pertanyaan yang Sering Ditanyakan</h2>
    <div class="grid-faq">

      <div class="kartu-faq">
        <div class="pertanyaan-faq">Bagaimana cara mendaftar jadi anggota?</div>
        <div class="jawaban-faq">Daftarkan diri ke petugas perpustakaan dengan membawa kartu pelajar. Pendaftaran gratis dan langsung aktif di hari yang sama.</div>
      </div>

      <div class="kartu-faq">
        <div class="pertanyaan-faq">Berapa lama batas waktu peminjaman?</div>
        <div class="jawaban-faq">Batas waktu peminjaman adalah 14 hari. Perpanjangan bisa dilakukan 1 kali melalui petugas atau sistem online.</div>
      </div>

      <div class="kartu-faq">
        <div class="pertanyaan-faq">Apa yang terjadi jika buku terlambat dikembalikan?</div>
        <div class="jawaban-faq">Akan dikenakan denda Rp500 per hari per buku. Poin gamifikasi juga akan berkurang sebesar 10 poin per keterlambatan.</div>
      </div>

      <div class="kartu-faq">
        <div class="pertanyaan-faq">Bagaimana cara mengakses e-library?</div>
        <div class="jawaban-faq">Login menggunakan akun yang sama dengan sistem perpustakaan. E-library bisa diakses dari browser maupun aplikasi di smartphone.</div>
      </div>

      <div class="kartu-faq">
        <div class="pertanyaan-faq">Apakah bisa request buku koleksi baru?</div>
        <div class="jawaban-faq">Bisa! Gunakan form pesan di atas dengan subjek "Saran Koleksi Buku". Kami akan mempertimbangkan setiap usulan yang masuk.</div>
      </div>

      <div class="kartu-faq">
        <div class="pertanyaan-faq">Bagaimana cara mendapat poin gamifikasi?</div>
        <div class="jawaban-faq">Poin didapat dari kunjungan, peminjaman, pengembalian tepat waktu, dan partisipasi kuis. Lihat detail di halaman Leaderboard.</div>
      </div>

    </div>
  </div>

</div><!-- /bungkus-kontak -->

<?= $this->include('layouts/home_footer') ?>

<?= $this->endSection() ?>