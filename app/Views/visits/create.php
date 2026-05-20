<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Catat Kunjungan</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<a href="<?= base_url('admin/kunjungan') ?>" class="btn btn-outline-primary mb-3">
  <i class="ti ti-arrow-left"></i> Kembali
</a>

<div class="row">

  <!-- ── Kolom kiri: Scan QR ── -->
  <div class="col-12 col-lg-6 mb-3">
    <div class="card h-100">
      <div class="card-body">
        <h5 class="card-title fw-semibold mb-3">
          <i class="ti ti-qrcode me-1"></i> Scan QR Anggota
        </h5>

        <!-- Scanner -->
        <div id="reader" class="border border-2 border-primary mb-3"
             style="max-width:400px;min-height:350px;border-radius:10px;overflow:hidden"></div>

        <button class="btn btn-primary mb-3" style="display:none" id="resumeBtn"
                onclick="html5QrcodeScanner.resume(); this.style.display='none'">
          <i class="ti ti-refresh"></i> Scan Ulang
        </button>

        <!-- Hasil scan -->
        <div id="hasilScan" style="display:none">
          <div class="alert alert-dismissible fade show" id="alertScan" role="alert">
            <span id="pesanScan"></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
          <div id="infoMemberScan" class="card border-0 bg-light" style="display:none">
            <div class="card-body py-2">
              <b id="namaMemberScan"></b><br>
              <small class="text-muted" id="identitasMemberScan"></small>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- ── Kolom kanan: Form Manual ── -->
  <div class="col-12 col-lg-6 mb-3">
    <div class="card h-100">
      <div class="card-body">
        <h5 class="card-title fw-semibold mb-3">
          <i class="ti ti-pencil me-1"></i> Catat Manual
        </h5>

        <form action="<?= base_url('admin/kunjungan') ?>" method="post">
          <?= csrf_field() ?>

          <!-- Cari anggota -->
          <div class="mb-3">
            <label class="form-label">Cari Anggota <span class="text-danger">*</span></label>
            <input type="text" class="form-control mb-2" id="cariAnggota"
                   placeholder="Ketik nama atau no. identitas..."
                   autocomplete="off">
            <div id="hasilCari" class="list-group" style="display:none"></div>
            <input type="hidden" name="member_uid" id="memberUid"
                   value="<?= esc($oldInput['member_uid'] ?? '') ?>">
            <?php if (!empty($errorMember)): ?>
              <div class="text-danger small mt-1"><?= esc($errorMember) ?></div>
            <?php endif; ?>
            <!-- Info anggota terpilih -->
            <div id="infoAnggotaTerpilih" class="card border-0 bg-light mt-2"
                 style="display:<?= !empty($oldInput['member_uid']) ? 'block' : 'none' ?>">
              <div class="card-body py-2">
                <b id="namaAnggotaTerpilih"></b><br>
                <small class="text-muted" id="identitasAnggotaTerpilih"></small>
              </div>
            </div>
          </div>

          <!-- Tanggal kunjungan -->
          <div class="mb-3">
            <label class="form-label">Tanggal & Waktu Kunjungan <span class="text-danger">*</span></label>
            <input type="datetime-local" class="form-control" name="visit_date"
                  value="<?= esc($oldInput['visit_date'] ?? date('Y-m-d\TH:i')) ?>"
                  max="<?= date('Y-m-d\TH:i') ?>"
                  required>
            <?php if ($validation->hasError('visit_date')): ?>
              <div class="text-danger small mt-1"><?= $validation->getError('visit_date') ?></div>
            <?php endif; ?>
            <?php if (!empty($errorVisitDate)): ?>
              <div class="text-danger small mt-1"><?= esc($errorVisitDate) ?></div>
            <?php endif; ?>
          </div>

          <!-- Catatan -->
          <div class="mb-3">
            <label class="form-label">Catatan <span class="text-muted">(opsional)</span></label>
            <select class="form-select" id="notesSelect" onchange="toggleCatatanLain(this.value)">
              <option value="">— Pilih tujuan kunjungan —</option>
              <option value="Kunjungan"
                <?= ($oldInput['notes'] ?? '') === 'Kunjungan' ? 'selected' : '' ?>>
                Kunjungan Perpustakaan
              </option>
              <option value="Belajar / Mengerjakan tugas"
                <?= ($oldInput['notes'] ?? '') === 'Belajar / Mengerjakan tugas' ? 'selected' : '' ?>>
                Belajar / Mengerjakan tugas
              </option>
              <option value="Membaca buku"
                <?= ($oldInput['notes'] ?? '') === 'Membaca buku' ? 'selected' : '' ?>>
                Membaca buku
              </option>
              <option value="Meminjam / Mengembalikan buku"
                <?= ($oldInput['notes'] ?? '') === 'Meminjam / Mengembalikan buku' ? 'selected' : '' ?>>
                Meminjam / Mengembalikan buku
              </option>
              <option value="Menggunakan komputer / internet"
                <?= ($oldInput['notes'] ?? '') === 'Menggunakan komputer / internet' ? 'selected' : '' ?>>
                Menggunakan komputer / internet
              </option>
              <option value="__lainnya__"
                <?= !empty($oldInput['notes']) && !in_array($oldInput['notes'], [
                  'Kunjungan','Belajar / Mengerjakan tugas','Membaca buku',
                  'Meminjam / Mengembalikan buku','Menggunakan komputer / internet'
                ]) ? 'selected' : '' ?>>
                Lainnya...
              </option>
            </select>

            <!-- Input teks muncul hanya jika pilih "Lainnya" -->
            <input type="text" class="form-control mt-2" id="notesLainInput"
                  placeholder="Tulis catatan..."
                  style="display:<?= (!empty($oldInput['notes']) && !in_array($oldInput['notes'], [
                    'Kunjungan','Belajar / Mengerjakan tugas','Membaca buku',
                    'Meminjam / Mengembalikan buku','Menggunakan komputer / internet'
                  ])) ? 'block' : 'none' ?>"
                  value="<?= (!empty($oldInput['notes']) && !in_array($oldInput['notes'], [
                    'Kunjungan','Belajar / Mengerjakan tugas','Membaca buku',
                    'Meminjam / Mengembalikan buku','Menggunakan komputer / internet'
                  ])) ? esc($oldInput['notes']) : '' ?>">

            <!-- Input hidden inilah yang dikirim ke controller -->
            <input type="hidden" name="notes" id="notesHidden"
                  value="<?= esc($oldInput['notes'] ?? '') ?>">
          </div>

          <button type="submit" class="btn btn-primary w-100">
            <i class="ti ti-check me-1"></i> Simpan Kunjungan
          </button>
        </form>

      </div>
    </div>
  </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/libs/html5-qrcode/html5-qrcode.min.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// ── Scanner QR ──────────────────────────────────────────────
