<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Import Anggota</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center gap-2 mb-3">
  <a href="<?= base_url('admin/members') ?>" class="btn btn-outline-primary">
    <i class="ti ti-arrow-left"></i> Kembali
  </a>
  <h5 class="mb-0 fw-semibold">Import Anggota</h5>
</div>

<div class="row justify-content-center">
  <div class="col-12 col-lg-7">

    <div class="card">
      <div class="card-body">

        <h6 class="fw-semibold mb-1">Import dari Excel</h6>
        <p class="text-muted small mb-4">Upload file Excel untuk pendaftaran massal</p>

        <!-- Download Template -->
        <a href="<?= base_url('admin/members/import/template') ?>"
           class="btn btn-primary w-100 mb-4">
          <i class="ti ti-download me-1"></i> Download Template
        </a>

        <!-- Form Upload -->
        <form action="<?= base_url('admin/members/import') ?>" method="post"
              enctype="multipart/form-data" id="formImport">
          <?= csrf_field() ?>

          <!-- Drop zone -->
          <div class="drop-zone" id="dropZone" onclick="document.getElementById('fileExcel').click()">
            <div class="drop-zone-isi" id="dropZoneIsi">
              <i class="ti ti-file-spreadsheet drop-zone-icon"></i>
              <div class="drop-zone-teks">Drag &amp; Drop Excel disini</div>
              <div class="drop-zone-sub">atau klik untuk memilih file</div>
              <div class="drop-zone-format">.xlsx, .xls, .csv</div>
            </div>
            <div class="drop-zone-file" id="dropZoneFile" style="display:none">
              <i class="ti ti-file-check drop-zone-icon text-success"></i>
              <div class="drop-zone-teks" id="namaFile">—</div>
              <div class="drop-zone-sub text-muted" id="ukuranFile">—</div>
              <button type="button" class="btn btn-sm btn-outline-danger mt-2"
                      onclick="event.stopPropagation(); hapusFile()">
                <i class="ti ti-x"></i> Hapus
              </button>
            </div>
          </div>
          <input type="file" id="fileExcel" name="file_excel"
                 accept=".xlsx,.xls,.csv" style="display:none">

          <!-- Tombol proses -->
          <button type="submit" class="btn btn-success w-100 mt-3" id="btnProses" disabled>
            <i class="ti ti-refresh me-1"></i> Proses Import
          </button>

        </form>

        <!-- Hasil import (muncul setelah proses) -->
        <?php if (isset($hasilImport)): ?>
          <hr class="my-4">
          <h6 class="fw-semibold mb-3">Hasil Import</h6>
          <div class="list-group">
            <div class="list-group-item d-flex justify-content-between align-items-center">
              <span>Total Data</span>
              <span class="fw-bold"><?= $hasilImport['total'] ?></span>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center">
              <span class="text-success">Berhasil</span>
              <span class="fw-bold text-success"><?= $hasilImport['berhasil'] ?></span>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center">
              <span class="text-danger">Gagal</span>
              <span class="fw-bold text-danger"><?= $hasilImport['gagal'] ?></span>
            </div>
          </div>

          <!-- Detail error jika ada -->
          <?php if (!empty($hasilImport['errors'])): ?>
            <div class="mt-3">
              <h6 class="text-danger fw-semibold mb-2">Detail Error:</h6>
              <div class="table-responsive">
                <table class="table table-sm table-bordered">
                  <thead class="table-light">
                    <tr>
                      <th>Baris</th>
                      <th>No. Identitas</th>
                      <th>Keterangan</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($hasilImport['errors'] as $err): ?>
                      <tr>
                        <td><?= $err['baris'] ?></td>
                        <td><?= esc($err['no_identitas'] ?? '—') ?></td>
                        <td class="text-danger"><?= esc($err['pesan']) ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          <?php endif; ?>

        <?php endif; ?>

      </div>
    </div>

    <!-- Panduan kolom -->
    <div class="card mt-3">
      <div class="card-body">
        <h6 class="fw-semibold mb-3">
          <i class="ti ti-info-circle me-1 text-primary"></i>
          Panduan Kolom Excel
        </h6>
        <div class="table-responsive">
          <table class="table table-sm table-bordered mb-0">
            <thead class="table-light">
              <tr>
                <th>Kolom</th>
                <th>Nama Kolom di Excel</th>
                <th>Wajib</th>
                <th>Keterangan</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>A</td>
                <td><code>first_name</code></td>
                <td><span class="badge bg-danger">Wajib</span></td>
                <td>Nama depan</td>
              </tr>
              <tr>
                <td>B</td>
                <td><code>last_name</code></td>
                <td><span class="badge bg-secondary">Opsional</span></td>
                <td>Nama belakang</td>
              </tr>
              <tr>
                <td>C</td>
                <td><code>no_identitas</code></td>
                <td><span class="badge bg-danger">Wajib</span></td>
                <td>NIS / NISN / NIK (unik, digunakan sebagai username & password login)</td>
              </tr>
              <tr>
                <td>D</td>
                <td><code>tipe_anggota</code></td>
                <td><span class="badge bg-danger">Wajib</span></td>
                <td>Murid / Guru / Staf</td>
              </tr>
              <tr>
                <td>E</td>
                <td><code>gender</code></td>
                <td><span class="badge bg-danger">Wajib</span></td>
                <td>Male / Female</td>
              </tr>
              <tr>
                <td>F</td>
                <td><code>email</code></td>
                <td><span class="badge bg-secondary">Opsional</span></td>
                <td>Alamat email</td>
              </tr>
              <tr>
                <td>G</td>
                <td><code>phone</code></td>
                <td><span class="badge bg-secondary">Opsional</span></td>
                <td>Nomor telepon</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>

