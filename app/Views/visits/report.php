<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Laporan Kunjungan</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php use CodeIgniter\I18n\Time; ?>

<a href="<?= base_url('admin/kunjungan') ?>" class="btn btn-outline-primary mb-3">
  <i class="ti ti-arrow-left"></i> Kembali
</a>

<div class="card mb-3">
  <div class="card-body">
    <h5 class="card-title fw-semibold mb-3">
      <i class="ti ti-file-text me-1"></i> Laporan Kunjungan
    </h5>

    <!-- Filter -->
    <form action="" method="get" class="row g-2 align-items-end">
      <div class="col-12 col-md-4">
        <label class="form-label">Filter Bulan</label>
        <input type="month" class="form-control" name="bulan"
               value="<?= esc($bulan ?? '') ?>"
               max="<?= date('Y-m') ?>">
      </div>
      <div class="col-auto">
        <button type="submit" class="btn btn-primary">
          <i class="ti ti-search me-1"></i> Tampilkan
        </button>
      </div>
      <?php if ($bulan): ?>
        <div class="col-auto">
          <a href="<?= base_url('admin/kunjungan/laporan') ?>" class="btn btn-outline-secondary">
            <i class="ti ti-x me-1"></i> Reset
          </a>
        </div>
      <?php endif; ?>
      <?php if (!empty($visits)): ?>
        <div class="col-auto ms-auto">
          <a href="<?= base_url('admin/kunjungan/laporan/export') . ($bulan ? '?bulan=' . $bulan : '') ?>"
             class="btn btn-danger">
            <i class="ti ti-file-type-pdf me-1"></i> Export PDF
          </a>
        </div>
      <?php endif; ?>
    </form>
  </div>
</div>

<?php if (!empty($visits)): ?>

  <!-- Summary -->
  <div class="row mb-3 g-2">
    <div class="col-6 col-md-2">
      <div class="card text-center border-primary">
        <div class="card-body py-2">
          <div class="fs-4 fw-bold text-primary"><?= $summary['total'] ?></div>
          <small class="text-muted">Total</small>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-2">
      <div class="card text-center">
        <div class="card-body py-2">
          <div class="fs-4 fw-bold"><?= $summary['murid'] ?></div>
          <small class="text-muted">Murid</small>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-2">
      <div class="card text-center">
        <div class="card-body py-2">
          <div class="fs-4 fw-bold"><?= $summary['guru'] ?></div>
          <small class="text-muted">Guru</small>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-2">
      <div class="card text-center">
        <div class="card-body py-2">
          <div class="fs-4 fw-bold"><?= $summary['staf'] ?></div>
          <small class="text-muted">Staf</small>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-2">
      <div class="card text-center">
        <div class="card-body py-2">
          <div class="fs-4 fw-bold"><?= $summary['manual'] ?></div>
          <small class="text-muted">Manual</small>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-2">
      <div class="card text-center">
        <div class="card-body py-2">
          <div class="fs-4 fw-bold"><?= $summary['scan'] ?></div>
          <small class="text-muted">Scan QR</small>
        </div>
      </div>
    </div>
  </div>

  <!-- Tabel -->
  <div class="card">
    <div class="card-body">
      <div class="overflow-x-scroll">
        <table class="table table-hover table-striped">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Nama Anggota</th>
              <th>No. Identitas</th>
              <th>Tipe</th>
              <th>Tanggal Kunjungan</th>
              <th class="text-center">Metode</th>
              <th>Catatan</th>
            </tr>
          </thead>
          <tbody class="table-group-divider">
            <?php $i = 1; foreach ($visits as $visit):
              $visitDate = Time::parse($visit['visit_date'], locale: 'id');
            ?>
            <tr>
              <th><?= $i++ ?></th>
              <td><b><?= esc(trim($visit['first_name'] . ' ' . $visit['last_name'])) ?></b></td>
              <td><?= esc($visit['no_identitas']) ?></td>
              <td><?= esc($visit['tipe_anggota']) ?></td>
              <td>
                <b><?= $visitDate->toLocalizedString('dd/MM/y') ?></b><br>
                <small class="text-muted"><?= $visitDate->toLocalizedString('HH:mm:ss') ?></small>
              </td>
              <td class="text-center">
                <?php if ($visit['method'] === 'scan'): ?>
                  <span class="badge bg-primary rounded-3 fw-semibold">Scan QR</span>
                <?php else: ?>
                  <span class="badge bg-secondary rounded-3 fw-semibold">Manual</span>
                <?php endif; ?>
              </td>
              <td><?= esc($visit['notes'] ?? '-') ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

<?php else: ?>
  <div class="card">
    <div class="card-body text-center py-5">
      <i class="ti ti-database-off fs-1 text-muted"></i>
      <p class="mt-2 text-muted">Tidak ada data kunjungan<?= $bulan ? ' pada periode ini' : '' ?>.</p>
    </div>
  </div>
<?php endif; ?>

<?= $this->endSection() ?>