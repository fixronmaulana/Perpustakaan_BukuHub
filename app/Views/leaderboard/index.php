<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Leaderboard — Admin</title>
<?= $this->endSection() ?>

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

<!-- Header -->
<div class="card mb-3">
  <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div class="d-flex align-items-center gap-2">
      <h5 class="fw-semibold mb-0">Leaderboard</h5>
      <span class="badge bg-primary"><?= $bulanLabel ?></span>
      <?php if ($isRealtime): ?>
        <span class="badge bg-success">Live</span>
      <?php endif; ?>
    </div>
    <form method="get" action="" class="d-flex align-items-center gap-2">
      <label class="text-muted small mb-0">Periode:</label>
      <select name="bulan" class="form-select form-select-sm" style="width:auto" id="selectBulan">
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
<div class="card">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
      <h5 class="card-title fw-semibold mb-0">Semua Peringkat</h5>
      <div class="d-flex align-items-center gap-3">
        <!-- Search -->
        <div class="input-group input-group-sm" style="width:220px">
          <span class="input-group-text bg-white border-end-0">
            <i class="ti ti-search" style="font-size:.85rem;color:#94a3b8"></i>
          </span>
          <input type="text" id="cariMember" class="form-control border-start-0 ps-0"
                 placeholder="Cari nama anggota..." oninput="filterDanPaginasi()">
        </div>
        <span class="text-muted small" id="jumlahAnggota"><?= count($leaderboard) ?> anggota</span>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0" style="min-width:800px">
        <thead class="table-light">
          <tr>
            <th style="width:56px" class="text-center">#</th>
            <th>Anggota</th>
            <th class="text-center border-start" title="Poin dari kunjungan">
              <span class="d-block" style="font-size:.65rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:.4px">Kunjungan</span>
            </th>
            <th class="text-center" title="Poin dari peminjaman buku">
              <span class="d-block" style="font-size:.65rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:.4px">Peminjaman</span>
            </th>
            <th class="text-center" title="Poin pengembalian tepat waktu">
              <span class="d-block" style="font-size:.65rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:.4px">Tepat Waktu</span>
            </th>
            <th class="text-center" title="Pengurangan poin terlambat">
              <span class="d-block" style="font-size:.65rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:.4px">Terlambat</span>
            </th>
            <th class="text-center" title="Poin dari kuis buku">
              <span class="d-block" style="font-size:.65rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:.4px">Kuis</span>
            </th>
            <th class="text-center border-start" style="width:110px">
              <span class="d-block" style="font-size:.65rem;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:.4px">Total Poin</span>
            </th>
          </tr>
        </thead>
        <tbody id="tabelBody">
          <?php if (empty($leaderboard)): ?>
            <tr>
              <td colspan="8" class="text-center py-5 text-muted">
                <i class="ti ti-chart-bar" style="font-size:2.5rem;display:block;margin-bottom:.5rem;opacity:.3"></i>
                Belum ada data leaderboard bulan ini
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($leaderboard as $i => $row):
              $rank           = $i + 1;
              $nama           = ucwords(strtolower(trim($row['first_name'] . ' ' . ($row['last_name'] ?? ''))));
              $inisial        = strtoupper(substr($row['first_name'], 0, 1) . substr($row['last_name'] ?? '', 0, 1));
              $adaFoto        = !empty($row['foto_profil']) && file_exists(FCPATH . 'uploads/foto_profil/' . $row['foto_profil']);
              $poinKunjungan  = (int) ($row['poin_kunjungan']  ?? 0);
              $poinPeminjaman = (int) ($row['poin_peminjaman'] ?? 0);
              $poinTepat      = (int) ($row['poin_tepat']      ?? 0);
              $poinTerlambat  = (int) ($row['poin_terlambat']  ?? 0);
              $poinKuis       = (int) ($row['poin_kuis']       ?? 0);
              $bgBaris = '';
              if ($rank === 1) $bgBaris = 'background:#fffbeb';
              elseif ($rank === 2) $bgBaris = 'background:#f8fafc';
              elseif ($rank === 3) $bgBaris = 'background:#fff7ed';
            ?>
              <tr style="<?= $bgBaris ?>" data-nama="<?= strtolower($nama) ?>">
                <!-- Rank -->
                <td class="text-center">
                  <?php if ($rank === 1): ?>
                    <span style="font-size:1.15rem">🥇</span>
                  <?php elseif ($rank === 2): ?>
                    <span style="font-size:1.15rem">🥈</span>
                  <?php elseif ($rank === 3): ?>
                    <span style="font-size:1.15rem">🥉</span>
                  <?php else: ?>
                    <span class="text-muted small"><?= $rank ?></span>
                  <?php endif; ?>
                </td>

                <!-- Anggota -->
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <div style="width:34px;height:34px;border-radius:50%;flex-shrink:0;overflow:hidden;
                                background:#e2e8f0;display:flex;align-items:center;justify-content:center;
                                font-size:.7rem;font-weight:700;color:#64748b;border:2px solid #f1f5f9">
                      <?php if ($adaFoto): ?>
                        <img src="<?= base_url('uploads/foto_profil/' . $row['foto_profil']) ?>"
                             style="width:100%;height:100%;object-fit:cover" alt="">
                      <?php else: ?>
                        <?= esc($inisial) ?>
                      <?php endif; ?>
                    </div>
                    <div>
                      <div class="fw-semibold small"><?= esc($nama) ?></div>
                      <div class="text-muted" style="font-size:.72rem">
                        <?= esc($row['no_identitas']) ?>
                        <span class="badge bg-light text-secondary border ms-1" style="font-size:.6rem">
                          <?= esc($row['tipe_anggota']) ?>
                        </span>
                      </div>
                    </div>
                  </div>
                </td>

                <!-- Kunjungan -->
                <td class="text-center border-start">
                  <?php if ($poinKunjungan > 0): ?>
                    <span class="badge" style="background:#f0fdf4;color:#16a34a;font-size:.72rem;font-weight:600">
                      +<?= $poinKunjungan ?>
                    </span>
                  <?php else: ?>
                    <span class="text-muted" style="font-size:.8rem">—</span>
                  <?php endif; ?>
                </td>

                <!-- Peminjaman -->
                <td class="text-center">
                  <?php if ($poinPeminjaman > 0): ?>
                    <span class="badge" style="background:#f0fdf4;color:#16a34a;font-size:.72rem;font-weight:600">
                      +<?= $poinPeminjaman ?>
                    </span>
                  <?php else: ?>
                    <span class="text-muted" style="font-size:.8rem">—</span>
                  <?php endif; ?>
                </td>

                <!-- Tepat Waktu -->
                <td class="text-center">
                  <?php if ($poinTepat > 0): ?>
                    <span class="badge" style="background:#f0fdf4;color:#16a34a;font-size:.72rem;font-weight:600">
                      +<?= $poinTepat ?>
                    </span>
                  <?php else: ?>
                    <span class="text-muted" style="font-size:.8rem">—</span>
                  <?php endif; ?>
                </td>

                <!-- Terlambat -->
                <td class="text-center">
                  <?php if ($poinTerlambat < 0): ?>
                    <span class="badge" style="background:#fef2f2;color:#dc2626;font-size:.72rem;font-weight:600">
                      <?= $poinTerlambat ?>
                    </span>
                  <?php else: ?>
                    <span class="text-muted" style="font-size:.8rem">—</span>
                  <?php endif; ?>
                </td>

                <!-- Kuis -->
                <td class="text-center">
                  <?php if ($poinKuis > 0): ?>
                    <span class="badge" style="background:#f0fdf4;color:#16a34a;font-size:.72rem;font-weight:600">
                      +<?= $poinKuis ?>
                    </span>
                  <?php else: ?>
                    <span class="text-muted" style="font-size:.8rem">—</span>
                  <?php endif; ?>
                </td>

                <!-- Total -->
                <td class="text-center border-start">
                  <span class="fw-bold" style="color:<?= $row['total_points'] >= 0 ? '#16a34a' : '#dc2626' ?>;font-size:.9rem">
                    <?= ($row['total_points'] >= 0 ? '+' : '') . number_format($row['total_points']) ?>
                  </span>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Pagination Bootstrap -->
    <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2" id="pagerArea" style="display:none!important">
      <div class="text-muted small" id="pagerInfo"></div>
      <nav>
        <ul class="pagination pagination-sm mb-0" id="pagerList"></ul>
      </nav>
    </div>

  </div>