<style>
.drop-zone {
  border: 2px dashed #dee2e6;
  border-radius: 12px;
  padding: 2.5rem 1.5rem;
  text-align: center;
  cursor: pointer;
  transition: border-color 0.2s, background 0.2s;
  background: #f8f9fa;
}
.drop-zone:hover,
.drop-zone.dragover {
  border-color: #1e3a8a;
  background: #eff4ff;
}
.drop-zone-icon {
  font-size: 2.5rem;
  color: #adb5bd;
  display: block;
  margin-bottom: 0.75rem;
}
.drop-zone-teks {
  font-size: 0.95rem;
  font-weight: 600;
  color: #495057;
  margin-bottom: 0.25rem;
}
.drop-zone-sub {
  font-size: 0.82rem;
  color: #6c757d;
}
.drop-zone-format {
  font-size: 0.75rem;
  color: #adb5bd;
  margin-top: 0.5rem;
}
</style>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
const fileInput  = document.getElementById('fileExcel');
const dropZone   = document.getElementById('dropZone');
const dropIsi    = document.getElementById('dropZoneIsi');
const dropFile   = document.getElementById('dropZoneFile');
const namaFile   = document.getElementById('namaFile');
const ukuranFile = document.getElementById('ukuranFile');
const btnProses  = document.getElementById('btnProses');

// Klik file input
fileInput.addEventListener('change', function() {
  if (this.files[0]) tampilkanFile(this.files[0]);
});

// Drag & drop
dropZone.addEventListener('dragover', function(e) {
  e.preventDefault();
  this.classList.add('dragover');
});

dropZone.addEventListener('dragleave', function() {
  this.classList.remove('dragover');
});

dropZone.addEventListener('drop', function(e) {
  e.preventDefault();
  this.classList.remove('dragover');
  const file = e.dataTransfer.files[0];
  if (file) {
    fileInput.files = e.dataTransfer.files;
    tampilkanFile(file);
  }
});

function tampilkanFile(file) {
  const ekstensi = file.name.split('.').pop().toLowerCase();
  if (!['xlsx','xls','csv'].includes(ekstensi)) {
    alert('Format file tidak didukung. Gunakan .xlsx, .xls, atau .csv');
    return;
  }
  namaFile.textContent   = file.name;
  ukuranFile.textContent = (file.size / 1024).toFixed(1) + ' KB';
  dropIsi.style.display  = 'none';
  dropFile.style.display = 'block';
  btnProses.disabled     = false;
}

function hapusFile() {
  fileInput.value        = '';
  dropIsi.style.display  = 'block';
  dropFile.style.display = 'none';
  btnProses.disabled     = true;
}
</script>
<?= $this->endSection() ?>