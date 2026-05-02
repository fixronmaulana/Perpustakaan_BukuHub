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
                   required>
            <?php if ($validation->hasError('visit_date')): ?>
              <div class="text-danger small mt-1"><?= $validation->getError('visit_date') ?></div>
            <?php endif; ?>
          </div>

          <!-- Catatan -->
          <div class="mb-3">
            <label class="form-label">Catatan <span class="text-muted">(opsional)</span></label>
            <textarea class="form-control" name="notes" rows="3"
                      placeholder="Catatan tambahan..."><?= esc($oldInput['notes'] ?? '') ?></textarea>
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
      // --- MODAL REWARD POIN (SESUAI GAMBAR) ---
      Swal.fire({
          title: '<span style="font-size: 1.25rem; font-weight: bold; color: #333;">Kunjungan Berhasil!</span>',
          html: `
              <p style="color: #666; font-size: 0.9rem; margin-top: -10px;">Kunjungan tercatat dalam sistem</p>
              <div class="p-3 mb-3" style="background-color: #eef2ff; border-radius: 15px;">
                  <div class="d-flex justify-content-center align-items-center mb-1">
                      <i class="ti ti-star-poly style="color: #1e3a8a; font-size: 1.2rem;"></i>
                      <span class="mx-2" style="color: #1e3a8a; font-weight: bold; font-size: 1rem;">Mendapat Reward Poin</span>
                      <i class="ti ti-star-poly style="color: #1e3a8a; font-size: 1.2rem;"></i>
                  </div>
                  <h2 class="fw-bold" style="font-size: 2.5rem; color: #1e3a8a; margin: 5px 0;">+${data.poin}</h2>
                  <p class="mb-0 text-muted" style="font-size: 0.85rem;">Poin diberikan ke ${data.member.nama}</p>
              </div>
              
              <div class="text-start mb-3" style="font-size: 0.85rem; color: #666;">
                  <div class="d-flex justify-content-between border-bottom py-1">
                      <span>Anggota</span>
                      <span class="text-dark fw-bold">${data.member.nama}</span>
                  </div>
                  <div class="d-flex justify-content-between py-1">
                      <span>ID</span>
                      <span class="text-dark fw-bold">${data.member.no_identitas}</span>
                  </div>
              </div>
          `,
          icon: 'success',
          iconColor: '#1e3a8a',
          showConfirmButton: true,
          confirmButtonText: 'Selesai',
          buttonsStyling: false,
          customClass: {
              popup: 'rounded-4',
              confirmButton: 'btn btn-primary w-100 py-2' // Membuat tombol lebar penuh
          },
          didOpen: () => {
              // Style khusus untuk menyamai gambar: Rounded sedang & warna #1e3a8a
              const btn = Swal.getConfirmButton();
              btn.style.backgroundColor = '#1e3a8a';
              btn.style.borderRadius = '10px'; // Sesuai dengan gambar, bukan pill
              btn.style.fontSize = '1rem';
              btn.style.fontWeight = 'bold';
              btn.style.border = 'none';
          }
      }).then(() => {
          html5QrcodeScanner.resume();
          document.getElementById('resumeBtn').style.display = 'none';
          hasilDiv.style.display = 'none';
      });

      // Update tampilan alert lama (opsional tetap dipertahankan)
      alertDiv.className = 'alert alert-success alert-dismissible fade show';
      infoDiv.style.display = 'block';
      document.getElementById('namaMemberScan').textContent      = data.member.nama;
      document.getElementById('identitasMemberScan').textContent = data.member.no_identitas + ' — ' + data.member.tipe;

    } else {
      // --- MODAL PERINGATAN (MISAL SUDAH ABSEN) ---
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
</script>
<?= $this->endSection() ?>