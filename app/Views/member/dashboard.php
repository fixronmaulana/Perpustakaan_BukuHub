<?= $this->extend('layouts/member_layout') ?>

<?= $this->section('head') ?>
<title>Dashboard — Portal Anggota</title>
<style>
  /* Menghapus grid 2 kolom agar tabel tidak terhimpit dan bisa memanjang */
  .grid-konten-dashboard {
    display: flex;
    flex-direction: column;
  }

  /* Container Fitur Scroll: Menciut secara proporsional sebelum muncul scrollbar */
  .table-responsive-custom {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    /* Memberikan sedikit border radius agar rapi seperti kotak-konten */
    border-radius: 8px;
    border: 1px solid #eef2f7;
  }

  /* Memastikan tabel mempertahankan lebar konten aslinya agar tidak gepeng/menciut paksa */
  .table-responsive-custom table {
    min-width: max-content;
    width: 100%;
  }

  /* Memastikan judul buku tetap terbaca rapi saat kolom menciut */
  .judul-tabel-wrapper {
    white-space: normal !important;
    min-width: 250px;
    max-width: 450px;
  }

  /* Scrollbar halus agar tetap estetik */
  .table-responsive-custom::-webkit-scrollbar {
    height: 6px;
  }
  .table-responsive-custom::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('pageTitle') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php use CodeIgniter\I18n\Time; $now = Time::now(); ?>

<div class="grid-stat" style="margin-bottom:1.25rem">
  <div class="kartu-stat-admin">
    <div class="ksa-body">
      <div class="ksa-icon"><svg viewBox="0 0 24 24" width="22" height="22"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg></div>
      <div class="ksa-angka"><?= $sedangDipinjam ?></div>
      <div class="ksa-label">Buku Dipinjam</div>
    </div>
  </div>

  <div class="kartu-stat-admin">
    <div class="ksa-body">
      <div class="ksa-icon"><svg viewBox="0 0 24 24" width="22" height="22"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></div>
      <div class="ksa-angka"><?= $terlambat ?></div>
      <div class="ksa-label">Terlambat</div>
    </div>
  </div>

  <div class="kartu-stat-admin">
    <div class="ksa-body">
      <div class="ksa-icon"><svg viewBox="0 0 24 24" width="22" height="22"><polyline points="9 14 4 9 9 4"/><path d="M20 20v-7a4 4 0 00-4-4H4"/></svg></div>
      <div class="ksa-angka"><?= $totalKembali ?></div>
      <div class="ksa-label">Total Dikembalikan</div>
    </div>
  </div>

  <div class="kartu-stat-admin">
    <div class="ksa-body">
      <div class="ksa-icon"><svg viewBox="0 0 24 24" width="22" height="22"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div>
      <div class="ksa-angka"><?= $kunjunganBulanIni ?></div>
      <div class="ksa-label">Kunjungan Bulan Ini</div>
    </div>
  </div>

  <div class="kartu-stat-admin">
    <div class="ksa-body">
      <div class="ksa-icon"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg></div>
      <div class="ksa-angka"><?= $totalPoinBulanIni ?? 0 ?></div>
      <div class="ksa-label">Poin Bulan Ini</div>
    </div>
  </div>

  <div class="kartu-stat-admin">
    <div class="ksa-body">
      <div class="ksa-icon"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg></div>
      <div class="ksa-angka"><?= isset($rankBulanIni) && $rankBulanIni > 0 ? $rankBulanIni : '—' ?></div>
      <div class="ksa-label">Peringkat Bulan Ini</div>
    </div>
  </div>
</div>

<?php if ($peringatan > 0): ?>
<div class="profil-alert err" style="margin-bottom:1.25rem">
  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
  Kamu memiliki <strong><?= $peringatan ?> peminjaman</strong> terlambat.
  <a href="<?= base_url('member/peminjaman') ?>" style="margin-left:8px;font-weight:700;color:inherit;text-decoration:underline">Lihat →</a>
</div>
<?php endif; ?>

<?php if (!empty($kuisBelumDikerjakan) && $kuisBelumDikerjakan > 0): ?>
<div class="profil-alert" style="margin-bottom:1.25rem;background:#eff4ff;border-color:#c7d7fe;color:#1e3a8a">
  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
  Kamu memiliki <strong><?= $kuisBelumDikerjakan ?> kuis</strong> yang belum dikerjakan.
  <a href="<?= base_url('member/pengembalian') ?>" style="margin-left:8px;font-weight:700;color:inherit;text-decoration:underline">Kerjakan Sekarang →</a>
</div>
<?php endif; ?>

