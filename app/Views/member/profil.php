<?= $this->extend('layouts/member_layout') ?>

<?= $this->section('head') ?>
<title>Profil Saya — Portal Anggota</title>
<?= $this->endSection() ?>

<?= $this->section('pageTitle') ?>Profil Saya<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
  $inisial = '';
  if (!empty($member['first_name'])) $inisial .= strtoupper(substr($member['first_name'], 0, 1));
  if (!empty($member['last_name']))  $inisial .= strtoupper(substr($member['last_name'],  0, 1));
  $inisial = $inisial ?: 'A';

  $fotoUrl   = !empty($member['foto_profil'])
    ? base_url('uploads/foto_profil/' . esc($member['foto_profil']))
    : null;
  $errProfil = $errors    ?? [];
  $errPw     = $errors_pw ?? [];
?>

<div class="profil-wrapper">

  <!-- Flash messages -->
  <?php if (!empty($success)): ?>
    <div class="profil-alert ok" id="flashAlert">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
      <?= esc($success) ?>
      <button onclick="this.closest('.profil-alert').remove()">×</button>
    </div>
  <?php endif; ?>
  <?php if (!empty($error)): ?>
    <div class="profil-alert err" id="flashAlert">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      <?= esc($error) ?>
      <button onclick="this.closest('.profil-alert').remove()">×</button>
    </div>
  <?php endif; ?>

  <!-- ══ KIRI: Informasi Profil ══ -->
  <div class="profil-section">
    <div class="profil-section-header">
      <h3>Informasi Profil</h3>
      <p>Perbarui informasi profil kamu</p>
    </div>

    <form action="<?= base_url('member/profil/update') ?>" method="POST" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <input type="file" id="inputFoto" name="foto_profil" accept="image/jpg,image/jpeg,image/png,image/webp" style="display:none">

      <!-- Foto -->
      <div class="foto-area">
        <?php if ($fotoUrl): ?>
          <img src="<?= $fotoUrl ?>" alt="Foto Profil" class="foto-lingkaran" id="previewImg">
        <?php else: ?>
          <div class="foto-inisial" id="avatarInisial"><?= $inisial ?></div>
        <?php endif; ?>
        <button type="button" class="tombol-ubah-foto" onclick="document.getElementById('inputFoto').click()">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/>
            <circle cx="12" cy="13" r="4"/>
          </svg>
          Ubah Foto
        </button>
        <p class="hint-foto">Format JPG/JPEG/PNG/WEBP, maks. 2 MB</p>
        <div class="info-file-dipilih" id="infoFile" style="display:none">
          <span id="namaFile"></span>
          <button type="button" class="batal-file" onclick="batalFile()">Batalkan</button>
        </div>
      </div>

      <!-- Form 2 kolom -->
      <div class="form-grid">

        <!-- Nama Depan — bisa diubah -->
        <div class="form-grup">
          <label class="form-label" for="first_name">
            Nama Depan <span class="wajib">*</span>
          </label>
          <input type="text" id="first_name" name="first_name"
                 class="form-input <?= !empty($errProfil['first_name']) ? 'invalid' : '' ?>"
                 value="<?= old('first_name', esc($member['first_name'] ?? '')) ?>"
                 placeholder="Nama depan" required>
          <?php if (!empty($errProfil['first_name'])): ?>
            <span class="form-error"><?= esc($errProfil['first_name']) ?></span>
          <?php endif; ?>
        </div>

        <!-- Nama Belakang — bisa diubah -->
        <div class="form-grup">
          <label class="form-label" for="last_name">Nama Belakang</label>
          <input type="text" id="last_name" name="last_name"
                 class="form-input"
                 value="<?= old('last_name', esc($member['last_name'] ?? '')) ?>"
                 placeholder="Nama belakang">
        </div>

        <!-- No. Telepon — bisa diubah -->
        <div class="form-grup">
          <label class="form-label" for="phone">No. Telepon</label>
          <input type="tel" id="phone" name="phone"
                 class="form-input <?= !empty($errProfil['phone']) ? 'invalid' : '' ?>"
                 value="<?= old('phone', esc($member['phone'] ?? '')) ?>"
                 placeholder="08xxxxxxxxxx">
          <?php if (!empty($errProfil['phone'])): ?>
            <span class="form-error"><?= esc($errProfil['phone']) ?></span>
          <?php endif; ?>
        </div>

        <!-- Jenis Kelamin — bisa diubah -->
        <div class="form-grup">
          <label class="form-label" for="gender">Jenis Kelamin</label>
          <select id="gender" name="gender" class="form-select">
            <option value="">— Pilih —</option>
            <option value="Male"   <?= (old('gender', $member['gender'] ?? '') === 'Male')   ? 'selected' : '' ?>>Laki-laki</option>
            <option value="Female" <?= (old('gender', $member['gender'] ?? '') === 'Female') ? 'selected' : '' ?>>Perempuan</option>
          </select>
        </div>

        <!-- No. Identitas — TIDAK bisa diubah -->
        <div class="form-grup">
          <label class="form-label">
            No. Identitas
            <span class="kunci">
              <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
              Tidak dapat diubah
            </span>
          </label>
          <input type="text" class="form-input readonly"
                 value="<?= esc($member['no_identitas'] ?? '—') ?>" readonly>
        </div>

        <!-- Tipe Anggota — TIDAK bisa diubah -->
        <div class="form-grup">
          <label class="form-label">
            Tipe Anggota
            <span class="kunci">
              <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
              Tidak dapat diubah
            </span>
          </label>
          <input type="text" class="form-input readonly"
                 value="<?= esc($member['tipe_anggota'] ?? '—') ?>" readonly>
        </div>

        <!-- Email — TIDAK bisa diubah -->
        <div class="form-grup">
          <label class="form-label">
            Email
            <span class="kunci">
              <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
              Tidak dapat diubah
            </span>
          </label>
          <input type="email" class="form-input readonly"
                 value="<?= esc($member['email'] ?? '—') ?>" readonly>
        </div>

      </div>

      <div class="form-aksi">
        <button type="submit" class="tombol-simpan">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
          Simpan Perubahan
        </button>
      </div>
    </form>
  </div>

  <!-- ══ KANAN: Ubah Kata Sandi ══ -->
  <div class="profil-section">
    <div class="profil-section-header">
      <h3>Ubah Kata Sandi</h3>
      <p>Perbarui kata sandi akun kamu</p>
    </div>

    <form action="<?= base_url('member/profil/password') ?>" method="POST">
      <?= csrf_field() ?>

      <div class="form-grid satu-kolom">

        <div class="form-grup">
          <label class="form-label" for="password_lama">Kata Sandi Lama <span class="wajib">*</span></label>
          <div class="pw-wrap">
            <input type="password" id="password_lama" name="password_lama"
                   class="form-input <?= !empty($errPw['password_lama']) ? 'invalid' : '' ?>"
                   autocomplete="current-password" placeholder="••••••••">
            <button type="button" class="pw-toggle" onclick="togglePw('password_lama', this)">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
          <?php if (!empty($errPw['password_lama'])): ?>
            <span class="form-error"><?= esc($errPw['password_lama']) ?></span>
          <?php endif; ?>
        </div>

        <div class="form-grup">
          <label class="form-label" for="password_baru">Kata Sandi Baru <span class="wajib">*</span></label>
          <div class="pw-wrap">
            <input type="password" id="password_baru" name="password_baru"
                   class="form-input <?= !empty($errPw['password_baru']) ? 'invalid' : '' ?>"
                   autocomplete="new-password" placeholder="Min. 8 karakter"
                   oninput="cekKekuatan(this.value)">
            <button type="button" class="pw-toggle" onclick="togglePw('password_baru', this)">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
          <div class="kekuatan-wrap" id="kekuatanWrap" style="display:none">
            <div class="kekuatan-bar"><div class="kekuatan-isi" id="kekuatanIsi"></div></div>
            <span class="kekuatan-teks" id="kekuatanTeks"></span>
          </div>
          <?php if (!empty($errPw['password_baru'])): ?>
            <span class="form-error"><?= esc($errPw['password_baru']) ?></span>
          <?php endif; ?>
        </div>

        <div class="form-grup">
          <label class="form-label" for="konfirmasi">Konfirmasi Kata Sandi Baru <span class="wajib">*</span></label>
          <div class="pw-wrap">
            <input type="password" id="konfirmasi" name="konfirmasi"
                   class="form-input <?= !empty($errPw['konfirmasi']) ? 'invalid' : '' ?>"
                   autocomplete="new-password" placeholder="Ulangi kata sandi baru"
                   oninput="cekKonfirmasi()">
            <button type="button" class="pw-toggle" onclick="togglePw('konfirmasi', this)">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
          </div>
          <span class="cocok-teks" id="cocokTeks" style="display:none"></span>
          <?php if (!empty($errPw['konfirmasi'])): ?>
            <span class="form-error"><?= esc($errPw['konfirmasi']) ?></span>
          <?php endif; ?>
        </div>

      </div>

      <div class="form-aksi">
        <button type="submit" class="tombol-simpan">
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
          Simpan Password
        </button>
      </div>
    </form>
  </div>

