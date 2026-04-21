<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Pengaturan Poin</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php if (session()->getFlashdata('msg')): ?>
  <div class="pb-2">
    <div class="alert <?= (session()->getFlashdata('error') ?? false) ? 'alert-danger' : 'alert-success' ?> alert-dismissible fade show">
      <?= session()->getFlashdata('msg') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  </div>
<?php endif; ?>

<div class="row justify-content-center">
  <div class="col-12 col-lg-7">
    <div class="card">
      <div class="card-body">

        <h5 class="card-title fw-semibold mb-1">Pengaturan Poin Gamifikasi</h5>
        <p class="text-muted small mb-4">
          Atur jumlah poin yang diperoleh member untuk setiap aktivitas.
          Nilai negatif akan mengurangi poin member.
        </p>

        <form action="<?= base_url('admin/pengaturan-poin') ?>" method="post">
          <?= csrf_field() ?>

          <div class="table-responsive">
            <table class="table table-bordered align-middle mb-4">
              <thead class="table-light">
                <tr>
                  <th>Aktivitas</th>
                  <th style="width:180px" class="text-center">Poin</th>
                  <th>Keterangan</th>
                </tr>
              </thead>
              <tbody>

                <?php
                $keterangan = [
                    'visit'         => 'Diberikan setiap member melakukan kunjungan ke perpustakaan (scan QR atau manual).',
                    'loan'          => 'Diberikan setiap member meminjam buku.',
                    'return_ontime' => 'Diberikan ketika member mengembalikan buku tepat waktu atau sebelum tenggat.',
                    'return_late'   => 'Dikurangi ketika member mengembalikan buku setelah melewati tenggat. Isi dengan nilai negatif.',
                ];

                $ikon = [
                    'visit'         => 'ti-door-enter',
                    'loan'          => 'ti-book',
                    'return_ontime' => 'ti-check',
                    'return_late'   => 'ti-clock-exclamation',
                ];

                $warnaPoin = [
                    'visit'         => 'text-success',
                    'loan'          => 'text-primary',
                    'return_ontime' => 'text-success',
                    'return_late'   => 'text-danger',
                ];

                $urutan = ['visit', 'loan', 'return_ontime', 'return_late'];
                foreach ($urutan as $type):
                    $row = $settings[$type] ?? null;
                    if (!$row) continue;
                ?>
                <tr>
                  <td>
                    <div class="d-flex align-items-center gap-2">
                      <i class="ti <?= $ikon[$type] ?> fs-5 <?= $warnaPoin[$type] ?>"></i>
                      <b><?= esc($row['label']) ?></b>
                    </div>
                  </td>
                  <td class="text-center">
                    <div class="input-group input-group-sm justify-content-center">
                      <span class="input-group-text">
                        <i class="ti ti-star-filled text-warning" style="font-size:.8rem"></i>
                      </span>
                      <input type="number"
                             name="points[<?= $type ?>]"
                             class="form-control text-center fw-bold <?= $warnaPoin[$type] ?>"
                             value="<?= $row['points'] ?>"
                             style="max-width:90px"
                             required>
                    </div>
                  </td>
                  <td class="text-muted small"><?= $keterangan[$type] ?></td>
                </tr>
                <?php endforeach; ?>

              </tbody>
            </table>
          </div>

          <!-- Info poin kuis -->
          <div class="alert alert-info d-flex gap-2 align-items-start py-2 mb-4">
            <i class="ti ti-info-circle mt-1 flex-shrink-0"></i>
            <div class="small">
              <b>Poin Kuis</b> tidak dapat diatur di sini karena dihitung otomatis berdasarkan
              persentase jawaban benar (maks. 100 poin).
              Contoh: benar 8 dari 10 soal = <b>80 poin</b>.
            </div>
          </div>

          <button type="submit" class="btn btn-primary w-100">
            <i class="ti ti-device-floppy me-1"></i> Simpan Pengaturan
          </button>
        </form>

      </div>
    </div>

    <!-- Preview poin -->
    <div class="card mt-3">
      <div class="card-body">
        <h6 class="fw-semibold mb-3">
          <i class="ti ti-eye me-1 text-primary"></i>
          Preview Poin Saat Ini
        </h6>
        <div class="row g-2">
          <?php foreach ($urutan as $type):
            $row = $settings[$type] ?? null;
            if (!$row) continue;
            $isNegatif = $row['points'] < 0;
          ?>
            <div class="col-6 col-md-3">
              <div class="text-center p-3 rounded border <?= $isNegatif ? 'border-danger bg-danger bg-opacity-10' : 'border-success bg-success bg-opacity-10' ?>">
                <div class="fw-bold fs-4 <?= $isNegatif ? 'text-danger' : 'text-success' ?>">
                  <?= ($isNegatif ? '' : '+') . $row['points'] ?>
                </div>
                <div class="small text-muted"><?= esc($row['label']) ?></div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

  </div>
</div>

<?= $this->endSection() ?>