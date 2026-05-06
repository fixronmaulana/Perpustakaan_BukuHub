<?= $this->extend('layouts/home_layout') ?>

<?= $this->section('head') ?>
<title>Leaderboard — Perpustakaan SMK Al-Munawwir</title>
<?= $this->endSection() ?>
<?= $this->section('content') ?>
<?= $this->include('layouts/navbar') ?>

<?php
$namaBulan = [
    1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',
    5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',
    9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
];
$isRealtime = ($bulan === $bulanIni && $tahun === $tahunIni);
$bulanLabel = ($namaBulan[$bulan] ?? $bulan) . ' ' . $tahun;
$top3       = array_slice($leaderboard, 0, 3);
$namaRank   = [1 => 'Juara 1', 2 => 'Juara 2', 3 => 'Juara 3'];
$emojiRank  = [1 => '🥇', 2 => '🥈', 3 => '🥉'];
?>

<!-- Header -->
<div class="header-halaman">
  <h1>Leaderboard Perpustakaan</h1>
  <div class="garis-emas"></div>
  <!-- Hitung mundur -->
  <?php if ($isRealtime): ?>
  <div class="lb-countdown-section">
    <div class="lb-countdown-label">Periode berakhir dalam</div>
    <div class="lb-countdown-inner">
      <div class="lb-cd-unit">
        <div class="lb-cd-num" id="cdHari">–</div>
        <div class="lb-cd-label">Hari</div>
      </div>
      <div class="lb-cd-sep">:</div>
      <div class="lb-cd-unit">
        <div class="lb-cd-num" id="cdJam">–</div>
        <div class="lb-cd-label">Jam</div>
      </div>
      <div class="lb-cd-sep">:</div>
      <div class="lb-cd-unit">
        <div class="lb-cd-num" id="cdMenit">–</div>
        <div class="lb-cd-label">Menit</div>
      </div>
      <div class="lb-cd-sep">:</div>
      <div class="lb-cd-unit">
        <div class="lb-cd-num" id="cdDetik">–</div>
        <div class="lb-cd-label">Detik</div>
      </div>
    </div>
    <div><span class="lb-live-pill"><span class="lb-live-dot"></span> Live</span></div>
  </div>
  <?php endif; ?>
</div>

<!-- Podium -->
<?php if (count($top3) >= 1): ?>
<div class="lb-podium-section">
  <div class="lb-podium-wrap">
    <?php foreach ([1, 0, 2] as $idx):
      $row = $top3[$idx] ?? null;
      if (!$row) continue;
      $rank    = $idx + 1;
      $nama    = ucwords(strtolower(trim($row['first_name'] . ' ' . ($row['last_name'] ?? ''))));
      $inisial = strtoupper(substr($row['first_name'], 0, 1) . substr($row['last_name'] ?? '', 0, 1));
      $adaFoto = !empty($row['foto_profil']) && file_exists(FCPATH . 'uploads/foto_profil/' . $row['foto_profil']);
      $rc      = 'rank-' . $rank;
      $h       = $hadiah[$rank] ?? null;
    ?>
      <div class="lb-podium-item">
        <?php if ($rank === 1): ?>
          <div class="lb-mahkota">👑</div>
        <?php else: ?>
          <div class="lb-spacer"></div>
        <?php endif; ?>
        <div class="lb-podium-avatar-wrap">
          <div class="lb-podium-avatar <?= $rc ?>">
            <?php if ($adaFoto): ?>
              <img src="<?= base_url('uploads/foto_profil/' . $row['foto_profil']) ?>" alt="">
            <?php else: ?>
              <?= esc($inisial) ?>
            <?php endif; ?>
          </div>
          <div class="lb-podium-badge <?= $rc ?>"><?= $rank ?></div>
        </div>
        <div class="lb-podium-nama <?= $rc ?>"><?= esc(mb_strimwidth($nama, 0, 16, '...')) ?></div>
        <div class="lb-podium-tipe"><?= esc($row['tipe_anggota']) ?></div>
        <div class="lb-podium-poin <?= $rc ?>"><?= number_format($row['total_points']) ?> poin</div>
        <?php if ($h): ?>
          <button class="lb-btn-hadiah <?= $rc ?>" onclick="bukaModalHadiah(<?= $rank ?>)">
            🎁 Hadiah
          </button>
        <?php endif; ?>
        <div class="lb-tiang <?= $rc ?>"><?= $rank ?></div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<!-- Filter + Tabel -->
