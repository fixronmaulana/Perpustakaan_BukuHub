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
      margin: 18mm 16mm 18mm 16mm;
    }

    .kop {
      width: 100%;
      border-bottom: 3px solid #1e3a8a;
      padding-bottom: 8px;
      margin-bottom: 6px;
    }
    .kop-inner {
      width: 100%;
      border-collapse: collapse;
    }
    .kop-inner td {
      vertical-align: middle;
      padding: 0;
    }
    .kop-logo {
      width: 76px;
      text-align: left;
    }
    .kop-logo img {
      width: 88px;
      height: 88px;
      display: block;
    }
    .kop-teks {
      text-align: center;
    }
    .kop-teks .nama-instansi {
      font-family: 'Times New Roman', Times, serif;
      font-size: 20px;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 1.5px;
    }
    .kop-teks .judul-laporan {
      font-family: 'Times New Roman', Times, serif;
      font-size: 17px;
      font-weight: bold;
      color: #1a1a1a;
      margin-top: 4px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .kop-teks .sub-judul {
      font-family: Arial, sans-serif;
      font-size: 12px;
      color: #555;
      margin-top: 3px;
      font-style: italic;
    }
    .kop-spacer {
      width: 76px;
    }

    .info-bar {
      width: 100%;
      border-collapse: collapse;
      margin: 8px 0 10px 0;
      font-size: 9px;
      color: #555;
    }
    .info-bar td:last-child { text-align: right; }

    .summary-title {
      font-size: 9px;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 5px;
    }
    .summary-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 10px;
    }
    .summary-table td {
      text-align: center;
      padding: 7px 4px;
      border: 1px solid #dce3f5;
      width: 16.66%;
    }
    .summary-table td.total-cell { background-color: #1e3a8a; }
    .summary-table td.normal-cell { background-color: #f0f4ff; }
    .s-value {
      font-size: 16px;
      font-weight: bold;
      color: #1e3a8a;
      display: block;
    }
    .total-cell .s-value { color: #ffffff; }
    .s-label {
      font-size: 7.5px;
      color: #777;
      display: block;
      margin-top: 2px;
      text-transform: uppercase;
    }
    .total-cell .s-label { color: #c8d4f0; }

    .divider {
      border: none;
      border-top: 1px solid #dce3f5;
      margin: 8px 0;
    }
    .table-title {
      font-size: 9px;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 5px;
    }
    table.data {
      width: 100%;
      border-collapse: collapse;
      font-size: 8.5px;
      table-layout: fixed;
    }
    table.data thead tr {
      background-color: #1e3a8a;
      color: #fff;
    }
    table.data thead th {
      padding: 6px 5px;
      text-align: left;
      font-weight: bold;
      overflow: hidden;
    }
    table.data tbody tr:nth-child(even) { background-color: #f5f7ff; }
    table.data tbody tr:nth-child(odd)  { background-color: #ffffff; }
    table.data tbody td {
      padding: 5px 5px;
      border-bottom: 1px solid #eaecf5;
      vertical-align: middle;
      overflow: hidden;
      word-wrap: break-word;
    }
    table.data tbody tr:last-child td {
      border-bottom: 2px solid #1e3a8a;
    }

    col.c-no   { width: 5%;  }
    col.c-nama { width: 21%; }
    col.c-noid { width: 14%; }
    col.c-tipe { width: 7%;  }
    col.c-tgl  { width: 11%; }
    col.c-jam  { width: 8%;  }
    col.c-met  { width: 10%; }
    col.c-cat  { width: 24%; }

    .badge-scan   { background:#1e3a8a; color:#fff; padding:2px 5px; border-radius:3px; font-size:7.5px; }
    .badge-manual { background:#6b7280; color:#fff; padding:2px 5px; border-radius:3px; font-size:7.5px; }

    .footer { margin-top: 14px; }
    .footer-ttd { width: 100%; border-collapse: collapse; }
    .footer-ttd td { vertical-align: top; font-size: 9px; color: #444; }
    .footer-ttd .ttd-right { text-align: center; width: 180px; }
    .ttd-space { height: 45px; }
    .ttd-line {
      border-top: 1px solid #333;
      padding-top: 3px;
      font-size: 9px;
      text-align: center;
    }
    .footer-note {
      margin-top: 10px;
      text-align: center;
      font-size: 7.5px;
      color: #aaa;
      border-top: 1px solid #eee;
      padding-top: 5px;
    }
  </style>
</head>
<body>
  <div class="kop">
    <table class="kop-inner">
      <tr>
        <td class="kop-logo">
          <img src="<?= base_url('assets/images/logo-perpus2.png') ?>" alt="Logo Perpustakaan">
        </td>
        <td class="kop-teks">
          <div class="nama-instansi">Perpustakaan SMK Al-Munawwir IIBS</div>
          <div class="judul-laporan">Laporan Data Kunjungan</div>
          <div class="sub-judul">Periode: <?= esc($periodeLabel) ?></div>
        </td>
        <td class="kop-spacer"></td>
      </tr>
    </table>
  </div>

  <table class="info-bar">
    <tr>
      <td>Dicetak pada: <?= date('d/m/Y, H:i') ?> WIB</td>
      <td>Periode: <?= esc($periodeLabel) ?></td>
    </tr>
  </table>

  <div class="summary-title">Ringkasan Kunjungan</div>
  <table class="summary-table">
    <tr>
      <td class="total-cell">
        <span class="s-value"><?= $summary['total'] ?></span>
        <span class="s-label">Total</span>
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

  <hr class="divider">

  <div class="table-title">Detail Data Kunjungan</div>
  <table class="data">
    <colgroup>
      <col class="c-no">
      <col class="c-nama">
      <col class="c-noid">
      <col class="c-tipe">
      <col class="c-tgl">
      <col class="c-jam">
      <col class="c-met">
      <col class="c-cat">
    </colgroup>
    <thead>
      <tr>
        <th>#</th>
        <th>Nama Anggota</th>
        <th>No. Identitas</th>
        <th>Tipe</th>
        <th>Tanggal</th>
        <th>Jam</th>
        <th>Metode</th>
        <th>Catatan</th>
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

  <div class="footer">
    <table class="footer-ttd">
      <tr>
        <td><i>* Laporan ini digenerate otomatis oleh sistem.</i></td>
        <td class="ttd-right">
          <?= date('d/m/Y') ?><br>
          Petugas Perpustakaan,
          <div class="ttd-space"></div>
          <div class="ttd-line">( ________________________ )</div>
        </td>
      </tr>
    </table>
    <div class="footer-note">
      Sistem Informasi Perpustakaan Al-Munawwir &mdash; Dokumen ini sah tanpa tanda tangan basah jika dicetak dari sistem.
    </div>
  </div>

</body>
</html>