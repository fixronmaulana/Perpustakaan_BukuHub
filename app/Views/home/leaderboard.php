<?= $this->extend('layouts/home_layout') ?>

<?= $this->section('head') ?>
<title>Leaderboard — Perpustakaan SMK Al-Munawwir</title>
<style>
/* ════════════════════════════════════════════
   LEADERBOARD LANDING PAGE
════════════════════════════════════════════ */

/* ── Hitung mundur ── */
.lb-countdown-section {
  text-align: center;
  margin-bottom: 0;
}
.lb-countdown-label {
  font-size: .78rem;
  font-weight: 600;
  color: rgba(255,255,255,.55);
  text-transform: uppercase;
  letter-spacing: 1px;
  margin-bottom: 1rem;
}
.lb-countdown-inner {
  display: inline-flex;
  gap: .75rem;
  align-items: center;
}
.lb-cd-unit {
  background: rgba(255,255,255,.08);
  border: 1px solid rgba(255,255,255,.12);
  border-radius: 12px;
  padding: .60rem 1.10rem;
  min-width: 64px;
  text-align: center;
  backdrop-filter: blur(4px);
}
.lb-cd-num {
  font-size: 1.75rem;
  font-weight: 900;
  color: #fff;
  line-height: 1;
  font-variant-numeric: tabular-nums;
}
.lb-cd-label {
  font-size: .6rem;
  color: rgba(255,255,255,.5);
  text-transform: uppercase;
  letter-spacing: .5px;
  margin-top: 4px;
}
.lb-cd-sep {
  font-size: 1.5rem;
  font-weight: 700;
  color: rgba(255,255,255,.3);
}
.lb-live-pill {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: rgba(22,163,74,.2);
  border: 1px solid rgba(22,163,74,.4);
  color: #4ade80;
  font-size: .72rem;
  font-weight: 600;
  padding: 3px 10px;
  border-radius: 20px;
  margin-top: .75rem;
}
.lb-live-dot {
  width: 6px; height: 6px;
  border-radius: 50%;
  background: #4ade80;
  animation: pulse-dot 1.5s infinite;
}
@keyframes pulse-dot { 0%,100%{opacity:1} 50%{opacity:.25} }

/* ── Podium section ── */
.lb-podium-section {
  background: #fff;
  border-bottom: 1px solid #e2e8f0;
  padding: 2.5rem 1rem 0;
}
.lb-podium-wrap {
  display: flex;
  justify-content: center;
  align-items: flex-end;
  gap: 1.5rem;
  max-width: 560px;
  margin: 0 auto;
}
.lb-podium-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  flex: 1;
  max-width: 160px;
}