</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Preview foto
document.getElementById('inputFoto').addEventListener('change', function () {
  const file = this.files[0];
  if (!file) return;
  if (file.size > 2 * 1024 * 1024) {
    alert('Ukuran foto maksimal 2 MB.');
    this.value = '';
    return;
  }
  const reader = new FileReader();
  reader.onload = function (e) {
    const avatar = document.getElementById('avatarInisial');
    if (avatar) avatar.style.display = 'none';
    let img = document.getElementById('previewImg');
    if (!img) {
      img = document.createElement('img');
      img.id = 'previewImg';
      img.className = 'foto-lingkaran';
      document.querySelector('.foto-area').prepend(img);
    }
    img.src = e.target.result;
  };
  reader.readAsDataURL(file);
  document.getElementById('namaFile').textContent = file.name;
  document.getElementById('infoFile').style.display = 'flex';
});

function batalFile() {
  document.getElementById('inputFoto').value = '';
  document.getElementById('infoFile').style.display = 'none';
  location.reload();
}

// Toggle password show/hide
function togglePw(id, btn) {
  const input = document.getElementById(id);
  const show  = input.type === 'password';
  input.type  = show ? 'text' : 'password';
  btn.querySelector('svg').innerHTML = show
    ? `<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19M1 1l22 22" fill="none"/>`
    : `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
}

// Indikator kekuatan password
function cekKekuatan(val) {
  const wrap = document.getElementById('kekuatanWrap');
  const isi  = document.getElementById('kekuatanIsi');
  const teks = document.getElementById('kekuatanTeks');
  if (!val) { wrap.style.display = 'none'; return; }
  wrap.style.display = 'flex';
  let s = 0;
  if (val.length >= 8)           s++;
  if (/[A-Z]/.test(val))         s++;
  if (/[0-9]/.test(val))         s++;
  if (/[^A-Za-z0-9]/.test(val))  s++;
  const label = ['Terlalu Pendek', 'Lemah', 'Cukup', 'Kuat', 'Sangat Kuat'];
  const warna = ['#ef4444', '#ef4444', '#f59e0b', '#3b82f6', '#10b981'];
  isi.style.width      = (s * 25) + '%';
  isi.style.background = warna[s];
  teks.textContent     = label[s];
  teks.style.color     = warna[s];
}

// Cek cocok konfirmasi
function cekKonfirmasi() {
  const baru = document.getElementById('password_baru').value;
  const conf = document.getElementById('konfirmasi').value;
  const teks = document.getElementById('cocokTeks');
  if (!conf) { teks.style.display = 'none'; return; }
  teks.style.display = 'inline';
  teks.textContent   = baru === conf ? '✓ Kata sandi cocok' : '✗ Kata sandi tidak cocok';
  teks.style.color   = baru === conf ? 'var(--hijau)' : 'var(--merah)';
}

// Auto-dismiss flash 4 detik
setTimeout(() => {
  const el = document.getElementById('flashAlert');
  if (el) {
    el.style.transition = 'opacity .4s';
    el.style.opacity    = '0';
    setTimeout(() => el.remove(), 400);
  }
}, 4000);
</script>
<?= $this->endSection() ?>