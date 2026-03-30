<?= $this->extend('layouts/member_layout') ?>

<?= $this->section('head') ?>
<title>Dashboard — Portal Anggota</title>
<?= $this->endSection() ?>

<?= $this->section('pageTitle') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php use CodeIgniter\I18n\Time; $now = Time::now(); ?>

<!-- ── Kartu Statistik ── -->
<div class="grid-stat" style="margin-bottom:1.25rem">

  <div class="kartu-stat-admin">
    <div class="ksa-body">
      <div class="ksa-icon">
        <svg viewBox="0 0 24 24" width="22" height="22"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
      </div>
      <div class="ksa-angka"><?= $sedangDipinjam ?></div>
      <div class="ksa-label">Buku Dipinjam</div>
    </div>
  </div>

  <div class="kartu-stat-admin">
    <div class="ksa-body">
      <div class="ksa-icon">
        <svg viewBox="0 0 24 24" width="22" height="22"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      </div>
      <div class="ksa-angka"><?= $terlambat ?></div>
      <div class="ksa-label">Terlambat</div>
    </div>
  </div>

  <div class="kartu-stat-admin">
    <div class="ksa-body">
      <div class="ksa-icon">
        <svg viewBox="0 0 24 24" width="22" height="22"><polyline points="9 14 4 9 9 4"/><path d="M20 20v-7a4 4 0 00-4-4H4"/></svg>
      </div>
      <div class="ksa-angka"><?= $totalKembali ?></div>
      <div class="ksa-label">Total Dikembalikan</div>
    </div>
  </div>

  <div class="kartu-stat-admin">
    <div class="ksa-body">
      <div class="ksa-icon">
        <svg viewBox="0 0 24 24" width="22" height="22"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
      </div>
      <div class="ksa-angka">11</div>
      <div class="ksa-label">Kunjungan Bulan Ini</div>
    </div>
  </div>

  <div class="kartu-stat-admin">
  <div class="ksa-body">
    <div class="ksa-icon">
      <!-- Icon Poin (Bintang) -->
      <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
        <path d="M12 2l2.9 6.3L22 9.2l-5 4.9L18.2 22 12 18.6 5.8 22 7 14.1 2 9.2l7.1-0.9L12 2z"/>
      </svg>
    </div>
    <div class="ksa-angka">132</div>
    <div class="ksa-label">Poin Bulan Ini</div>
  </div>
</div>

<div class="kartu-stat-admin">
  <div class="ksa-body">
    <div class="ksa-icon">
      <!-- Icon Peringkat (Trophy) -->
      <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor">
        <path d="M18 2h-2V1H8v1H6a1 1 0 00-1 1v3a5 5 0 004 4.9V13H7v2h10v-2h-2v-2.1A5 5 0 0019 6V3a1 1 0 00-1-1zm-1 4a3 3 0 01-2 2.83V4h2v2zm-10 0V4h2v4.83A3 3 0 017 6z"/>
      </svg>
    </div>
    <div class="ksa-angka">5</div>
    <div class="ksa-label">Peringkat Bulan Ini</div>
  </div>
</div>

</div>

<!-- ── Peringatan jatuh tempo ── -->
<?php if ($peringatan > 0): ?>
<div class="profil-alert err" style="margin-bottom:1.25rem">
  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
  Kamu memiliki <strong><?= $peringatan ?> peminjaman</strong> yang sudah melewati batas waktu pengembalian.
  <a href="<?= base_url('member/peminjaman') ?>" style="margin-left:8px;font-weight:700;color:inherit;text-decoration:underline">Lihat →</a>
</div>
<?php endif; ?>

