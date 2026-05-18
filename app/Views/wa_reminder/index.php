<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>WA Reminder</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php if (session()->getFlashdata('msg')) : ?>
  <div class="pb-2">
    <div class="alert <?= (session()->getFlashdata('error') ?? false) ? 'alert-danger' : 'alert-success' ?> alert-dismissible fade show" role="alert">
      <?= session()->getFlashdata('msg') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  </div>
<?php endif; ?>

<?php if (session()->getFlashdata('logs')) : ?>
  <div class="pb-2">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title fw-semibold mb-3">Log Pengiriman</h5>
        <div style="max-height:240px; overflow-y:auto;">
          <table class="table table-hover table-striped">
            <thead class="table-light">
              <tr>
                <th>Status</th>
                <th>Nama</th>
                <th>Nomor WA</th>
                <th>Buku</th>
                <th>Keterangan</th>
              </tr>
            </thead>
            <tbody class="table-group-divider">
              <?php foreach (session()->getFlashdata('logs') as $log) : ?>
                <tr>
                  <td>
                    <?php if ($log['status'] === 'sent') : ?>
                      <span class="badge bg-success rounded-3 fw-semibold">Terkirim</span>
                    <?php elseif ($log['status'] === 'failed') : ?>
                      <span class="badge bg-danger rounded-3 fw-semibold">Gagal</span>
                    <?php elseif ($log['status'] === 'skipped') : ?>
                      <span class="badge bg-warning rounded-3 fw-semibold">Dilewati</span>
                    <?php else : ?>
                      <span class="badge bg-secondary rounded-3 fw-semibold">Info</span>
                    <?php endif; ?>
                  </td>
                  <td><?= esc($log['nama'] ?? '-') ?></td>
                  <td><?= esc($log['phone'] ?? '-') ?></td>
                  <td><?= esc($log['buku'] ?? '-') ?></td>
                  <td class="text-muted"><?= esc($log['reason'] ?? '') ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>

<!-- Status Fonnte -->
<div class="card mb-3">
  <div class="card-body">
    <div class="row align-items-center">
      <div class="col-12 col-lg-6 mb-2 mb-lg-0">
        <h5 class="card-title fw-semibold mb-1">
          <i class="ti ti-brand-whatsapp text-success me-1"></i> Status Fonnte API
        </h5>
        <p class="mb-0">
          Token:
          <span class="fw-semibold <?= str_starts_with($fonnteToken, '✓') ? 'text-success' : 'text-danger' ?>">
            <?= esc($fonnteToken) ?>
          </span>
        </p>
        <?php if (!str_starts_with($fonnteToken, '✓')) : ?>
          <small class="text-muted">
            Tambahkan <code>FONNTE_TOKEN=xxxxx</code> di file <code>.env</code>
          </small>
        <?php endif; ?>
      </div>
      <div class="col-12 col-lg-6 text-lg-end">
        <small class="text-muted d-block mb-1">Pengiriman otomatis via cron job:</small>
        <code class="bg-light px-2 py-1 rounded small">0 8 * * * php spark wa:send-reminder</code>
      </div>
    </div>
  </div>
</div>

