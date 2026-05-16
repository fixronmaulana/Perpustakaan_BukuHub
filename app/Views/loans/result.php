<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Peminjaman Baru</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<a href="<?= base_url('admin/loans'); ?>" class="btn btn-outline-primary mb-3">
  <i class="ti ti-arrow-left"></i>
  Kembali
</a>

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
    <h5 class="card-title fw-semibold mb-4">Peminjaman Buku Berhasil</h5>

    <div class="overflow-x-scroll">
      <table class="table table-hover table-striped">
        <thead class="table-light">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Nama peminjam</th>
            <th scope="col">Judul buku</th>
            <th scope="col" class="text-center">Jumlah</th>
            <th scope="col">Tgl pinjam</th>
            <th scope="col">Tgl pengembalian</th>
            <th scope="col" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="table-group-divider">
          <?php
          $i = 1;
          foreach ($newLoans as $loan) : ?>
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
              <td class="text-center"><b><?= $loan['quantity']; ?></b></td>
              <td><b><?= Time::parse($loan['loan_date'])->toLocalizedString('d/M/y'); ?></b></td>
              <td>
                <b><?= Time::parse($loan['due_date'])->toLocalizedString('d/M/y'); ?></b>
              </td>
              <td class="text-center">
                <div class="d-flex justify-content-center gap-2">
                  <a href="<?= base_url("admin/loans/{$loan['uid']}"); ?>" class="btn btn-primary mb-2">
                    <i class="ti ti-eye"></i>
                    Detail
                  </a>
                  <form action="<?= base_url("admin/loans/{$loan['uid']}"); ?>" method="post">
                    <?= csrf_field(); ?>
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-danger mb-2" onclick="return confirm('Are you sure?');">
                      <i class="ti ti-x"></i>
                      Batalkan
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
<?php
$firstLoan    = $newLoans[0] ?? null;
$totalBuku    = count($newLoans);
$namaPeminjam = $firstLoan ? esc($firstLoan['first_name'] . ' ' . $firstLoan['last_name']) : '';
$tglKembali   = $firstLoan ? Time::parse($firstLoan['due_date'])->toLocalizedString('d MMMM yyyy') : '';
?>

<?php if (!empty($newLoans)) : ?>
Swal.fire({
    width: '380px',
    icon: 'success',
    title: '<span style="font-size:1.5rem; font-weight:700;">Peminjaman Berhasil!</span>',
    html: `
        <p style="color:#6c757d; font-size:0.85rem; margin-top:-6px; margin-bottom:14px;">
            Peminjaman telah tercatat dalam sistem
        </p>
        <div style="background:#cfe2ff; border-radius:10px; padding:14px 16px; margin-bottom:14px;">
            <span style="font-size:0.78rem; color:#084298; font-weight:600; text-transform:uppercase; letter-spacing:0.05em;">
                Reward Poin Diberikan
            </span>
            <div style="font-size:2.2rem; font-weight:700; color:#084298; line-height:1.4;">
                <?= $poinPeminjaman ?>
            </div>
        </div>
        <div style="font-size:0.85rem; color:#495057; margin-bottom:6px;">
            <b><?= $namaPeminjam ?></b>
        </div>
        <div style="font-size:0.82rem; color:#6c757d;">
            Batas Kembali: <b style="color:#212529;"><?= $tglKembali ?></b>
        </div>
    `,
    iconColor: '#0d6efd',
    showConfirmButton: true,
    confirmButtonText: 'Selesai',
    buttonsStyling: false,
    customClass: {
        popup: 'rounded-4',
        confirmButton: 'btn btn-primary w-100 py-2 mt-3'
    }
});
<?php endif; ?>
</script>
<?= $this->endSection() ?>