const html5QrcodeScanner = new Html5QrcodeScanner(
  'reader',
  { formatsToSupport: [Html5QrcodeSupportedFormats.QR_CODE] },
  { fps: 30, qrbox: { width: 250, height: 250 } },
  false
);

function onScanSuccess(decodedText) {
  html5QrcodeScanner.pause(true);
  document.getElementById('resumeBtn').style.display = 'block';
  kirimScan(decodedText);
}

function onScanFailure(error) {}

html5QrcodeScanner.render(onScanSuccess, onScanFailure);

setTimeout(() => {
  ['#html5-qrcode-button-camera-start',
   '#html5-qrcode-button-camera-stop',
   '#html5-qrcode-button-file-selection'].forEach(sel => {
    const el = document.querySelector(sel);
    if (el) el.classList.add('btn', 'btn-primary', 'mb-2');
  });
}, 3000);

function kirimScan(uid) {
  fetch('<?= base_url('admin/kunjungan/scan') ?>', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
    },
    body: new URLSearchParams({
      uid: uid,
      '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
    })
  })
  .then(r => r.json())
  .then(data => {
    const hasilDiv  = document.getElementById('hasilScan');
    const alertDiv  = document.getElementById('alertScan');
    const pesanSpan = document.getElementById('pesanScan');
    const infoDiv   = document.getElementById('infoMemberScan');

    hasilDiv.style.display = 'block';
    pesanSpan.textContent  = data.message;

    if (data.success) {
      // --- MODAL REWARD POIN ---
      Swal.fire({
          width: '360px',
          icon: 'success',
          title: '<span style="font-size:1.5rem; font-weight:700;">Kunjungan Berhasil!</span>',
          html: `
              <p style="color:#6c757d; font-size:0.82rem; margin-top:-8px; margin-bottom:12px;">
                  Kunjungan tercatat dalam sistem
              </p>
              <div style="background:#d1e7dd; border-radius:10px; padding:10px 16px; margin-bottom:12px;">
                  <span style="font-size:0.78rem; color:#0f5132; font-weight:600; text-transform:uppercase; letter-spacing:0.05em;">
                      Reward Poin Diberikan
                  </span>
                  <div style="font-size:1.8rem; font-weight:700; color:#0f5132; line-height:1.5;">
                      ${data.poin}
                  </div>
              </div>
              <div style="font-size:0.82rem; color:#495057;">
                  <b>${data.member.nama}</b> &nbsp;|&nbsp; ${data.member.no_identitas}
              </div>
          `,
          iconColor: '#198754',
          showConfirmButton: true,
          confirmButtonText: 'Selesai',
          buttonsStyling: false,
          customClass: {
              popup: 'rounded-4',
              confirmButton: 'btn btn-success w-100 py-2 mt-2'
          }
      }).then(() => {
          html5QrcodeScanner.resume();
          document.getElementById('resumeBtn').style.display = 'none';
          hasilDiv.style.display = 'none';
      });

      alertDiv.className = 'alert alert-success alert-dismissible fade show';
      infoDiv.style.display = 'block';
      document.getElementById('namaMemberScan').textContent      = data.member.nama;
      document.getElementById('identitasMemberScan').textContent = data.member.no_identitas + ' — ' + data.member.tipe;

    } else {
      // --- MODAL PERINGATAN SUDAH KUNJUNGAN ---
      Swal.fire({
        title: 'Perhatian',
        text: data.message,
        icon: 'warning',
        confirmButtonColor: '#0d6efd'
      }).then(() => {
        html5QrcodeScanner.resume();
        document.getElementById('resumeBtn').style.display = 'none';
      });

      alertDiv.className = 'alert alert-warning alert-dismissible fade show';
      infoDiv.style.display = data.member ? 'block' : 'none';
      if (data.member) {
        document.getElementById('namaMemberScan').textContent      = data.member.nama;
        document.getElementById('identitasMemberScan').textContent = data.member.no_identitas + ' — ' + data.member.tipe;
      }
    }
  })
  .catch(() => {
    Swal.fire('Error', 'Terjadi kesalahan sistem.', 'error');
  });
}
// ── Cari anggota untuk form manual ─────────────────────────
let cariTimeout;
document.getElementById('cariAnggota').addEventListener('input', function() {
  clearTimeout(cariTimeout);
  const val = this.value.trim();
  if (val.length < 2) {
    document.getElementById('hasilCari').style.display = 'none';
    return;
  }
  cariTimeout = setTimeout(() => cariAnggota(val), 350);
});