<!-- Kirim Manual -->
<div class="card mb-3">
  <div class="card-body">
    <h5 class="card-title fw-semibold mb-4">Kirim Reminder Manual</h5>
    <div class="row">

      <!-- Card H-1 -->
      <div class="col-12 col-md-4 mb-3 mb-md-0">
        <div class="card border h-100">
          <div class="card-body text-center py-4">
            <div class="fs-1 mb-2">⏰</div>
            <h6 class="fw-bold">Reminder H-1</h6>
            <p class="text-muted small mb-1">Jatuh tempo besok</p>
            <p class="mb-2">
              <span class="fs-4 fw-bold text-primary"><?= $countBeforeDue ?></span>
              <span class="text-muted small"> peminjaman</span>
            </p>
            <?php if ($countBeforeDue > 0) : ?>
              <button type="button" class="btn btn-outline-primary btn-sm w-100 mb-2"
                onclick="lihatData('before_due', 'Peminjaman H-1 Jatuh Tempo')">
                <i class="ti ti-eye me-1"></i> Lihat Data
              </button>
            <?php endif; ?>
            <form action="<?= base_url('admin/wa-reminder/send/before_due') ?>" method="post">
              <?= csrf_field() ?>
              <button type="submit"
                class="btn btn-primary btn-sm w-100"
                <?= $countBeforeDue === 0 ? 'disabled' : '' ?>
                onclick="return confirm('Kirim reminder H-1 ke <?= $countBeforeDue ?> peminjaman?')">
                <i class="ti ti-send me-1"></i> Kirim Sekarang
              </button>
            </form>
          </div>
        </div>
      </div>

      <!-- Card Terlambat -->
      <div class="col-12 col-md-4 mb-3 mb-md-0">
        <div class="card border h-100">
          <div class="card-body text-center py-4">
            <div class="fs-1 mb-2">🔴</div>
            <h6 class="fw-bold">Notifikasi Terlambat</h6>
            <p class="text-muted small mb-1">Jatuh tempo kemarin, belum dikembalikan</p>
            <p class="mb-2">
              <span class="fs-4 fw-bold text-danger"><?= $countOverdue ?></span>
              <span class="text-muted small"> peminjaman</span>
            </p>
            <?php if ($countOverdue > 0) : ?>
              <button type="button" class="btn btn-outline-danger btn-sm w-100 mb-2"
                onclick="lihatData('overdue', 'Peminjaman Terlambat')">
                <i class="ti ti-eye me-1"></i> Lihat Data
              </button>
            <?php endif; ?>
            <form action="<?= base_url('admin/wa-reminder/send/overdue') ?>" method="post">
              <?= csrf_field() ?>
              <button type="submit"
                class="btn btn-danger btn-sm w-100"
                <?= $countOverdue === 0 ? 'disabled' : '' ?>
                onclick="return confirm('Kirim notifikasi terlambat ke <?= $countOverdue ?> peminjaman?')">
                <i class="ti ti-send me-1"></i> Kirim Sekarang
              </button>
            </form>
          </div>
        </div>
      </div>

      <!-- Card Kirim Semua -->
      <div class="col-12 col-md-4">
        <div class="card border h-100">
          <div class="card-body text-center py-4">
            <div class="fs-1 mb-2">📢</div>
            <h6 class="fw-bold">Kirim Semua</h6>
            <p class="text-muted small mb-1">H-1 + terlambat sekaligus</p>
            <p class="mb-2">
              <span class="fs-4 fw-bold"><?= $countBeforeDue + $countOverdue ?></span>
              <span class="text-muted small"> peminjaman</span>
            </p>
            <?php if (($countBeforeDue + $countOverdue) > 0) : ?>
              <button type="button" class="btn btn-outline-secondary btn-sm w-100 mb-2"
                onclick="lihatSemuaData()">
                <i class="ti ti-eye me-1"></i> Lihat Data
              </button>
            <?php endif; ?>
            <form action="<?= base_url('admin/wa-reminder/send-all') ?>" method="post">
              <?= csrf_field() ?>
              <button type="submit"
                class="btn btn-secondary btn-sm w-100"
                <?= ($countBeforeDue + $countOverdue) === 0 ? 'disabled' : '' ?>
                onclick="return confirm('Kirim semua reminder hari ini?')">
                <i class="ti ti-send me-1"></i> Kirim Semua
              </button>
            </form>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- Daftar Template -->
