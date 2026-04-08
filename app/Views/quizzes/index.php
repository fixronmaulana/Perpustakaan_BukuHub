<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Kelola Kuis</title>
<!-- Select2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
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

<div class="card">
  <div class="card-body">

    <div class="row mb-3">
      <div class="col-12 col-lg-5">
        <h5 class="card-title fw-semibold mb-0">Data Kuis</h5>
      </div>
      <div class="col-12 col-lg-7">
        <div class="d-flex gap-2 justify-content-md-end">
          <form action="" method="get">
            <div class="input-group">
              <input type="text" class="form-control" name="search"
                     value="<?= esc($search ?? '') ?>"
                     placeholder="Cari nama kuis / judul buku">
              <button class="btn btn-outline-secondary" type="submit">Cari</button>
            </div>
          </form>
          <button class="btn btn-primary text-nowrap" data-bs-toggle="modal" data-bs-target="#modalTambahKuis">
            <i class="ti ti-plus"></i> Tambah Kuis
          </button>
        </div>
      </div>
    </div>

    <div class="overflow-x-scroll">
      <table class="table table-hover table-striped">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Nama Kuis</th>
            <th>Buku</th>
            <th class="text-center">Jumlah Soal</th>
            <th class="text-center">Durasi</th>
            <th class="text-center">Maks. Percobaan</th>
            <th class="text-center">Status</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="table-group-divider">
          <?php if (empty($quizzes)): ?>
            <tr>
              <td colspan="8" class="text-center py-4"><b>Belum ada data kuis</b></td>
            </tr>
          <?php else: ?>
            <?php $i = 1; foreach ($quizzes as $quiz): ?>
              <tr>
                <th><?= $i++ ?></th>
                <td><b><?= esc($quiz['name']) ?></b></td>
                <td>
                  <div><?= esc($quiz['book_title']) ?></div>
                  <small class="text-muted"><?= esc($quiz['author']) ?></small>
                </td>
                <td class="text-center">
                  <span class="badge bg-primary rounded-3"><?= $quiz['total_soal'] ?> soal</span>
                </td>
                <td class="text-center"><?= $quiz['duration_minutes'] ?> menit</td>
                <td class="text-center"><?= $quiz['max_attempts'] ?>x</td>
                <td class="text-center">
                  <?php if ($quiz['is_active']): ?>
                    <span class="badge bg-success rounded-3">Aktif</span>
                  <?php else: ?>
                    <span class="badge bg-secondary rounded-3">Nonaktif</span>
                  <?php endif; ?>
                </td>
                <td>
                  <div class="d-flex justify-content-center gap-1">
                    <a href="<?= base_url("admin/kuis/{$quiz['id']}") ?>"
                       class="btn btn-sm btn-primary">
                      <i class="ti ti-list"></i> Kelola soal
                    </a>
                    <form action="<?= base_url("admin/kuis/{$quiz['id']}/toggle") ?>" method="post">
                      <?= csrf_field() ?>
                      <button type="submit" class="btn btn-sm <?= $quiz['is_active'] ? 'btn-warning' : 'btn-success' ?>"
                              title="<?= $quiz['is_active'] ? 'Nonaktifkan' : 'Aktifkan' ?>">
                        <i class="ti ti-<?= $quiz['is_active'] ? 'eye-off' : 'eye' ?>"></i>
                      </button>
                    </form>
                    <form action="<?= base_url("admin/kuis/{$quiz['id']}") ?>" method="post">
                      <?= csrf_field() ?>
                      <input type="hidden" name="_method" value="DELETE">
                      <button type="submit" class="btn btn-sm btn-danger"
                              onclick="return confirm('Hapus kuis ini beserta semua soalnya?')">
                        <i class="ti ti-trash"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

  </div>
</div>

<!-- ── Modal Tambah Kuis ── -->
<div class="modal fade" id="modalTambahKuis" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="<?= base_url('admin/kuis') ?>" method="post">
        <?= csrf_field() ?>
        <div class="modal-header">
          <h5 class="modal-title fw-semibold">Tambah Kuis Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Pilih Buku <span class="text-danger">*</span></label>
            <select name="book_id" id="selectBuku" class="form-select" required>
              <option value="">-- Pilih Buku --</option>
              <?php foreach ($books as $book): ?>
                <option value="<?= $book['id'] ?>">
                  <?= esc($book['title']) ?> (<?= esc($book['year']) ?>) — <?= esc($book['author']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Nama Kuis <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control"
                   placeholder="cth: Kuis Pemahaman Laskar Pelangi" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Deskripsi <span class="text-muted small">(opsional)</span></label>
            <textarea name="description" class="form-control" rows="2"
                      placeholder="Deskripsi singkat tentang kuis ini..."></textarea>
          </div>
          <div class="row">
            <div class="col-6 mb-3">
              <label class="form-label">Durasi (menit) <span class="text-danger">*</span></label>
              <input type="number" name="duration_minutes" class="form-control"
                     value="15" min="1" max="180" required>
            </div>
            <div class="col-6 mb-3">
              <label class="form-label">Maks. Percobaan <span class="text-danger">*</span></label>
              <input type="number" name="max_attempts" class="form-control"
                     value="3" min="1" max="10" required>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">
            <i class="ti ti-check me-1"></i> Simpan Kuis
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
  $('#selectBuku').select2({
    theme: 'bootstrap-5',
    dropdownParent: $('#modalTambahKuis'),
    placeholder: '-- Pilih Buku --',
    allowClear: true,
  });
</script>
<?= $this->endSection() ?>