function cariAnggota(q) {
  fetch(`<?= base_url('admin/kunjungan/search') ?>?param=${encodeURIComponent(q)}`, {
    headers: { 'X-Requested-With': 'XMLHttpRequest' }
  })
  .then(r => r.json())
  .then(data => {
    const box = document.getElementById('hasilCari');
    if (!data.length) {
      box.style.display = 'none';
      return;
    }
    box.innerHTML = data.map(m =>
      `<button type="button" class="list-group-item list-group-item-action"
               onclick="pilihAnggota('${m.uid}','${m.nama}','${m.no_identitas}','${m.tipe}')">
         <b>${m.nama}</b> <small class="text-muted">${m.no_identitas} — ${m.tipe}</small>
       </button>`
    ).join('');
    box.style.display = 'block';
  });
}

function pilihAnggota(uid, nama, noId, tipe) {
  document.getElementById('memberUid').value             = uid;
  document.getElementById('cariAnggota').value           = nama;
  document.getElementById('hasilCari').style.display     = 'none';
  document.getElementById('infoAnggotaTerpilih').style.display = 'block';
  document.getElementById('namaAnggotaTerpilih').textContent   = nama;
  document.getElementById('identitasAnggotaTerpilih').textContent = noId + ' — ' + tipe;
}

// Tutup dropdown cari saat klik di luar
document.addEventListener('click', function(e) {
  if (!e.target.closest('#cariAnggota') && !e.target.closest('#hasilCari')) {
    document.getElementById('hasilCari').style.display = 'none';
  }
});
// ── Dropdown Catatan ────────────────────────────────────────
function toggleCatatanLain(val) {
  const inputLain   = document.getElementById('notesLainInput');
  const hiddenNotes = document.getElementById('notesHidden');

  if (val === '__lainnya__') {
    inputLain.style.display = 'block';
    inputLain.focus();
    hiddenNotes.value = '';
    inputLain.addEventListener('input', function () {
      hiddenNotes.value = this.value;
    });
  } else {
    inputLain.style.display = 'none';
    inputLain.value   = '';
    hiddenNotes.value = val; // langsung pakai nilai dropdown
  }
}

// Inisialisasi saat halaman load (untuk kondisi oldInput)
(function () {
  const sel = document.getElementById('notesSelect');
  if (sel) toggleCatatanLain(sel.value);
})();
</script>
<?= $this->endSection() ?>