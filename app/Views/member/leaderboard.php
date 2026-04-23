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

<!-- Header + filter bulan -->
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
      <select name="bulan" class="form-select form-select-sm" style="width:auto"
              onchange="this.form.submit()">
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

<!-- Rank saya -->
<?php if ($rankSaya > 0): ?>
<div class="profil-alert" style="margin-bottom:1.25rem;
     background:<?= $rankSaya <= 3 ? '#fefce8' : '#eff4ff' ?>;
     border-color:<?= $rankSaya <= 3 ? '#fde047' : '#c7d7fe' ?>;
     color:<?= $rankSaya <= 3 ? '#854d0e' : '#1e3a8a' ?>">
  <?php if ($rankSaya === 1): ?>🥇
  <?php elseif ($rankSaya === 2): ?>🥈
  <?php elseif ($rankSaya === 3): ?>🥉
  <?php else: ?>🏅
  <?php endif; ?>
  Peringkat kamu bulan ini: <strong>#<?= $rankSaya ?></strong> dari <?= count($leaderboard) ?> anggota.
</div>
<?php endif; ?>

<!-- Tabel full leaderboard -->
<div class="kotak-konten">
  <div class="kepala-kotak">
    <h3>Semua Peringkat</h3>
    <span class="teks-redup-sm"><?= count($leaderboard) ?> anggota</span>
  </div>
  <div class="bungkus-tabel">
    <table class="tabel-admin-member">
      <thead>
        <tr>
          <th style="width:60px" class="teks-center">#</th>
          <th>Anggota</th>
          <th>Tipe</th>
          <th class="teks-center">Poin</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($leaderboard)): ?>
          <tr>
            <td colspan="4">
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
            <tr style="<?= $isMe ? 'background:#eff4ff;font-weight:600' : '' ?>">
              <td class="teks-center">
                <?php if ($rank === 1): ?>
                  <span style="font-size:1.2rem">🥇</span>
                <?php elseif ($rank === 2): ?>
                  <span style="font-size:1.2rem">🥈</span>
                <?php elseif ($rank === 3): ?>
                  <span style="font-size:1.2rem">🥉</span>
                <?php else: ?>
                  <span class="teks-redup-sm"><?= $rank ?></span>
                <?php endif; ?>
              </td>
              <td>
                <div style="display:flex;align-items:center;gap:10px">
                  <!-- Avatar -->
                  <div style="width:34px;height:34px;border-radius:50%;flex-shrink:0;overflow:hidden;
                              background:#e2e8f0;display:flex;align-items:center;justify-content:center;
                              font-size:.72rem;font-weight:700;color:#64748b;
                              border:2px solid <?= $isMe ? '#818cf8' : '#f1f5f9' ?>">
                    <?php if ($adaFoto): ?>
                      <img src="<?= base_url('uploads/foto_profil/' . $row['foto_profil']) ?>"
                           style="width:100%;height:100%;object-fit:cover" alt="">
                    <?php else: ?>
                      <?= esc($inisial) ?>
                    <?php endif; ?>
                  </div>
                  <!-- Info -->
                  <div>
                    <div class="judul-tabel">
                      <?= esc($nama) ?>
                      <?php if ($isMe): ?>
                        <span class="badge-admin biru" style="font-size:.68rem;margin-left:4px">Kamu</span>
                      <?php endif; ?>
                    </div>
                    <div class="penulis-tabel"><?= esc($row['no_identitas']) ?></div>
                  </div>
                </div>
              </td>
              <td>
                <span class="badge-admin" style="background:#f1f5f9;color:#475569;font-size:.75rem">
                  <?= esc($row['tipe_anggota']) ?>
                </span>
              </td>
              <td class="teks-center">
                <span style="font-weight:700;color:<?= $row['total_points'] >= 0 ? '#16a34a' : '#dc2626' ?>">
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