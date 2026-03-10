<?= $this->extend('layouts/member_layout') ?>

<?= $this->section('head') ?>
<title>Pengembalian — Portal Anggota</title>
<?= $this->endSection() ?>

<?= $this->section('pageTitle') ?>Pengembalian<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Statistik -->
<div class="grid-stat">
  <div class="kartu-stat">
    <div class="isi-stat">
      <div class="label-stat">Total Dikembalikan</div>
      <div class="angka-stat">12</div>
    </div>
    <div class="ikon-stat-wrap hijau">
      <svg viewBox="0 0 24 24"><polyline points="9 14 4 9 9 4"/><path d="M20 20v-7a4 4 0 00-4-4H4"/></svg>
    </div>
  </div>
  <div class="kartu-stat">
    <div class="isi-stat">
      <div class="label-stat">Tepat Waktu</div>
      <div class="angka-stat">10</div>
    </div>
    <div class="ikon-stat-wrap biru">
      <svg viewBox="0 0 24 24"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
    </div>
  </div>
  <div class="kartu-stat">
    <div class="isi-stat">
      <div class="label-stat">Terlambat</div>
      <div class="angka-stat">2</div>
    </div>
    <div class="ikon-stat-wrap merah">
      <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    </div>
  </div>
</div>

<!-- Tabel -->
<div class="kotak-konten">
  <div class="kepala-kotak">
    <h3>Riwayat Pengembalian</h3>
    <div class="filter-status">
      <button class="pil-filter aktif" onclick="filterKembali(this,'semua')">Semua</button>
      <button class="pil-filter" onclick="filterKembali(this,'tepat-waktu')">Tepat Waktu</button>
      <button class="pil-filter" onclick="filterKembali(this,'terlambat')">Terlambat</button>
    </div>
  </div>

  <div class="bungkus-tabel">
    <table class="tabel-member" id="tabel-pengembalian">
      <thead>
        <tr>
          <th style="width:40px">#</th>
          <th>Judul Buku</th>
          <th>Tgl Pinjam</th>
          <th>Tenggat</th>
          <th>Tgl Kembali</th>
          <th>Keterlambatan</th>
          <th>Status</th>
          <th style="width:80px">Aksi</th>
        </tr>
      </thead>
      <tbody>

        <tr data-status="terlambat">
          <td class="teks-redup-sm">1</td>
          <td>
            <div class="judul-tabel">Kehidupan Setelah di Dunia (2021)</div>
            <div class="penulis-tabel">Author: Mansur Hidayat</div>
          </td>
          <td class="tgl-normal">01/01/2026</td>
          <td class="tgl-terlambat">04/01/2026</td>
          <td class="tgl-normal">10/01/2026</td>
          <td><span class="tgl-terlambat">+6 hari</span></td>
          <td><span class="badge terlambat">Terlambat</span></td>
          <td><button class="tombol-detail">Detail</button></td>
        </tr>

        <tr data-status="tepat-waktu">
          <td class="teks-redup-sm">2</td>
          <td>
            <div class="judul-tabel">Laskar Pelangi (2005)</div>
            <div class="penulis-tabel">Author: Andrea Hirata</div>
          </td>
          <td class="tgl-normal">20/12/2025</td>
          <td class="tgl-normal">03/01/2026</td>
          <td class="tgl-normal">03/01/2026</td>
          <td><span class="teks-redup-sm">—</span></td>
          <td><span class="badge kembali">Tepat Waktu</span></td>
          <td><button class="tombol-detail">Detail</button></td>
        </tr>

        <tr data-status="tepat-waktu">
          <td class="teks-redup-sm">3</td>
          <td>
            <div class="judul-tabel">Atomic Habits (2018)</div>
            <div class="penulis-tabel">Author: James Clear</div>
          </td>
          <td class="tgl-normal">01/12/2025</td>
          <td class="tgl-normal">15/12/2025</td>
          <td class="tgl-normal">13/12/2025</td>
          <td><span class="teks-redup-sm">—</span></td>
          <td><span class="badge kembali">Tepat Waktu</span></td>
          <td><button class="tombol-detail">Detail</button></td>
        </tr>

        <tr data-status="terlambat">
          <td class="teks-redup-sm">4</td>
          <td>
            <div class="judul-tabel">Bumi Manusia (1980)</div>
            <div class="penulis-tabel">Author: Pramoedya Ananta Toer</div>
          </td>
          <td class="tgl-normal">01/11/2025</td>
          <td class="tgl-terlambat">15/11/2025</td>
          <td class="tgl-normal">20/11/2025</td>
          <td><span class="tgl-terlambat">+5 hari</span></td>
          <td><span class="badge terlambat">Terlambat</span></td>
          <td><button class="tombol-detail">Detail</button></td>
        </tr>

        <tr data-status="tepat-waktu">
          <td class="teks-redup-sm">5</td>
          <td>
            <div class="judul-tabel">The Obstacle Is the Way (2014)</div>
            <div class="penulis-tabel">Author: Ryan Holiday</div>
          </td>
          <td class="tgl-normal">10/10/2025</td>
          <td class="tgl-normal">24/10/2025</td>
          <td class="tgl-normal">22/10/2025</td>
          <td><span class="teks-redup-sm">—</span></td>
          <td><span class="badge kembali">Tepat Waktu</span></td>
          <td><button class="tombol-detail">Detail</button></td>
        </tr>

      </tbody>
    </table>
  </div>

  <div class="kondisi-kosong" id="kondisi-kosong-kembali" style="display:none">
    <svg viewBox="0 0 24 24"><polyline points="9 14 4 9 9 4"/><path d="M20 20v-7a4 4 0 00-4-4H4"/></svg>
    <p>Tidak ada data pengembalian</p>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function filterKembali(btn, status) {
  document.querySelectorAll('.pil-filter').forEach(b => b.classList.remove('aktif'));
  btn.classList.add('aktif');
  const baris = document.querySelectorAll('#tabel-pengembalian tbody tr');
  let terlihat = 0;
  baris.forEach(tr => {
    const cocok = status === 'semua' || tr.dataset.status === status;
    tr.style.display = cocok ? '' : 'none';
    if (cocok) terlihat++;
  });
  document.getElementById('kondisi-kosong-kembali').style.display = terlihat === 0 ? 'flex' : 'none';
}
</script>
<?= $this->endSection() ?>