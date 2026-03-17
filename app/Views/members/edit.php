<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Edit Anggota</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<a href="<?= previous_url() ?>" class="btn btn-outline-primary mb-3">
  <i class="ti ti-arrow-left"></i>
  Kembali
</a>

<?php if (session()->getFlashdata('msg')) : ?>
  <div class="pb-2">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= session()->getFlashdata('msg') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  </div>
<?php endif; ?>

<div class="card">
  <div class="card-body">
    <h5 class="card-title fw-semibold mb-1">Edit Anggota</h5>

    <form action="<?= base_url('admin/members/' . $member['uid']); ?>" method="post">
      <?= csrf_field(); ?>
      <input type="hidden" name="_method" value="PUT">

      <!-- Nama -->
      <div class="row">
        <div class="col-12 col-md-6 mb-3">
          <label for="first_name" class="form-label">Nama Depan <span class="text-danger">*</span></label>
          <input type="text"
                 class="form-control <?= $validation->hasError('first_name') ? 'is-invalid' : '' ?>"
                 id="first_name" name="first_name"
                 value="<?= esc($oldInput['first_name'] ?? $member['first_name'] ?? '') ?>"
                 required>
          <div class="invalid-feedback"><?= $validation->getError('first_name') ?></div>
        </div>

        <div class="col-12 col-md-6 mb-3">
          <label for="last_name" class="form-label">Nama Belakang</label>
          <input type="text"
                 class="form-control <?= $validation->hasError('last_name') ? 'is-invalid' : '' ?>"
                 id="last_name" name="last_name"
                 value="<?= esc($oldInput['last_name'] ?? $member['last_name'] ?? '') ?>">
          <div class="invalid-feedback"><?= $validation->getError('last_name') ?></div>
        </div>
      </div>

      <!-- No. Identitas + Tipe -->
      <div class="row">
        <div class="col-12 col-md-6 mb-3">
          <label for="no_identitas" class="form-label">No. Identitas (NIS/NISN/NIK) <span class="text-danger">*</span></label>
          <input type="text"
                 class="form-control <?= $validation->hasError('no_identitas') ? 'is-invalid' : '' ?>"
                 id="no_identitas" name="no_identitas"
                 value="<?= esc($oldInput['no_identitas'] ?? $member['no_identitas'] ?? '') ?>"
                 required>
          <div class="form-text">Digunakan sebagai username dan password login.</div>
          <div class="invalid-feedback"><?= $validation->getError('no_identitas') ?></div>
        </div>

        <div class="col-12 col-md-6 mb-3">
          <label for="tipe_anggota" class="form-label">Tipe Anggota <span class="text-danger">*</span></label>
          <select class="form-select <?= $validation->hasError('tipe_anggota') ? 'is-invalid' : '' ?>"
                  id="tipe_anggota" name="tipe_anggota" required>
            <option value="">-- Pilih Tipe --</option>
            <option value="Murid"  <?= ($oldInput['tipe_anggota'] ?? $member['tipe_anggota'] ?? '') === 'Murid' ? 'selected' : '' ?>>Murid</option>
            <option value="Guru"   <?= ($oldInput['tipe_anggota'] ?? $member['tipe_anggota'] ?? '') === 'Guru' ? 'selected' : '' ?>>Guru</option>
            <option value="Staf"   <?= ($oldInput['tipe_anggota'] ?? $member['tipe_anggota'] ?? '') === 'Staf' ? 'selected' : '' ?>>Staf</option>
          </select>
          <div class="invalid-feedback"><?= $validation->getError('tipe_anggota') ?></div>
        </div>
      </div>

      <!-- Email + Phone -->
      <div class="row">
        <div class="col-12 col-md-6 mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email"
                 class="form-control <?= $validation->hasError('email') ? 'is-invalid' : '' ?>"
                 id="email" name="email"
                 value="<?= esc($oldInput['email'] ?? $member['email'] ?? '') ?>">
          <div class="invalid-feedback"><?= $validation->getError('email') ?></div>
        </div>

        <div class="col-12 col-md-6 mb-3">
          <label for="phone" class="form-label">Nomor Telepon</label>
          <input type="tel"
                 class="form-control <?= $validation->hasError('phone') ? 'is-invalid' : '' ?>"
                 id="phone" name="phone"
                 value="<?= esc($oldInput['phone'] ?? $member['phone'] ?? '') ?>">
          <div class="invalid-feedback"><?= $validation->getError('phone') ?></div>
        </div>
      </div>

      <!-- Gender -->
      <div class="mb-4">
        <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
        <?php $gender = $oldInput['gender'] ?? $member['gender'] ?? ''; ?>

        <div class="<?= $validation->hasError('gender') ? 'is-invalid' : '' ?>">
          <div class="form-check form-check-inline">
            <input type="radio" class="form-check-input" id="male" name="gender" value="Male"
                   <?= $gender === 'Male' ? 'checked' : '' ?> required>
            <label class="form-check-label" for="male">Laki-laki</label>
          </div>

          <div class="form-check form-check-inline">
            <input type="radio" class="form-check-input" id="female" name="gender" value="Female"
                   <?= $gender === 'Female' ? 'checked' : '' ?> required>
            <label class="form-check-label" for="female">Perempuan</label>
          </div>
        </div>

        <div class="invalid-feedback d-block"><?= $validation->getError('gender') ?></div>
      </div>

      <button type="submit" class="btn btn-primary">
        <i class="ti ti-device-floppy"></i> Simpan
      </button>
    </form>
  </div>
</div>

<?= $this->endSection() ?>