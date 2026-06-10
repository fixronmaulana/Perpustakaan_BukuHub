<?= $this->extend('layouts/member_layout') ?>

<?= $this->section('head') ?>
<title>Kuis — <?= esc($quiz['name']) ?></title>
<style>
/* ══ EXAM MODE ══ */
.sidebar, .topbar { display: none !important; }
.member-konten    { margin-left: 0 !important; padding-left: 0 !important; }
.area-halaman     { padding: 0 !important; margin: 0 !important; }

/* ══ Header exam mandiri ══ */
.exam-topbar {
  position: sticky; top: 0; z-index: 100;
  background: #1e3a8a; color: #fff;
  display: flex; align-items: center; justify-content: space-between;
  padding: 0.85rem 1.5rem; gap: 1rem; flex-wrap: wrap;
  box-shadow: 0 2px 12px rgba(0,0,0,0.18);
}
.exam-topbar-kiri           { display: flex; align-items: center; gap: 12px; }
.exam-topbar-kiri svg       { stroke: #93c5fd; flex-shrink: 0; }
.exam-topbar-judul          { font-size: 0.95rem; font-weight: 700; color: #fff; }
.exam-topbar-sub            { font-size: 0.78rem; color: #93c5fd; margin-top: 1px; }
.exam-topbar-member         { font-size: 0.8rem; color: #bfdbfe; display: flex; align-items: center; gap: 6px; }

/* Timer */
.kuis-timer {
  display: flex; align-items: center; gap: 6px;
  font-size: 1.05rem; font-weight: 700; color: #fff;
  background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.25);
  border-radius: 8px; padding: 6px 14px;
}
.kuis-timer svg { stroke: #fff; }
.kuis-timer.mepet {
  color: #fca5a5; background: rgba(239,68,68,0.2);
  border-color: rgba(239,68,68,0.4); animation: kedip 1s infinite;
}
.kuis-timer.mepet svg { stroke: #fca5a5; }
@keyframes kedip { 0%,100%{opacity:1} 50%{opacity:0.6} }

/* ══ Body ══ */
.kuis-body { max-width: 720px; margin: 0 auto; padding: 1.5rem 1rem 3rem; }

/* Progress */
.kuis-progress-wrap {
  background: var(--putih); border: 1px solid var(--batas);
  border-radius: var(--radius); padding: 1rem 1.5rem; margin-bottom: 1.25rem;
}
.kuis-progress-label {
  font-size: 0.82rem; color: var(--teks-redup);
  margin-bottom: 6px; display: flex; justify-content: space-between;
}
.kuis-progress-bar-bg   { height: 8px; background: #e2e8f0; border-radius: 99px; overflow: hidden; }
.kuis-progress-bar-fill {
  height: 100%; background: var(--navy-main);
  border-radius: 99px; transition: width 0.4s ease;
}

/* Dot indikator */
.soal-dots { display: flex; gap: 6px; flex-wrap: wrap; margin-top: 10px; }
.soal-dot  {
  width: 10px; height: 10px; border-radius: 50%;
  background: #e2e8f0; border: 2px solid #cbd5e1;
  transition: background 0.2s, border-color 0.2s;
}
.soal-dot.aktif         { border-color: var(--navy-main); background: #fff; }
.soal-dot.dijawab       { background: var(--navy-main); border-color: var(--navy-main); }
.soal-dot.aktif.dijawab { background: #2563eb; border-color: #1e40af; }

/* Kartu soal */
.kartu-soal {
  background: var(--putih); border: 1px solid var(--batas);
  border-radius: var(--radius); padding: 1.75rem; margin-bottom: 1.25rem;
}
.nomor-soal {
  font-size: 0.78rem; font-weight: 700; color: var(--navy-main);
  text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.75rem;
}
.teks-soal { font-size: 1rem; font-weight: 600; color: var(--teks); line-height: 1.6; margin-bottom: 1.5rem; }

/* Opsi */
.opsi-list { display: flex; flex-direction: column; gap: 0.75rem; }
.opsi-item {
  display: flex; align-items: center; gap: 12px;
  padding: 0.85rem 1rem; border: 2px solid var(--batas);
  border-radius: 10px; cursor: pointer;
  transition: border-color 0.15s, background 0.15s; user-select: none;
}
.opsi-item:hover    { border-color: var(--navy-main); background: #eff4ff; }
.opsi-item.terpilih { border-color: var(--navy-main); background: #eff4ff; }
.opsi-item input[type="radio"] { display: none; }
.opsi-huruf {
  width: 32px; height: 32px; border-radius: 50%; background: #e2e8f0;
  display: flex; align-items: center; justify-content: center;
  font-size: 0.82rem; font-weight: 700; color: var(--teks); flex-shrink: 0;
  transition: background 0.15s, color 0.15s;
}
.opsi-item.terpilih .opsi-huruf { background: var(--navy-main); color: #fff; }
.opsi-teks { font-size: 0.9rem; color: var(--teks); }

/* Navigasi */
.kuis-nav { display: flex; justify-content: space-between; gap: 1rem; margin-bottom: 2rem; }
.kuis-nav button {
  flex: 1; padding: 0.75rem; border-radius: 10px;
  font-weight: 600; font-size: 0.9rem; border: none; cursor: pointer; transition: opacity 0.15s;
}
.btn-prev            { background: #e2e8f0; color: var(--teks); }
.btn-prev:disabled   { opacity: 0.4; cursor: not-allowed; }
.btn-next            { background: var(--navy-main); color: #fff; }
.btn-submit          { background: #16a34a; color: #fff; }
.btn-submit:disabled { opacity: 0.6; cursor: not-allowed; }

/* ══ Modal Konfirmasi Back ══ */
.modal-back-overlay {
  display: none; position: fixed; inset: 0;
  background: rgba(0,0,0,0.55); backdrop-filter: blur(3px);
  z-index: 9999; align-items: center; justify-content: center; padding: 1rem;
}
.modal-back-overlay.tampil { display: flex; }
@keyframes popIn {
  from { transform: scale(0.85) translateY(16px); opacity: 0; }
  to   { transform: scale(1)    translateY(0);    opacity: 1; }
}
.modal-back-box {
  background: #fff; border-radius: 20px; max-width: 400px; width: 100%;
  text-align: center; box-shadow: 0 25px 80px rgba(0,0,0,0.25);
  animation: popIn 0.3s cubic-bezier(.34,1.56,.64,1); overflow: hidden;
}
.modal-back-header {
  background: linear-gradient(135deg, #dc2626, #ef4444);
  padding: 1.75rem 1.5rem 1.25rem;
}
.modal-back-icon {
  width: 60px; height: 60px; border-radius: 50%;
  background: rgba(255,255,255,0.2); border: 3px solid rgba(255,255,255,0.45);
  display: flex; align-items: center; justify-content: center; margin: 0 auto 0.85rem;
}
.modal-back-icon svg  { stroke: #fff; }
.modal-back-judul     { font-size: 1.2rem; font-weight: 800; color: #fff; margin-bottom: 0.2rem; }
.modal-back-sub       { font-size: 0.82rem; color: rgba(255,255,255,0.85); }
.modal-back-nama-kuis { font-size: 0.78rem; color: rgba(255,255,255,0.65); margin-top: 0.4rem; font-style: italic; }
.modal-back-body      { padding: 1.4rem; }
.modal-back-body p    { font-size: 0.87rem; color: #64748b; margin-bottom: 1.2rem; line-height: 1.65; }
.modal-back-actions   { display: flex; flex-direction: column; gap: 0.6rem; }
.btn-back-submit {
  width: 100%; padding: 0.8rem; border: none; border-radius: 11px;
  background: linear-gradient(135deg, #dc2626, #ef4444);
  color: #fff; font-weight: 700; font-size: 0.9rem; cursor: pointer; transition: opacity 0.15s;
}
.btn-back-submit:hover { opacity: 0.88; }
.btn-back-lanjut {
  width: 100%; padding: 0.8rem; border: none; border-radius: 11px;
  background: linear-gradient(135deg, #1e3a8a, #2563eb);
  color: #fff; font-weight: 700; font-size: 0.9rem; cursor: pointer; transition: opacity 0.15s;
}
.btn-back-lanjut:hover { opacity: 0.88; }
</style>
<?= $this->endSection() ?>

<?= $this->section('pageTitle') ?>Kerjakan Kuis<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php $totalSoal = count($questions); ?>

<!-- ══ Header Exam Mandiri ══ -->
<div class="exam-topbar">
  <div class="exam-topbar-kiri">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke-width="2"
         stroke-linecap="round" stroke-linejoin="round">
      <path d="M9 11l3 3L22 4"/>
      <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/>
    </svg>
    <div>
      <div class="exam-topbar-judul"><?= esc($quiz['name']) ?></div>
      <div class="exam-topbar-sub">
        <?= esc($quiz['book_title']) ?> · <?= $totalSoal ?> soal · Maks. 100 poin
      </div>
    </div>
  </div>
  <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">
    <div class="exam-topbar-member">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke-width="2"
           stroke-linecap="round" stroke-linejoin="round">
        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
        <circle cx="12" cy="7" r="4"/>
      </svg>
      <?= esc(trim($member['first_name'] . ' ' . $member['last_name'])) ?>
    </div>
    <div class="kuis-timer" id="timerBox">
      <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke-width="2"
           stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"/>
        <polyline points="12 6 12 12 16 14"/>
      </svg>
      <span id="timerTeks">--:--</span>
    </div>
  </div>
</div>

<!-- ══ Body ══ -->
<div class="kuis-body">

  <!-- Progress + Dot -->
  <div class="kuis-progress-wrap">
    <div class="kuis-progress-label">
      <span>Progress</span>
      <span id="labelProgress">Soal 1 dari <?= $totalSoal ?></span>
    </div>
    <div class="kuis-progress-bar-bg">
      <div class="kuis-progress-bar-fill" id="progressFill"
           style="width:<?= round(1 / $totalSoal * 100) ?>%"></div>
    </div>
    <div class="soal-dots" id="soalDots">
      <?php for ($i = 0; $i < $totalSoal; $i++): ?>
        <div class="soal-dot <?= $i === 0 ? 'aktif' : '' ?>" id="dot-<?= $i ?>"></div>
      <?php endfor; ?>
    </div>
  </div>

  <!-- Form -->
  <form id="formKuis" action="<?= base_url("member/kuis/{$quiz['id']}/submit") ?>" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="durasi_detik" id="durasiDetik" value="0">
    <input type="hidden" name="loan_id" value="<?= (int)($loanId ?? 0) ?>">

    <?php foreach ($questions as $idx => $q): ?>
      <div class="kartu-soal soal-panel" id="soal-<?= $idx ?>"
           style="display:<?= $idx === 0 ? 'block' : 'none' ?>">
        <div class="nomor-soal">Soal <?= $idx + 1 ?> / <?= $totalSoal ?></div>
        <div class="teks-soal"><?= esc($q['question']) ?></div>
        <div class="opsi-list">
          <?php foreach (['A','B','C','D'] as $opt):
            $key = 'option_' . strtolower($opt);
          ?>
            <label class="opsi-item" id="opsi-<?= $idx ?>-<?= $opt ?>">
              <input type="radio"
                     name="jawaban[<?= $q['id'] ?>]"
                     value="<?= $opt ?>"
                     onchange="pilihOpsi(<?= $idx ?>, '<?= $opt ?>', <?= $q['id'] ?>)">
              <div class="opsi-huruf"><?= $opt ?></div>
              <div class="opsi-teks"><?= esc($q[$key]) ?></div>
            </label>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>

    <div class="kuis-nav">
      <button type="button" class="btn-prev" id="btnPrev" onclick="pindahSoal(-1)" disabled>
        ← Sebelumnya
      </button>
      <button type="button" class="btn-next" id="btnNext" onclick="pindahSoal(1)"
              style="display:<?= $totalSoal > 1 ? 'block' : 'none' ?>">
        Selanjutnya →
      </button>
      <button type="button" class="btn-submit" id="btnSubmit" onclick="konfirmasiSubmit()"
              style="display:<?= $totalSoal === 1 ? 'block' : 'none' ?>">
        Selesai & Kirim ✓
      </button>
    </div>
  </form>

</div>

<!-- ══ Modal Konfirmasi Back ══ -->
<div class="modal-back-overlay" id="modalKonfirmasiBack">
  <div class="modal-back-box">
    <div class="modal-back-header">
      <div class="modal-back-icon">
        <svg width="30" height="30" viewBox="0 0 24 24" fill="none"
             stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
          <line x1="12" y1="9" x2="12" y2="13"/>
          <line x1="12" y1="17" x2="12.01" y2="17"/>
        </svg>
      </div>
      <div class="modal-back-judul">Kuis Belum Selesai!</div>
      <div class="modal-back-sub">Kamu mencoba meninggalkan halaman kuis.</div>
      <div class="modal-back-nama-kuis"><?= esc($quiz['name']) ?></div>
    </div>
    <div class="modal-back-body">
      <p>
        Jika keluar sekarang, jawaban yang sudah diisi akan
        <b>otomatis dikumpulkan</b> dan percobaan kuis akan dihitung.
        Apakah kamu ingin mengumpulkan sekarang?
      </p>
      <div class="modal-back-actions">
        <button class="btn-back-submit" onclick="submitDariBack()">
          📤 Ya, Kumpulkan & Keluar
        </button>
        <button class="btn-back-lanjut" onclick="tutupModalBack()">
          ← Lanjutkan Kuis
        </button>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const TOTAL_SOAL   = <?= $totalSoal ?>;
const DURASI_DETIK = <?= $quiz['duration_minutes'] * 60 ?>;
const URL_KEMBALI  = '<?= base_url('member/pengembalian') ?>';

let soalAktif   = 0;
let jawaban     = {};
let timerSisa   = DURASI_DETIK;
let timerStart  = Date.now();
let interval;
let sudahSubmit = false;

// ── Timer ─────────────────────────────────────────────────
function startTimer() {
  interval = setInterval(() => {
    timerSisa--;
    document.getElementById('durasiDetik').value = DURASI_DETIK - timerSisa;

    const m = String(Math.floor(timerSisa / 60)).padStart(2, '0');
    const s = String(timerSisa % 60).padStart(2, '0');
    document.getElementById('timerTeks').textContent = `${m}:${s}`;

    if (timerSisa <= 60) document.getElementById('timerBox').classList.add('mepet');
    if (timerSisa <= 0)  { clearInterval(interval); submitKuis(); }
  }, 1000);
}

//  Dot indikator 
function updateDots() {
  for (let i = 0; i < TOTAL_SOAL; i++) {
    const dot     = document.getElementById(`dot-${i}`);
    const checked = document.querySelector(`#soal-${i} input[type="radio"]:checked`);
    dot.className = 'soal-dot';
    if (checked)         dot.classList.add('dijawab');
    if (i === soalAktif) dot.classList.add('aktif');
  }
}

//  Pilih opsi 
function pilihOpsi(idx, opt, questionId) {
  ['A','B','C','D'].forEach(o =>
    document.getElementById(`opsi-${idx}-${o}`)?.classList.remove('terpilih')
  );
  document.getElementById(`opsi-${idx}-${opt}`)?.classList.add('terpilih');
  jawaban[questionId] = opt;
  updateDots();
}

// Navigasi soal 
function pindahSoal(arah) {
  document.getElementById(`soal-${soalAktif}`).style.display = 'none';
  soalAktif += arah;
  document.getElementById(`soal-${soalAktif}`).style.display = 'block';

  ['A','B','C','D'].forEach(o =>
    document.getElementById(`opsi-${soalAktif}-${o}`)?.classList.remove('terpilih')
  );
  const checked = document.querySelector(`#soal-${soalAktif} input[type="radio"]:checked`);
  if (checked) document.getElementById(`opsi-${soalAktif}-${checked.value}`)?.classList.add('terpilih');

  document.getElementById('btnPrev').disabled        = soalAktif === 0;
  document.getElementById('btnNext').style.display   = soalAktif < TOTAL_SOAL - 1 ? '' : 'none';
  document.getElementById('btnSubmit').style.display = soalAktif === TOTAL_SOAL - 1 ? '' : 'none';

  const persen = Math.round((soalAktif + 1) / TOTAL_SOAL * 100);
  document.getElementById('progressFill').style.width  = persen + '%';
  document.getElementById('labelProgress').textContent = `Soal ${soalAktif + 1} dari ${TOTAL_SOAL}`;
  updateDots();
}

// ── Konfirmasi submit normal ──────────────────────────────
function konfirmasiSubmit() {
  const belumDijawab = TOTAL_SOAL - Object.keys(jawaban).length;
  const pesan = belumDijawab > 0
    ? `Masih ada ${belumDijawab} soal yang belum dijawab. Yakin ingin mengirim?`
    : 'Semua soal sudah dijawab. Yakin ingin mengirim?';
  if (!confirm(pesan)) return;
  submitKuis();
}

// ── Submit via AJAX ───────────────────────────────────────
function submitKuis() {
  if (sudahSubmit) return;
  sudahSubmit = true;

  clearInterval(interval);
  document.getElementById('durasiDetik').value = Math.floor((Date.now() - timerStart) / 1000);

  const btnSubmit       = document.getElementById('btnSubmit');
  btnSubmit.disabled    = true;
  btnSubmit.textContent = 'Mengirim...';

  const form     = document.getElementById('formKuis');
  const formData = new FormData(form);

  fetch(form.action, {
    method: 'POST',
    headers: { 'X-Requested-With': 'XMLHttpRequest' },
    body: formData,
  })
  .then(r => r.json())
  .then(data => tampilHasil(data))
  .catch(() => { form.submit(); });
}

// ── Tampil hasil dengan SweetAlert2 (konsisten dengan pengembalian) ──
function tampilHasil(data) {
  window.onbeforeunload = null;
  document.getElementById('modalKonfirmasiBack').classList.remove('tampil');

  // Tentukan warna & teks berdasarkan skor
  const bagus  = data.skor >= 70;
  const cukup  = data.skor >= 40 && data.skor < 70;

  const iconColor  = bagus ? '#16a34a' : cukup ? '#d97706' : '#2563eb';
  const warnaBg    = bagus ? '#d1fae5' : cukup ? '#fef3c7' : '#dbeafe';
  const warnaTeks  = bagus ? '#065f46' : cukup ? '#78350f' : '#1e3a8a';
  const btnClass   = bagus ? 'btn-success' : cukup ? 'btn-warning' : 'btn-primary';
  const judulTeks  = bagus ? 'Luar Biasa! 🎉' : cukup ? 'Cukup Baik! 👍' : 'Terus Belajar! 📚';
  const subTeks    = bagus
    ? 'Kamu menjawab dengan sangat baik!'
    : cukup
      ? 'Tingkatkan lagi di percobaan berikutnya!'
      : 'Jangan menyerah, kamu pasti bisa!';
  const iconSwal   = bagus ? 'success' : cukup ? 'warning' : 'info';

  Swal.fire({
    width: '380px',
    icon: iconSwal,
    iconColor: iconColor,
    title: `<span style="font-size:1.5rem; font-weight:700;">${judulTeks}</span>`,
    html: `
      <p style="color:#6c757d; font-size:0.85rem; margin-top:-6px; margin-bottom:14px;">
        ${subTeks}
      </p>

      <!-- Kotak reward poin -->
      <div style="background:${warnaBg}; border-radius:10px; padding:14px 16px; margin-bottom:14px;">
        <span style="font-size:0.78rem; color:${warnaTeks}; font-weight:600; text-transform:uppercase; letter-spacing:0.05em;">
          Reward Poin Kuis
        </span>
        <div style="font-size:2.2rem; font-weight:700; color:${warnaTeks}; line-height:1.4;">
          ${data.poin}
        </div>
        <div style="font-size:0.8rem; color:${warnaTeks}; opacity:0.8; margin-top:2px;">
          Total poin kuis yang didapat
        </div>
      </div>

      <!-- Poin sebelum → sesudah -->
      <div style="font-size:0.85rem; color:#6c757d; margin-bottom:14px;">
        ${data.total_sebelum} <span style="color:#16a34a; font-weight:700;">→</span>
        <b style="color:#15803d;">${data.total_sesudah}</b>
      </div>

      <!-- Statistik benar / salah / skor -->
      <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:8px; margin-bottom:14px;">
        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:8px 6px;">
          <div style="font-size:1.3rem; font-weight:800; color:#16a34a;">${data.benar}</div>
          <div style="font-size:0.7rem; color:#94a3b8; margin-top:2px;">✓ Benar</div>
        </div>
        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:8px 6px;">
          <div style="font-size:1.3rem; font-weight:800; color:#dc2626;">${data.salah}</div>
          <div style="font-size:0.7rem; color:#94a3b8; margin-top:2px;">✗ Salah</div>
        </div>
        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:8px 6px;">
          <div style="font-size:1.3rem; font-weight:800; color:#2563eb;">${data.skor}%</div>
          <div style="font-size:0.7rem; color:#94a3b8; margin-top:2px;">Skor</div>
        </div>
      </div>

      <!-- Info buku & anggota -->
      <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:8px 10px; text-align:left;">
          <div style="font-size:0.68rem; color:#94a3b8; margin-bottom:2px;">Buku</div>
          <div style="font-size:0.82rem; font-weight:700; color:#1e293b;"><?= esc($quiz['book_title']) ?></div>
        </div>
        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:8px 10px; text-align:left;">
          <div style="font-size:0.68rem; color:#94a3b8; margin-bottom:2px;">Anggota</div>
          <div style="font-size:0.82rem; font-weight:700; color:#1e293b;"><?= esc(trim($member['first_name'] . ' ' . $member['last_name'])) ?></div>
        </div>
      </div>
    `,
    showConfirmButton: true,
    confirmButtonText: 'Selesai',
    buttonsStyling: false,
    allowOutsideClick: false,
    allowEscapeKey: false,
    customClass: {
      popup:         'rounded-4',
      confirmButton: 'swal-btn-selesai',
    },
    didRender: () => {
      // Warna tombol selalu hijau, pakai inline style agar tidak bergantung Bootstrap
      const btnColor = bagus ? '#16a34a' : cukup ? '#d97706' : '#1e3a8a';
      const btn = document.querySelector('.swal-btn-selesai');
      if (btn) {
        btn.style.cssText = `
          display: block;
          width: 100%;
          padding: 0.65rem 1rem;
          margin-top: 12px;
          background: ${btnColor};
          color: #fff;
          border: none;
          border-radius: 10px;
          font-size: 0.95rem;
          font-weight: 700;
          cursor: pointer;
          letter-spacing: 0.3px;
          transition: opacity 0.15s;
        `;
        btn.onmouseover = () => btn.style.opacity = '0.88';
        btn.onmouseout  = () => btn.style.opacity = '1';
      }
    },
  }).then(() => {
    window.location.href = URL_KEMBALI;
  });
}

// ── Modal Konfirmasi Back ─────────────────────────────────
function submitDariBack() {
  document.getElementById('modalKonfirmasiBack').classList.remove('tampil');
  submitKuis();
}

function tutupModalBack() {
  document.getElementById('modalKonfirmasiBack').classList.remove('tampil');
  history.pushState({ kuisAktif: true }, '', window.location.href);
}

// ── Init ──────────────────────────────────────────────────
startTimer();
updateDots();

history.pushState({ kuisAktif: true }, '', window.location.href);

window.addEventListener('popstate', function(e) {
  if (!sudahSubmit) {
    history.pushState({ kuisAktif: true }, '', window.location.href);
    document.getElementById('modalKonfirmasiBack').classList.add('tampil');
  }
});

window.addEventListener('beforeunload', function(e) {
  if (!sudahSubmit) {
    e.preventDefault();
    e.returnValue = '';
  }
});
</script>
<?= $this->endSection() ?>