<div class="lb-tabel-section">
  <div class="lb-tabel-toolbar">
    <div class="lb-search-wrap">
      <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
      </svg>
      <input type="text" class="lb-search-input" id="cariMember"
             placeholder="Cari nama anggota..." onkeyup="filterMember()">
    </div>
    <div class="lb-filter-right">
      <span class="lb-filter-label">Periode:</span>
      <form method="get" action="" style="display:flex;align-items:center;gap:6px">
        <select name="bulan" class="lb-select" id="selectBulan">
          <?php foreach ($daftarBulan as $db): ?>
            <option value="<?= $db['bulan'] ?>"
                    data-tahun="<?= $db['tahun'] ?>"
                    <?= ($db['bulan'] == $bulan && $db['tahun'] == $tahun) ? 'selected' : '' ?>>
              <?= esc($db['label']) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <input type="hidden" name="tahun" id="tahunInput" value="<?= $tahun ?>">
      </form>
    </div>
  </div>

  <div style="overflow-x:auto">
    <div class="lb-tabel-wrap" style="min-width:850px">
      <div class="lb-tabel-header">
        <h2>Semua Peringkat</h2>
        <span id="jumlahAnggota"><?= count($leaderboard) ?> anggota</span>
      </div>
      <table class="lb-tabel">
        <thead>
          <tr>
            <th class="center" style="width:52px">#</th>
            <th>Anggota</th>
            <th class="center divider-l" title="Poin dari kunjungan perpustakaan">Kunjungan</th>
            <th class="center" title="Poin dari peminjaman buku">Peminjaman</th>
            <th class="center" title="Poin dari pengembalian tepat waktu">Tepat Waktu</th>
            <th class="center" title="Pengurangan poin karena terlambat">Terlambat</th>
            <th class="center" title="Poin dari kuis buku">Kuis</th>
            <th class="center divider-l" style="width:110px">Total Poin</th>
          </tr>
        </thead>
        <tbody id="tabelBody">
          <?php if (empty($leaderboard)): ?>
            <tr>
              <td colspan="8">
                <div class="lb-kosong">
                  <svg viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="20" x2="18" y2="10"/>
                    <line x1="12" y1="20" x2="12" y2="4"/>
                    <line x1="6"  y1="20" x2="6"  y2="14"/>
                  </svg>
                  Belum ada data leaderboard bulan ini
                </div>
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($leaderboard as $i => $row):
              $rank     = $i + 1;
              $nama     = ucwords(strtolower(trim($row['first_name'] . ' ' . ($row['last_name'] ?? ''))));
              $inisial  = strtoupper(substr($row['first_name'], 0, 1) . substr($row['last_name'] ?? '', 0, 1));
              $adaFoto  = !empty($row['foto_profil']) && file_exists(FCPATH . 'uploads/foto_profil/' . $row['foto_profil']);
              $rowClass = $rank === 1 ? 'row-top-1' : ($rank === 2 ? 'row-top-2' : ($rank === 3 ? 'row-top-3' : ''));
              $poinKunjungan  = (int) ($row['poin_kunjungan']  ?? 0);
              $poinPeminjaman = (int) ($row['poin_peminjaman'] ?? 0);
              $poinTepat      = (int) ($row['poin_tepat']      ?? 0);
              $poinTerlambat  = (int) ($row['poin_terlambat']  ?? 0);
              $poinKuis       = (int) ($row['poin_kuis']       ?? 0);
            ?>
              <tr class="<?= $rowClass ?>" data-nama="<?= strtolower($nama) ?>">
                <td class="center">
                  <?php if ($rank === 1): ?>
                    <span class="lb-rank-badge gold">1</span>
                  <?php elseif ($rank === 2): ?>
                    <span class="lb-rank-badge silver">2</span>
                  <?php elseif ($rank === 3): ?>
                    <span class="lb-rank-badge bronze">3</span>
                  <?php else: ?>
                    <span class="lb-rank-num"><?= $rank ?></span>
                  <?php endif; ?>
                </td>
                <td>
                  <div class="lb-member-cell">
                    <div class="lb-avatar-sm">
                      <?php if ($adaFoto): ?>
                        <img src="<?= base_url('uploads/foto_profil/' . $row['foto_profil']) ?>" alt="">
                      <?php else: ?>
                        <?= esc($inisial) ?>
                      <?php endif; ?>
                    </div>
                    <div>
                      <div class="lb-member-nama"><?= esc($nama) ?></div>
                      <div class="lb-member-sub">
                        <?= esc($row['no_identitas']) ?>
                        <span class="lb-tipe-badge"><?= esc($row['tipe_anggota']) ?></span>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="center divider-l">
                  <?= $poinKunjungan > 0
                    ? '<span class="lb-chip pos">+' . $poinKunjungan . '</span>'
                    : '<span class="lb-dash">—</span>' ?>
                </td>
                <td class="center">
                  <?= $poinPeminjaman > 0
                    ? '<span class="lb-chip pos">+' . $poinPeminjaman . '</span>'
                    : '<span class="lb-dash">—</span>' ?>
                </td>
                <td class="center">
                  <?= $poinTepat > 0
                    ? '<span class="lb-chip pos">+' . $poinTepat . '</span>'
                    : '<span class="lb-dash">—</span>' ?>
                </td>
                <td class="center">
                  <?= $poinTerlambat < 0
                    ? '<span class="lb-chip neg">' . $poinTerlambat . '</span>'
                    : '<span class="lb-dash">—</span>' ?>
                </td>
                <td class="center">
                  <?= $poinKuis > 0
                    ? '<span class="lb-chip pos">+' . $poinKuis . '</span>'
                    : '<span class="lb-dash">—</span>' ?>
                </td>
                <td class="center divider-l">
                  <span class="lb-poin-val <?= $row['total_points'] >= 0 ? 'pos' : 'neg' ?>">
                    <?= ($row['total_points'] >= 0 ? '+' : '') . number_format($row['total_points']) ?>
                  </span>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <?php if (!auth()->loggedIn()): ?>
  <div class="lb-cta">
    Ingin tahu peringkat kamu?
    <a href="<?= base_url('login') ?>">Login sekarang</a> untuk melihat posisi kamu di leaderboard.
  </div>
  <?php endif; ?>
