<?= $this->extend('layouts/member_layout') ?>

<?= $this->section('head') ?>
<title>Kartu Perpustakaan — Portal Anggota</title>
<?= $this->endSection() ?>

<?= $this->section('pageTitle') ?>Kartu Perpustakaan<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
  $inisial = '';
  if (!empty($member['first_name'])) $inisial .= strtoupper(substr($member['first_name'], 0, 1));
  if (!empty($member['last_name']))  $inisial .= strtoupper(substr($member['last_name'],  0, 1));
  $inisial = $inisial ?: 'AM';

  $namaLengkap = esc(ucwords(strtolower(trim(($member['first_name'] ?? '') . ' ' . ($member['last_name'] ?? ''))))) ?: 'Nama Anggota';  $noIdentitas  = $member['no_identitas'] ?? '—';
  $tipeAnggota  = $member['tipe_anggota'] ?? 'Anggota';
  $gender       = ($member['gender'] ?? '') === 'Male' ? 'Laki-laki' : (($member['gender'] ?? '') === 'Female' ? 'Perempuan' : '—');
  $phone        = $member['phone'] ?? '—';
  $noAnggota    = 'LIB-' . date('Y') . '-' . str_pad(($member['id'] ?? 1), 4, '0', STR_PAD_LEFT);
  $berlakuHingga = date('d F Y', strtotime('+1 year'));

  $adaQr  = !empty($member['qr_code']) && file_exists(MEMBERS_QR_CODE_PATH . $member['qr_code']);
  $qrUrl = $adaQr ? base_url(MEMBERS_QR_CODE_URI . $member['qr_code']) : null;

  $adaFoto = !empty($member['foto_profil']) && file_exists(FCPATH . 'uploads/foto_profil/' . $member['foto_profil']);
  $fotoUrl = $adaFoto ? base_url('uploads/foto_profil/' . $member['foto_profil']) : null;
?>

<div class="area-kartu">
  <div class="kotak-kartu-wrapper">

    <span class="label-kartu">Kartu Perpustakaan Digital</span>

    <!-- ════ KARTU ID ════ -->
    <div class="kartu-id" id="kartu-cetak">

      <!-- Header -->
      <div class="kartu-header">
        <div class="kartu-instansi">
          <img src="<?= base_url('assets/images/logo-smk.png') ?>" alt="Logo SMK" class="kartu-logo">
          <div>
            <div class="kartu-nama-sekolah">SMK Al-Munawwir</div>
            <div class="kartu-alamat-sekolah">Perpustakaan Digital</div>
          </div>
        </div>
        <div class="kartu-label-jenis"><?= esc($tipeAnggota) ?></div>
      </div>

      <!-- Body -->
      <div class="kartu-body">

        <!-- Avatar: foto atau inisial -->
        <div class="kartu-avatar">
          <?php if ($adaFoto): ?>
            <img src="<?= $fotoUrl ?>" alt="Foto" style="width:100%;height:100%;object-fit:cover;border-radius:50%">
          <?php else: ?>
            <?= $inisial ?>
          <?php endif; ?>
        </div>

        <div class="kartu-info">
          <div class="kartu-nama-anggota"><?= $namaLengkap ?></div>
          <div class="kartu-jurusan"><?= esc($gender) ?> · <?= esc($tipeAnggota) ?></div>
          <div class="kartu-row-detail">
            <div class="kartu-detail-item">
              <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
              <?= esc($noIdentitas) ?>
            </div>
            <?php if ($phone !== '—'): ?>
            <div class="kartu-detail-item">
              <svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81 19.79 19.79 0 010 1.18 2 2 0 012 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 14.92z"/></svg>
              <?= esc($phone) ?>
            </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- QR Code -->
        <div class="kartu-qr">
          <?php if ($qrUrl): ?>
            <img src="<?= $qrUrl ?>" alt="QR Code">
          <?php else: ?>
            <!-- QR placeholder SVG -->
            <svg viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg">
              <rect x="2"  y="2"  width="20" height="20" rx="2" fill="none" stroke="#0d1b3e" stroke-width="2.5"/>
              <rect x="7"  y="7"  width="10" height="10" fill="#0d1b3e"/>
              <rect x="28" y="2"  width="20" height="20" rx="2" fill="none" stroke="#0d1b3e" stroke-width="2.5"/>
              <rect x="33" y="7"  width="10" height="10" fill="#0d1b3e"/>
              <rect x="2"  y="28" width="20" height="20" rx="2" fill="none" stroke="#0d1b3e" stroke-width="2.5"/>
              <rect x="7"  y="33" width="10" height="10" fill="#0d1b3e"/>
              <rect x="28" y="28" width="4"  height="4"  fill="#0d1b3e"/>
              <rect x="34" y="28" width="4"  height="4"  fill="#0d1b3e"/>
              <rect x="40" y="28" width="8"  height="4"  fill="#0d1b3e"/>
              <rect x="28" y="34" width="8"  height="4"  fill="#0d1b3e"/>
              <rect x="38" y="34" width="10" height="4"  fill="#0d1b3e"/>
              <rect x="28" y="40" width="4"  height="8"  fill="#0d1b3e"/>
              <rect x="34" y="44" width="14" height="4"  fill="#0d1b3e"/>
              <rect x="44" y="38" width="4"  height="6"  fill="#0d1b3e"/>
            </svg>
          <?php endif; ?>
        </div>

      </div>

      <!-- Footer -->
      <div class="kartu-footer">
        <div class="kartu-no-anggota">
          <span class="kartu-no-label">No. Anggota</span>
          <span class="kartu-no-value"><?= $noAnggota ?></span>
        </div>
      </div>

    </div><!-- /kartu-id -->

    <!-- Tombol aksi -->
    <div class="grup-tombol-kartu">
      <button class="tombol-kartu sekunder" onclick="cetakKartu()">
        <svg viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
        Cetak Kartu
      </button>
      <button class="tombol-kartu primer" onclick="unduhKartu()">
        <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        Unduh Kartu
      </button>
    </div>

    <!-- Catatan -->
    <div class="catatan-kartu">
      <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      <p>Kartu ini adalah identitas resmi kamu sebagai anggota perpustakaan. Tunjukkan QR code saat meminjam atau mengunjungi perpustakaan.</p>
    </div>

  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- html2canvas untuk unduh PNG -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
