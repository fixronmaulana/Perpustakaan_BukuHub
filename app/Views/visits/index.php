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
          <a href="<?= base_url('admin/kunjungan/laporan') ?>" class="btn btn-outline-success text-nowrap">
            <i class="ti ti-file-text me-1"></i> Laporan
          </a>
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
<?= $this->section('scripts') ?>
<!-- Memanggil Library SweetAlert2 via CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Mengecek apakah ada flashdata dengan key 'success_visit'
    <?php if (session()->getFlashdata('success_visit')) : 
        $data = session()->getFlashdata('success_visit'); ?>
        
    Swal.fire({
        title: '<span style="font-size: 1.25rem; font-weight: bold; color: #333;">Kunjungan Berhasil!</span>',
        html: `
            <p style="color: #666; font-size: 0.9rem; margin-top: -10px;">Kunjungan tercatat dalam sistem</p>
            <div class="p-3 mb-3" style="background-color: #eef2ff; border-radius: 15px;">
                <div class="d-flex justify-content-center align-items-center mb-1">
                    <span class="mx-2" style="color: #1e3a8a; font-weight: bold; font-size: 1rem;">Mendapat Reward Poin</span>
                </div>
                <h2 class="fw-bold" style="font-size: 2.5rem; color: #1e3a8a; margin: 5px 0;">+<?= $data['poin'] ?></h2>
                <p class="mb-0 text-muted" style="font-size: 0.85rem;">Poin diberikan ke <?= esc($data['nama']) ?></p>
            </div>
            
            <div class="text-start mb-3" style="font-size: 0.85rem; color: #666;">
                <div class="d-flex justify-content-between border-bottom py-1">
                    <span>Anggota</span>
                    <span class="text-dark fw-bold"><?= esc($data['nama']) ?></span>
                </div>
                <div class="d-flex justify-content-between py-1">
                    <span>ID</span>
                    <span class="text-dark fw-bold"><?= esc($data['no_identitas']) ?></span>
                </div>
            </div>
        `,
        icon: 'success',
        iconColor: '#1e3a8a',
        showConfirmButton: true,
        confirmButtonText: 'Selesai',
        buttonsStyling: false,
        customClass: {
            popup: 'rounded-4',
            confirmButton: 'btn btn-primary w-100 py-2'
        },
        didOpen: () => {
            // Menyesuaikan style tombol agar persis seperti gambar
            const btn = Swal.getConfirmButton();
            btn.style.backgroundColor = '#1e3a8a';
            btn.style.borderRadius = '10px'; 
            btn.style.fontSize = '1rem';
            btn.style.fontWeight = 'bold';
            btn.style.border = 'none';
        }
    });
<?php endif; ?>
</script>
<?= $this->endSection() ?>