</div>

<!-- Modal Hadiah -->
<?php foreach ([1,2,3] as $r):
  $h = $hadiah[$r] ?? null;
  if (!$h) continue;
  $adaFotoH = !empty($h['foto']) && file_exists(FCPATH . 'uploads/hadiah/' . $h['foto']);
?>
<div class="lb-modal-overlay" id="modalHadiah<?= $r ?>">
  <div class="lb-modal">
    <div class="lb-modal-header rank-<?= $r ?>">
      <button class="lb-modal-close" onclick="tutupModalHadiah(<?= $r ?>)">✕</button>
      <div class="lb-modal-rank-label">Hadiah Bulan Ini</div>
      <div class="lb-modal-emoji"><?= $emojiRank[$r] ?></div>
      <div class="lb-modal-rank-title"><?= $namaRank[$r] ?></div>
    </div>
    <div class="lb-modal-body">
      <div class="lb-modal-foto-wrap">
        <?php if ($adaFotoH): ?>
          <img src="<?= base_url('uploads/hadiah/' . $h['foto']) ?>"
               class="lb-modal-foto" alt="<?= esc($h['nama_hadiah']) ?>">
        <?php else: ?>
          <div class="lb-modal-foto-placeholder">🎁</div>
        <?php endif; ?>
      </div>
      <div class="lb-modal-nama"><?= esc($h['nama_hadiah']) ?></div>
      <?php if (!empty($h['deskripsi'])): ?>
        <div class="lb-modal-desc"><?= esc($h['deskripsi']) ?></div>
      <?php endif; ?>
      <div class="lb-modal-info">📅 Diberikan kepada pemenang di akhir periode bulan ini</div>
      <button class="lb-modal-btn-tutup" onclick="tutupModalHadiah(<?= $r ?>)">Tutup</button>
    </div>
  </div>
</div>
<?php endforeach; ?>

<?= $this->include('layouts/home_footer') ?>

<script>
function filterMember() {
  const q    = document.getElementById('cariMember').value.toLowerCase();
  const rows = document.querySelectorAll('#tabelBody tr[data-nama]');
  let vis    = 0;
  rows.forEach(tr => {
    const match = tr.dataset.nama.includes(q);
    tr.style.display = match ? '' : 'none';
    if (match) vis++;
  });
  document.getElementById('jumlahAnggota').textContent = vis + ' anggota';
}

document.getElementById('selectBulan').addEventListener('change', function() {
  document.getElementById('tahunInput').value = this.options[this.selectedIndex].dataset.tahun;
  this.form.submit();
});

function bukaModalHadiah(rank) {
  const m = document.getElementById('modalHadiah' + rank);
  if (m) m.classList.add('tampil');
}
function tutupModalHadiah(rank) {
  const m = document.getElementById('modalHadiah' + rank);
  if (m) m.classList.remove('tampil');
}
document.addEventListener('click', e => {
  if (e.target.classList.contains('lb-modal-overlay')) e.target.classList.remove('tampil');
});
document.addEventListener('keydown', e => {
  if (e.key === 'Escape')
    document.querySelectorAll('.lb-modal-overlay.tampil').forEach(m => m.classList.remove('tampil'));
});

<?php if ($isRealtime): ?>
(function() {
  function tick() {
    const now   = new Date();
    const akhir = new Date(now.getFullYear(), now.getMonth() + 1, 0, 23, 59, 59);
    const sisa  = akhir - now;
    if (sisa <= 0) return;
    document.getElementById('cdHari').textContent  = String(Math.floor(sisa/86400000)).padStart(2,'0');
    document.getElementById('cdJam').textContent   = String(Math.floor((sisa%86400000)/3600000)).padStart(2,'0');
    document.getElementById('cdMenit').textContent = String(Math.floor((sisa%3600000)/60000)).padStart(2,'0');
    document.getElementById('cdDetik').textContent = String(Math.floor((sisa%60000)/1000)).padStart(2,'0');
  }
  tick(); setInterval(tick, 1000);
})();
<?php endif; ?>
</script>

<?= $this->endSection() ?>