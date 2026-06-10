<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Laporan Kunjungan</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php use CodeIgniter\I18n\Time; ?>

<a href="<?= base_url('admin/kunjungan') ?>" class="btn btn-outline-primary mb-3">
  <i class="ti ti-arrow-left"></i> Kembali
</a>

<!-- ── Filter Card ── -->
<div class="card mb-3">
  <div class="card-body">
    <div class="row align-items-end">
      <div class="col-12 col-lg-6 mb-2 mb-lg-0">
        <h5 class="card-title fw-semibold mb-0">
          <i class="ti ti-file-analytics me-1"></i> Laporan Kunjungan
        </h5>
        <small class="text-muted">Filter data kunjungan berdasarkan rentang tanggal</small>
      </div>
      <div class="col-12 col-lg-6">
        <form action="" method="get" class="d-flex gap-2 justify-content-lg-end align-items-end flex-wrap">
          <div>
            <label class="form-label mb-1 small fw-semibold">Dari Tanggal</label>
            <input type="date" class="form-control" name="dari_tanggal"
                   value="<?= esc($dariTanggal ?? '') ?>"
                   max="<?= date('Y-m-d') ?>">
          </div>
          <div>
            <label class="form-label mb-1 small fw-semibold">Sampai Tanggal</label>
            <input type="date" class="form-control" name="sampai_tanggal"
                   value="<?= esc($sampaiTanggal ?? '') ?>"
                   max="<?= date('Y-m-d') ?>">
          </div>
          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
              <i class="ti ti-search me-1"></i> Tampilkan
            </button>
            <?php if ($dariTanggal && $sampaiTanggal): ?>
              <a href="<?= base_url('admin/kunjungan/laporan') ?>" class="btn btn-outline-secondary">
                <i class="ti ti-x"></i> Reset
              </a>
            <?php endif; ?>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php if (!empty($visits)): ?>

  <!-- Summary Cards -->
  <div class="row g-2 mb-3">
    <div class="col-6 col-md-3">
      <div class="card border-primary h-100">
        <div class="card-body d-flex flex-column align-items-center justify-content-center py-3 gap-1">
          <div class="fs-2 fw-bold text-primary lh-1"><?= $summary['total'] ?></div>
          <div class="text-muted fw-semibold" style="font-size:0.75rem;letter-spacing:0.05em;">TOTAL</div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card h-100">
        <div class="card-body d-flex flex-column align-items-center justify-content-center py-3 gap-1">
          <div class="fs-2 fw-bold lh-1"><?= $summary['murid'] ?></div>
          <div class="text-muted fw-semibold" style="font-size:0.75rem;letter-spacing:0.05em;">MURID</div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card h-100">
        <div class="card-body d-flex flex-column align-items-center justify-content-center py-3 gap-1">
          <div class="fs-2 fw-bold lh-1"><?= $summary['guru'] ?></div>
          <div class="text-muted fw-semibold" style="font-size:0.75rem;letter-spacing:0.05em;">GURU</div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card h-100">
        <div class="card-body d-flex flex-column align-items-center justify-content-center py-3 gap-1">
          <div class="fs-2 fw-bold lh-1"><?= $summary['staf'] ?></div>
          <div class="text-muted fw-semibold" style="font-size:0.75rem;letter-spacing:0.05em;">STAF</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Tabel Data -->
  <div class="card">
    <div class="card-body">
      <div class="row mb-3">
        <div class="col-12 col-lg-6">
          <h5 class="card-title fw-semibold mb-0">Detail Kunjungan</h5>
          <small class="text-muted">
            <?php if ($dariTanggal && $sampaiTanggal): ?>
              Periode: <b><?= date('d/m/Y', strtotime($dariTanggal)) ?></b>
              &mdash; <b><?= date('d/m/Y', strtotime($sampaiTanggal)) ?></b>
              &nbsp;&mdash;&nbsp; <?= $summary['total'] ?> data ditemukan
            <?php endif; ?>
          </small>
        </div>
        <div class="col-12 col-lg-6 d-flex justify-content-lg-end mt-2 mt-lg-0">
          <div class="d-flex gap-2">
            <a href="<?= base_url('admin/kunjungan/laporan/export') ?>?dari_tanggal=<?= $dariTanggal ?>&sampai_tanggal=<?= $sampaiTanggal ?>&preview=1"
              target="_blank"
              class="btn btn-outline-danger d-inline-flex align-items-center gap-1">
              <i class="ti ti-eye" style="font-size:1rem;"></i>
              <span>Preview PDF</span>
            </a>
            <a href="<?= base_url('admin/kunjungan/laporan/export') ?>?dari_tanggal=<?= $dariTanggal ?>&sampai_tanggal=<?= $sampaiTanggal ?>"
              class="btn btn-danger d-inline-flex align-items-center gap-1">
              <i class="ti ti-file-type-pdf" style="font-size:1rem;"></i>
              <span>Export PDF</span>
            </a>
          </div>
        </div>
      </div>

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
              <th scope="row"><?= $i++ ?></th>
              <td><b><?= esc(trim($visit['first_name'] . ' ' . $visit['last_name'])) ?></b></td>
              <td><?= esc($visit['no_identitas'] ?? '-') ?></td>
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

  <?php elseif ($dariTanggal && $sampaiTanggal && empty($visits)): ?>
    <div class="card">
      <div class="card-body text-center py-5">
        <i class="ti ti-database-off fs-1 text-muted d-block mb-2"></i>
        <h6 class="fw-semibold">Tidak ada data kunjungan</h6>
        <p class="text-muted mb-0">
          Tidak ada kunjungan pada periode
          <b><?= date('d/m/Y', strtotime($dariTanggal)) ?></b> —
          <b><?= date('d/m/Y', strtotime($sampaiTanggal)) ?></b>.
        </p>
      </div>
    </div>
  <?php elseif (!$dariTanggal || !$sampaiTanggal): ?>
    <div class="card">
      <div class="card-body text-center py-5">
        <i class="ti ti-filter fs-1 text-muted d-block mb-2"></i>
        <h6 class="fw-semibold">Pilih rentang tanggal terlebih dahulu</h6>
        <p class="text-muted mb-0">Isi <b>Dari Tanggal</b> dan <b>Sampai Tanggal</b> lalu klik <b>Tampilkan</b>.</p>
      </div>
    </div>
  <?php endif; ?>

<?= $this->endSection() ?>