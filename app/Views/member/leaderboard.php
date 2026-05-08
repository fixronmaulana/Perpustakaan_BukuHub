<?= $this->extend('layouts/member_layout') ?>

<?= $this->section('head') ?>
<title>Leaderboard — Portal Anggota</title>
<?= $this->endSection() ?>

<?= $this->section('pageTitle') ?>Leaderboard<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
$namaBulan = [
    1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',
    5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',
    9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
];
$bulanLabel = ($namaBulan[$bulan] ?? $bulan) . ' ' . $tahun;
$isRealtime = ($bulan === $bulanIni && $tahun === $tahunIni);
?>

<div class="kotak-konten" style="margin-bottom:1.25rem">
  <div class="kepala-kotak">
    <h3>
      Leaderboard
      <span class="badge-admin biru" style="font-size:.75rem;margin-left:6px">
        <?= $bulanLabel ?>
      </span>
      <?php if ($isRealtime): ?>
        <span class="badge-admin hijau" style="font-size:.72rem;margin-left:4px">Live</span>
      <?php endif; ?>
    </h3>
    <form method="get" action="" style="display:flex;gap:8px;align-items:center">
      <select name="bulan" class="form-select form-select-sm" style="width:auto">
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

<?php if ($rankSaya > 0): ?>
<div class="kotak-konten" style="margin-bottom:1.25rem">
  <div style="padding:1rem 1.25rem;display:flex;align-items:center;gap:1rem;flex-wrap:wrap">
    <div style="width:42px;height:42px;border-radius:50%;background:#eff4ff;
                display:flex;align-items:center;justify-content:center;flex-shrink:0">
      <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="#4f46e5"
           stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="18" y1="20" x2="18" y2="10"/>
        <line x1="12" y1="20" x2="12" y2="4"/>
        <line x1="6"  y1="20" x2="6"  y2="14"/>
      </svg>
    </div>
    <div style="flex:1; min-width:200px">
      <div style="font-size:.82rem;font-weight:700;color:#1a2340">Peringkat Kamu Bulan Ini</div>
      <div style="font-size:.75rem;color:#6b7a9d">Berdasarkan total poin yang dikumpulkan</div>
    </div>
    <div style="text-align:right">
      <div style="font-size:1.5rem;font-weight:800;color:#4f46e5">#<?= $rankSaya ?></div>
      <div style="font-size:.72rem;color:#6b7a9d">dari <?= count($leaderboard) ?> anggota</div>
    </div>
  </div>
</div>
<?php endif; ?>

<div class="kotak-konten">
  <div class="kepala-kotak">
    <h3>Semua Peringkat</h3>
    <span class="teks-redup-sm"><?= count($leaderboard) ?> anggota</span>
  </div>

  <div class="table-responsive-custom">
    <table class="table table-hover mb-0" id="tabel-leaderboard">
      <thead>
        <tr>
          <th style="width:60px" class="text-center">#</th>
          <th>Anggota</th>
          <th class="text-center">Kunjungan</th>
          <th class="text-center">Pinjam</th>
          <th class="text-center">Tepat</th>
          <th class="text-center">Telat</th>
          <th class="text-center">Kuis</th>
          <th class="text-center">Total</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($leaderboard)): ?>
          <tr>
            <td colspan="8">
              <div class="kondisi-kosong">
                <svg viewBox="0 0 24 24"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                <p>Belum ada data leaderboard bulan ini</p>
              </div>
            </td>
          </tr>
        <?php else: ?>
          <?php foreach ($leaderboard as $i => $row):
            $rank    = $i + 1;
            $isMe    = ($row['member_id'] == $member['id']);
            $nama    = ucwords(strtolower(trim($row['first_name'] . ' ' . ($row['last_name'] ?? ''))));
            $inisial = strtoupper(substr($row['first_name'], 0, 1) . substr($row['last_name'] ?? '', 0, 1));
            $adaFoto = !empty($row['foto_profil']) && file_exists(FCPATH . 'uploads/foto_profil/' . $row['foto_profil']);
          ?>
            <tr style="<?= $isMe ? 'background:#f8faff !important' : '' ?>">
              <td class="text-center">
                <?php if ($rank === 1): ?>
                  <span style="font-size:1.2rem">🥇</span>
                <?php elseif ($rank === 2): ?>
                  <span style="font-size:1.2rem">🥈</span>
                <?php elseif ($rank === 3): ?>
                  <span style="font-size:1.2rem">🥉</span>
                <?php else: ?>
                  <span class="teks-redup-sm" style="font-weight:600"><?= $rank ?></span>
                <?php endif; ?>
              </td>
              <td>
                <div style="display:flex;align-items:center;gap:10px">
                  <div style="width:36px;height:36px;border-radius:50%;flex-shrink:0;overflow:hidden;
                              background:#e2e8f0;display:flex;align-items:center;justify-content:center;
                              font-size:.75rem;font-weight:700;color:#64748b;
                              border:2px solid <?= $isMe ? '#818cf8' : '#f1f5f9' ?>">
                    <?php if ($adaFoto): ?>
                      <img src="<?= base_url('uploads/foto_profil/' . $row['foto_profil']) ?>"
                           style="width:100%;height:100%;object-fit:cover" alt="">
                    <?php else: ?>
                      <?= esc($inisial) ?>
                    <?php endif; ?>
                  </div>
                  <div>
                    <div class="judul-tabel">
                      <?= esc($nama) ?>
                      <?php if ($isMe): ?>
                        <span class="badge-admin biru" style="font-size:.65rem;margin-left:4px">Kamu</span>
                      <?php endif; ?>
                    </div>
                    <div class="penulis-tabel">
                      <?= esc($row['no_identitas']) ?> • <?= esc($row['tipe_anggota']) ?>
                    </div>
                  </div>
                </div>
              </td>
              <td class="text-center">
                <span class="lb-chip <?= ($row['poin_kunjungan'] > 0) ? 'pos' : '' ?>"><?= ($row['poin_kunjungan'] > 0) ? '+'.$row['poin_kunjungan'] : '—' ?></span>
              </td>
              <td class="text-center">
                <span class="lb-chip <?= ($row['poin_peminjaman'] > 0) ? 'pos' : '' ?>"><?= ($row['poin_peminjaman'] > 0) ? '+'.$row['poin_peminjaman'] : '—' ?></span>
              </td>
              <td class="text-center">
                <span class="lb-chip <?= ($row['poin_tepat'] > 0) ? 'pos' : '' ?>"><?= ($row['poin_tepat'] > 0) ? '+'.$row['poin_tepat'] : '—' ?></span>
              </td>
              <td class="text-center">
                <span class="lb-chip <?= ($row['poin_terlambat'] < 0) ? 'neg' : '' ?>"><?= ($row['poin_terlambat'] < 0) ? $row['poin_terlambat'] : '—' ?></span>
              </td>
              <td class="text-center">
                <span class="lb-chip <?= ($row['poin_kuis'] > 0) ? 'pos' : '' ?>"><?= ($row['poin_kuis'] > 0) ? '+'.$row['poin_kuis'] : '—' ?></span>
              </td>
              <td class="text-center">
                <span style="font-weight:700;font-size:.9rem;color:<?= $row['total_points'] >= 0 ? '#16a34a' : '#dc2626' ?>">
                  <?= ($row['total_points'] >= 0 ? '+' : '') . $row['total_points'] ?>
                </span>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
document.querySelector('select[name="bulan"]').addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    document.getElementById('tahunInput').value = opt.dataset.tahun;
    this.form.submit();
});
</script>

<?= $this->endSection() ?>