// ── Unduh sebagai PNG ──
function unduhKartu() {
  const kartu = document.getElementById('kartu-cetak');
  html2canvas(kartu, {
    scale: 3,
    useCORS: true,
    backgroundColor: null,
  }).then(function(canvas) {
    const link      = document.createElement('a');
    link.download   = 'kartu-perpustakaan.png';
    link.href       = canvas.toDataURL('image/png');
    link.click();
  });
}

// ── Cetak kartu proporsional ──
function cetakKartu() {
  const kartu = document.getElementById('kartu-cetak');
  const win   = window.open('', '_blank');
  win.document.write(`<!DOCTYPE html><html><head>
    <meta charset="UTF-8">
    <title>Cetak Kartu Perpustakaan</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
      *{box-sizing:border-box;margin:0;padding:0}
      html,body{width:100%;height:100%}
      body{display:flex;align-items:center;justify-content:center;min-height:100vh;background:#fff;font-family:'DM Sans',sans-serif}
      .kartu-id{width:85.6mm;aspect-ratio:85.6/54;border-radius:14px;background:linear-gradient(135deg,#0d1b3e 0%,#1a2f6a 55%,#0d1b3e 100%);position:relative;overflow:hidden;color:#fff;display:flex;flex-direction:column;justify-content:space-between;box-shadow:0 8px 30px rgba(13,27,62,.3)}
      .kartu-id::before{content:'';position:absolute;width:220px;height:220px;border-radius:50%;background:rgba(201,168,76,.10);top:-60px;right:-50px}
      .kartu-header{display:flex;align-items:center;justify-content:space-between;padding:10px 14px 8px;border-bottom:1px solid rgba(255,255,255,.10);position:relative;z-index:1}
      .kartu-instansi{display:flex;align-items:center;gap:7px}
      .kartu-logo{width:26px;height:26px;border-radius:5px;background:#fff;padding:2px;object-fit:contain}
      .kartu-nama-sekolah{font-size:9px;font-weight:800;color:#fff}
      .kartu-alamat-sekolah{font-size:7px;color:rgba(255,255,255,.5)}
      .kartu-label-jenis{font-size:7px;font-weight:700;letter-spacing:.8px;text-transform:uppercase;color:#c9a84c;background:rgba(201,168,76,.15);border:1px solid rgba(201,168,76,.3);padding:2px 7px;border-radius:999px}
      .kartu-body{display:flex;align-items:center;gap:10px;padding:10px 14px;flex:1;position:relative;z-index:1}
      .kartu-avatar{width:44px;height:44px;border-radius:50%;background:rgba(255,255,255,.12);border:2px solid rgba(201,168,76,.5);display:flex;align-items:center;justify-content:center;font-size:1rem;font-weight:800;color:#c9a84c;flex-shrink:0;overflow:hidden}
      .kartu-info{flex:1;min-width:0}
      .kartu-nama-anggota{font-size:11px;font-weight:800;color:#fff;margin-bottom:3px}
      .kartu-jurusan{font-size:7.5px;color:rgba(255,255,255,.6);margin-bottom:4px}
      .kartu-row-detail{display:flex;flex-direction:column;gap:2px}
      .kartu-detail-item{display:flex;align-items:center;gap:3px;font-size:7.5px;color:rgba(255,255,255,.75)}
      .kartu-detail-item svg{width:8px;height:8px;stroke:#c9a84c;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;flex-shrink:0}
      .kartu-qr{width:52px;height:52px;background:#fff;border-radius:6px;padding:4px;flex-shrink:0;display:flex;align-items:center;justify-content:center}
      .kartu-qr img,.kartu-qr svg{width:100%;height:100%;object-fit:contain}
      .kartu-footer{display:flex;align-items:center;justify-content:space-between;padding:6px 14px 10px;border-top:1px solid rgba(255,255,255,.08);position:relative;z-index:1}
      .kartu-no-anggota{display:flex;flex-direction:column;gap:1px}
      .kartu-no-label{font-size:6.5px;color:rgba(255,255,255,.45);text-transform:uppercase;letter-spacing:.5px}
      .kartu-no-value{font-size:8px;font-weight:700;color:#fff;letter-spacing:1px}
      .kartu-berlaku{font-size:6.5px;color:rgba(255,255,255,.45);text-align:right}
      .kartu-berlaku span{display:block;font-size:7.5px;color:#c9a84c;font-weight:600}
      @media print{
        @page{size:landscape;margin:0}
        body{background:white}
      }
    </style></head><body>${kartu.outerHTML}</body></html>`);
  win.document.close();
  setTimeout(() => win.print(), 800);
}
</script>
<?= $this->endSection() ?>