<?= $this->extend('layouts/member_layout') ?>

<?= $this->section('head') ?>
<title>Kunjungan — Portal Anggota</title>
<?= $this->endSection() ?>

<?= $this->section('pageTitle') ?>Kunjungan<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Statistik -->
<div class="grid-stat">
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
      <div class="label-stat">Total Kunjungan</div>
      <div class="angka-stat">28</div>
    </div>
    <div class="ikon-stat-wrap biru">
      <svg viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
    </div>
  </div>
  <div class="kartu-stat">
    <div class="isi-stat">
      <div class="label-stat">Poin dari Kunjungan</div>
      <div class="angka-stat">140</div>
    </div>
    <div class="ikon-stat-wrap emas">
      <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
    </div>
  </div>
</div>

<!-- Grafik mini kunjungan per bulan -->
<div class="kotak-konten">
  <div class="kepala-kotak">
    <h3>Kunjungan per Bulan</h3>
    <span class="teks-redup-sm">Tahun 2026</span>
  </div>
  <div class="area-grafik-batang">
    <?php
      $bulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
      $data  = [4, 3, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0];
      $maks  = max($data) ?: 1;
      $bulanIni = (int) date('n') - 1;
    ?>
    <?php foreach ($bulan as $i => $b): ?>
      <div class="kolom-batang">
        <div class="batang-wrap">
          <div class="batang <?= $i === $bulanIni ? 'aktif' : '' ?>"
               style="height: <?= round(($data[$i] / $maks) * 100) ?>%"
               title="<?= $data[$i] ?> kunjungan">
          </div>
        </div>
        <div class="label-batang <?= $i === $bulanIni ? 'aktif' : '' ?>"><?= $b ?></div>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- Tabel riwayat -->
<div class="kotak-konten">
  <div class="kepala-kotak">
    <h3>Riwayat Kunjungan</h3>
    <div class="filter-status">
      <button class="pil-filter aktif" onclick="filterBulan(this,'semua')">Semua</button>
      <button class="pil-filter" onclick="filterBulan(this,'mar-2026')">Mar 2026</button>
      <button class="pil-filter" onclick="filterBulan(this,'feb-2026')">Feb 2026</button>
      <button class="pil-filter" onclick="filterBulan(this,'jan-2026')">Jan 2026</button>
    </div>
  </div>

  <div class="bungkus-tabel">
    <table class="tabel-member" id="tabel-kunjungan">
      <thead>
        <tr>
          <th style="width:40px">#</th>
          <th>Tanggal Kunjungan</th>
          <th>Hari</th>
          <th>Waktu Masuk</th>
          <th>Waktu Keluar</th>
          <th>Durasi</th>
          <th>Poin</th>
        </tr>
      </thead>
      <tbody>

        <tr data-bulan="mar-2026">
          <td class="teks-redup-sm">1</td>
          <td class="tgl-normal">08/03/2026</td>
          <td class="tgl-normal">Minggu</td>
          <td class="tgl-normal">09:15:00</td>
          <td class="tgl-normal">11:30:00</td>
          <td class="tgl-normal">2 jam 15 mnt</td>
          <td><span class="badge-poin positif">+5</span></td>
        </tr>

        <tr data-bulan="mar-2026">
          <td class="teks-redup-sm">2</td>
          <td class="tgl-normal">03/03/2026</td>
          <td class="tgl-normal">Selasa</td>
          <td class="tgl-normal">13:00:00</td>
          <td class="tgl-normal">14:45:00</td>
          <td class="tgl-normal">1 jam 45 mnt</td>
          <td><span class="badge-poin positif">+5</span></td>
        </tr>

        <tr data-bulan="mar-2026">
          <td class="teks-redup-sm">3</td>
          <td class="tgl-normal">01/03/2026</td>
          <td class="tgl-normal">Minggu</td>
          <td class="tgl-normal">10:00:00</td>
          <td class="tgl-normal">12:00:00</td>
          <td class="tgl-normal">2 jam</td>
          <td><span class="badge-poin positif">+5</span></td>
        </tr>

        <tr data-bulan="feb-2026">
          <td class="teks-redup-sm">4</td>
          <td class="tgl-normal">22/02/2026</td>
          <td class="tgl-normal">Minggu</td>
          <td class="tgl-normal">08:30:00</td>
          <td class="tgl-normal">10:00:00</td>
          <td class="tgl-normal">1 jam 30 mnt</td>
          <td><span class="badge-poin positif">+5</span></td>
        </tr>

        <tr data-bulan="feb-2026">
          <td class="teks-redup-sm">5</td>
          <td class="tgl-normal">15/02/2026</td>
          <td class="tgl-normal">Minggu</td>
          <td class="tgl-normal">09:00:00</td>
          <td class="tgl-normal">11:00:00</td>
          <td class="tgl-normal">2 jam</td>
          <td><span class="badge-poin positif">+5</span></td>
        </tr>

        <tr data-bulan="feb-2026">
          <td class="teks-redup-sm">6</td>
          <td class="tgl-normal">08/02/2026</td>
          <td class="tgl-normal">Minggu</td>
          <td class="tgl-normal">13:30:00</td>
          <td class="tgl-normal">15:00:00</td>
          <td class="tgl-normal">1 jam 30 mnt</td>
          <td><span class="badge-poin positif">+5</span></td>
        </tr>

        <tr data-bulan="jan-2026">
          <td class="teks-redup-sm">7</td>
          <td class="tgl-normal">25/01/2026</td>
          <td class="tgl-normal">Minggu</td>
          <td class="tgl-normal">10:00:00</td>
          <td class="tgl-normal">12:30:00</td>
          <td class="tgl-normal">2 jam 30 mnt</td>
          <td><span class="badge-poin positif">+5</span></td>
        </tr>

        <tr data-bulan="jan-2026">
          <td class="teks-redup-sm">8</td>
          <td class="tgl-normal">11/01/2026</td>
          <td class="tgl-normal">Minggu</td>
          <td class="tgl-normal">09:45:00</td>
          <td class="tgl-normal">11:15:00</td>
          <td class="tgl-normal">1 jam 30 mnt</td>
          <td><span class="badge-poin positif">+5</span></td>
        </tr>

        <tr data-bulan="jan-2026">
          <td class="teks-redup-sm">9</td>
          <td class="tgl-normal">04/01/2026</td>
          <td class="tgl-normal">Minggu</td>
          <td class="tgl-normal">08:00:00</td>
          <td class="tgl-normal">10:00:00</td>
          <td class="tgl-normal">2 jam</td>
          <td><span class="badge-poin positif">+5</span></td>
        </tr>

      </tbody>
    </table>
  </div>

  <div class="kondisi-kosong" id="kondisi-kosong-kunjungan" style="display:none">
    <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
    <p>Tidak ada data kunjungan</p>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function filterBulan(btn, bulan) {
  document.querySelectorAll('.pil-filter').forEach(b => b.classList.remove('aktif'));
  btn.classList.add('aktif');
  const baris = document.querySelectorAll('#tabel-kunjungan tbody tr');
  let terlihat = 0;
  baris.forEach(tr => {
    const cocok = bulan === 'semua' || tr.dataset.bulan === bulan;
    tr.style.display = cocok ? '' : 'none';
    if (cocok) terlihat++;
  });
  document.getElementById('kondisi-kosong-kunjungan').style.display = terlihat === 0 ? 'flex' : 'none';
}
</script>
<?= $this->endSection() ?>