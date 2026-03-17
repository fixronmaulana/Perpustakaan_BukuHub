<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Anggota Baru</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<a href="<?= base_url('admin/members'); ?>" class="btn btn-outline-primary mb-3">
  <i class="ti ti-arrow-left"></i>
  Kembali
</a>

<?php if (session()->getFlashdata('msg')) : ?>
  <div class="pb-2">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= session()->getFlashdata('msg') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  </div>
<?php endif; ?>

<div class="card">
  <div class="card-body">
    <h5 class="card-title fw-semibold">Form Anggota Baru</h5>
    <p class="text-muted small mb-4">Username dan password login akan menggunakan <strong>No. Identitas</strong>.</p>
    <form action="<?= base_url('admin/members'); ?>" method="post">
      <?= csrf_field(); ?>

      <!-- Nama -->
      <div class="row mt-3">
        <div class="col-12 col-md-6 mb-3">
          <label for="first_name" class="form-label">Nama Depan <span class="text-danger">*</span></label>
          <input type="text"
                 class="form-control <?= $validation->hasError('first_name') ? 'is-invalid' : '' ?>"
                 id="first_name" name="first_name"
                 value="<?= $oldInput['first_name'] ?? '' ?>"
                 placeholder="Contoh: Budi" required>
          <div class="invalid-feedback"><?= $validation->getError('first_name') ?></div>
        </div>
        <div class="col-12 col-md-6 mb-3">
          <label for="last_name" class="form-label">Nama Belakang</label>
          <input type="text"
                 class="form-control <?= $validation->hasError('last_name') ? 'is-invalid' : '' ?>"
                 id="last_name" name="last_name"
                 value="<?= $oldInput['last_name'] ?? '' ?>"
                 placeholder="Contoh: Santoso">
          <div class="invalid-feedback"><?= $validation->getError('last_name') ?></div>
        </div>
      </div>

      <!-- No. Identitas & Tipe Anggota -->
      <div class="row">
        <div class="col-12 col-md-6 mb-3">
          <label for="no_identitas" class="form-label">No. Identitas (NIS/NISN/NIK) <span class="text-danger">*</span></label>
          <input type="text"
                 class="form-control <?= $validation->hasError('no_identitas') ? 'is-invalid' : '' ?>"
                 id="no_identitas" name="no_identitas"
                 value="<?= $oldInput['no_identitas'] ?? '' ?>"
                 placeholder="Contoh: 12345678" required>
          <div class="form-text">Digunakan sebagai username dan password login.</div>
          <div class="invalid-feedback"><?= $validation->getError('no_identitas') ?></div>
        </div>
        <div class="col-12 col-md-6 mb-3">
          <label for="tipe_anggota" class="form-label">Tipe Anggota <span class="text-danger">*</span></label>
          <select class="form-select <?= $validation->hasError('tipe_anggota') ? 'is-invalid' : '' ?>"
                  id="tipe_anggota" name="tipe_anggota" required>
            <option value="">— Pilih Tipe —</option>
            <option value="Murid" <?= ($oldInput['tipe_anggota'] ?? '') === 'Murid' ? 'selected' : '' ?>>Murid</option>
            <option value="Guru"  <?= ($oldInput['tipe_anggota'] ?? '') === 'Guru'  ? 'selected' : '' ?>>Guru</option>
            <option value="Staf"  <?= ($oldInput['tipe_anggota'] ?? '') === 'Staf'  ? 'selected' : '' ?>>Staf</option>
          </select>
          <div class="invalid-feedback"><?= $validation->getError('tipe_anggota') ?></div>
        </div>
      </div>

      <!-- Email & Phone -->
      <div class="row">
        <div class="col-12 col-md-6 mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email"
                 class="form-control <?= $validation->hasError('email') ? 'is-invalid' : '' ?>"
                 id="email" name="email"
                 value="<?= $oldInput['email'] ?? '' ?>"
                 placeholder="contoh@gmail.com">
          <div class="invalid-feedback"><?= $validation->getError('email') ?></div>
        </div>
        <div class="col-12 col-md-6 mb-3">
          <label for="phone" class="form-label">Nomor Telepon</label>
          <input type="tel"
                 class="form-control <?= $validation->hasError('phone') ? 'is-invalid' : '' ?>"
                 id="phone" name="phone"
                 value="<?= $oldInput['phone'] ?? '' ?>"
                 placeholder="08123456789">
          <div class="invalid-feedback"><?= $validation->getError('phone') ?></div>
        </div>
      </div>

      <!-- Gender -->
      <div class="mb-3">
        <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
        <div class="my-2">
          <div class="form-check form-check-inline">
            <input type="radio" class="form-check-input" id="male" name="gender" value="Male"
                   <?= ($oldInput['gender'] ?? '') === 'Male' ? 'checked' : '' ?> required>
            <label class="form-check-label" for="male">Laki-laki</label>
          </div>
          <div class="form-check form-check-inline">
            <input type="radio" class="form-check-input" id="female" name="gender" value="Female"
                   <?= ($oldInput['gender'] ?? '') === 'Female' ? 'checked' : '' ?> required>
            <label class="form-check-label" for="female">Perempuan</label>
          </div>
        </div>
        <?php if ($validation->hasError('gender')) : ?>
          <div class="text-danger small"><?= $validation->getError('gender') ?></div>
        <?php endif; ?>
      </div>

      <button type="submit" class="btn btn-primary mt-2">Simpan</button>
    </form>
  </div>
</div>
<?= $this->endSection() ?>