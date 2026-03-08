<?= $this->extend('layouts/home_layout') ?>

<?= $this->section('head') ?>
<title>Leaderboard — Perpustakaan SMK</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?= $this->include('layouts/navbar') ?>

<!-- ══ HEADER HALAMAN ══ -->
<div class="header-halaman">
  <h1>Leaderboard</h1>
  <div class="garis-emas"></div>
  <p>Peringkat anggota perpustakaan terbaik bulan ini berdasarkan poin gamifikasi</p>
</div>

<!-- ══ KONTEN LEADERBOARD ══ -->
<div class="bungkus-leaderboard">

  <!-- ── INFO BULAN + PENJELASAN POIN ── -->
  <div class="baris-atas-leaderboard">

    <!-- Periode aktif -->
    <div class="kotak-periode">
      <div class="ikon-periode">🏆</div>
      <div>
        <div class="label-periode">Periode Aktif</div>
        <div class="nilai-periode"><?= date('F Y') ?></div>
      </div>
      <div class="pemisah-vertikal"></div>
      <div>
        <div class="label-periode">Sisa Waktu</div>
        <div class="nilai-periode hitung-mundur" id="hitungMundur">–</div>
      </div>
    </div>

    <!-- Panduan poin -->
    <div class="kotak-panduan-poin">
      <div class="judul-panduan">Cara Mendapat Poin</div>
      <div class="daftar-panduan">
        <div class="item-panduan positif">
          <span class="ikon-panduan">🚶</span>
          <span class="nama-panduan">Kunjungan</span>
          <span class="nilai-panduan">+5 poin</span>
        </div>
        <div class="item-panduan positif">
          <span class="ikon-panduan">📖</span>
          <span class="nama-panduan">Peminjaman</span>
          <span class="nilai-panduan">+10 poin</span>
        </div>
        <div class="item-panduan positif">
          <span class="ikon-panduan">✅</span>
          <span class="nama-panduan">Kembali Tepat Waktu</span>
          <span class="nilai-panduan">+15 poin</span>
        </div>
        <div class="item-panduan negatif">
          <span class="ikon-panduan">⏰</span>
          <span class="nama-panduan">Kembali Terlambat</span>
          <span class="nilai-panduan">-10 poin</span>
        </div>
        <div class="item-panduan positif">
          <span class="ikon-panduan">🎯</span>
          <span class="nama-panduan">Partisipasi Kuis</span>
          <span class="nilai-panduan">+20 poin</span>
        </div>
      </div>
    </div>

  </div>

  <!-- ── PODIUM TOP 3 ── -->
  <div class="area-podium">

    <!-- Peringkat 2 -->
    <div class="podium-item podium-dua">
      <div class="avatar-podium">
        <div class="foto-avatar" style="background: linear-gradient(135deg,#6c757d,#adb5bd)">BW</div>
        <div class="lencana-podium">2</div>
      </div>
      <div class="nama-podium">Budi Wijaya</div>
      <div class="kelas-podium">XI RPL 1</div>
      <div class="poin-podium">1.840 poin</div>
      <div class="tiang-podium tiang-dua"></div>
    </div>

    <!-- Peringkat 1 -->
    <div class="podium-item podium-satu">
      <div class="mahkota-juara">👑</div>
      <div class="avatar-podium">
        <div class="foto-avatar foto-juara" style="background: linear-gradient(135deg,#c9a84c,#f0c040)">AS</div>
        <div class="lencana-podium lencana-juara">1</div>
      </div>
      <div class="nama-podium">Ahmad Santoso</div>
      <div class="kelas-podium">XII TKJ 2</div>
      <div class="poin-podium poin-juara">2.150 poin</div>
      <div class="tiang-podium tiang-satu"></div>
    </div>

    <!-- Peringkat 3 -->
    <div class="podium-item podium-tiga">
      <div class="avatar-podium">
        <div class="foto-avatar" style="background: linear-gradient(135deg,#cd7f32,#e8a87c)">SR</div>
        <div class="lencana-podium lencana-tiga">3</div>
      </div>
      <div class="nama-podium">Siti Rahayu</div>
      <div class="kelas-podium">X AKL 1</div>
      <div class="poin-podium">1.620 poin</div>
      <div class="tiang-podium tiang-tiga"></div>
    </div>

  </div><!-- /area-podium -->

  <!-- ── TABEL PERINGKAT LENGKAP ── -->
  <div class="kotak-tabel-peringkat">

    <div class="kepala-tabel-peringkat">
      <h2>Peringkat Lengkap</h2>
      <div class="filter-bulan">
        <select class="pilih-bulan" onchange="// nanti connect ke controller">
          <option value="<?= date('Y-m') ?>" selected><?= date('F Y') ?></option>
          <option value="<?= date('Y-m', strtotime('-1 month')) ?>"><?= date('F Y', strtotime('-1 month')) ?></option>
          <option value="<?= date('Y-m', strtotime('-2 month')) ?>"><?= date('F Y', strtotime('-2 month')) ?></option>
        </select>
      </div>
    </div>

    <div class="bungkus-tabel">
      <table class="tabel-peringkat">
        <thead>
          <tr>
            <th class="kolom-peringkat">#</th>
            <th class="kolom-anggota">Anggota</th>
            <th class="kolom-poin-detail">Kunjungan</th>
            <th class="kolom-poin-detail">Peminjaman</th>
            <th class="kolom-poin-detail">Tepat Waktu</th>
            <th class="kolom-poin-detail">Terlambat</th>
            <th class="kolom-poin-detail">Kuis</th>
            <th class="kolom-total">Total</th>
          </tr>
        </thead>
        <tbody>

          <?php
          // ── DATA DUMMY ── ganti dengan $leaderboard dari controller nanti
          $dummy = [
            ['nama'=>'Ahmad Santoso',   'kelas'=>'XII TKJ 2', 'inisial'=>'AS', 'warna'=>'#c9a84c,#f0c040', 'kunjungan'=>10,'pinjam'=>8,'tepat'=>6,'terlambat'=>1,'kuis'=>5],
            ['nama'=>'Budi Wijaya',     'kelas'=>'XI RPL 1',  'inisial'=>'BW', 'warna'=>'#6c757d,#adb5bd', 'kunjungan'=>9, 'pinjam'=>7,'tepat'=>5,'terlambat'=>0,'kuis'=>4],
            ['nama'=>'Siti Rahayu',     'kelas'=>'X AKL 1',   'inisial'=>'SR', 'warna'=>'#cd7f32,#e8a87c', 'kunjungan'=>8, 'pinjam'=>6,'tepat'=>5,'terlambat'=>2,'kuis'=>3],
            ['nama'=>'Dewi Lestari',    'kelas'=>'XI AKL 2',  'inisial'=>'DL', 'warna'=>'#1e3a8a,#3b82f6', 'kunjungan'=>7, 'pinjam'=>6,'tepat'=>4,'terlambat'=>1,'kuis'=>3],
            ['nama'=>'Rizky Pratama',   'kelas'=>'XII RPL 1', 'inisial'=>'RP', 'warna'=>'#065f46,#10b981', 'kunjungan'=>6, 'pinjam'=>5,'tepat'=>4,'terlambat'=>0,'kuis'=>2],
            ['nama'=>'Nur Fadillah',    'kelas'=>'X TKJ 1',   'inisial'=>'NF', 'warna'=>'#6d28d9,#a78bfa', 'kunjungan'=>5, 'pinjam'=>5,'tepat'=>3,'terlambat'=>1,'kuis'=>2],
            ['nama'=>'Andi Firmansyah', 'kelas'=>'XI TKJ 2',  'inisial'=>'AF', 'warna'=>'#be123c,#fb7185', 'kunjungan'=>5, 'pinjam'=>4,'tepat'=>3,'terlambat'=>2,'kuis'=>1],
            ['nama'=>'Maya Putri',      'kelas'=>'XII AKL 1', 'inisial'=>'MP', 'warna'=>'#0e7490,#22d3ee', 'kunjungan'=>4, 'pinjam'=>4,'tepat'=>2,'terlambat'=>0,'kuis'=>1],
            ['nama'=>'Fajar Nugroho',   'kelas'=>'X RPL 2',   'inisial'=>'FN', 'warna'=>'#92400e,#fbbf24', 'kunjungan'=>3, 'pinjam'=>3,'tepat'=>2,'terlambat'=>1,'kuis'=>1],
            ['nama'=>'Lina Marlina',    'kelas'=>'XI AKL 1',  'inisial'=>'LM', 'warna'=>'#134e4a,#34d399', 'kunjungan'=>3, 'pinjam'=>2,'tepat'=>2,'terlambat'=>0,'kuis'=>0],
          ];

          foreach ($dummy as $i => $row) :
            $poinKunjungan = $row['kunjungan'] * 5;
            $poinPinjam    = $row['pinjam']    * 10;
            $poinTepat     = $row['tepat']     * 15;
            $poinTerlambat = $row['terlambat'] * 10;
            $poinKuis      = $row['kuis']      * 20;
            $total         = $poinKunjungan + $poinPinjam + $poinTepat - $poinTerlambat + $poinKuis;
            $peringkat     = $i + 1;

            $kelasBaris = '';
            if ($peringkat === 1) $kelasBaris = 'baris-juara';
            elseif ($peringkat === 2) $kelasBaris = 'baris-dua';
            elseif ($peringkat === 3) $kelasBaris = 'baris-tiga';
          ?>
          <tr class="baris-peringkat <?= $kelasBaris ?>">
            <td class="kolom-peringkat">
              <?php if ($peringkat === 1) : ?>
                <span class="ikon-peringkat">🥇</span>
              <?php elseif ($peringkat === 2) : ?>
                <span class="ikon-peringkat">🥈</span>
              <?php elseif ($peringkat === 3) : ?>
                <span class="ikon-peringkat">🥉</span>
              <?php else : ?>
                <span class="nomor-peringkat"><?= $peringkat ?></span>
              <?php endif; ?>
            </td>
            <td class="kolom-anggota">
              <div class="sel-anggota">
                <div class="avatar-kecil" style="background: linear-gradient(135deg,<?= $row['warna'] ?>)">
                  <?= $row['inisial'] ?>
                </div>
                <div>
                  <div class="nama-anggota"><?= $row['nama'] ?></div>
                  <div class="kelas-anggota"><?= $row['kelas'] ?></div>
                </div>
              </div>
            </td>
            <td class="kolom-poin-detail">
              <span class="pil-poin positif">+<?= $poinKunjungan ?></span>
            </td>
            <td class="kolom-poin-detail">
              <span class="pil-poin positif">+<?= $poinPinjam ?></span>
            </td>
            <td class="kolom-poin-detail">
              <span class="pil-poin positif">+<?= $poinTepat ?></span>
            </td>
            <td class="kolom-poin-detail">
              <span class="pil-poin <?= $poinTerlambat > 0 ? 'negatif' : 'nol' ?>">
                <?= $poinTerlambat > 0 ? '-'.$poinTerlambat : '0' ?>
              </span>
            </td>
            <td class="kolom-poin-detail">
              <span class="pil-poin positif">+<?= $poinKuis ?></span>
            </td>
            <td class="kolom-total">
              <span class="angka-total"><?= number_format($total) ?></span>
              <span class="satuan-total">poin</span>
            </td>
          </tr>
          <?php endforeach; ?>

        </tbody>
      </table>
    </div><!-- /bungkus-tabel -->

  </div><!-- /kotak-tabel-peringkat -->

</div><!-- /bungkus-leaderboard -->

<?= $this->include('layouts/home_footer') ?>

<!-- Script hitung mundur akhir bulan -->
<script>
  (function () {
    const el = document.getElementById('hitungMundur');
    if (!el) return;

    function hitungSisa() {
      const sekarang  = new Date();
      const akhirBulan = new Date(sekarang.getFullYear(), sekarang.getMonth() + 1, 0, 23, 59, 59);
      const selisih   = akhirBulan - sekarang;

      if (selisih <= 0) { el.textContent = 'Periode berakhir'; return; }

      const hari  = Math.floor(selisih / 86400000);
      const jam   = Math.floor((selisih % 86400000) / 3600000);
      const menit = Math.floor((selisih % 3600000)  / 60000);

      el.textContent = `${hari}h ${jam}j ${menit}m`;
    }

    hitungSisa();
    setInterval(hitungSisa, 60000);
  })();
</script>

<?= $this->endSection() ?>