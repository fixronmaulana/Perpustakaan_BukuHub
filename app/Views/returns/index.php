<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Pengembalian</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php

use CodeIgniter\I18n\Time;

if (session()->getFlashdata('msg')) : ?>
  <div class="pb-2">
    <div class="alert <?= (session()->getFlashdata('error') ?? false) ? 'alert-danger' : 'alert-success'; ?> alert-dismissible fade show" role="alert">
      <?= session()->getFlashdata('msg') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  </div>
<?php endif; ?>

<div class="card">
  <div class="card-body">
    <div class="row mb-2">
      <div class="col-12 col-lg-5">
        <h5 class="card-title fw-semibold mb-4">Data Pengembalian</h5>
      </div>
      <div class="col-12 col-lg-7">
        <div class="d-flex gap-2 justify-content-md-end">
          <div>
            <form action="" method="get">
              <div class="input-group mb-3">
                <input type="text" class="form-control" name="search" value="<?= $search ?? ''; ?>" placeholder="Cari data pengembalian" aria-label="Cari data pengembalian" aria-describedby="searchButton">
                <button class="btn btn-outline-secondary" type="submit" id="searchButton">Cari</button>
              </div>
            </form>
          </div>
          <div>
            <a href="<?= base_url('admin/returns/new/search'); ?>" class="btn btn-primary py-2">
              <i class="ti ti-plus"></i>
              Pengembalian baru
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="overflow-x-scroll">
      <table class="table table-hover table-striped">
        <thead class="table-light">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Nama peminjam</th>
            <th scope="col">Judul buku</th>
            <th scope="col" class="text-center">Jumlah</th>
            <th scope="col">Tgl pinjam</th>
            <th scope="col">Tenggat</th>
            <th scope="col">Tgl pengembalian</th>
            <th scope="col" class="text-center">Status</th>
            <th scope="col" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="table-group-divider">
          <?php
          $i = 1 + ($itemPerPage * ($currentPage - 1));

          $now = Time::now(locale: 'id');
          ?>
          <?php if (empty($loans)) : ?>
            <tr>
              <td class="text-center" colspan="9"><b>Tidak ada data</b></td>
            </tr>
          <?php endif; ?>
          <?php
          foreach ($loans as $key => $loan) :
            $loanCreateDate = Time::parse($loan['loan_date'], locale: 'id');
            $loanDueDate = Time::parse($loan['due_date'], locale: 'id');
            $loanReturnDate = Time::parse($loan['return_date'], locale: 'id');

            $isFined = $loan['fine_id'] != null;
            $isFinePaid = $isFined ? (($loan['amount_paid'] ?? 0) >= $loan['fine_amount']) : true;

            $isLate = $now->isAfter($loanDueDate);
          ?>
            <tr>
              <th scope="row"><?= $i++; ?></th>
              <td>
                <a href="<?= base_url("admin/members/{$loan['member_uid']}"); ?>" class="text-primary-emphasis text-decoration-underline">
                  <p>
                    <b><?= "{$loan['first_name']} {$loan['last_name']}"; ?></b>
                  </p>
                </a>
              </td>
              <td>
                <a href="<?= base_url("admin/books/{$loan['slug']}"); ?>">
                  <p class="text-primary-emphasis text-decoration-underline"><b><?= "{$loan['title']} ({$loan['year']})"; ?></b></p>
                  <p class="text-body"><?= "Author: {$loan['author']}"; ?></p>
                </a>
              </td>
              <td class="text-center"><?= $loan['quantity']; ?></td>
              <td>
                <b><?= $loanCreateDate->toLocalizedString('dd/MM/y'); ?></b><br>
                <b><?= $loanCreateDate->toLocalizedString('HH:mm:ss'); ?></b>
              </td>
              <td>
                <b><?= $loanDueDate->toLocalizedString('dd/MM/y'); ?></b>
              </td>
              <td class="<?= $isLate ? 'text-danger-emphasis' : ''; ?>">
                <b><?= $loanReturnDate->toLocalizedString('dd/MM/y'); ?></b><br>
                <b><?= $loanReturnDate->toLocalizedString('HH:mm:ss'); ?></b>
              </td>
              <td class="text-center">
                <?php if ($isFinePaid) : ?>
                  <span class="badge bg-success rounded-3 fw-semibold"><?= $isFined ? 'Lunas' : 'Selesai'; ?></span>
                <?php else : ?>
                  <span class="badge bg-danger rounded-3 fw-semibold">Menunggak</span>
                <?php endif; ?>
              </td>
              <td>
                <a href="<?= base_url("admin/returns/{$loan['uid']}"); ?>" class="d-block btn btn-primary w-100 mb-2">
                  Detail
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?= $pager->links('returns', 'my_pager'); ?>
  </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
<?php if (session()->getFlashdata('success_return')) :
    $data = session()->getFlashdata('success_return'); ?>

Swal.fire({
    width: '380px',
    icon: '<?= $data['terlambat'] ? 'error' : 'success' ?>',
    title: '<span style="font-size:1.5rem; font-weight:700;"><?= $data['terlambat'] ? 'Pengembalian Terlambat!' : 'Pengembalian Berhasil!' ?></span>',
    html: `
        <p style="color:#6c757d; font-size:0.85rem; margin-top:-6px; margin-bottom:14px;">
            <?= $data['terlambat'] ? 'Pengembalian melebihi batas waktu' : 'Pengembalian telah tercatat dalam sistem' ?>
        </p>
        <div style="background:<?= $data['terlambat'] ? '#f8d7da' : '#ffe5d0' ?>; border-radius:10px; padding:14px 16px; margin-bottom:14px;">
            <span style="font-size:0.78rem; color:<?= $data['terlambat'] ? '#842029' : '#7c3000' ?>; font-weight:600; text-transform:uppercase; letter-spacing:0.05em;">
                <?= $data['terlambat'] ? 'Pengurangan Poin' : 'Reward Poin Diberikan' ?>
            </span>
            <div style="font-size:2.2rem; font-weight:700; color:<?= $data['terlambat'] ? '#842029' : '#7c3000' ?>; line-height:1.4;">
                <?= $data['poin'] ?>
            </div>
        </div>
        <div style="font-size:0.85rem; color:#495057; margin-bottom:6px;">
            <b><?= esc($data['nama']) ?></b>
        </div>
        <?php if ($data['terlambat']) : ?>
        <div style="font-size:0.82rem; color:#6c757d; margin-bottom:4px;">
            Keterlambatan: <b style="color:#dc3545;"><?= $data['hari'] ?> hari</b>
        </div>
        <div style="font-size:0.82rem; color:#6c757d;">
            Total denda: <b style="color:#dc3545;">Rp <?= number_format($data['denda'], 0, ',', '.') ?></b>
        </div>
        <?php endif; ?>
    `,
    iconColor: '<?= $data['terlambat'] ? '#dc3545' : '#fd7e14' ?>',
    showConfirmButton: true,
    confirmButtonText: 'Selesai',
    buttonsStyling: false,
    customClass: {
        popup: 'rounded-4',
        confirmButton: 'btn <?= $data['terlambat'] ? 'btn-danger' : 'btn-warning' ?> w-100 py-2 mt-3'
    }
});
<?php endif; ?>
</script>
<?= $this->endSection() ?>