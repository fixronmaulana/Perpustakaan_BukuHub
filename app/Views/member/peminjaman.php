<?= $this->extend('layouts/member_layout') ?>

<?= $this->section('head') ?>
<title>Peminjaman — Portal Anggota</title>
<?= $this->endSection() ?>

<?= $this->section('pageTitle') ?>Peminjaman<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php use CodeIgniter\I18n\Time; $now = Time::now(); ?>

<div class="grid-stat">
  <div class="kartu-stat-admin">
    <div class="ksa-body">
      <div class="ksa-icon">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
      </div>
      <div class="ksa-angka"><?= $sedangDipinjam ?></div>
      <div class="ksa-label">Sedang Dipinjam</div>
    </div>
  </div>
  <div class="kartu-stat-admin">
    <div class="ksa-body">
      <div class="ksa-icon">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
      </div>
      <div class="ksa-angka"><?= $totalPeminjaman ?></div>
      <div class="ksa-label">Total Peminjaman</div>
    </div>
  </div>
  <div class="kartu-stat-admin">
    <div class="ksa-body">
      <div class="ksa-icon">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      </div>
      <div class="ksa-angka"><?= $terlambat ?></div>
      <div class="ksa-label">Terlambat</div>
    </div>
  </div>
</div>

<div class="kotak-konten">
  <div class="kepala-kotak">
    <h3>Peminjaman Aktif</h3>
    <div class="filter-status">
      <button class="pil-filter aktif" onclick="filterStatus(this,'semua')">Semua</button>
      <button class="pil-filter" onclick="filterStatus(this,'dipinjam')">Normal</button>
      <button class="pil-filter" onclick="filterStatus(this,'jatuh-tempo')">Jatuh Tempo</button>
      <button class="pil-filter" onclick="filterStatus(this,'terlambat')">Terlambat</button>
    </div>
  </div>

  <div class="table-responsive-custom">
    <table class="table table-hover mb-0" id="tabel-peminjaman">
      <thead>
        <tr>
          <th style="width:40px">#</th>
          <th>Judul Buku</th>
          <th style="width:80px" class="text-center">Jumlah</th>
          <th>Tgl Pinjam</th>
          <th>Tenggat</th>
          <th class="text-center">Status</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($loans)): ?>
          <tr>
            <td colspan="6">
              <div class="kondisi-kosong" style="padding: 40px 0; text-align: center;">
                <p style="color: #94a3b8;">Tidak ada peminjaman aktif</p>
              </div>
            </td>
          </tr>
        <?php else: ?>
          <?php $i = 1; foreach ($loans as $loan):
            $dueDate    = Time::parse($loan['due_date']);
            $isLate     = $loan['is_late'];
            $isDueToday = $loan['is_due_today'];
            if ($isLate)        { $statusKey = 'terlambat';  $badgeClass = 'badge-admin merah'; $badgeLabel = 'Terlambat'; }
            elseif ($isDueToday){ $statusKey = 'jatuh-tempo';$badgeClass = 'badge-admin kuning';$badgeLabel = 'Jatuh Tempo'; }
            else                { $statusKey = 'dipinjam';   $badgeClass = 'badge-admin biru';  $badgeLabel = 'Dipinjam'; }
          ?>
            <tr data-status="<?= $statusKey ?>">
              <td class="teks-redup-sm"><?= $i++ ?></td>
              <td>
                <div class="judul-tabel"><?= esc($loan['title']) ?> (<?= esc($loan['year']) ?>)</div>
                <div class="penulis-tabel">Author: <?= esc($loan['author']) ?></div>
              </td>
              <td class="text-center"><?= $loan['quantity'] ?></td>
              <td>
                <b><?= Time::parse($loan['loan_date'])->format('d/m/Y') ?></b><br>
                <span class="teks-redup-sm"><?= Time::parse($loan['loan_date'])->format('H:i:s') ?></span>
              </td>
              <td class="<?= $isLate ? 'tgl-terlambat' : '' ?>">
                <b><?= $dueDate->format('d/m/Y') ?></b>
              </td>
              <td class="text-center">
                <span class="<?= $badgeClass ?>"><?= $badgeLabel ?></span>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="kondisi-kosong" id="kondisi-kosong" style="display:none; padding: 40px; text-align: center; color: #94a3b8;">
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
  document.getElementById('kondisi-kosong').style.display = terlihat === 0 ? 'block' : 'none';
}
</script>
<?= $this->endSection() ?>