<div class="card">
  <div class="card-body">
    <div class="row mb-3">
      <div class="col-12 col-lg-6">
        <h5 class="card-title fw-semibold mb-0">Daftar Template Pesan</h5>
      </div>
      <div class="col-12 col-lg-6 d-flex justify-content-lg-end mt-2 mt-lg-0 gap-2">
        <a href="<?= base_url('admin/wa-reminder/logs') ?>" class="btn btn-outline-secondary py-2">
          <i class="ti ti-history me-1"></i> Riwayat Kirim
        </a>
        <a href="<?= base_url('admin/wa-reminder/create') ?>" class="btn btn-primary py-2">
          <i class="ti ti-plus"></i> Tambah Template
        </a>
      </div>
    </div>

    <div class="overflow-x-scroll">
      <table class="table table-hover table-striped">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Nama Template</th>
            <th>Tipe</th>
            <th class="text-center">Status</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="table-group-divider">
          <?php if (empty($templates)) : ?>
            <tr>
              <td class="text-center" colspan="5"><b>Belum ada template</b></td>
            </tr>
          <?php endif; ?>
          <?php foreach ($templates as $i => $tpl) : ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td>
                <b><?= esc($tpl['template_name']) ?></b><br>
                <small class="text-muted"><?= esc(mb_substr($tpl['message_template'], 0, 60)) ?>...</small>
              </td>
              <td>
                <?php if ($tpl['type'] === 'before_due') : ?>
                  <span class="badge bg-primary rounded-3 fw-semibold">H-1 Jatuh Tempo</span>
                <?php else : ?>
                  <span class="badge bg-danger rounded-3 fw-semibold">H+1 Terlambat</span>
                <?php endif; ?>
              </td>
              <td class="text-center">
                <form action="<?= base_url("admin/wa-reminder/{$tpl['id']}/toggle") ?>" method="post" class="d-inline">
                  <?= csrf_field() ?>
                  <button type="submit" class="btn btn-sm <?= $tpl['is_active'] ? 'btn-success' : 'btn-secondary' ?>">
                    <?= $tpl['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                  </button>
                </form>
              </td>
              <td class="text-center">
                <a href="<?= base_url("admin/wa-reminder/{$tpl['id']}/edit") ?>" class="btn btn-sm btn-primary me-1">
                  <i class="ti ti-edit"></i> Edit
                </a>
                <form action="<?= base_url("admin/wa-reminder/{$tpl['id']}") ?>" method="post" class="d-inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="_method" value="DELETE">
                  <button type="submit" class="btn btn-sm btn-danger"
                    onclick="return confirm('Hapus template ini?')">
                    <i class="ti ti-trash"></i> Hapus
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Lihat Data Peminjaman -->
<div class="modal fade" id="modalLihatData" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-semibold" id="modalTitle">Data Peminjaman</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-0">
        <div id="modalLoading" class="text-center py-5">
          <div class="spinner-border text-primary" role="status"></div>
          <p class="mt-2 text-muted">Memuat data...</p>
        </div>
        <div id="modalContent" style="display:none;">
          <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
              <thead class="table-light">
                <tr>
                  <th>#</th>
                  <th>Nama Member</th>
                  <th>Judul Buku</th>
                  <th>Tgl Pinjam</th>
                  <th>Jatuh Tempo</th>
                  <th class="text-center">Nomor WA</th>
                </tr>
              </thead>
              <tbody id="modalTableBody" class="table-group-divider"></tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<script>
const BASE_URL = '<?= base_url() ?>';
const CSRF_TOKEN_NAME = '<?= csrf_token() ?>';
const CSRF_TOKEN_HASH = '<?= csrf_hash() ?>';

function lihatData(type, title) {
  document.getElementById('modalTitle').textContent = title;
  document.getElementById('modalLoading').style.display = 'block';
  document.getElementById('modalContent').style.display = 'none';

  const modal = new bootstrap.Modal(document.getElementById('modalLihatData'));
  modal.show();

  fetch(`${BASE_URL}admin/wa-reminder/preview-loans/${type}`, {
    headers: { 'X-Requested-With': 'XMLHttpRequest' }
  })
  .then(r => r.json())
  .then(data => {
    const tbody = document.getElementById('modalTableBody');
    tbody.innerHTML = '';

    if (!data.loans || data.loans.length === 0) {
      tbody.innerHTML = '<tr><td colspan="6" class="text-center">Tidak ada data</td></tr>';
    } else {
      data.loans.forEach((loan, i) => {
        const nama    = `${loan.first_name ?? ''} ${loan.last_name ?? ''}`.trim();
        const phone   = loan.phone
          ? `<span class="badge bg-success rounded-3">${loan.phone}</span>`
          : `<span class="badge bg-warning rounded-3 text-dark">Tidak ada</span>`;
        const tglPinjam    = loan.loan_date ? loan.loan_date.substring(0, 10) : '-';
        const tglJatuhTempo = loan.due_date ? loan.due_date.substring(0, 10) : '-';

        tbody.innerHTML += `
          <tr>
            <td>${i + 1}</td>
            <td><b>${nama}</b></td>
            <td>${loan.book_title ?? '-'}</td>
            <td>${tglPinjam}</td>
            <td><b>${tglJatuhTempo}</b></td>
            <td class="text-center">${phone}</td>
          </tr>`;
      });
    }

    document.getElementById('modalLoading').style.display = 'none';
    document.getElementById('modalContent').style.display = 'block';
  })
  .catch(() => {
    document.getElementById('modalTableBody').innerHTML =
      '<tr><td colspan="6" class="text-center text-danger">Gagal memuat data</td></tr>';
    document.getElementById('modalLoading').style.display = 'none';
    document.getElementById('modalContent').style.display = 'block';
  });
}

function lihatSemuaData() {
  // Muat H-1 dulu, lalu gabungkan dengan overdue
  document.getElementById('modalTitle').textContent = 'Semua Peminjaman (H-1 + Terlambat)';
  document.getElementById('modalLoading').style.display = 'block';
  document.getElementById('modalContent').style.display = 'none';

  const modal = new bootstrap.Modal(document.getElementById('modalLihatData'));
  modal.show();

  Promise.all([
    fetch(`${BASE_URL}admin/wa-reminder/preview-loans/before_due`, {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    }).then(r => r.json()),
    fetch(`${BASE_URL}admin/wa-reminder/preview-loans/overdue`, {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    }).then(r => r.json()),
  ])
  .then(([beforeDue, overdue]) => {
    const tbody = document.getElementById('modalTableBody');
    tbody.innerHTML = '';

    const allLoans = [
      ...(beforeDue.loans || []).map(l => ({ ...l, _type: 'before_due' })),
      ...(overdue.loans   || []).map(l => ({ ...l, _type: 'overdue' })),
    ];

    if (allLoans.length === 0) {
      tbody.innerHTML = '<tr><td colspan="6" class="text-center">Tidak ada data</td></tr>';
    } else {
      allLoans.forEach((loan, i) => {
        const nama  = `${loan.first_name ?? ''} ${loan.last_name ?? ''}`.trim();
        const phone = loan.phone
          ? `<span class="badge bg-success rounded-3">${loan.phone}</span>`
          : `<span class="badge bg-warning rounded-3 text-dark">Tidak ada</span>`;
        const typeBadge = loan._type === 'before_due'
          ? `<span class="badge bg-primary rounded-3">H-1</span>`
          : `<span class="badge bg-danger rounded-3">Terlambat</span>`;
        const tglPinjam     = loan.loan_date ? loan.loan_date.substring(0, 10) : '-';
        const tglJatuhTempo = loan.due_date  ? loan.due_date.substring(0, 10)  : '-';

        tbody.innerHTML += `
          <tr>
            <td>${i + 1}</td>
            <td><b>${nama}</b> ${typeBadge}</td>
            <td>${loan.book_title ?? '-'}</td>
            <td>${tglPinjam}</td>
            <td><b>${tglJatuhTempo}</b></td>
            <td class="text-center">${phone}</td>
          </tr>`;
      });
    }

    document.getElementById('modalLoading').style.display = 'none';
    document.getElementById('modalContent').style.display = 'block';
  })
  .catch(() => {
    document.getElementById('modalTableBody').innerHTML =
      '<tr><td colspan="6" class="text-center text-danger">Gagal memuat data</td></tr>';
    document.getElementById('modalLoading').style.display = 'none';
    document.getElementById('modalContent').style.display = 'block';
  });
}
</script>

<?= $this->endSection() ?>