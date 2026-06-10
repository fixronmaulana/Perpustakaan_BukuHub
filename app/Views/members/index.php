<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Anggota</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php if (session()->getFlashdata('msg')) : ?>
  <div class="pb-2">
    <div class="alert <?= (session()->getFlashdata('error') ?? false) ? 'alert-danger' : 'alert-success'; ?> alert-dismissible fade show" role="alert">
      <?= session()->getFlashdata('msg') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  </div>
<?php endif; ?>

<div class="card">
  <div class="card-body">
    <div class="row mb-3">
      <div class="col-12 col-lg-5">
        <h5 class="card-title fw-semibold mb-0">Data Anggota</h5>
      </div>
      <div class="col-12 col-lg-7">
        <div class="d-flex gap-2 justify-content-md-end">
          <form action="" method="get">
            <div class="input-group">
              <input type="text" class="form-control" name="search"
                     value="<?= esc($search ?? '') ?>"
                     placeholder="Cari nama / no. identitas"
                     aria-label="Cari anggota">
              <button class="btn btn-outline-secondary" type="submit">Cari</button>
            </div>
          </form>
          <a href="<?= base_url('admin/members/import') ?>" 
            class="btn btn-success text-nowrap">
            <i class="ti ti-file-import"></i> Import
          </a>
          <a href="<?= base_url('admin/members/new'); ?>" class="btn btn-primary text-nowrap">
            <i class="ti ti-plus"></i> Tambah Anggota
          </a>
        </div>
      </div>
    </div>

    <div class="overflow-x-scroll">
      <table class="table table-hover table-striped">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Nama Lengkap</th>
            <th>No. Identitas</th>
            <th>Tipe</th>
            <th>Email</th>
            <th>No. Telepon</th>
            <th>Jenis Kelamin</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="table-group-divider">
          <?php $i = 1 + ($itemPerPage * ($currentPage - 1)) ?>
          <?php if (empty($members)) : ?>
            <tr>
              <td class="text-center" colspan="8"><b>Tidak ada data</b></td>
            </tr>
          <?php endif; ?>
          <?php foreach ($members as $member) : ?>
            <tr>
              <th><?= $i++ ?></th>
              <td>
                <a href="<?= base_url("admin/members/{$member['uid']}") ?>"
                   class="text-primary-emphasis text-decoration-underline">
                  <b><?= esc($member['first_name'] . ' ' . $member['last_name']) ?></b>
                </a>
              </td>
              <td><?= esc($member['no_identitas']) ?></td>
              <td>
                <?php
                  $tipeClass = match($member['tipe_anggota'] ?? 'Murid') {
                    'Guru'  => 'bg-primary',
                    'Staf'  => 'bg-warning text-dark',
                    default => 'bg-success',
                  };
                ?>
                <span class="badge <?= $tipeClass ?>">
                  <?= esc($member['tipe_anggota'] ?? 'Murid') ?>
                </span>
              </td>
              <td><?= esc($member['email']) ?></td>
              <td><?= esc($member['phone']) ?></td>
              <td><?= $member['gender'] === 'Male' ? 'Laki-laki' : 'Perempuan' ?></td>
              <td>
                <div class="d-flex justify-content-center gap-2">
                  <a href="<?= base_url("admin/members/{$member['uid']}/edit") ?>"
                     class="btn btn-sm btn-primary">
                    <i class="ti ti-edit"></i> Edit
                  </a>
                  <form action="<?= base_url("admin/members/{$member['uid']}") ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-sm btn-danger"
                            onclick="return confirm('Hapus anggota ini?')">
                      <i class="ti ti-trash"></i> Hapus
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?= $pager->links('members', 'my_pager') ?>
  </div>
</div>
<?= $this->endSection() ?>