<div class="grid-konten-dashboard">

  <div class="kotak-konten">
    <div class="kepala-kotak">
      <h3>Peminjaman Aktif</h3>
      <a href="<?= base_url('member/peminjaman') ?>" class="tautan-lihat-semua">Lihat Semua →</a>
    </div>
    
    <div class="table-responsive-custom">
      <table class="table table-hover mb-0">
        <thead>
          <tr>
            <th>Judul Buku</th>
            <th>Tgl Pinjam</th>
            <th>Tenggat</th>
            <th class="teks-center">Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($pinjamanAktif)): ?>
            <tr>
              <td colspan="4">
                <div class="kondisi-kosong" style="padding: 40px 0; text-align: center;">
                  <p style="color: #94a3b8;">Tidak ada peminjaman aktif</p>
                </div>
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($pinjamanAktif as $loan):
              $dueDate = Time::parse($loan['due_date']);
              $isLate = $now->isAfter($dueDate);
              $isDueToday = $now->toDateString() === $dueDate->toDateString();
              if ($isLate) { $bc = 'badge-admin merah'; $bl = 'Terlambat'; }
              elseif ($isDueToday) { $bc = 'badge-admin kuning'; $bl = 'Jatuh Tempo'; }
              else { $bc = 'badge-admin biru'; $bl = 'Dipinjam'; }
            ?>
              <tr>
                <td class="judul-tabel-wrapper">
                  <div class="judul-tabel"><?= esc($loan['title']) ?> (<?= esc($loan['year']) ?>)</div>
                  <div class="penulis-tabel">Author: <?= esc($loan['author']) ?></div>
                </td>
                <td style="white-space:nowrap">
                  <?= Time::parse($loan['loan_date'])->format('d/m/Y') ?>
                </td>
                <td style="white-space:nowrap" class="<?= $isLate ? 'tgl-terlambat' : 'tgl-normal' ?>">
                  <?= $dueDate->format('d/m/Y') ?>
                </td>
                <td class="teks-center">
                  <span class="<?= $bc ?>"><?= $bl ?></span>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="kotak-konten">
    <div class="kepala-kotak">
      <h3>Pengembalian Terakhir</h3>
      <a href="<?= base_url('member/pengembalian') ?>" class="tautan-lihat-semua">Lihat Semua →</a>
    </div>

    <div class="table-responsive-custom">
      <table class="table table-hover mb-0">
        <thead>
          <tr>
            <th>Judul Buku</th>
            <th>Tgl Kembali</th>
            <th class="teks-center">Status</th>
            <th class="teks-center">Kuis</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($pengembalianTerakhir)): ?>
            <tr>
              <td colspan="4">
                <div class="kondisi-kosong" style="padding: 40px 0; text-align: center;">
                  <p style="color: #94a3b8;">Belum ada riwayat pengembalian</p>
                </div>
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($pengembalianTerakhir as $ret):
              $isLate = $ret['is_late'];
              $quizInfo = $ret['quiz_info'] ?? null;
              $sudahKuis = $ret['sudah_kuis'] ?? false;
              $expired = $ret['kuis_expired'] ?? false;
              $maxHabis = $ret['max_habis'] ?? false;
            ?>
              <tr>
                <td class="judul-tabel-wrapper">
                  <div class="judul-tabel"><?= esc($ret['title']) ?> (<?= esc($ret['year']) ?>)</div>
                  <div class="penulis-tabel">Author: <?= esc($ret['author']) ?></div>
                </td>
                <td style="white-space:nowrap"><?= Time::parse($ret['return_date'])->format('d/m/Y') ?></td>
                <td class="teks-center">
                  <span class="badge-admin <?= $isLate ? 'merah' : 'hijau' ?>"><?= $isLate ? 'Terlambat' : 'Tepat Waktu' ?></span>
                </td>
                <td class="text-center">
                <?php 
                  $quizInfo = $ret['quiz_info'] ?? null;
                  $sudahKuis = $ret['sudah_kuis'] ?? false;
                  $maxHabis = $ret['max_habis'] ?? false;
                  $expired = $ret['kuis_expired'] ?? false;
                ?>

                <?php if (!$quizInfo): ?>
                  <span style="display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:6px;font-size:.78rem;font-weight:500;background:#f8fafc;color:#cbd5e1;border:1px solid #e2e8f0;cursor:default;white-space:nowrap">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                    Belum ada kuis
                  </span>
                <?php elseif ($maxHabis || ($sudahKuis && $expired)): ?>
                  <span style="display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:6px;font-size:.78rem;font-weight:500;background:#f8fafc;color:#94a3b8;border:1px solid #e2e8f0;cursor:default;white-space:nowrap">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    Sudah Selesai
                  </span>
                <?php elseif ($expired): ?>
                  <span style="display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:6px;font-size:.78rem;font-weight:500;background:#fff7ed;color:#c2410c;border:1px solid #fed7aa;cursor:default;white-space:nowrap">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    Kedaluwarsa
                  </span>
                <?php elseif ($sudahKuis): ?>
                  <a href="<?= base_url("member/kuis/{$quizInfo['id']}?loan_id={$ret['id']}") ?>"
                     style="display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:6px;font-size:.78rem;font-weight:600;background:#eff6ff;color:#2563eb;border:1px solid #bfdbfe;text-decoration:none;white-space:nowrap">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-4"/></svg>
                    Ulangi Kuis
                  </a>
                <?php else: ?>
                  <a href="<?= base_url("member/kuis/{$quizInfo['id']}?loan_id={$ret['id']}") ?>"
                     style="display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:6px;font-size:.78rem;font-weight:600;background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0;text-decoration:none;white-space:nowrap">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    Kerjakan Kuis
                  </a>
                <?php endif; ?>
              </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?= $this->endSection() ?>