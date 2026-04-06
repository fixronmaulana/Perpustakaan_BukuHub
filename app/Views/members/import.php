<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Import Anggota</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex align-items-center gap-3 mb-4">
  <a href="<?= base_url('admin/members') ?>" class="btn btn-outline-primary">
    <i class="ti ti-arrow-left"></i> Kembali
  </a>
  <h5 class="mb-0 fw-semibold">Import Anggota</h5>
</div>

<div class="row">

  <!-- ── Kolom kiri: Form upload ── -->
  <div class="col-12 col-lg-7 mb-4">
    <div class="card">
      <div class="card-body">

        <h6 class="fw-semibold mb-1">Import dari Excel</h6>
        <p class="text-muted small mb-4">Upload file Excel untuk pendaftaran anggota massal</p>

        <!-- Download Template -->
        <a href="<?= base_url('admin/members/import/template') ?>"
           class="btn btn-primary w-100 mb-4">
          <i class="ti ti-download me-1"></i> Download Template Excel
        </a>

        <!-- Form Upload -->
        <form action="<?= base_url('admin/members/import') ?>" method="post"
              enctype="multipart/form-data" id="formImport">
          <?= csrf_field() ?>

          <!-- Drop zone -->
          <div class="drop-zone" id="dropZone"
               onclick="document.getElementById('fileExcel').click()">
            <div id="dropZoneIsi">
              <i class="ti ti-file-spreadsheet" style="font-size:2.5rem;color:#adb5bd;display:block;margin-bottom:.75rem"></i>
              <div style="font-size:.95rem;font-weight:600;color:#495057;margin-bottom:.25rem">
                Drag &amp; Drop file Excel disini
              </div>
              <div style="font-size:.82rem;color:#6c757d">atau klik untuk memilih file</div>
              <div style="font-size:.75rem;color:#adb5bd;margin-top:.5rem">.xlsx · .xls · .csv</div>
            </div>
            <div id="dropZoneFile" style="display:none">
              <i class="ti ti-file-check text-success" style="font-size:2.5rem;display:block;margin-bottom:.75rem"></i>
              <div class="fw-semibold" id="namaFile">—</div>
              <div class="text-muted small" id="ukuranFile">—</div>
              <button type="button" class="btn btn-sm btn-outline-danger mt-2"
                      onclick="event.stopPropagation(); hapusFile()">
                <i class="ti ti-x"></i> Hapus
              </button>
            </div>
          </div>

          <input type="file" id="fileExcel" name="file_excel"
                 accept=".xlsx,.xls,.csv" style="display:none">

          <button type="submit" class="btn btn-success w-100 mt-3"
                  id="btnProses" disabled>
            <i class="ti ti-refresh me-1"></i> Proses Import
          </button>
        </form>

        <!-- Hasil import -->
        <?php if (isset($hasilImport)): ?>
          <hr class="my-4">
          <h6 class="fw-semibold mb-3">Hasil Import</h6>
          <div class="list-group mb-3">
            <div class="list-group-item d-flex justify-content-between align-items-center py-2">
              <span class="fw-500">Total Data</span>
              <span class="fw-bold"><?= $hasilImport['total'] ?></span>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center py-2">
              <span class="text-success fw-500">Berhasil</span>
              <span class="fw-bold text-success"><?= $hasilImport['berhasil'] ?></span>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center py-2">
              <span class="text-danger fw-500">Gagal</span>
              <span class="fw-bold text-danger"><?= $hasilImport['gagal'] ?></span>
            </div>
          </div>

          <?php if ($hasilImport['berhasil'] > 0): ?>
            <div class="alert alert-success">
              <i class="ti ti-check-circle me-1"></i>
              <?= $hasilImport['berhasil'] ?> anggota berhasil didaftarkan.
            </div>
          <?php endif; ?>

          <?php if (!empty($hasilImport['errors'])): ?>
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
                      <td class="text-danger small"><?= esc($err['pesan']) ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        <?php endif; ?>

      </div>
    </div>
  </div>

  <!-- ── Kolom kanan: Panduan ── -->
  <div class="col-12 col-lg-5 mb-4">
    <div class="card">
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
                <th>Nama</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>A</td>
                <td><code>first_name</code></td>
                <td><span class="badge bg-danger">Wajib</span></td>
              </tr>
              <tr>
                <td>B</td>
                <td><code>last_name</code></td>
                <td><span class="badge bg-secondary">Opsional</span></td>
              </tr>
              <tr>
                <td>C</td>
                <td><code>no_identitas</code></td>
                <td><span class="badge bg-danger">Wajib</span></td>
              </tr>
              <tr>
                <td>D</td>
                <td><code>tipe_anggota</code></td>
                <td><span class="badge bg-danger">Wajib</span></td>
              </tr>
              <tr>
                <td>E</td>
                <td><code>gender</code></td>
                <td><span class="badge bg-danger">Wajib</span></td>
              </tr>
              <tr>
                <td>F</td>
                <td><code>email</code></td>
                <td><span class="badge bg-secondary">Opsional</span></td>
              </tr>
              <tr>
                <td>G</td>
                <td><code>phone</code></td>
                <td><span class="badge bg-secondary">Opsional</span></td>
              </tr>
            </tbody>
          </table>
        </div>

        <hr>

        <h6 class="fw-semibold mb-2">Nilai yang Diizinkan</h6>
        <ul class="small text-muted mb-0">
          <li><code>tipe_anggota</code> → <b>Murid</b>, <b>Guru</b>, atau <b>Staf</b></li>
          <li><code>gender</code> → <b>Male</b> atau <b>Female</b></li>
          <li><code>no_identitas</code> harus unik, dipakai sebagai username &amp; password login</li>
          <li>Baris pertama adalah header, <b>jangan dihapus</b></li>
          <li>Email kosong akan diisi otomatis: <code>no_identitas@member.local</code></li>
        </ul>
      </div>
    </div>

    <!-- Info akun -->
    <div class="card mt-3 border-primary">
      <div class="card-body">
        <h6 class="fw-semibold text-primary mb-2">
          <i class="ti ti-key me-1"></i> Info Akun Login
        </h6>
        <p class="small text-muted mb-0">
          Setiap anggota yang berhasil diimport akan otomatis mendapatkan akun login dengan:
        </p>
        <ul class="small text-muted mt-2 mb-0">
          <li><b>Username:</b> no_identitas</li>
          <li><b>Password:</b> no_identitas (dapat diubah sendiri)</li>
        </ul>
      </div>
    </div>
  </div>

