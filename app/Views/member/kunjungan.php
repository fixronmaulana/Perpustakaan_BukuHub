<?= $this->extend('layouts/member_layout') ?>

<?= $this->section('head') ?>
<title>Kunjungan — Portal Anggota</title>
<?= $this->endSection() ?>

<?= $this->section('pageTitle') ?>Kunjungan<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php use CodeIgniter\I18n\Time; ?>

<!-- Statistik -->
<div class="grid-stat" style="grid-template-columns: repeat(2,1fr); margin-bottom:1.25rem">
  <div class="kartu-stat-admin">
    <div class="ksa-body">
      <div class="ksa-icon">
        <svg viewBox="0 0 24 24" width="22" height="22"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
      </div>
      <div class="ksa-angka"><?= $kunjunganBulanIni ?></div>
      <div class="ksa-label">Kunjungan Bulan Ini</div>
    </div>
  </div>
  <div class="kartu-stat-admin">
    <div class="ksa-body">
      <div class="ksa-icon">
        <svg viewBox="0 0 24 24" width="22" height="22"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
      </div>
      <div class="ksa-angka"><?= $totalKunjungan ?></div>
      <div class="ksa-label">Total Kunjungan</div>
    </div>
  </div>
</div>

<!-- Tabel -->
<div class="kotak-konten">
  <div class="kepala-kotak">
    <h3>Riwayat Kunjungan</h3>
  </div>

  <div class="table-responsive">
    <table class="table table-hover table-sm mb-0" id="tabel-kunjungan">
      <thead>
        <tr>
          <th style="width:40px">#</th>
          <th>Tanggal Kunjungan</th>
          <th>Hari</th>
          <th>Waktu</th>
          <th class="text-center">Metode</th>
          <th>Catatan</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($visits)): ?>
          <tr>
            <td colspan="6">
              <div class="kondisi-kosong">
                <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                <p>Belum ada riwayat kunjungan</p>
              </div>
            </td>
          </tr>
        <?php else: ?>
          <?php
            $hariId = ['Sunday'=>'Minggu','Monday'=>'Senin','Tuesday'=>'Selasa',
                       'Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu'];
            $i = 1;
            foreach ($visits as $visit):
              $vDate = Time::parse($visit['visit_date']);
              $hari  = $hariId[$vDate->format('l')] ?? $vDate->format('l');
          ?>
            <tr>
              <td class="teks-redup-sm"><?= $i++ ?></td>
              <td><b><?= $vDate->format('d/m/Y') ?></b></td>
              <td class="teks-redup-sm"><?= $hari ?></td>
              <td class="teks-redup-sm"><?= $vDate->format('H:i:s') ?></td>
              <td class="text-center">
                <?php if ($visit['method'] === 'scan'): ?>
                  <span class="badge-admin biru">Scan QR</span>
                <?php else: ?>
                  <span class="badge-admin" style="background:#f1f5f9;color:#475569">Manual</span>
                <?php endif; ?>
              </td>
              <td class="teks-redup-sm"><?= esc($visit['notes'] ?? '—') ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?= $this->endSection() ?>