<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Pengaturan Poin & Hadiah</title>
<style>
  /* Hilangkan spinner arrows pada input number */
  .no-spinner::-webkit-outer-spin-button,
  .no-spinner::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
  .no-spinner { -moz-appearance: textfield; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
$namaBulan = [
    1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',
    5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',
    9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
];
$labelRank = [1 => '🥇 Peringkat 1', 2 => '🥈 Peringkat 2', 3 => '🥉 Peringkat 3'];
?>

<?php if (session()->getFlashdata('msg')): ?>
  <div class="pb-2">
    <div class="alert <?= (session()->getFlashdata('error') ?? false) ? 'alert-danger' : 'alert-success' ?> alert-dismissible fade show">
      <?= session()->getFlashdata('msg') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  </div>
<?php endif; ?>

<div class="row g-4 align-items-start">

  <!-- Kolom Pengaturan Poin -->
  <div class="col-12 col-lg-6">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title fw-semibold mb-1">Pengaturan Poin Gamifikasi</h5>
        <p class="text-muted small mb-4"></p>
        <form action="<?= base_url('admin/pengaturan-poin') ?>" method="post" id="formPengaturanPoin" novalidate>
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
                    'return_late'   => 'Dikurangi ketika member mengembalikan buku setelah melewati tenggat atau terlambat.',
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
                // Definisi tipe validasi per aktivitas untuk JS
                $validasiTipe = [
                    'visit'         => 'positive',
                    'loan'          => 'positive',
                    'return_ontime' => 'positive',
                    'return_late'   => 'negative',
                ];
                $urutan = ['visit', 'loan', 'return_ontime', 'return_late'];
                foreach ($urutan as $type):
                    $row = $settings[$type] ?? null;
                    if (!$row) continue;
                    $isNegative = ($validasiTipe[$type] === 'negative');
                    // Untuk return_late: tampilkan nilai absolut (positif) di input
                    $displayValue = $isNegative ? abs((int) $row['points']) : (int) $row['points'];
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
                      <?php if ($isNegative): ?>
                        <!-- Prefix minus untuk aktivitas negatif -->
                        <span class="input-group-text bg-danger-subtle text-danger fw-bold">−</span>
                      <?php else: ?>
                        <span class="input-group-text">
                          <i class="ti ti-star-filled text-warning" style="font-size:.8rem"></i>
                        </span>
                      <?php endif; ?>
                      <input type="number"
                             name="points[<?= $type ?>]"
                             id="input_<?= $type ?>"
                             class="form-control text-center fw-bold <?= $warnaPoin[$type] ?> point-input no-spinner"
                             value="<?= $displayValue ?>"
                             style="max-width:150px"
                             data-type="<?= $validasiTipe[$type] ?>"
                             data-label="<?= esc($row['label']) ?>"
                             required>
                    </div>
                    <!-- Pesan error inline -->
                    <div class="invalid-feedback d-block text-start mt-1 ps-1"
                         id="error_<?= $type ?>"
                         style="font-size:.75rem; display:none!important">
                    </div>
                  </td>
                  <td class="text-muted small"><?= $keterangan[$type] ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <div class="alert alert-info d-flex gap-2 align-items-start py-2 mb-4">
            <i class="ti ti-info-circle mt-1 flex-shrink-0"></i>
            <div class="small">
              <b>Poin Kuis</b> tidak dapat diatur di sini karena dihitung otomatis berdasarkan
              persentase jawaban benar (maks. 100 poin).
            </div>
          </div>

          <button type="submit" class="btn btn-primary w-100" id="btnSimpan">
            <i class="ti ti-device-floppy me-1"></i> Simpan Pengaturan
          </button>
        </form>

      </div>
    </div>
  </div>

  <!-- ════ Kolom Kanan: Hadiah Leaderboard ════ -->
  <div class="col-12 col-lg-6">

    <!-- Form tambah/edit hadiah -->
    <div class="card">
      <div class="card-body">
        <h5 class="card-title fw-semibold mb-1">Hadiah Leaderboard</h5>
        <p class="text-muted small mb-4"></p>

        <!-- Tab rank 1/2/3 -->
        <ul class="nav nav-tabs mb-3" id="tabHadiah">
          <?php for ($r = 1; $r <= 3; $r++): ?>
            <li class="nav-item">
              <button class="nav-link <?= $r === 1 ? 'active' : '' ?>"
                      data-bs-toggle="tab"
                      data-bs-target="#tabRank<?= $r ?>">
                <?= $labelRank[$r] ?>
                <?php if (!empty($hadiahBulanIni[$r])): ?>
                  <span class="badge bg-success ms-1" style="font-size:.6rem">✓</span>
                <?php endif; ?>
              </button>
            </li>
          <?php endfor; ?>
        </ul>

        <div class="tab-content" id="tabHadiahContent">
          <?php for ($r = 1; $r <= 3; $r++):
            $hadiah = $hadiahBulanIni[$r] ?? null;
          ?>
            <div class="tab-pane fade <?= $r === 1 ? 'show active' : '' ?>" id="tabRank<?= $r ?>">
              <form action="<?= base_url('admin/hadiah') ?>" method="post"
                    enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="rank"  value="<?= $r ?>">
                <input type="hidden" name="bulan" value="<?= $bulanIni ?>">
                <input type="hidden" name="tahun" value="<?= $tahunIni ?>">

                <?php if ($hadiah && !empty($hadiah['foto'])): ?>
                  <div class="mb-3 d-flex align-items-center gap-3">
                    <img src="<?= base_url('uploads/hadiah/' . $hadiah['foto']) ?>"
                         style="width:64px;height:64px;object-fit:cover;border-radius:10px;border:1px solid #e2e8f0"
                         alt="Foto Hadiah">
                    <div class="small text-muted">Foto hadiah saat ini.<br>Upload baru untuk mengganti.</div>
                  </div>
                <?php endif; ?>

                <div class="mb-3">
                  <label class="form-label fw-semibold">
                    Nama Hadiah <span class="text-danger">*</span>
                  </label>
                  <input type="text" name="nama_hadiah" class="form-control"
                         placeholder="cth: Voucher Belanja Rp 100.000"
                         value="<?= esc($hadiah['nama_hadiah'] ?? '') ?>"
                         required>
                </div>

                <div class="mb-3">
                  <label class="form-label fw-semibold">Deskripsi</label>
                  <textarea name="deskripsi" class="form-control" rows="2"
                            placeholder="Deskripsi singkat hadiah..."><?= esc($hadiah['deskripsi'] ?? '') ?></textarea>
                </div>

                <div class="mb-4">
                  <label class="form-label fw-semibold">Foto Hadiah</label>
                  <input type="file" name="foto" class="form-control"
                         accept="image/jpeg,image/png,image/webp">
                  <div class="form-text">Format: JPG, PNG, WebP. Maks 2MB.</div>
                </div>

                <div class="d-flex gap-2">
                  <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="ti ti-device-floppy me-1"></i>
                    <?= $hadiah ? 'Perbarui Hadiah' : 'Simpan Hadiah' ?>
                  </button>
                  <?php if ($hadiah): ?>
                    <form action="<?= base_url('admin/hadiah/' . $hadiah['id'] . '/toggle') ?>"
                          method="post" class="mb-0">
                      <?= csrf_field() ?>
                      <button type="submit"
                              class="btn <?= $hadiah['is_active'] ? 'btn-warning' : 'btn-success' ?>"
                              title="<?= $hadiah['is_active'] ? 'Nonaktifkan' : 'Aktifkan' ?>">
                        <i class="ti <?= $hadiah['is_active'] ? 'ti-eye-off' : 'ti-eye' ?>"></i>
                      </button>
                    </form>
                    <form action="<?= base_url('admin/hadiah/' . $hadiah['id'] . '/delete') ?>"
                          method="post" class="mb-0"
                          onsubmit="return confirm('Hapus hadiah ini?')">
                      <?= csrf_field() ?>
                      <button type="submit" class="btn btn-danger" title="Hapus">
                        <i class="ti ti-trash"></i>
                      </button>
                    </form>
                  <?php endif; ?>
                </div>

              </form>
            </div>
          <?php endfor; ?>
        </div>
      </div>
    </div>

    <!-- Riwayat hadiah -->
    <?php if (!empty($riwayatHadiah)): ?>
    <div class="card mt-3">
      <div class="card-body">
        <h6 class="fw-semibold mb-3">
          <i class="ti ti-history me-1 text-muted"></i>
          Riwayat Hadiah
        </h6>
        <div class="table-responsive">
          <table class="table table-sm align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>Periode</th>
                <th>Rank</th>
                <th>Hadiah</th>
                <th class="text-center">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($riwayatHadiah as $h): ?>
                <tr>
                  <td class="small text-muted">
                    <?= ($namaBulan[$h['bulan']] ?? $h['bulan']) . ' ' . $h['tahun'] ?>
                  </td>
                  <td><?= $labelRank[$h['rank']] ?? '#' . $h['rank'] ?></td>
                  <td>
                    <div class="fw-semibold small"><?= esc($h['nama_hadiah']) ?></div>
                    <?php if (!empty($h['deskripsi'])): ?>
                      <div class="text-muted" style="font-size:.72rem">
                        <?= esc(mb_strimwidth($h['deskripsi'], 0, 50, '...')) ?>
                      </div>
                    <?php endif; ?>
                  </td>
                  <td class="text-center">
                    <span class="badge <?= $h['is_active'] ? 'bg-success' : 'bg-secondary' ?>">
                      <?= $h['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                    </span>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <?php endif; ?>

  </div>

</div><!-- /row -->

<script>
(function () {
  'use strict';

  // ── Fungsi validasi satu input ───────────────────────────
  function validateInput(input) {
    const type    = input.dataset.type;   // 'positive' | 'negative'
    const label   = input.dataset.label;
    const value   = parseInt(input.value, 10);
    const errorEl = document.getElementById('error_' + input.id.replace('input_', ''));

    let message = '';

    if (isNaN(value)) {
      message = `Poin ${label} harus diisi dengan angka.`;
    } else if (type === 'positive' && value <= 0) {
      message = `Poin <b>${label}</b> harus bernilai positif (lebih dari 0).`;
    } else if (type === 'negative' && value === 0) {
      message = `Poin <b>${label}</b> tidak boleh 0.`;
    } else if (type === 'negative' && value < 0) {
      // User mengetik angka negatif langsung → tolak, minta positif
      message = `Input angka positif untuk <b>${label}</b>. Sistem otomatis menjadikan negatif.`;
    }

    if (message) {
      input.classList.add('is-invalid');
      input.classList.remove('is-valid');
      errorEl.innerHTML = '<i class="ti ti-alert-circle me-1"></i>' + message;
      errorEl.style.display = 'block';
      return false;
    } else {
      input.classList.remove('is-invalid');
      input.classList.add('is-valid');
      errorEl.innerHTML = '';
      errorEl.style.display = 'none';
      return true;
    }
  }

  // ── Pasang event listener real-time pada semua input poin ─
  document.querySelectorAll('.point-input').forEach(function (input) {
    // Validasi saat mengetik
    input.addEventListener('input', function () {
      validateInput(this);
    });

    // Validasi saat keluar dari field
    input.addEventListener('blur', function () {
      validateInput(this);
    });
  });

  // ── Validasi saat submit ──────────────────────────────────
  document.getElementById('formPengaturanPoin').addEventListener('submit', function (e) {
    const inputs  = document.querySelectorAll('.point-input');
    let   isValid = true;
    const errorMessages = [];

    inputs.forEach(function (input) {
      const ok = validateInput(input);
      if (!ok) {
        isValid = false;
        errorMessages.push(input.dataset.label);
      }
    });

    if (!isValid) {
      e.preventDefault();

      // Buat alert error di atas form
      const alertHtml = `
        <div class="alert alert-danger alert-dismissible fade show d-flex gap-2 align-items-start" id="alertValidasiPoin">
          <i class="ti ti-alert-circle fs-5 flex-shrink-0 mt-1"></i>
          <div>
            <b>Gagal menyimpan!</b> Periksa kembali nilai poin berikut:
            <ul class="mb-0 mt-1">
              ${errorMessages.map(l => `<li>${l}</li>`).join('')}
            </ul>
          </div>
          <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>`;

      // Hapus alert lama jika ada
      const oldAlert = document.getElementById('alertValidasiPoin');
      if (oldAlert) oldAlert.remove();

      // Sisipkan alert sebelum form
      const form = document.getElementById('formPengaturanPoin');
      form.insertAdjacentHTML('beforebegin', alertHtml);

      // Scroll ke atas card
      form.closest('.card').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  });

})();
</script>

<?= $this->endSection() ?>