</div>

<style>
.drop-zone {
  border: 2px dashed #dee2e6;
  border-radius: 10px;
  padding: 2.5rem 1.5rem;
  text-align: center;
  cursor: pointer;
  transition: border-color .2s, background .2s;
  background: #f8f9fa;
}
.drop-zone:hover,
.drop-zone.dragover {
  border-color: #1e3a8a;
  background: #eff4ff;
}
</style>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
const fileInput = document.getElementById('fileExcel');
const dropZone  = document.getElementById('dropZone');
const dropIsi   = document.getElementById('dropZoneIsi');
const dropFile  = document.getElementById('dropZoneFile');
const namaFile  = document.getElementById('namaFile');
const ukuran    = document.getElementById('ukuranFile');
const btnProses = document.getElementById('btnProses');

fileInput.addEventListener('change', function() {
  if (this.files[0]) tampilkanFile(this.files[0]);
});

dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('dragover'); });
dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
dropZone.addEventListener('drop', function(e) {
  e.preventDefault();
  dropZone.classList.remove('dragover');
  const file = e.dataTransfer.files[0];
  if (file) { fileInput.files = e.dataTransfer.files; tampilkanFile(file); }
});

function tampilkanFile(file) {
  const ext = file.name.split('.').pop().toLowerCase();
  if (!['xlsx','xls','csv'].includes(ext)) {
    alert('Format tidak didukung. Gunakan .xlsx, .xls, atau .csv');
    return;
  }
  namaFile.textContent  = file.name;
  ukuran.textContent    = (file.size / 1024).toFixed(1) + ' KB';
  dropIsi.style.display = 'none';
  dropFile.style.display = 'block';
  btnProses.disabled    = false;
}

function hapusFile() {
  fileInput.value        = '';
  dropIsi.style.display  = 'block';
  dropFile.style.display = 'none';
  btnProses.disabled     = true;
}
</script>
<?= $this->endSection() ?>