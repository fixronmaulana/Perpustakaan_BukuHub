<?= $this->extend('layouts/member_layout') ?>

<?= $this->section('head') ?>
<title>Pengembalian — Portal Anggota</title>
<?= $this->endSection() ?>

<?= $this->section('pageTitle') ?>Pengembalian<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php use CodeIgniter\I18n\Time; ?>

<div class="grid-stat">
  <div class="kartu-stat-admin">
    <div class="ksa-body">
      <div class="ksa-icon">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 14 4 9 9 4"/><path d="M20 20v-7a4 4 0 00-4-4H4"/></svg>
      </div>
      <div class="ksa-angka"><?= $totalKembali ?></div>
      <div class="ksa-label">Total Dikembalikan</div>
    </div>
  </div>
  <div class="kartu-stat-admin">
    <div class="ksa-body">
      <div class="ksa-icon">
        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
      </div>
      <div class="ksa-angka"><?= $tepatWaktu ?></div>
      <div class="ksa-label">Tepat Waktu</div>
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
    <h3>Riwayat Pengembalian</h3>
    <div class="filter-status">
      <button class="pil-filter aktif" onclick="filterKembali(this,'semua')">Semua</button>
      <button class="pil-filter" onclick="filterKembali(this,'tepat-waktu')">Tepat Waktu</button>
      <button class="pil-filter" onclick="filterKembali(this,'terlambat')">Terlambat</button>
    </div>
  </div>

  <div class="table-responsive-custom">
    <table class="table table-hover mb-0" id="tabel-pengembalian">
      <thead>
        <tr>
          <th style="width:40px">#</th>
          <th>Judul Buku</th>
          <th>Tgl Pinjam</th>
          <th>Tenggat</th>
          <th>Tgl Kembali</th>
          <th>Keterlambatan</th>
          <th class="text-center">Status</th>
          <th class="text-center">Kuis</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($returns)): ?>
          <tr><td colspan="8" class="text-center py-5">Belum ada riwayat pengembalian</td></tr>
        <?php else: ?>
          <?php $i = 1; foreach ($returns as $ret): 
            $isLate = $ret['is_late']; 
            $statusKey = $isLate ? 'terlambat' : 'tepat-waktu';
          ?>
            <tr data-status="<?= $statusKey ?>">
              <td class="teks-redup-sm"><?= $i++ ?></td>
              <td>
                <div class="judul-tabel"><?= esc($ret['title']) ?> (<?= esc($ret['year']) ?>)</div>
                <div class="penulis-tabel">Author: <?= esc($ret['author']) ?></div>
              </td>
              <td><b><?= Time::parse($ret['loan_date'])->format('d/m/Y') ?></b></td>
              <td class="<?= $isLate ? 'tgl-terlambat' : '' ?>">
                <b><?= Time::parse($ret['due_date'])->format('d/m/Y') ?></b>
              </td>
              <td><b><?= Time::parse($ret['return_date'])->format('d/m/Y') ?></b></td>
              <td>
                <?php if ($isLate): ?>
                  <span class="tgl-terlambat">+<?= $ret['days_late'] ?> hari</span>
                <?php else: ?>
                  <span class="teks-redup-sm">—</span>
                <?php endif; ?>
              </td>
              <td class="text-center">
                <span class="badge-admin <?= $isLate ? 'merah' : 'hijau' ?>">
                  <?= $isLate ? 'Terlambat' : 'Tepat Waktu' ?>
                </span>
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
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function filterKembali(btn, status) {
    document.querySelectorAll('.pil-filter').forEach(b => b.classList.remove('aktif'));
    btn.classList.add('aktif');
    const baris = document.querySelectorAll('#tabel-pengembalian tbody tr');
    baris.forEach(tr => {
        tr.style.display = (status === 'semua' || tr.dataset.status === status) ? '' : 'none';
    });
}
</script>
<?= $this->endSection() ?>