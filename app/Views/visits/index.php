<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Kunjungan</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php use CodeIgniter\I18n\Time; ?>

<?php if (session()->getFlashdata('msg')) : ?>
  <div class="pb-2">
    <div class="alert <?= (session()->getFlashdata('error') ?? false) ? 'alert-danger' : 'alert-success' ?> alert-dismissible fade show" role="alert">
      <?= session()->getFlashdata('msg') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  </div>
<?php endif; ?>

<div class="card">
  <div class="card-body">

    <div class="row mb-3">
      <div class="col-12 col-lg-5">
        <h5 class="card-title fw-semibold mb-0">Data Kunjungan</h5>
      </div>
      <div class="col-12 col-lg-7">
        <div class="d-flex gap-2 justify-content-md-end">
          <form action="" method="get">
            <div class="input-group">
              <input type="text" class="form-control" name="search"
                     value="<?= esc($search ?? '') ?>"
                     placeholder="Cari nama / no. identitas">
              <button class="btn btn-outline-secondary" type="submit">Cari</button>
            </div>
          </form>
          <a href="<?= base_url('admin/kunjungan/new') ?>" class="btn btn-primary text-nowrap">
            <i class="ti ti-plus"></i> Catat Kunjungan
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
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="table-group-divider">
          <?php if (empty($visits)) : ?>
            <tr>
              <td colspan="8" class="text-center py-4"><b>Tidak ada data kunjungan</b></td>
            </tr>
          <?php else : ?>
            <?php
              $i = 1 + ($itemPerPage * ($currentPage - 1));
              foreach ($visits as $visit) :
                $visitDate = Time::parse($visit['visit_date'], locale: 'id');
            ?>
              <tr>
                <th scope="row"><?= $i++ ?></th>
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
                <td><?= esc($visit['notes'] ?? '—') ?></td>
                <td class="text-center">
                  <form action="<?= base_url("admin/kunjungan/{$visit['id']}") ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-sm btn-danger"
                            onclick="return confirm('Hapus data kunjungan ini?')">
                      <i class="ti ti-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <?= $pager->links('visits', 'my_pager') ?>

  </div>
</div>

<?= $this->endSection() ?>