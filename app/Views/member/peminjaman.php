<?= $this->extend('layouts/member_layout') ?>

<?= $this->section('head') ?>
<title>Peminjaman — Portal Anggota</title>
<?= $this->endSection() ?>

<?= $this->section('pageTitle') ?>Peminjaman<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Statistik -->
<div class="grid-stat">
  <div class="kartu-stat">
    <div class="isi-stat">
      <div class="label-stat">Sedang Dipinjam</div>
      <div class="angka-stat">2</div>
    </div>
    <div class="ikon-stat-wrap biru">
      <svg viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
    </div>
  </div>
  <div class="kartu-stat">
    <div class="isi-stat">
      <div class="label-stat">Total Peminjaman</div>
      <div class="angka-stat">14</div>
    </div>
    <div class="ikon-stat-wrap hijau">
      <svg viewBox="0 0 24 24"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
    </div>
  </div>
  <div class="kartu-stat">
    <div class="isi-stat">
      <div class="label-stat">Terlambat</div>
      <div class="angka-stat">1</div>
    </div>
    <div class="ikon-stat-wrap merah">
      <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    </div>
  </div>
</div>

<!-- Tabel -->
<div class="kotak-konten">
  <div class="kepala-kotak">
    <h3>Riwayat Peminjaman</h3>
    <div class="filter-status">
      <button class="pil-filter aktif" onclick="filterStatus(this,'semua')">Semua</button>
      <button class="pil-filter" onclick="filterStatus(this,'dipinjam')">Dipinjam</button>
      <button class="pil-filter" onclick="filterStatus(this,'jatuh-tempo')">Jatuh Tempo</button>
      <button class="pil-filter" onclick="filterStatus(this,'terlambat')">Terlambat</button>
      <button class="pil-filter" onclick="filterStatus(this,'dikembalikan')">Dikembalikan</button>
    </div>
  </div>

  <div class="bungkus-tabel">
    <table class="tabel-member" id="tabel-peminjaman">
      <thead>
        <tr>
          <th style="width:40px">#</th>
          <th>Judul Buku</th>
          <th style="width:70px">Jumlah</th>
          <th>Tgl Pinjam</th>
          <th>Tenggat</th>
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
          <td class="teks-center">1</td>
          <td class="tgl-normal">01/01/2026<br><span class="teks-redup-sm">13:10:08</span></td>
          <td class="tgl-terlambat">04/01/2026</td>
          <td><span class="badge terlambat">Terlambat</span></td>
          <td><button class="tombol-detail">Detail</button></td>
        </tr>

        <tr data-status="jatuh-tempo">
          <td class="teks-redup-sm">2</td>
          <td>
            <div class="judul-tabel">Tentang Hidup dan Mati (2019)</div>
            <div class="penulis-tabel">Author: Alexander</div>
          </td>
          <td class="teks-center">1</td>
          <td class="tgl-normal">10/01/2026<br><span class="teks-redup-sm">09:22:41</span></td>
          <td class="tgl-terlambat">13/01/2026</td>
          <td><span class="badge jatuh-tempo">Jatuh Tempo</span></td>
          <td><button class="tombol-detail">Detail</button></td>
        </tr>

        <tr data-status="dipinjam">
          <td class="teks-redup-sm">3</td>
          <td>
            <div class="judul-tabel">Laskar Pelangi (2005)</div>
            <div class="penulis-tabel">Author: Andrea Hirata</div>
          </td>
          <td class="teks-center">1</td>
          <td class="tgl-normal">05/03/2026<br><span class="teks-redup-sm">13:33:24</span></td>
          <td class="tgl-normal">19/03/2026</td>
          <td><span class="badge dipinjam">Dipinjam</span></td>
          <td><button class="tombol-detail">Detail</button></td>
        </tr>

        <tr data-status="dikembalikan">
          <td class="teks-redup-sm">4</td>
          <td>
            <div class="judul-tabel">Atomic Habits (2018)</div>
            <div class="penulis-tabel">Author: James Clear</div>
          </td>
          <td class="teks-center">1</td>
          <td class="tgl-normal">01/12/2025<br><span class="teks-redup-sm">10:15:00</span></td>
          <td class="tgl-normal">15/12/2025</td>
          <td><span class="badge kembali">Dikembalikan</span></td>
          <td><button class="tombol-detail">Detail</button></td>
        </tr>

        <tr data-status="dikembalikan">
          <td class="teks-redup-sm">5</td>
          <td>
            <div class="judul-tabel">Bumi Manusia (1980)</div>
            <div class="penulis-tabel">Author: Pramoedya Ananta Toer</div>
          </td>
          <td class="teks-center">1</td>
          <td class="tgl-normal">05/10/2025<br><span class="teks-redup-sm">08:44:12</span></td>
          <td class="tgl-normal">19/10/2025</td>
          <td><span class="badge kembali">Dikembalikan</span></td>
          <td><button class="tombol-detail">Detail</button></td>
        </tr>

      </tbody>
    </table>
  </div>

  <div class="kondisi-kosong" id="kondisi-kosong" style="display:none">
    <svg viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
    <p>Tidak ada data peminjaman</p>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function filterStatus(btn, status) {
  document.querySelectorAll('.pil-filter').forEach(b => b.classList.remove('aktif'));
  btn.classList.add('aktif');
  const baris = document.querySelectorAll('#tabel-peminjaman tbody tr');
  let terlihat = 0;
  baris.forEach(tr => {
    const cocok = status === 'semua' || tr.dataset.status === status;
    tr.style.display = cocok ? '' : 'none';
    if (cocok) terlihat++;
  });
  document.getElementById('kondisi-kosong').style.display = terlihat === 0 ? 'flex' : 'none';
}
</script>
<?= $this->endSection() ?>