<!-- ── Grid 2 kolom ── -->
<div class="grid-konten-dashboard">

  <!-- Kolom kiri -->
  <div>

    <!-- Peminjaman Aktif -->
    <div class="kotak-konten">
      <div class="kepala-kotak">
        <h3>Peminjaman Aktif</h3>
        <a href="<?= base_url('member/peminjaman') ?>" class="tautan-lihat-semua">Lihat Semua →</a>
      </div>
      <div class="bungkus-tabel">
        <table class="tabel-admin-member">
          <thead>
            <tr>
              <th>Judul Buku</th>
              <th>Tenggat</th>
              <th class="teks-center">Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($pinjamanAktif)): ?>
              <tr>
                <td colspan="3">
                  <div class="kondisi-kosong">
                    <svg viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
                    <p>Tidak ada peminjaman aktif</p>
                  </div>
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($pinjamanAktif as $loan):
                $dueDate    = Time::parse($loan['due_date']);
                $isLate     = $now->isAfter($dueDate);
                $isDueToday = $now->toDateString() === $dueDate->toDateString();
                if ($isLate)        { $badgeClass = 'badge-admin merah'; $badgeLabel = 'Terlambat'; }
                elseif ($isDueToday){ $badgeClass = 'badge-admin kuning'; $badgeLabel = 'Jatuh Tempo'; }
                else                { $badgeClass = 'badge-admin biru';  $badgeLabel = 'Dipinjam'; }
              ?>
                <tr>
                  <td>
                    <div class="judul-tabel"><?= esc($loan['title']) ?> (<?= esc($loan['year']) ?>)</div>
                    <div class="penulis-tabel">Author: <?= esc($loan['author']) ?></div>
                  </td>
                  <td class="<?= $isLate ? 'tgl-terlambat' : 'tgl-normal' ?>">
                    <?= $dueDate->format('d/m/Y') ?>
                  </td>
                  <td class="teks-center">
                    <span class="<?= $badgeClass ?>"><?= $badgeLabel ?></span>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Pengembalian Terakhir -->
    <div class="kotak-konten">
      <div class="kepala-kotak">
        <h3>Pengembalian Terakhir</h3>
        <a href="<?= base_url('member/pengembalian') ?>" class="tautan-lihat-semua">Lihat Semua →</a>
      </div>
      <div class="bungkus-tabel">
        <table class="tabel-admin-member">
          <thead>
            <tr>
              <th>Judul Buku</th>
              <th>Tgl Kembali</th>
              <th class="teks-center">Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($pengembalianTerakhir)): ?>
              <tr>
                <td colspan="3">
                  <div class="kondisi-kosong">
                    <svg viewBox="0 0 24 24"><polyline points="9 14 4 9 9 4"/><path d="M20 20v-7a4 4 0 00-4-4H4"/></svg>
                    <p>Belum ada riwayat pengembalian</p>
                  </div>
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($pengembalianTerakhir as $ret):
                $isLate = $ret['is_late'];
              ?>
                <tr>
                  <td>
                    <div class="judul-tabel"><?= esc($ret['title']) ?> (<?= esc($ret['year']) ?>)</div>
                    <div class="penulis-tabel">Author: <?= esc($ret['author']) ?></div>
                  </td>
                  <td class="tgl-normal">
                    <?= Time::parse($ret['return_date'])->format('d/m/Y') ?>
                  </td>
                  <td class="teks-center">
                    <?php if ($isLate): ?>
                      <span class="badge-admin merah">Terlambat</span>
                    <?php else: ?>
                      <span class="badge-admin hijau">Tepat Waktu</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div><!-- /kolom kiri -->

  <!-- Kolom kanan: Riwayat Poin (dummy) -->
  <div>
    <div class="kotak-konten">
      <div class="kepala-kotak">
        <h3>Riwayat Poin</h3>
        <a href="<?= base_url('member/poin') ?>" class="tautan-lihat-semua">Lihat Semua →</a>
      </div>

      <div class="daftar-riwayat-poin">

        <div class="item-riwayat-poin">
          <div class="ikon-poin-wrap positif">+</div>
          <div class="info-riwayat-poin">
            <div class="aksi-poin">Peminjaman Buku</div>
            <div class="detail-poin">Laskar Pelangi — 21 Mar 2026</div>
          </div>
          <span class="badge-poin positif">+ 10</span>
        </div>

        <div class="item-riwayat-poin">
          <div class="ikon-poin-wrap positif">+</div>
          <div class="info-riwayat-poin">
            <div class="aksi-poin">Pengembalian Tepat Waktu</div>
            <div class="detail-poin">Atomic Habits — 18 Mar 2026</div>
          </div>
          <span class="badge-poin positif">+ 15</span>
        </div>

        <div class="item-riwayat-poin">
          <div class="ikon-poin-wrap positif">+</div>
          <div class="info-riwayat-poin">
            <div class="aksi-poin">Kunjungan Perpustakaan</div>
            <div class="detail-poin">15 Mar 2026</div>
          </div>
          <span class="badge-poin positif">+ 5</span>
        </div>

        <div class="item-riwayat-poin">
          <div class="ikon-poin-wrap negatif">−</div>
          <div class="info-riwayat-poin">
            <div class="aksi-poin">Keterlambatan Pengembalian</div>
            <div class="detail-poin">Bumi Manusia — 5 Mar 2026</div>
          </div>
          <span class="badge-poin negatif">− 10</span>
        </div>

        <div class="item-riwayat-poin">
          <div class="ikon-poin-wrap positif">+</div>
          <div class="info-riwayat-poin">
            <div class="aksi-poin">Peminjaman Buku</div>
            <div class="detail-poin">Filosofi Teras — 1 Mar 2026</div>
          </div>
          <span class="badge-poin positif">+ 10</span>
        </div>

      </div>
    </div>
  </div><!-- /kolom kanan -->

</div>

<?= $this->endSection() ?>