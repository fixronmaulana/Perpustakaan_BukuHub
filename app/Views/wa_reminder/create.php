<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Tambah Template WA</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row">
  <div class="col-12 col-lg-7">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title fw-semibold mb-4">Tambah Template WA</h5>

        <form action="<?= base_url('admin/wa-reminder') ?>" method="post">
          <?= csrf_field() ?>

          <div class="mb-3">
            <label class="form-label fw-semibold">
              Nama Template <span class="text-danger">*</span>
            </label>
            <input type="text" name="template_name"
              class="form-control <?= $validation->hasError('template_name') ? 'is-invalid' : '' ?>"
              value="<?= esc(old('template_name')) ?>"
              placeholder="cth. Reminder H-1 untuk Murid">
            <div class="invalid-feedback"><?= $validation->getError('template_name') ?></div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">
              Tipe <span class="text-danger">*</span>
            </label>
            <select name="type" class="form-select <?= $validation->hasError('type') ? 'is-invalid' : '' ?>">
              <option value="">-- Pilih Tipe --</option>
              <option value="before_due" <?= old('type') === 'before_due' ? 'selected' : '' ?>>
                H-1 (Sehari sebelum jatuh tempo)
              </option>
              <option value="overdue" <?= old('type') === 'overdue' ? 'selected' : '' ?>>
                H+1 (Sudah melewati jatuh tempo)
              </option>
            </select>
            <div class="invalid-feedback"><?= $validation->getError('type') ?></div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">
              Isi Pesan <span class="text-danger">*</span>
              <button type="button" class="btn btn-sm btn-outline-secondary ms-2" id="btnPreview">
                <i class="ti ti-eye me-1"></i> Preview
              </button>
            </label>
            <textarea name="message_template" id="messageTemplate" rows="9"
              class="form-control <?= $validation->hasError('message_template') ? 'is-invalid' : '' ?>"
              placeholder="Tulis pesan di sini. Gunakan variabel seperti {nama}, {judul_buku}, dst."><?= esc(old('message_template')) ?></textarea>
            <div class="invalid-feedback"><?= $validation->getError('message_template') ?></div>
            <small class="text-muted">Bold WA: *teks* &nbsp;|&nbsp; Baris baru: Enter</small>
          </div>

          <div class="mb-4">
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" name="is_active"
                id="isActive" value="1" <?= old('is_active', '1') ? 'checked' : '' ?>>
              <label class="form-check-label" for="isActive">Aktifkan template ini</label>
            </div>
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
              <i class="ti ti-device-floppy me-1"></i> Simpan Template
            </button>
            <a href="<?= base_url('admin/wa-reminder') ?>" class="btn btn-outline-secondary">
              Batal
            </a>
          </div>

        </form>
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-5">

    <!-- Preview -->
    <div class="card mb-3">
      <div class="card-body">
        <h5 class="card-title fw-semibold mb-3">
          <i class="ti ti-brand-whatsapp text-success me-1"></i> Preview Pesan
        </h5>
        <div id="previewBox" class="bg-light rounded p-3"
          style="min-height:150px; white-space:pre-wrap; font-size:0.875rem;">
          <span class="text-muted">Klik tombol Preview untuk melihat hasil pesan...</span>
        </div>
      </div>
    </div>

    <!-- Panduan Variabel -->
    <div class="card">
      <div class="card-body">
        <h5 class="card-title fw-semibold mb-3">📌 Variabel Tersedia</h5>
        <?php
        $vars = [
          '{nama}'            => 'Nama lengkap member',
          '{judul_buku}'      => 'Judul buku',
          '{tgl_pinjam}'      => 'Tanggal pinjam',
          '{tgl_jatuh_tempo}' => 'Tanggal jatuh tempo',
          '{hari_tersisa}'    => 'Sisa hari (H-1)',
          '{hari_terlambat}'  => 'Hari terlambat (H+1)',
        ];
        foreach ($vars as $var => $desc) : ?>
          <div class="d-flex justify-content-between align-items-center border-bottom py-2 small">
            <code class="text-primary"><?= $var ?></code>
            <span class="text-muted"><?= $desc ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

  </div>
</div>

<script>
document.getElementById('btnPreview').addEventListener('click', function () {
  const msg = document.getElementById('messageTemplate').value;
  fetch('<?= base_url('admin/wa-reminder/preview') ?>', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
      'X-Requested-With': 'XMLHttpRequest',
    },
    body: new URLSearchParams({
      message_template: msg,
      '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
    })
  })
  .then(r => r.json())
  .then(data => {
    document.getElementById('previewBox').textContent = data.preview;
  });
});
</script>

<?= $this->endSection() ?>