</div>

<script>
/* ═══════════════════════════════════════════════════════════
   LEADERBOARD ADMIN — Client-side Pagination + Search
═══════════════════════════════════════════════════════════ */
(function () {
  const PER_PAGE   = 20;
  let currentPage  = 1;
  let filteredRows = [];

  const semuaBaris = Array.from(
    document.querySelectorAll('#tabelBody tr[data-nama]')
  );

  // Sembunyikan semua baris dulu
  semuaBaris.forEach(tr => tr.style.display = 'none');

  /* ── Filter ── */
  function filter(keyword) {
    const q  = keyword.trim().toLowerCase();
    filteredRows = q
      ? semuaBaris.filter(tr => tr.dataset.nama.includes(q))
      : [...semuaBaris];
  }

  /* ── Render halaman ── */
  function render(page) {
    currentPage = page;
    const total     = filteredRows.length;
    const totalPage = Math.ceil(total / PER_PAGE) || 1;

    if (currentPage < 1)         currentPage = 1;
    if (currentPage > totalPage) currentPage = totalPage;

    const start = (currentPage - 1) * PER_PAGE;
    const end   = Math.min(start + PER_PAGE, total);

    semuaBaris.forEach(tr => tr.style.display = 'none');
    filteredRows.slice(start, end).forEach(tr => tr.style.display = '');

    // Counter anggota
    document.getElementById('jumlahAnggota').textContent = total + ' anggota';

    // Info halaman
    const infoEl = document.getElementById('pagerInfo');
    infoEl.textContent = total > 0
      ? 'Menampilkan ' + (start + 1) + '–' + end + ' dari ' + total + ' anggota'
      : '';

    // Pagination
    renderPager(currentPage, totalPage, total);
  }

  /* ── Render tombol pagination Bootstrap ── */
  function renderPager(page, totalPage, total) {
    const area = document.getElementById('pagerArea');
    const list = document.getElementById('pagerList');

    if (totalPage <= 1) {
      area.style.setProperty('display', 'none', 'important');
      return;
    }
    area.style.removeProperty('display');
    area.style.display = 'flex';

    // Algoritma halaman dengan ellipsis
    function pages(cur, tot) {
      const delta = 2;
      const range = [];
      const result = [];
      let l;
      for (let i = 1; i <= tot; i++) {
        if (i === 1 || i === tot || (i >= cur - delta && i <= cur + delta)) {
          range.push(i);
        }
      }
      for (const i of range) {
        if (l) {
          if (i - l === 2) result.push(l + 1);
          else if (i - l !== 1) result.push('...');
        }
        result.push(i);
        l = i;
      }
      return result;
    }

    const pageNums = pages(page, totalPage);
    let html = '';

    // Prev
    html += `<li class="page-item ${page === 1 ? 'disabled' : ''}">
      <a class="page-link" href="#" onclick="lbGoPage(${page - 1});return false;">‹</a>
    </li>`;

    // Nomor
    for (const p of pageNums) {
      if (p === '...') {
        html += `<li class="page-item disabled"><span class="page-link">…</span></li>`;
      } else if (p === page) {
        html += `<li class="page-item active"><span class="page-link">${p}</span></li>`;
      } else {
        html += `<li class="page-item">
          <a class="page-link" href="#" onclick="lbGoPage(${p});return false;">${p}</a>
        </li>`;
      }
    }

    // Next
    html += `<li class="page-item ${page === totalPage ? 'disabled' : ''}">
      <a class="page-link" href="#" onclick="lbGoPage(${page + 1});return false;">›</a>
    </li>`;

    list.innerHTML = html;
  }

  /* ── Public: pindah halaman ── */
  window.lbGoPage = function (page) {
    render(page);
    document.querySelector('.card').scrollIntoView({ behavior: 'smooth', block: 'start' });
  };

  /* ── Public: dipanggil saat input search ── */
  window.filterDanPaginasi = function () {
    const q = document.getElementById('cariMember').value;
    filter(q);
    render(1);
  };

  // Init
  filter('');
  render(1);
})();

/* ── Select bulan ── */
document.getElementById('selectBulan').addEventListener('change', function () {
  document.getElementById('tahunInput').value =
    this.options[this.selectedIndex].dataset.tahun;
  this.form.submit();
});
</script>

<?= $this->endSection() ?>