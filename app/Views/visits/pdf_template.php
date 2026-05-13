<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: Arial, sans-serif;
      font-size: 10px;
      color: #1a1a1a;
      background: #fff;
    }

    /* ── HEADER / KOP ─────────────────────────────── */
    .kop {
      width: 100%;
      border-bottom: 3px solid #1e3a8a;
      padding-bottom: 10px;
      margin-bottom: 4px;
    }
    .kop-inner {
      text-align: center;
    }
    .kop-inner .nama-instansi {
      font-size: 16px;
      font-weight: bold;
      color: #1e3a8a;
      letter-spacing: 1px;
      text-transform: uppercase;
    }
    .kop-inner .judul-laporan {
      font-size: 13px;
      font-weight: bold;
      color: #1a1a1a;
      margin-top: 2px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .kop-inner .sub-judul {
      font-size: 10px;
      color: #555;
      margin-top: 2px;
    }
    .garis-bawah {
      border-top: 1px solid #1e3a8a;
      margin-top: 6px;
    }

    /* ── INFO BAR ─────────────────────────────────── */
    .info-bar {
      width: 100%;
      margin: 8px 0 10px 0;
      font-size: 9px;
      color: #444;
    }
    .info-bar td {
      padding: 1px 0;
    }
    .info-bar td:last-child {
      text-align: right;
    }

    /* ── SUMMARY CARDS ────────────────────────────── */
    .summary-wrap {
      margin-bottom: 12px;
    }
    .summary-title {
      font-size: 9px;
      font-weight: bold;
      color: #1e3a8a;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 5px;
      border-left: 3px solid #1e3a8a;
      padding-left: 6px;
    }
    .summary-table {
      width: 100%;
      border-collapse: collapse;
    }
    .summary-table td {
      width: 16.6%;
      text-align: center;
      padding: 8px 4px;
      border: 1px solid #dce3f5;
    }
    .summary-table td.total-cell {
      background-color: #1e3a8a;
      color: #fff;
    }
    .summary-table td.total-cell .s-value { color: #fff; }
    .summary-table td.total-cell .s-label { color: #c8d4f0; }
    .summary-table td.normal-cell {
      background-color: #f0f4ff;
    }
    .s-value {
      font-size: 18px;
      font-weight: bold;
      color: #1e3a8a;
      display: block;
    }
    .s-label {
      font-size: 8px;
      color: #666;
      display: block;
      margin-top: 2px;
      text-transform: uppercase;
      letter-spacing: 0.3px;
    }
    .s-divider {
      width: 100%;
      border: none;
      border-top: 1px solid #dce3f5;
      margin: 10px 0;
    }

    /* ── JUDUL TABEL ──────────────────────────────── */
    .table-title {
      font-size: 9px;
      font-weight: bold;
      color: #1e3a8a;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 5px;
      border-left: 3px solid #1e3a8a;
      padding-left: 6px;
    }

    /* ── TABEL DATA ───────────────────────────────── */
    table.data {
      width: 100%;
      border-collapse: collapse;
      font-size: 9px;
    }
    table.data thead tr {
      background-color: #1e3a8a;
      color: #fff;
    }
    table.data thead th {
      padding: 7px 6px;
      text-align: left;
      font-size: 9px;
      font-weight: bold;
      letter-spacing: 0.3px;
    }
    table.data tbody tr:nth-child(even) {
      background-color: #f5f7ff;
    }
    table.data tbody tr:nth-child(odd) {
      background-color: #ffffff;
    }
    table.data tbody td {
      padding: 5px 6px;
      border-bottom: 1px solid #e8ecf5;
      vertical-align: middle;
    }
    table.data tbody tr:last-child td {
      border-bottom: 2px solid #1e3a8a;
    }
    .badge-scan   { background:#1e3a8a; color:#fff; padding:2px 6px; border-radius:3px; font-size:8px; }
    .badge-manual { background:#6b7280; color:#fff; padding:2px 6px; border-radius:3px; font-size:8px; }
    .no-col   { width: 4%;  }
    .nama-col { width: 22%; }
    .noid-col { width: 14%; }
    .tipe-col { width: 8%;  }
    .tgl-col  { width: 12%; }
    .jam-col  { width: 9%;  }
    .met-col  { width: 11%; }
    .cat-col  { width: 20%; }

    /* ── FOOTER ───────────────────────────────────── */
    .footer {
      margin-top: 16px;
      width: 100%;
    }
    .footer-ttd {
      width: 100%;
    }
    .footer-ttd td {
      vertical-align: top;
      font-size: 9px;
      color: #444;
    }
    .footer-ttd td.ttd-right {
      text-align: center;
      width: 200px;
    }
    .ttd-box {
      margin-top: 3px;
      display: inline-block;
    }
    .ttd-line {
      margin-top: 50px;
      border-top: 1px solid #333;
      padding-top: 3px;
      font-size: 9px;
      color: #333;
      text-align: center;
      width: 160px;
    }
    .footer-note {
      margin-top: 10px;
      text-align: center;
      font-size: 8px;
      color: #aaa;
      border-top: 1px solid #eee;
      padding-top: 6px;
    }
  </style>
</head>
<body>

  <!-- ── KOP SURAT ── -->
  <div class="kop">
    <div class="kop-inner">
      <div class="nama-instansi">Perpustakaan Al-Munawwir</div>
      <div class="judul-laporan">Laporan Data Kunjungan</div>
      <div class="sub-judul">Periode: <?= esc($periodeLabel) ?></div>
    </div>
    <div class="garis-bawah"></div>
  </div>

  <!-- ── INFO BAR ── -->
  <table class="info-bar">
    <tr>
      <td>Dicetak pada &nbsp;: <?= date('d/m/Y, H:i') ?> WIB</td>
      <td>Periode &nbsp;: <?= esc($periodeLabel) ?></td>
    </tr>
  </table>

  <!-- ── SUMMARY ── -->
  <div class="summary-wrap">
    <div class="summary-title">Ringkasan Kunjungan</div>
    <table class="summary-table">
      <tr>
        <td class="total-cell">
          <span class="s-value"><?= $summary['total'] ?></span>
          <span class="s-label">Total Kunjungan</span>
        </td>
        <td class="normal-cell">
          <span class="s-value"><?= $summary['murid'] ?></span>
          <span class="s-label">Murid</span>
        </td>
        <td class="normal-cell">
          <span class="s-value"><?= $summary['guru'] ?></span>
          <span class="s-label">Guru</span>
        </td>
        <td class="normal-cell">
          <span class="s-value"><?= $summary['staf'] ?></span>
          <span class="s-label">Staf</span>
        </td>
        <td class="normal-cell">
          <span class="s-value"><?= $summary['manual'] ?></span>
          <span class="s-label">Manual</span>
        </td>
        <td class="normal-cell">
          <span class="s-value"><?= $summary['scan'] ?></span>
          <span class="s-label">Scan QR</span>
        </td>
      </tr>
    </table>
  </div>

  <hr class="s-divider">

  <!-- ── TABEL DATA ── -->
  <div class="table-title">Detail Data Kunjungan</div>
  <table class="data">
    <thead>
      <tr>
        <th class="no-col">#</th>
        <th class="nama-col">Nama Anggota</th>
        <th class="noid-col">No. Identitas</th>
        <th class="tipe-col">Tipe</th>
        <th class="tgl-col">Tanggal</th>
        <th class="jam-col">Jam</th>
        <th class="met-col">Metode</th>
        <th class="cat-col">Catatan</th>
      </tr>
    </thead>
    <tbody>
      <?php $i = 1; foreach ($visits as $visit):
        $d = \CodeIgniter\I18n\Time::parse($visit['visit_date']);
      ?>
      <tr>
        <td><?= $i++ ?></td>
        <td><?= esc(trim($visit['first_name'] . ' ' . $visit['last_name'])) ?></td>
        <td><?= esc($visit['no_identitas'] ?? '-') ?></td>
        <td><?= esc($visit['tipe_anggota']) ?></td>
        <td><?= $d->toLocalizedString('dd/MM/yyyy') ?></td>
        <td><?= $d->toLocalizedString('HH:mm') ?></td>
        <td>
          <?php if ($visit['method'] === 'scan'): ?>
            <span class="badge-scan">Scan QR</span>
          <?php else: ?>
            <span class="badge-manual">Manual</span>
          <?php endif; ?>
        </td>
        <td><?= esc($visit['notes'] ?? '-') ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <!-- ── FOOTER TTD ── -->
  <div class="footer">
    <table class="footer-ttd">
      <tr>
        <td>
          <i>*Laporan ini digenerate otomatis oleh sistem.</i>
        </td>
        <td class="ttd-right">
          <?= date('d/m/Y') ?><br>
          Petugas Perpustakaan,
          <div class="ttd-box">
            <div class="ttd-line">( ________________________ )</div>
          </div>
        </td>
      </tr>
    </table>
    <div class="footer-note">
      Sistem Informasi Perpustakaan Al-Munawwir &nbsp;&mdash;&nbsp; Dokumen ini sah tanpa tanda tangan basah jika dicetak dari sistem.
    </div>
  </div>

</body>
</html>