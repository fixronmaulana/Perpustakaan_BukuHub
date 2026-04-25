<?= $this->extend('layouts/home_layout') ?>

<?= $this->section('head') ?>
<title>Leaderboard — Perpustakaan SMK Al-Munawwir</title>
<style>
/* ════════════════════════════════════════════
   LEADERBOARD LANDING PAGE
════════════════════════════════════════════ */

.lb-page { padding: 2.5rem 0 4rem; }

/* ── Header ── */
.lb-header {
  text-align: center;
  margin-bottom: 2.5rem;
}
.lb-header h1 {
  font-size: clamp(1.6rem, 4vw, 2.2rem);
  font-weight: 800;
  color: #0f172a;
  letter-spacing: -.5px;
  margin-bottom: .5rem;
}
.lb-header p {
  font-size: .95rem;
  color: #64748b;
  max-width: 480px;
  margin: 0 auto 1.25rem;
}
.lb-header-meta {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 20px;
  padding: 6px 14px;
  font-size: .78rem;
  color: #475569;
}
.lb-live-dot {
  width: 7px; height: 7px;
  border-radius: 50%;
  background: #16a34a;
  animation: pulse-dot 1.5s infinite;
}
@keyframes pulse-dot {
  0%,100% { opacity:1; } 50% { opacity:.25; }
}

