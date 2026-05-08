<?= $this->extend('layouts/member_layout') ?>

<?= $this->section('head') ?>
<title>Kunjungan — Portal Anggota</title>
<?= $this->endSection() ?>

<?= $this->section('pageTitle') ?>Kunjungan<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php use CodeIgniter\I18n\Time; ?>

<div class="container-fluid" style="padding: 0; max-width: 100vw; overflow: hidden;">
    <div class="grid-stat dua-kolom">
      <div class="kartu-stat-admin">
        <div class="ksa-body">
          <div class="ksa-icon">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
          </div>
          <div class="ksa-angka"><?= $kunjunganBulanIni ?></div>
          <div class="ksa-label">Kunjungan Bulan Ini</div>
        </div>
      </div>
      <div class="kartu-stat-admin">
        <div class="ksa-body">
          <div class="ksa-icon">
            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
          </div>
          <div class="ksa-angka"><?= $totalKunjungan ?></div>
          <div class="ksa-label">Total Kunjungan</div>
        </div>
      </div>
    </div>

    <div class="kotak-konten">
      <div class="kepala-kotak">
        <h3>Riwayat Kunjungan</h3>
      </div>

      <div class="table-responsive-custom">
        <table class="table table-hover" id="tabel-kunjungan">
          <thead>
            <tr>
              <th style="width:50px">#</th>
              <th>Tanggal Kunjungan</th>
              <th>Hari</th>
              <th>Waktu</th>
              <th class="text-center">Metode</th>
              <th>Catatan</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($visits)): ?>
              <tr><td colspan="6" class="text-center py-5">Belum ada riwayat</td></tr>
            <?php else: ?>
              <?php
                $hariId = ['Sunday'=>'Minggu','Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu'];
                $i = 1;
                foreach ($visits as $visit):
                  $vDate = Time::parse($visit['visit_date']);
                  $hari  = $hariId[$vDate->format('l')] ?? $vDate->format('l');
              ?>
                <tr>
                  <td><?= $i++ ?></td>
                  <td><b><?= $vDate->format('d/m/Y') ?></b></td>
                  <td><?= $hari ?></td>
                  <td><?= $vDate->format('H:i:s') ?></td>
                  <td class="text-center">
                    <span class="badge-admin <?= $visit['method'] === 'scan' ? 'biru' : '' ?>" style="<?= $visit['method'] !== 'scan' ? 'background:#f1f5f9;color:#475569' : '' ?>">
                        <?= $visit['method'] === 'scan' ? 'Scan QR' : 'Manual' ?>
                    </span>
                  </td>
                  <td><?= esc($visit['notes'] ?? '—') ?></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
</div>

<?= $this->endSection() ?>