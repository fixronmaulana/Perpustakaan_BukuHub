<?= $this->extend('layouts/member_layout') ?>

<?= $this->section('head') ?>
<title>Poin Saya — Portal Anggota</title>
<?= $this->endSection() ?>

<?= $this->section('pageTitle') ?>Poin Saya<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
  $namaAnggota = isset($member['first_name'])
    ? esc(trim($member['first_name'] . ' ' . ($member['last_name'] ?? '')))
    : 'Anggota';
  $nisn    = !empty($member['uid']) ? esc(strtoupper($member['uid'])) : '—';
  $inisial = '';
  if (!empty($member['first_name'])) $inisial .= strtoupper(substr($member['first_name'], 0, 1));
  if (!empty($member['last_name']))  $inisial .= strtoupper(substr($member['last_name'],  0, 1));
  $inisial = $inisial ?: 'AA';
?>

<!-- ══ Header profil + peringkat ══ -->
<div class="header-poin">
  <div class="header-poin-kiri">
    <div class="avatar-poin"><?= $inisial ?></div>
    <div>
      <h2 class="nama-header-poin">Halo, <?= $namaAnggota ?>!</h2>
      <p class="nisn-header-poin">NISN <?= $nisn ?></p>
    </div>
  </div>
  <div class="badge-peringkat-poin">
    <span class="badge-peringkat-angka"># 10</span>
    <span class="badge-peringkat-label">Peringkat Anda</span>
  </div>
</div>

<!-- ══ 5 Kartu Statistik Poin ══ -->
<div class="grid-stat-poin">

  <!-- Total Poin — navy+emas -->
  <div class="kartu-stat-poin unggulan">
    <div class="ikon-stat-poin">
      <!-- Polygon wajib punya fill="none" eksplisit, tidak cukup inherit -->
      <svg width="18" height="18" viewBox="0 0 24 24"
           fill="none" stroke="currentColor"
           stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"
                 fill="none" stroke="currentColor"/>
      </svg>
    </div>
    <div class="angka-stat-poin">900</div>
    <div class="label-stat-poin">Total Poin</div>
  </div>

  <!-- Kunjungan -->
  <div class="kartu-stat-poin">
    <div class="ikon-stat-poin">
      <svg width="18" height="18" viewBox="0 0 24 24"
           fill="none" stroke="currentColor"
           stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" fill="none"/>
        <circle cx="9" cy="7" r="4" fill="none"/>
      </svg>
    </div>
    <div class="angka-stat-poin">35</div>
    <div class="label-stat-poin">Kunjungan</div>
  </div>

  <!-- Peminjaman -->
  <div class="kartu-stat-poin">
    <div class="ikon-stat-poin">
      <svg width="18" height="18" viewBox="0 0 24 24"
           fill="none" stroke="currentColor"
           stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M4 19.5A2.5 2.5 0 016.5 17H20" fill="none"/>
        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z" fill="none"/>
      </svg>
    </div>
    <div class="angka-stat-poin">22</div>
    <div class="label-stat-poin">Peminjaman</div>
  </div>

  <!-- Tepat Waktu — hijau -->
  <div class="kartu-stat-poin">
    <div class="ikon-stat-poin hijau">
      <svg width="18" height="18" viewBox="0 0 24 24"
           fill="none" stroke="currentColor"
           stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polyline points="9 11 12 14 22 4" fill="none"/>
        <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11" fill="none"/>
      </svg>
    </div>
    <div class="angka-stat-poin">22</div>
    <div class="label-stat-poin">Tepat Waktu</div>
  </div>

  <!-- Terlambat — merah -->
  <div class="kartu-stat-poin">
    <div class="ikon-stat-poin merah">
      <svg width="18" height="18" viewBox="0 0 24 24"
           fill="none" stroke="currentColor"
           stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10" fill="none"/>
        <line x1="12" y1="8" x2="12" y2="12"/>
        <line x1="12" y1="16" x2="12.01" y2="16"/>
      </svg>
    </div>
    <div class="angka-stat-poin">0</div>
    <div class="label-stat-poin">Terlambat</div>
  </div>

</div>