/* Avatar */
.lb-podium-avatar-wrap { position: relative; margin-bottom: .6rem; }
.lb-podium-avatar {
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 800;
  color: #fff;
  overflow: hidden;
  border: 3px solid;
  position: relative;
}
.lb-podium-avatar img { width:100%; height:100%; object-fit:cover; }
.lb-podium-avatar.rank-1 {
  width: 76px; height: 76px;
  border-color: #fbbf24;
  background: linear-gradient(135deg,#92400e,#d97706);
  box-shadow: 0 0 0 4px rgba(251,191,36,.2), 0 0 20px rgba(251,191,36,.3);
  animation: glow-gold 2.5s ease-in-out infinite;
}
@keyframes glow-gold {
  0%,100% { box-shadow: 0 0 0 4px rgba(251,191,36,.2), 0 0 20px rgba(251,191,36,.3); }
  50%      { box-shadow: 0 0 0 6px rgba(251,191,36,.35), 0 0 32px rgba(251,191,36,.5); }
}
.lb-podium-avatar.rank-2 {
  width: 62px; height: 62px;
  border-color: #94a3b8;
  background: linear-gradient(135deg,#475569,#94a3b8);
  font-size: .9rem;
}
.lb-podium-avatar.rank-3 {
  width: 58px; height: 58px;
  border-color: #b45309;
  background: linear-gradient(135deg,#78350f,#c2410c);
  font-size: .85rem;
}
.lb-podium-avatar.rank-1 { font-size: 1rem; }

.lb-podium-badge {
  position: absolute;
  bottom: -3px; right: -3px;
  width: 20px; height: 20px;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: .6rem; font-weight: 800;
  border: 2px solid #fff;
}
.lb-podium-badge.rank-1 { background: #d97706; color: #fff; }
.lb-podium-badge.rank-2 { background: #64748b; color: #fff; }
.lb-podium-badge.rank-3 { background: #b45309; color: #fff; }

.lb-mahkota { font-size: 1.4rem; margin-bottom: .2rem; line-height: 1; }
.lb-spacer   { height: 1.6rem; }

.lb-podium-nama {
  font-size: .8rem; font-weight: 700; color: #1e293b;
  text-align: center; margin-bottom: 1px;
  max-width: 130px; overflow: hidden;
  text-overflow: ellipsis; white-space: nowrap;
}
.lb-podium-nama.rank-1 { font-size: .88rem; }
.lb-podium-tipe  { font-size: .67rem; color: #94a3b8; margin-bottom: .3rem; }
.lb-podium-poin  { font-size: .82rem; font-weight: 700; margin-bottom: .6rem; }
.lb-podium-poin.rank-1 { font-size: .9rem; color: #d97706; }
.lb-podium-poin.rank-2 { color: #64748b; }
.lb-podium-poin.rank-3 { color: #b45309; }

/* Tombol hadiah — di bawah poin, di atas tiang */
.lb-btn-hadiah {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  margin-bottom: .6rem;
  padding: 4px 11px;
  border-radius: 20px;
  border: 1.5px solid;
  font-size: .67rem;
  font-weight: 600;
  cursor: pointer;
  background: transparent;
  transition: all .15s;
  letter-spacing: .2px;
}
.lb-btn-hadiah.rank-1 { border-color: #fbbf24; color: #d97706; }
.lb-btn-hadiah.rank-2 { border-color: #94a3b8; color: #64748b; }
.lb-btn-hadiah.rank-3 { border-color: #fb923c; color: #b45309; }
.lb-btn-hadiah:hover { transform: scale(1.05); }
.lb-btn-hadiah.rank-1:hover { background: #fef9c3; }
.lb-btn-hadiah.rank-2:hover { background: #f1f5f9; }
.lb-btn-hadiah.rank-3:hover { background: #ffedd5; }

/* Tiang podium */
.lb-tiang {
  width: 100%;
  border-radius: 8px 8px 0 0;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1rem;
  font-weight: 900;
  color: rgba(255,255,255,.8);
  box-shadow: 0 -4px 12px rgba(0,0,0,.1);
}
.lb-tiang.rank-1 { height: 110px; background: linear-gradient(180deg,#fde68a,#f59e0b,#d97706); }
.lb-tiang.rank-2 { height: 80px;  background: linear-gradient(180deg,#e2e8f0,#94a3b8,#64748b); }
.lb-tiang.rank-3 { height: 60px;  background: linear-gradient(180deg,#fed7aa,#fb923c,#c2410c); }

/* ── Filter + tabel ── */
.lb-tabel-section {
  padding: 2rem 6rem 3rem;
  background: #f8fafc;
}
.lb-tabel-toolbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
  gap: 1rem;
  flex-wrap: wrap;
}
.lb-search-wrap {
  position: relative;
  flex: 1;
  max-width: 280px;
}
.lb-search-wrap svg {
  position: absolute;
  left: 10px; top: 50%;
  transform: translateY(-50%);
  width: 15px; height: 15px;
  stroke: #94a3b8;
}
.lb-search-input {
  width: 100%;
  padding: 7px 12px 7px 32px;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  font-size: .82rem;
  background: #fff;
  color: #334155;
  outline: none;
}
.lb-search-input:focus { border-color: #94a3b8; }

.lb-filter-right { display: flex; align-items: center; gap: 8px; }
.lb-filter-label { font-size: .8rem; color: #64748b; font-weight: 500; }
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

/* Tabel */
.lb-tabel-wrap {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 14px;
  overflow: hidden;
  margin-bottom: 1rem;
}
.lb-tabel-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 1.25rem;
  border-bottom: 1px solid #f1f5f9;
}
.lb-tabel-header h2 { font-size: .92rem; font-weight: 700; color: #0f172a; margin: 0; }
.lb-tabel-header span { font-size: .75rem; color: #94a3b8; }
.lb-tabel { width: 100%; border-collapse: collapse; }
.lb-tabel thead th {
  padding: .65rem 1rem;
  font-size: .7rem;
  font-weight: 600;
  color: #94a3b8;
  text-transform: uppercase;
  letter-spacing: .5px;
  background: #f8fafc;
  border-bottom: 1px solid #f1f5f9;
  text-align: left;
}
.lb-tabel thead th.center { text-align: center; }
.lb-tabel tbody tr { border-bottom: 1px solid #f8fafc; transition: background .1s; }
.lb-tabel tbody tr:last-child { border-bottom: none; }
.lb-tabel tbody tr:hover { background: #f8fafc; }
.lb-tabel tbody tr.row-top-1 { background: #fffbeb; }
.lb-tabel tbody tr.row-top-2 { background: #f8fafc; }
.lb-tabel tbody tr.row-top-3 { background: #fff7ed; }
.lb-tabel tbody tr.row-top-1:hover { background: #fef9c3; }
.lb-tabel tbody tr.row-top-2:hover { background: #f1f5f9; }
.lb-tabel tbody tr.row-top-3:hover { background: #ffedd5; }
.lb-tabel tbody td { padding: .7rem 1rem; font-size: .82rem; color: #1e293b; vertical-align: middle; }
.lb-tabel tbody td.center { text-align: center; }

.lb-rank-badge {
  display: inline-flex;
  align-items: center; justify-content: center;
  width: 28px; height: 28px;
  border-radius: 7px;
  font-size: .72rem; font-weight: 800;
}
.lb-rank-badge.gold   { background: #1c1917; color: #fbbf24; }
.lb-rank-badge.silver { background: #334155; color: #cbd5e1; }
.lb-rank-badge.bronze { background: #431407; color: #fb923c; }
.lb-rank-num { font-size: .8rem; color: #94a3b8; font-weight: 500; }

.lb-member-cell { display: flex; align-items: center; gap: 10px; }
.lb-avatar-sm {
  width: 36px; height: 36px;
  border-radius: 50%;
  background: #e2e8f0;
  display: flex; align-items: center; justify-content: center;
  font-size: .72rem; font-weight: 700; color: #64748b;
  overflow: hidden; flex-shrink: 0;
  border: 2px solid #f1f5f9;
}
.lb-avatar-sm img { width:100%; height:100%; object-fit:cover; }
.lb-member-nama { font-size: .83rem; font-weight: 600; color: #1e293b; }
.lb-member-sub  { font-size: .71rem; color: #94a3b8; margin-top: 1px; }
.lb-tipe-badge  {
  display: inline-block; font-size: .65rem;
  padding: 1px 6px; border-radius: 4px;
  background: #f1f5f9; color: #475569;
  font-weight: 500; margin-left: 4px;
}
.lb-poin-val { font-size: .88rem; font-weight: 700; }
.lb-poin-val.pos { color: #16a34a; }
.lb-poin-val.neg { color: #dc2626; }

/* Kosong / no-result */
.lb-kosong { text-align: center; padding: 3rem 1rem; color: #94a3b8; font-size: .85rem; }
.lb-kosong svg { width:40px; height:40px; stroke:#cbd5e1; margin: 0 auto .75rem; display:block; }

/* CTA login */
.lb-cta {
  text-align: center;
  padding: 1.5rem 1rem;
  background: linear-gradient(135deg, #eff4ff, #e0e7ff);
  border-top: 1px solid #c7d7fe;
  font-size: .85rem;
  color: #3730a3;
}
.lb-cta a {
  font-weight: 700;
  color: #1e3a8a;
  text-decoration: underline;
  margin-left: 4px;
}

/* ── Modal hadiah ── */
.lb-modal-overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.55);
  backdrop-filter: blur(4px);
  z-index: 9999;
  align-items: center;
  justify-content: center;
  padding: 1rem;
}
.lb-modal-overlay.tampil { display: flex; }
@keyframes lb-modal-in {
  from { transform: scale(.8) translateY(20px); opacity: 0; }
  to   { transform: scale(1) translateY(0);     opacity: 1; }
}
.lb-modal {
  background: #fff;
  border-radius: 20px;
  max-width: 360px;
  width: 100%;
  overflow: hidden;
  box-shadow: 0 24px 64px rgba(0,0,0,.25);
  animation: lb-modal-in .3s cubic-bezier(.34,1.56,.64,1);
}
.lb-modal-header {
  padding: 1.75rem 1.5rem 1.5rem;
  text-align: center;
  position: relative;
}
.lb-modal-header.rank-1 { background: linear-gradient(135deg,#78350f,#d97706,#fbbf24); }
.lb-modal-header.rank-2 { background: linear-gradient(135deg,#1e293b,#475569,#94a3b8); }
.lb-modal-header.rank-3 { background: linear-gradient(135deg,#431407,#b45309,#fb923c); }
.lb-modal-close {
  position: absolute; top: 12px; right: 14px;
  background: rgba(255,255,255,.2); border: none; color: #fff;
  width: 28px; height: 28px; border-radius: 50%;
  cursor: pointer; font-size: .9rem;
  display: flex; align-items: center; justify-content: center;
  transition: background .15s;
}
.lb-modal-close:hover { background: rgba(255,255,255,.35); }
.lb-modal-emoji       { font-size: 2rem; margin-bottom: .25rem; line-height: 1; }
.lb-modal-rank-label  { font-size: .68rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase; color: rgba(255,255,255,.7); margin-bottom: .35rem; }
.lb-modal-rank-title  { font-size: 1.1rem; font-weight: 800; color: #fff; }

.lb-modal-body { padding: 1.5rem; }
.lb-modal-foto-wrap { text-align: center; margin-bottom: 1.25rem; }
.lb-modal-foto {
  width: 100px; height: 100px;
  border-radius: 16px;
  object-fit: cover;
  border: 3px solid #e2e8f0;
  box-shadow: 0 8px 24px rgba(0,0,0,.1);
}
.lb-modal-foto-placeholder {
  width: 100px; height: 100px;
  border-radius: 16px;
  background: #f1f5f9;
  display: inline-flex; align-items: center; justify-content: center;
  font-size: 2.75rem;
  border: 3px solid #e2e8f0;
}
.lb-modal-nama { font-size: 1.05rem; font-weight: 800; color: #0f172a; text-align: center; margin-bottom: .5rem; }
.lb-modal-desc { font-size: .82rem; color: #64748b; text-align: center; line-height: 1.65; margin-bottom: 1.25rem; }
.lb-modal-info {
  display: flex; align-items: center; justify-content: center; gap: 6px;
  background: #f8fafc; border: 1px solid #e2e8f0;
  border-radius: 10px; padding: .65rem 1rem;
  font-size: .75rem; color: #475569;
}
.lb-modal-btn-tutup {
  display: block; width: 100%; margin-top: .875rem;
  padding: .65rem; border-radius: 10px;
  border: 1px solid #e2e8f0; background: #f8fafc;
  color: #475569; font-size: .85rem; font-weight: 600;
  cursor: pointer; transition: background .15s;
}
.lb-modal-btn-tutup:hover { background: #e2e8f0; }

/* ── Responsif mobile ── */
@media (max-width: 640px) {
  .lb-tabel-section { padding: 1.5rem 1rem 2rem; }
  .lb-tabel-wrap { border-radius: 10px; }
  .lb-podium-wrap { gap: .5rem; }
  .lb-podium-item { max-width: 100px; }
  .lb-podium-avatar.rank-1 { width: 58px; height: 58px; }
  .lb-podium-avatar.rank-2 { width: 50px; height: 50px; }
  .lb-podium-avatar.rank-3 { width: 46px; height: 46px; }
  .lb-tiang.rank-1 { height: 80px; }
  .lb-tiang.rank-2 { height: 60px; }
  .lb-tiang.rank-3 { height: 44px; }
  .lb-countdown-inner { gap: .4rem; }
  .lb-cd-unit { min-width: 52px; padding: .6rem .75rem; }
  .lb-cd-num { font-size: 1.4rem; }
  .lb-search-wrap { max-width: 100%; }
}
</style>
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
      $rank     = $idx + 1;
      $nama     = ucwords(strtolower(trim($row['first_name'] . ' ' . ($row['last_name'] ?? ''))));
      $inisial  = strtoupper(substr($row['first_name'], 0, 1) . substr($row['last_name'] ?? '', 0, 1));
      $adaFoto  = !empty($row['foto_profil']) && file_exists(FCPATH . 'uploads/foto_profil/' . $row['foto_profil']);
      $rc       = 'rank-' . $rank;
      $h        = $hadiah[$rank] ?? null;
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

        <!-- Tombol hadiah di bawah poin, di atas tiang -->
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

  <!-- Toolbar: search + filter bulan -->
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

  <!-- Tabel -->
  <div class="lb-tabel-wrap">
    <div class="lb-tabel-header">
      <h2>Semua Peringkat</h2>
      <span id="jumlahAnggota"><?= count($leaderboard) ?> anggota</span>
    </div>
    <table class="lb-tabel">
      <thead>
        <tr>
          <th class="center" style="width:60px">#</th>
          <th>Anggota</th>
          <th class="center" style="width:130px">Total Poin</th>
        </tr>
      </thead>
      <tbody id="tabelBody">
        <?php if (empty($leaderboard)): ?>
          <tr>
            <td colspan="3">
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
            $rank    = $i + 1;
            $nama    = ucwords(strtolower(trim($row['first_name'] . ' ' . ($row['last_name'] ?? ''))));
            $inisial = strtoupper(substr($row['first_name'], 0, 1) . substr($row['last_name'] ?? '', 0, 1));
            $adaFoto = !empty($row['foto_profil']) && file_exists(FCPATH . 'uploads/foto_profil/' . $row['foto_profil']);
            $rowClass = $rank === 1 ? 'row-top-1' : ($rank === 2 ? 'row-top-2' : ($rank === 3 ? 'row-top-3' : ''));
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

  <!-- CTA login -->
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
      <div class="lb-modal-info">
        📅 Diberikan kepada pemenang di akhir periode bulan ini
      </div>
      <button class="lb-modal-btn-tutup" onclick="tutupModalHadiah(<?= $r ?>)">
        Tutup
      </button>
    </div>
  </div>
</div>
<?php endforeach; ?>

<?= $this->include('layouts/home_footer') ?>

<script>
// Filter member by nama
function filterMember() {
  const q     = document.getElementById('cariMember').value.toLowerCase();
  const rows  = document.querySelectorAll('#tabelBody tr[data-nama]');
  let visible = 0;
  rows.forEach(tr => {
    const match = tr.dataset.nama.includes(q);
    tr.style.display = match ? '' : 'none';
    if (match) visible++;
  });
  document.getElementById('jumlahAnggota').textContent = visible + ' anggota';
}

// Sync tahun ke select bulan
document.getElementById('selectBulan').addEventListener('change', function() {
  const opt = this.options[this.selectedIndex];
  document.getElementById('tahunInput').value = opt.dataset.tahun;
  this.form.submit();
});

// Modal hadiah
function bukaModalHadiah(rank) {
  const modal = document.getElementById('modalHadiah' + rank);
  if (modal) modal.classList.add('tampil');
}
function tutupModalHadiah(rank) {
  const modal = document.getElementById('modalHadiah' + rank);
  if (modal) modal.classList.remove('tampil');
}
document.addEventListener('click', function(e) {
  if (e.target.classList.contains('lb-modal-overlay')) {
    e.target.classList.remove('tampil');
  }
});
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    document.querySelectorAll('.lb-modal-overlay.tampil')
      .forEach(m => m.classList.remove('tampil'));
  }
});

// Hitung mundur
<?php if ($isRealtime): ?>
(function() {
  function tick() {
    const now   = new Date();
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