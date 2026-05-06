<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Pengaturan Poin & Hadiah</title>
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

<!-- ── Layout 2 kolom ── -->
<div class="row g-4 align-items-start">

  <!-- ════ Kolom Kiri: Pengaturan Poin (tidak diubah) ════ -->
  <div class="col-12 col-lg-6">
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
  </div>

  <!-- ════ Kolom Kanan: Hadiah Leaderboard ════ -->
  <div class="col-12 col-lg-6">

    <!-- Form tambah/edit hadiah -->
    <div class="card">
      <div class="card-body">
        <h5 class="card-title fw-semibold mb-1">Hadiah Leaderboard</h5>
        <p class="text-muted small mb-4">
          Set hadiah untuk peringkat 1, 2, dan 3 pada bulan
          <strong><?= $namaBulan[$bulanIni] . ' ' . $tahunIni ?></strong>.
          Hadiah akan ditampilkan di halaman leaderboard publik.
        </p>

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

                <!-- Preview foto jika sudah ada -->
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
                    <!-- Toggle aktif -->
                    <form action="<?= base_url('admin/hadiah/' . $hadiah['id'] . '/toggle') ?>"
                          method="post" class="mb-0">
                      <?= csrf_field() ?>
                      <button type="submit"
                              class="btn <?= $hadiah['is_active'] ? 'btn-warning' : 'btn-success' ?>"
                              title="<?= $hadiah['is_active'] ? 'Nonaktifkan' : 'Aktifkan' ?>">
                        <i class="ti <?= $hadiah['is_active'] ? 'ti-eye-off' : 'ti-eye' ?>"></i>
                      </button>
                    </form>
                    <!-- Hapus -->
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

  </div><!-- /kolom kanan -->

</div><!-- /row -->

<?= $this->endSection() ?>