<?= $this->extend('layouts/member_layout') ?>

<?= $this->section('head') ?>
<title>Dashboard — Portal Anggota</title>
<?= $this->endSection() ?>

<?= $this->section('pageTitle') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- ── Kartu Statistik ── -->
<div class="grid-stat">

  <div class="kartu-stat">
    <div class="isi-stat">
      <div class="label-stat">Buku Dipinjam</div>
      <div class="angka-stat">2</div>
    </div>
    <div class="ikon-stat-wrap biru">
      <svg viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
    </div>
  </div>

  <div class="kartu-stat">
    <div class="isi-stat">
      <div class="label-stat">Kunjungan Bulan Ini</div>
      <div class="angka-stat">3</div>
    </div>
    <div class="ikon-stat-wrap hijau">
      <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
    </div>
  </div>

  <div class="kartu-stat">
    <div class="isi-stat">
      <div class="label-stat">Peringkat</div>
      <div class="angka-stat">#5</div>
      <div class="sub-stat">Bulan ini</div>
    </div>
    <div class="ikon-stat-wrap emas">
      <svg viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
    </div>
  </div>

</div>

<!-- ── Grid 2 kolom: Tabel kiri + Riwayat poin kanan ── -->
<div class="grid-konten-dashboard">

  <!-- Kolom kiri: tabel pinjaman + pengembalian -->
  <div>

    <!-- Pinjaman Aktif -->
    <div class="kotak-konten">
      <div class="kepala-kotak">
        <h3>Pinjaman Aktif</h3>
        <a href="<?= base_url('member/peminjaman') ?>" class="tautan-lihat-semua">Lihat Semua →</a>
      </div>
      <div class="bungkus-tabel">
        <table class="tabel-member">
          <thead>
            <tr>
              <th>Buku</th>
              <th>Tgl Pinjam</th>
              <th>Jatuh Tempo</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <div class="sel-buku">
                  <div class="sampul-tabel" style="background:linear-gradient(135deg,#1e3a8a,#60a5fa)">📖</div>
                  <div>
                    <div class="penulis-tabel">Mansur Hidayat</div>
                    <div class="judul-tabel">Kehidupan Setelah di Dunia</div>
                  </div>
                </div>
              </td>
              <td class="tgl-normal">1 Jan 2026</td>
              <td class="tgl-terlambat">4 Jan 2026</td>
              <td><span class="badge dipinjam">Dipinjam</span></td>
            </tr>
            <tr>
              <td>
                <div class="sel-buku">
                  <div class="sampul-tabel" style="background:linear-gradient(135deg,#065f46,#34d399)">📗</div>
                  <div>
                    <div class="penulis-tabel">Alexander</div>
                    <div class="judul-tabel">Tentang Hidup dan Mati</div>
                  </div>
                </div>
              </td>
              <td class="tgl-normal">10 Jan 2026</td>
              <td class="tgl-normal">14 Jan 2026</td>
              <td><span class="badge dipinjam">Dipinjam</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Riwayat Pengembalian -->
    <div class="kotak-konten">
      <div class="kepala-kotak">
        <h3>Riwayat Pengembalian</h3>
        <a href="<?= base_url('member/pengembalian') ?>" class="tautan-lihat-semua">Lihat Semua →</a>
      </div>
      <div class="bungkus-tabel">
        <table class="tabel-member">
          <thead>
            <tr>
              <th>Buku</th>
              <th>Tgl Pinjam</th>
              <th>Jatuh Tempo</th>
              <th>Tgl Kembali</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <div class="sel-buku">
                  <div class="sampul-tabel" style="background:linear-gradient(135deg,#1e3a8a,#60a5fa)">📖</div>
                  <div>
                    <div class="penulis-tabel">Mansur Hidayat</div>
                    <div class="judul-tabel">Kehidupan Setelah di Dunia</div>
                  </div>
                </div>
              </td>
              <td class="tgl-normal">1 Jan 2026</td>
              <td class="tgl-normal">4 Jan 2026</td>
              <td class="tgl-normal">2 Jan 2026</td>
              <td><a href="#" class="tombol-kuis">Kerjakan Kuis</a></td>
            </tr>
            <tr>
              <td>
                <div class="sel-buku">
                  <div class="sampul-tabel" style="background:linear-gradient(135deg,#6d28d9,#a78bfa)">📕</div>
                  <div>
                    <div class="penulis-tabel">Andrea Hirata</div>
                    <div class="judul-tabel">Laskar Pelangi</div>
                  </div>
                </div>
              </td>
              <td class="tgl-normal">20 Des 2025</td>
              <td class="tgl-normal">3 Jan 2026</td>
              <td class="tgl-normal">3 Jan 2026</td>
              <td><span class="badge kembali">Dikembalikan</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

  </div><!-- /kolom kiri -->

  <!-- Kolom kanan: Riwayat Poin -->
  <div>
    <div class="kotak-konten" style="height:100%">
      <div class="kepala-kotak">
        <h3>Riwayat Point</h3>
        <a href="<?= base_url('member/poin') ?>" class="tautan-lihat-semua">Lihat Semua →</a>
      </div>

      <div class="daftar-riwayat-poin">

        <div class="item-riwayat-poin">
          <div class="ikon-poin-wrap positif">+</div>
          <div class="info-riwayat-poin">
            <div class="aksi-poin">Peminjaman Buku</div>
            <div class="detail-poin">Judul Laskar Pelangi — 21 Juni 2026</div>
          </div>
          <span class="badge-poin positif">+ 10</span>
        </div>

        <div class="item-riwayat-poin">
          <div class="ikon-poin-wrap positif">+</div>
          <div class="info-riwayat-poin">
            <div class="aksi-poin">Pengembalian Tepat Waktu</div>
            <div class="detail-poin">Judul Atomic Habits — 18 Juni 2026</div>
          </div>
          <span class="badge-poin positif">+ 15</span>
        </div>

        <div class="item-riwayat-poin">
          <div class="ikon-poin-wrap positif">+</div>
          <div class="info-riwayat-poin">
            <div class="aksi-poin">Kunjungan Perpustakaan</div>
            <div class="detail-poin">15 Juni 2026</div>
          </div>
          <span class="badge-poin positif">+ 5</span>
        </div>

        <div class="item-riwayat-poin">
          <div class="ikon-poin-wrap positif">+</div>
          <div class="info-riwayat-poin">
            <div class="aksi-poin">Partisipasi Kuis</div>
            <div class="detail-poin">Kuis Laskar Pelangi — 10 Juni 2026</div>
          </div>
          <span class="badge-poin positif">+ 20</span>
        </div>

        <div class="item-riwayat-poin">
          <div class="ikon-poin-wrap negatif">−</div>
          <div class="info-riwayat-poin">
            <div class="aksi-poin">Keterlambatan Pengembalian</div>
            <div class="detail-poin">Bumi Manusia — 5 Juni 2026</div>
          </div>
          <span class="badge-poin negatif">− 10</span>
        </div>

        <div class="item-riwayat-poin">
          <div class="ikon-poin-wrap positif">+</div>
          <div class="info-riwayat-poin">
            <div class="aksi-poin">Peminjaman Buku</div>
            <div class="detail-poin">Filosofi Teras — 1 Juni 2026</div>
          </div>
          <span class="badge-poin positif">+ 10</span>
        </div>

      </div><!-- /daftar-riwayat-poin -->
    </div>
  </div><!-- /kolom kanan -->

</div><!-- /grid-konten-dashboard -->

<?= $this->endSection() ?>