<!-- ══ Riwayat Poin ══ -->
<div class="kotak-konten">
  <div class="kepala-kotak">
    <h3>Riwayat Point</h3>
    <div class="filter-status">
      <button class="pil-filter aktif" onclick="filterPoin(this,'semua')">Semua</button>
      <button class="pil-filter" onclick="filterPoin(this,'mar-2026')">Mar 2026</button>
      <button class="pil-filter" onclick="filterPoin(this,'feb-2026')">Feb 2026</button>
      <button class="pil-filter" onclick="filterPoin(this,'jan-2026')">Jan 2026</button>
    </div>
  </div>

  <!-- Ringkasan total bulan (muncul saat filter aktif) -->
  <div id="ringkasan-bulan" style="display:none; padding:0.6rem 1.25rem; background:var(--latar); border-bottom:1px solid var(--batas); font-size:0.78rem; color:var(--teks-redup);">
    <span id="teks-ringkasan"></span>
  </div>

  <div class="daftar-riwayat-poin" id="daftar-poin">

    <div class="item-riwayat-poin" data-bulan="mar-2026" data-nilai="10">
      <div class="ikon-poin-wrap positif">+</div>
      <div class="info-riwayat-poin">
        <div class="aksi-poin">Peminjaman Buku</div>
        <div class="detail-poin">Laskar Pelangi — 21 Mar 2026</div>
      </div>
      <span class="badge-poin positif">+ 10</span>
    </div>

    <div class="item-riwayat-poin" data-bulan="mar-2026" data-nilai="15">
      <div class="ikon-poin-wrap positif">+</div>
      <div class="info-riwayat-poin">
        <div class="aksi-poin">Pengembalian Tepat Waktu</div>
        <div class="detail-poin">Atomic Habits — 18 Mar 2026</div>
      </div>
      <span class="badge-poin positif">+ 15</span>
    </div>

    <div class="item-riwayat-poin" data-bulan="mar-2026" data-nilai="5">
      <div class="ikon-poin-wrap positif">+</div>
      <div class="info-riwayat-poin">
        <div class="aksi-poin">Kunjungan Perpustakaan</div>
        <div class="detail-poin">15 Mar 2026</div>
      </div>
      <span class="badge-poin positif">+ 5</span>
    </div>

    <div class="item-riwayat-poin" data-bulan="mar-2026" data-nilai="20">
      <div class="ikon-poin-wrap positif">+</div>
      <div class="info-riwayat-poin">
        <div class="aksi-poin">Partisipasi Kuis</div>
        <div class="detail-poin">Kuis Laskar Pelangi — 10 Mar 2026</div>
      </div>
      <span class="badge-poin positif">+ 20</span>
    </div>

    <div class="item-riwayat-poin" data-bulan="feb-2026" data-nilai="-10">
      <div class="ikon-poin-wrap negatif">−</div>
      <div class="info-riwayat-poin">
        <div class="aksi-poin">Keterlambatan Pengembalian</div>
        <div class="detail-poin">Bumi Manusia — 20 Feb 2026</div>
      </div>
      <span class="badge-poin negatif">− 10</span>
    </div>

    <div class="item-riwayat-poin" data-bulan="feb-2026" data-nilai="10">
      <div class="ikon-poin-wrap positif">+</div>
      <div class="info-riwayat-poin">
        <div class="aksi-poin">Peminjaman Buku</div>
        <div class="detail-poin">Filosofi Teras — 10 Feb 2026</div>
      </div>
      <span class="badge-poin positif">+ 10</span>
    </div>

    <div class="item-riwayat-poin" data-bulan="feb-2026" data-nilai="5">
      <div class="ikon-poin-wrap positif">+</div>
      <div class="info-riwayat-poin">
        <div class="aksi-poin">Kunjungan Perpustakaan</div>
        <div class="detail-poin">5 Feb 2026</div>
      </div>
      <span class="badge-poin positif">+ 5</span>
    </div>

    <div class="item-riwayat-poin" data-bulan="jan-2026" data-nilai="15">
      <div class="ikon-poin-wrap positif">+</div>
      <div class="info-riwayat-poin">
        <div class="aksi-poin">Pengembalian Tepat Waktu</div>
        <div class="detail-poin">Tentang Hidup dan Mati — 28 Jan 2026</div>
      </div>
      <span class="badge-poin positif">+ 15</span>
    </div>

    <div class="item-riwayat-poin" data-bulan="jan-2026" data-nilai="20">
      <div class="ikon-poin-wrap positif">+</div>
      <div class="info-riwayat-poin">
        <div class="aksi-poin">Partisipasi Kuis</div>
        <div class="detail-poin">Kuis Atomic Habits — 15 Jan 2026</div>
      </div>
      <span class="badge-poin positif">+ 20</span>
    </div>

    <div class="item-riwayat-poin" data-bulan="jan-2026" data-nilai="10">
      <div class="ikon-poin-wrap positif">+</div>
      <div class="info-riwayat-poin">
        <div class="aksi-poin">Peminjaman Buku</div>
        <div class="detail-poin">Atomic Habits — 3 Jan 2026</div>
      </div>
      <span class="badge-poin positif">+ 10</span>
    </div>

  </div>

  <!-- Empty state -->
  <div class="kondisi-kosong" id="kondisi-kosong-poin" style="display:none">
    <svg width="40" height="40" viewBox="0 0 24 24"
         fill="none" stroke="#e4e8f4"
         stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
      <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"
               fill="none"/>
    </svg>
    <p>Tidak ada riwayat poin bulan ini</p>
  </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function filterPoin(btn, bulan) {
  document.querySelectorAll('.pil-filter').forEach(b => b.classList.remove('aktif'));
  btn.classList.add('aktif');

  const items = document.querySelectorAll('#daftar-poin .item-riwayat-poin');
  let n = 0, masuk = 0, keluar = 0;

  items.forEach(item => {
    const ok = bulan === 'semua' || item.dataset.bulan === bulan;
    item.style.display = ok ? '' : 'none';
    if (ok) {
      n++;
      const v = parseInt(item.dataset.nilai) || 0;
      if (v >= 0) masuk += v; else keluar += Math.abs(v);
    }
  });

  // Tampilkan ringkasan saat filter per bulan aktif
  const ringkasan = document.getElementById('ringkasan-bulan');
  if (bulan !== 'semua' && n > 0) {
    document.getElementById('teks-ringkasan').innerHTML =
      `Poin masuk <strong style="color:var(--hijau)">+${masuk}</strong>
       &nbsp;·&nbsp;
       Poin keluar <strong style="color:var(--merah)">−${keluar}</strong>
       &nbsp;·&nbsp;
       Total bulan ini <strong style="color:var(--navy)">${masuk - keluar}</strong>`;
    ringkasan.style.display = 'block';
  } else {
    ringkasan.style.display = 'none';
  }

  document.getElementById('kondisi-kosong-poin').style.display = n === 0 ? 'flex' : 'none';
}
</script>
<?= $this->endSection() ?>