/* ── Filter bulan ── */
.lb-filter-wrap {
  display: flex;
  justify-content: flex-end;
  margin-bottom: 1.5rem;
}
.lb-filter-wrap form {
  display: flex;
  align-items: center;
  gap: 8px;
}
.lb-filter-label {
  font-size: .8rem;
  color: #64748b;
  font-weight: 500;
}
.lb-select {
  font-size: .82rem;
  padding: 7px 12px;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  background: #fff;
  color: #334155;
  cursor: pointer;
  outline: none;
}
.lb-select:focus { border-color: #94a3b8; }

/* ── Panduan poin ── */
.lb-panduan {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  justify-content: center;
  margin-bottom: 2.5rem;
}
.lb-panduan-item {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 6px 14px;
  border-radius: 20px;
  font-size: .78rem;
  font-weight: 500;
  border: 1px solid;
}
.lb-panduan-item.pos { background: #f0fdf4; border-color: #bbf7d0; color: #166534; }
.lb-panduan-item.neg { background: #fef2f2; border-color: #fecaca; color: #991b1b; }
.lb-panduan-icon { font-size: .85rem; }

/* ── Podium ── */
.lb-podium-wrap {
  display: flex;
  justify-content: center;
  align-items: flex-end;
  gap: 1rem;
  margin-bottom: 2.5rem;
  padding: 0 3.5rem;
}

.lb-podium-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 160px;
}

/* Avatar podium */
.lb-podium-avatar-wrap { position: relative; margin-bottom: .75rem; }
.lb-podium-avatar {
  width: 68px; height: 68px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.1rem;
  font-weight: 800;
  color: #fff;
  overflow: hidden;
  position: relative;
  border: 3px solid;
}
.lb-podium-avatar img { width: 100%; height: 100%; object-fit: cover; }
.lb-podium-avatar.rank-1 { width: 80px; height: 80px; border-color: #d97706; background: linear-gradient(135deg,#92400e,#d97706); }
.lb-podium-avatar.rank-2 { border-color: #94a3b8; background: linear-gradient(135deg,#475569,#94a3b8); }
.lb-podium-avatar.rank-3 { border-color: #b45309; background: linear-gradient(135deg,#78350f,#d97706); }

.lb-podium-badge {
  position: absolute;
  bottom: -4px; right: -4px;
  width: 22px; height: 22px;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: .65rem; font-weight: 800;
  border: 2px solid #fff;
}
.lb-podium-badge.rank-1 { background: #d97706; color: #fff; }
.lb-podium-badge.rank-2 { background: #64748b; color: #fff; }
.lb-podium-badge.rank-3 { background: #b45309; color: #fff; }

.lb-mahkota { font-size: 1.5rem; margin-bottom: .25rem; line-height: 1; }

.lb-podium-nama {
  font-size: .85rem;
  font-weight: 700;
  color: #1e293b;
  text-align: center;
  margin-bottom: 2px;
  max-width: 140px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
.lb-podium-tipe {
  font-size: .7rem;
  color: #94a3b8;
  margin-bottom: .4rem;
}
.lb-podium-poin {
  font-size: .88rem;
  font-weight: 700;
  margin-bottom: .75rem;
}
.lb-podium-poin.rank-1 { color: #d97706; }
.lb-podium-poin.rank-2 { color: #64748b; }
.lb-podium-poin.rank-3 { color: #b45309; }

/* Tiang podium */
.lb-tiang {
  width: 100%;
  border-radius: 8px 8px 0 0;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.1rem;
  font-weight: 800;
  color: rgba(255,255,255,.7);
}
.lb-tiang.rank-1 { height: 110px; background: linear-gradient(180deg,#fbbf24,#d97706); }
.lb-tiang.rank-2 { height: 80px;  background: linear-gradient(180deg,#cbd5e1,#94a3b8); }
.lb-tiang.rank-3 { height: 60px;  background: linear-gradient(180deg,#fed7aa,#b45309); }

/* ── Tabel ── */
.lb-tabel-wrap {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 14px;
  overflow: hidden;
  margin: 0 3.5rem;
}
.lb-tabel-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1.125rem 1.25rem;
  border-bottom: 1px solid #f1f5f9;
}
.lb-tabel-header h2 {
  font-size: .95rem;
  font-weight: 700;
  color: #0f172a;
  margin: 0;
}
.lb-tabel-header span {
  font-size: .78rem;
  color: #94a3b8;
}
.lb-tabel {
  width: 100%;
  border-collapse: collapse;
}
.lb-tabel thead th {
  padding: .7rem 1rem;
  font-size: .72rem;
  font-weight: 600;
  color: #94a3b8;
  text-transform: uppercase;
  letter-spacing: .5px;
  background: #f8fafc;
  border-bottom: 1px solid #f1f5f9;
  text-align: left;
}
.lb-tabel thead th.center { text-align: center; }
.lb-tabel tbody tr {
  border-bottom: 1px solid #f8fafc;
  transition: background .1s;
}
.lb-tabel tbody tr:last-child { border-bottom: none; }
.lb-tabel tbody tr:hover { background: #f8fafc; }
.lb-tabel tbody td {
  padding: .75rem 1rem;
  font-size: .82rem;
  color: #1e293b;
  vertical-align: middle;
}
.lb-tabel tbody td.center { text-align: center; }

/* Rank badge */
.lb-rank-badge {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 28px; height: 28px;
  border-radius: 7px;
  font-size: .72rem;
  font-weight: 800;
}
.lb-rank-badge.gold   { background: #1c1917; color: #fbbf24; }
.lb-rank-badge.silver { background: #334155; color: #cbd5e1; }
.lb-rank-badge.bronze { background: #431407; color: #fb923c; }
.lb-rank-num { font-size: .8rem; color: #94a3b8; font-weight: 500; }

/* Member cell */
.lb-member-cell {
  display: flex;
  align-items: center;
  gap: 10px;
}
.lb-avatar-sm {
  width: 36px; height: 36px;
  border-radius: 50%;
  background: #e2e8f0;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: .72rem;
  font-weight: 700;
  color: #64748b;
  overflow: hidden;
  flex-shrink: 0;
  border: 2px solid #f1f5f9;
}
.lb-avatar-sm img { width: 100%; height: 100%; object-fit: cover; }
.lb-member-nama { font-size: .83rem; font-weight: 600; color: #1e293b; }
.lb-member-sub  { font-size: .71rem; color: #94a3b8; margin-top: 1px; }
.lb-tipe-badge  {
  display: inline-block;
  font-size: .65rem;
  padding: 1px 6px;
  border-radius: 4px;
  background: #f1f5f9;
  color: #475569;
  font-weight: 500;
  margin-left: 4px;
}

/* Poin */
.lb-poin-val {
  font-size: .88rem;
  font-weight: 700;
  text-align: center;
}
.lb-poin-val.pos { color: #16a34a; }
.lb-poin-val.neg { color: #dc2626; }

/* Kosong */
.lb-kosong {
  text-align: center;
  padding: 3rem 1rem;
  color: #94a3b8;
  font-size: .85rem;
}
.lb-kosong svg {
  width: 40px; height: 40px;
  stroke: #cbd5e1;
  margin-bottom: .75rem;
  display: block;
  margin-left: auto; margin-right: auto;
}

/* Hitung mundur */
.lb-countdown {
  text-align: center;
  margin-bottom: 1.5rem;
}
.lb-countdown-inner {
  display: inline-flex;
  gap: .75rem;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: .75rem 1.5rem;
}
.lb-cd-unit { text-align: center; min-width: 48px; }
.lb-cd-num  { font-size: 1.4rem; font-weight: 800; color: #0f172a; line-height: 1; }
.lb-cd-label { font-size: .65rem; color: #94a3b8; text-transform: uppercase; letter-spacing: .5px; margin-top: 2px; }
.lb-cd-sep  { font-size: 1.2rem; font-weight: 700; color: #cbd5e1; align-self: flex-start; padding-top: 4px; }

/* ── Hadiah Leaderboard ── */
.lb-hadiah-wrap {
  display: flex;
  justify-content: center;
  gap: 1rem;
  margin-bottom: 1.5rem;
  flex-wrap: wrap;
}
.lb-hadiah-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 160px;
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: .875rem .75rem;
  text-align: center;
  gap: 6px;
  position: relative;
}
.lb-hadiah-item.rank-1 { border-color: #fbbf24; background: #fffbeb; }
.lb-hadiah-item.rank-2 { border-color: #cbd5e1; background: #f8fafc; }
.lb-hadiah-item.rank-3 { border-color: #fed7aa; background: #fff7ed; }

.lb-hadiah-rank-label {
  font-size: .65rem;
  font-weight: 700;
  letter-spacing: .5px;
  text-transform: uppercase;
  padding: 2px 8px;
  border-radius: 20px;
}
.rank-1 .lb-hadiah-rank-label { background: #fef9c3; color: #b45309; }
.rank-2 .lb-hadiah-rank-label { background: #f1f5f9; color: #64748b; }
.rank-3 .lb-hadiah-rank-label { background: #ffedd5; color: #9a3412; }

.lb-hadiah-foto {
  width: 56px; height: 56px;
  border-radius: 10px;
  object-fit: cover;
  border: 1px solid #e2e8f0;
}
.lb-hadiah-foto-placeholder {
  width: 56px; height: 56px;
  border-radius: 10px;
  background: #f1f5f9;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
}
.lb-hadiah-nama {
  font-size: .78rem;
  font-weight: 700;
  color: #1e293b;
  line-height: 1.3;
}
.lb-hadiah-desc {
  font-size: .68rem;
  color: #64748b;
  line-height: 1.4;
}
.lb-hadiah-judul {
  text-align: center;
  font-size: .78rem;
  font-weight: 600;
  color: #94a3b8;
  text-transform: uppercase;
  letter-spacing: .5px;
  margin-bottom: .75rem;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('layouts/navbar') ?>

<!-- ══ HEADER HALAMAN ══ -->
<div class="header-halaman">
  <h1>Leaderboard</h1>
  <div class="garis-emas"></div>
  <p>Peringkat anggota perpustakaan terbaik berdasarkan poin gamifikasi</p>
</div>

<?php
$namaBulan = [
    1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',
    5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',
    9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
];
$isRealtime  = ($bulan === $bulanIni && $tahun === $tahunIni);
$bulanLabel  = ($namaBulan[$bulan] ?? $bulan) . ' ' . $tahun;
$top3        = array_slice($leaderboard, 0, 3);
$sisanya     = array_slice($leaderboard, 3);

$panduan = [
    ['ikon' => '🚶', 'label' => 'Kunjungan',         'poin' => $pointSettings['visit']['points']         ?? 5,   'pos' => true],
    ['ikon' => '📖', 'label' => 'Peminjaman',         'poin' => $pointSettings['loan']['points']          ?? 10,  'pos' => true],
    ['ikon' => '✅', 'label' => 'Kembali Tepat Waktu','poin' => $pointSettings['return_ontime']['points'] ?? 15,  'pos' => true],
    ['ikon' => '⏰', 'label' => 'Kembali Terlambat',  'poin' => $pointSettings['return_late']['points']   ?? -10, 'pos' => false],
    ['ikon' => '🎯', 'label' => 'Kuis (maks.)',        'poin' => 100, 'pos' => true],
];
?>

<div class="bungkus-utama lb-page">



  <!-- Hitung mundur -->
  <?php if ($isRealtime): ?>
  <div class="lb-countdown">
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
  </div>
  <?php endif; ?>



  <!-- Hadiah Leaderboard -->
  <?php
  $labelRankHadiah = [1 => '🥇 Juara 1', 2 => '🥈 Juara 2', 3 => '🥉 Juara 3'];
  $adaHadiah = !empty($hadiah);
  ?>
  <?php if ($adaHadiah): ?>
  <div style="text-align:center;margin-bottom:.5rem">
    <div class="lb-hadiah-judul">🎁 Hadiah Bulan Ini</div>
  </div>
  <div class="lb-hadiah-wrap">
    <?php foreach ([1, 2, 3] as $r):
      $h = $hadiah[$r] ?? null;
      if (!$h) continue;
      $adaFotoHadiah = !empty($h['foto']) && file_exists(FCPATH . 'uploads/hadiah/' . $h['foto']);
    ?>
      <div class="lb-hadiah-item rank-<?= $r ?>">
        <span class="lb-hadiah-rank-label"><?= $labelRankHadiah[$r] ?></span>
        <?php if ($adaFotoHadiah): ?>
          <img src="<?= base_url('uploads/hadiah/' . $h['foto']) ?>"
               class="lb-hadiah-foto" alt="<?= esc($h['nama_hadiah']) ?>">
        <?php else: ?>
          <div class="lb-hadiah-foto-placeholder">🎁</div>
        <?php endif; ?>
        <div class="lb-hadiah-nama"><?= esc($h['nama_hadiah']) ?></div>
        <?php if (!empty($h['deskripsi'])): ?>
          <div class="lb-hadiah-desc"><?= esc(mb_strimwidth($h['deskripsi'], 0, 60, '...')) ?></div>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <!-- Podium top 3 -->
  <?php if (count($top3) >= 1): ?>
  <div class="lb-podium-wrap">
    <?php
    // Urutan tampil: 2, 1, 3
    $urutan = [1 => null, 0 => null, 2 => null];
    foreach ([1, 0, 2] as $idx):
      $row = $top3[$idx] ?? null;
      if (!$row) continue;
      $rank    = $idx + 1;
      $nama    = ucwords(strtolower(trim($row['first_name'] . ' ' . ($row['last_name'] ?? ''))));
      $inisial = strtoupper(substr($row['first_name'], 0, 1) . substr($row['last_name'] ?? '', 0, 1));
      $adaFoto = !empty($row['foto_profil']) && file_exists(FCPATH . 'uploads/foto_profil/' . $row['foto_profil']);
      $rankClass = 'rank-' . $rank;
    ?>
      <div class="lb-podium-item">
        <?php if ($rank === 1): ?>
          <div class="lb-mahkota">👑</div>
        <?php else: ?>
          <div style="height:1.75rem"></div>
        <?php endif; ?>

        <div class="lb-podium-avatar-wrap">
          <div class="lb-podium-avatar <?= $rankClass ?>">
            <?php if ($adaFoto): ?>
              <img src="<?= base_url('uploads/foto_profil/' . $row['foto_profil']) ?>" alt="">
            <?php else: ?>
              <?= esc($inisial) ?>
            <?php endif; ?>
          </div>
          <div class="lb-podium-badge <?= $rankClass ?>"><?= $rank ?></div>
        </div>

        <div class="lb-podium-nama"><?= esc(mb_strimwidth($nama, 0, 18, '...')) ?></div>
        <div class="lb-podium-tipe"><?= esc($row['tipe_anggota']) ?></div>
        <div class="lb-podium-poin <?= $rankClass ?>">
          <?= number_format($row['total_points']) ?> poin
        </div>
        <div class="lb-tiang <?= $rankClass ?>">
          <?= $rank ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <!-- Tabel full -->
  <div class="lb-tabel-wrap">
    <div class="lb-tabel-header">
      <div>
        <h2>Semua Peringkat</h2>
        <span style="font-size:.75rem;color:#94a3b8"><?= count($leaderboard) ?> anggota</span>
      </div>
      <form method="get" action="" style="display:flex;align-items:center;gap:8px">
        <span class="lb-filter-label">Periode:</span>
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
    <table class="lb-tabel">
      <thead>
        <tr>
          <th class="center" style="width:60px">#</th>
          <th>Anggota</th>
          <th class="center" style="width:120px">Total Poin</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($leaderboard)): ?>
          <tr>
            <td colspan="3">
              <div class="lb-kosong">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                  <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/>
                  <line x1="6" y1="20" x2="6" y2="14"/>
                </svg>
                Belum ada data leaderboard bulan ini
              </div>
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($leaderboard as $i => $row):
            $rank    = $i + 1;
            $nama    = ucwords(strtolower(trim($row['first_name'] . ' ' . ($row['last_name'] ?? ''))));
            $inisial = strtoupper(substr($row['first_name'], 0, 1) . substr($row['last_name'] ?? '', 0, 1));
            $adaFoto = !empty($row['foto_profil']) && file_exists(FCPATH . 'uploads/foto_profil/' . $row['foto_profil']);
          ?>
            <tr>
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
              <td class="center">
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

</div><!-- /bungkus-utama -->

<?= $this->include('layouts/home_footer') ?>

<script>
// Sync tahun ke select bulan
document.getElementById('selectBulan').addEventListener('change', function() {
  const opt = this.options[this.selectedIndex];
  document.getElementById('tahunInput').value = opt.dataset.tahun;
  this.form.submit();
});

// Hitung mundur
<?php if ($isRealtime): ?>
(function() {
  function tick() {
    const now  = new Date();
    const akhir = new Date(now.getFullYear(), now.getMonth() + 1, 0, 23, 59, 59);
    const sisa  = akhir - now;
    if (sisa <= 0) return;
    const d = Math.floor(sisa / 86400000);
    const h = Math.floor((sisa % 86400000) / 3600000);
    const m = Math.floor((sisa % 3600000)  / 60000);
    const s = Math.floor((sisa % 60000)    / 1000);
    document.getElementById('cdHari').textContent  = String(d).padStart(2,'0');
    document.getElementById('cdJam').textContent   = String(h).padStart(2,'0');
    document.getElementById('cdMenit').textContent = String(m).padStart(2,'0');
    document.getElementById('cdDetik').textContent = String(s).padStart(2,'0');
  }
  tick();
  setInterval(tick, 1000);
})();
<?php endif; ?>
</script>

<?= $this->endSection() ?>