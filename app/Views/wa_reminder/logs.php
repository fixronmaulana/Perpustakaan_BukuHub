<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Riwayat Pengiriman WA</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
  <div class="card-body">

    <div class="row mb-3">
      <div class="col-12 col-lg-6">
        <h5 class="card-title fw-semibold mb-0">Riwayat Pengiriman WA</h5>
      </div>
      <div class="col-12 col-lg-6 d-flex justify-content-lg-end mt-2 mt-lg-0">
        <a href="<?= base_url('admin/wa-reminder') ?>" class="btn btn-outline-secondary py-2">
          <i class="ti ti-arrow-left me-1"></i> Kembali
        </a>
      </div>
    </div>

    <div class="overflow-x-scroll">
      <table class="table table-hover table-striped">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Nama Member</th>
            <th>Nomor WA</th>
            <th>Judul Buku</th>
            <th>Tipe</th>
            <th class="text-center">Status</th>
            <th>Keterangan</th>
            <th>Waktu Kirim</th>
          </tr>
        </thead>
        <tbody class="table-group-divider">
          <?php if (empty($logs)) : ?>
            <tr>
              <td class="text-center" colspan="8"><b>Belum ada riwayat pengiriman</b></td>
            </tr>
          <?php endif; ?>
          <?php
          $i = 1 + ($itemPerPage * ($currentPage - 1));
          foreach ($logs as $log) :
          ?>
            <tr>
              <td><?= $i++ ?></td>
              <td><b><?= esc($log['member_name']) ?></b></td>
              <td><?= esc($log['phone']) ?></td>
              <td><?= esc($log['book_title']) ?></td>
              <td>
                <?php if ($log['type'] === 'before_due') : ?>
                  <span class="badge bg-primary rounded-3 fw-semibold">H-1</span>
                <?php else : ?>
                  <span class="badge bg-danger rounded-3 fw-semibold">Terlambat</span>
                <?php endif; ?>
              </td>
              <td class="text-center">
                <?php if ($log['status'] === 'sent') : ?>
                  <span class="badge bg-success rounded-3 fw-semibold">Terkirim</span>
                <?php elseif ($log['status'] === 'failed') : ?>
                  <span class="badge bg-danger rounded-3 fw-semibold">Gagal</span>
                <?php else : ?>
                  <span class="badge bg-warning rounded-3 fw-semibold">Dilewati</span>
                <?php endif; ?>
              </td>
              <td class="text-muted small"><?= esc($log['note'] ?? '-') ?></td>
              <td>
                <b><?= date('d/m/Y', strtotime($log['sent_at'])) ?></b><br>
                <small><?= date('H:i:s', strtotime($log['sent_at'])) ?></small>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <?= $pager->links('wa_logs', 'my_pager') ?>

  </div>
</div>

<?= $this->endSection() ?>