<?= $this->extend('layouts/member_layout') ?>

<?= $this->section('head') ?>
<title>Peminjaman — Portal Anggota</title>
<?= $this->endSection() ?>

<?= $this->section('pageTitle') ?>Peminjaman<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php use CodeIgniter\I18n\Time; $now = Time::now(); ?>

<!-- Statistik -->
<div class="grid-stat">
  <div class="kartu-stat">
    <div class="isi-stat">
      <div class="label-stat">Sedang Dipinjam</div>
      <div class="angka-stat"><?= $sedangDipinjam ?></div>
    </div>
    <div class="ikon-stat-wrap biru">
      <svg viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
    </div>
  </div>
  <div class="kartu-stat">
    <div class="isi-stat">
      <div class="label-stat">Total Peminjaman</div>
      <div class="angka-stat"><?= $totalPeminjaman ?></div>
    </div>
    <div class="ikon-stat-wrap hijau">
      <svg viewBox="0 0 24 24"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
    </div>
  </div>
  <div class="kartu-stat">
    <div class="isi-stat">
      <div class="label-stat">Terlambat</div>
      <div class="angka-stat"><?= $terlambat ?></div>
    </div>
    <div class="ikon-stat-wrap merah">
      <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    </div>
  </div>
</div>

<!-- Tabel -->
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

  <div class="bungkus-tabel">
    <table class="tabel-member" id="tabel-peminjaman">
      <thead>
        <tr>
          <th style="width:40px">#</th>
          <th>Judul Buku</th>
          <th style="width:70px" class="teks-center">Jumlah</th>
          <th>Tgl Pinjam</th>
          <th>Tenggat</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($loans)): ?>
          <tr>
            <td colspan="6" class="teks-center" style="padding:2rem">
              <div class="kondisi-kosong">
                <svg viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
                <p>Tidak ada peminjaman aktif</p>
              </div>
            </td>
          </tr>
        <?php else: ?>
          <?php $i = 1; foreach ($loans as $loan):
            $dueDate  = Time::parse($loan['due_date']);
            $isLate   = $loan['is_late'];
            $isDueToday = $loan['is_due_today'];

            if ($isLate)      { $statusKey = 'terlambat';  $badgeClass = 'terlambat'; $badgeLabel = 'Terlambat'; }
            elseif ($isDueToday) { $statusKey = 'jatuh-tempo'; $badgeClass = 'jatuh-tempo'; $badgeLabel = 'Jatuh Tempo'; }
            else              { $statusKey = 'dipinjam';   $badgeClass = 'dipinjam';  $badgeLabel = 'Dipinjam'; }
          ?>
            <tr data-status="<?= $statusKey ?>">
              <td class="teks-redup-sm"><?= $i++ ?></td>
              <td>
                <div class="judul-tabel"><?= esc($loan['title']) ?> (<?= esc($loan['year']) ?>)</div>
                <div class="penulis-tabel">Author: <?= esc($loan['author']) ?></div>
              </td>
              <td class="teks-center"><?= $loan['quantity'] ?></td>
              <td class="tgl-normal">
                <?= Time::parse($loan['loan_date'])->format('d/m/Y') ?><br>
                <span class="teks-redup-sm"><?= Time::parse($loan['loan_date'])->format('H:i:s') ?></span>
              </td>
              <td class="<?= $isLate ? 'tgl-terlambat' : 'tgl-normal' ?>">
                <?= $dueDate->format('d/m/Y') ?>
              </td>
              <td><span class="badge <?= $badgeClass ?>"><?= $badgeLabel ?></span></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
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