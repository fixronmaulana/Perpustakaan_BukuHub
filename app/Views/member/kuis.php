<?= $this->extend('layouts/member_layout') ?>

<?= $this->section('head') ?>
<title>Kuis — <?= esc($quiz['name']) ?></title>
<style>
/* ── Wrapper kuis ── */
.kuis-wrapper {
  max-width: 720px;
  margin: 0 auto;
}

/* ── Header info kuis ── */
.kuis-header {
  background: var(--putih);
  border: 1px solid var(--batas);
  border-radius: var(--radius);
  padding: 1.25rem 1.5rem;
  margin-bottom: 1.25rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  flex-wrap: wrap;
}

.kuis-judul { font-size: 1rem; font-weight: 700; color: var(--teks); }
.kuis-sub   { font-size: 0.8rem; color: var(--teks-redup); margin-top: 2px; }

/* Timer */
.kuis-timer {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 1.1rem;
  font-weight: 700;
  color: var(--navy);
  background: #eff4ff;
  border: 1px solid #c7d7fe;
  border-radius: 8px;
  padding: 6px 14px;
}
.kuis-timer svg { stroke: var(--navy); }
.kuis-timer.mepet { color: #c0392b; background: #fde8e8; border-color: #f5c6c6; }
.kuis-timer.mepet svg { stroke: #c0392b; }

/* Progress */
.kuis-progress-wrap {
  background: var(--putih);
  border: 1px solid var(--batas);
  border-radius: var(--radius);
  padding: 1rem 1.5rem;
  margin-bottom: 1.25rem;
}
.kuis-progress-label {
  font-size: 0.82rem;
  color: var(--teks-redup);
  margin-bottom: 6px;
  display: flex;
  justify-content: space-between;
}
.kuis-progress-bar-bg {
  height: 8px;
  background: #e2e8f0;
  border-radius: 99px;
  overflow: hidden;
}
.kuis-progress-bar-fill {
  height: 100%;
  background: var(--navy-main);
  border-radius: 99px;
  transition: width 0.4s ease;
}

/* Kartu soal */
.kartu-soal {
  background: var(--putih);
  border: 1px solid var(--batas);
  border-radius: var(--radius);
  padding: 1.75rem;
  margin-bottom: 1.25rem;
}
.nomor-soal {
  font-size: 0.78rem;
  font-weight: 700;
  color: var(--navy-main);
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 0.75rem;
}
.teks-soal {
  font-size: 1rem;
  font-weight: 600;
  color: var(--teks);
  line-height: 1.6;
  margin-bottom: 1.5rem;
}

/* Opsi jawaban */
.opsi-list { display: flex; flex-direction: column; gap: 0.75rem; }

.opsi-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 0.85rem 1rem;
  border: 2px solid var(--batas);
  border-radius: 10px;
  cursor: pointer;
  transition: border-color 0.15s, background 0.15s;
  user-select: none;
}
.opsi-item:hover { border-color: var(--navy-main); background: #eff4ff; }
.opsi-item.terpilih {
  border-color: var(--navy-main);
  background: #eff4ff;
}
.opsi-item input[type="radio"] { display: none; }

.opsi-huruf {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: #e2e8f0;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.82rem;
  font-weight: 700;
  color: var(--teks);
  flex-shrink: 0;
  transition: background 0.15s, color 0.15s;
}
.opsi-item.terpilih .opsi-huruf {
  background: var(--navy-main);
  color: #fff;
}
.opsi-teks { font-size: 0.9rem; color: var(--teks); }

/* Navigasi */
.kuis-nav {
  display: flex;
  justify-content: space-between;
  gap: 1rem;
  margin-bottom: 2rem;
}
.kuis-nav button {
  flex: 1;
  padding: 0.75rem;
  border-radius: 10px;
  font-weight: 600;
  font-size: 0.9rem;
  border: none;
  cursor: pointer;
  transition: opacity 0.15s;
}
.btn-prev { background: #e2e8f0; color: var(--teks); }
.btn-prev:disabled { opacity: 0.4; cursor: not-allowed; }
.btn-next { background: var(--navy-main); color: #fff; }
.btn-submit { background: #16a34a; color: #fff; }

/* Modal hasil */
.modal-hasil-overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.55);
  backdrop-filter: blur(3px);
  z-index: 9999;
  align-items: center;
  justify-content: center;
  padding: 1rem;
}
.modal-hasil-overlay.tampil { display: flex; }

@keyframes popIn {
  from { transform: scale(0.8) translateY(20px); opacity: 0; }
  to   { transform: scale(1)   translateY(0);    opacity: 1; }
}

.modal-hasil {
  background: #fff;
  border-radius: 20px;
  max-width: 420px;
  width: 100%;
  text-align: center;
  box-shadow: 0 25px 80px rgba(0,0,0,0.25);
  animation: popIn 0.35s cubic-bezier(.34,1.56,.64,1);
  overflow: hidden;
}

/* Header berwarna dinamis */
.modal-hasil-header {
  padding: 2rem 1.5rem 1.5rem;
  position: relative;
}
.modal-hasil-header.bagus  { background: linear-gradient(135deg, #16a34a, #22c55e); }
.modal-hasil-header.cukup  { background: linear-gradient(135deg, #d97706, #f59e0b); }
.modal-hasil-header.kurang { background: linear-gradient(135deg, #1e3a8a, #3b82f6); }

/* Icon centang */
.hasil-centang {
  width: 64px;
  height: 64px;
  border-radius: 50%;
  background: rgba(255,255,255,0.25);
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 1rem;
  border: 3px solid rgba(255,255,255,0.5);
}
.hasil-centang svg { stroke: #fff; }

.hasil-judul {
  font-size: 1.3rem;
  font-weight: 800;
  color: #fff;
  margin-bottom: 0.25rem;
}
.hasil-subjudul {
  font-size: 0.85rem;
  color: rgba(255,255,255,0.8);
}

/* Nama kuis */
.hasil-nama-kuis {
  font-size: 0.8rem;
  color: rgba(255,255,255,0.7);
  margin-top: 0.5rem;
  font-style: italic;
}

/* Body modal */
.modal-hasil-body {
  padding: 1.5rem;
}

/* Poin utama */
.hasil-poin-wrap {
  background: linear-gradient(135deg, #f0fdf4, #dcfce7);
  border: 2px solid #bbf7d0;
  border-radius: 14px;
  padding: 1.25rem;
  margin-bottom: 1.25rem;
}
.hasil-poin-label {
  font-size: 0.78rem;
  color: #16a34a;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 5px;
  margin-bottom: 0.5rem;
}
.hasil-poin-angka {
  font-size: 3rem;
  font-weight: 900;
  color: #15803d;
  line-height: 1;
}
.hasil-poin-satuan {
  font-size: 1rem;
  font-weight: 600;
  color: #16a34a;
}
.hasil-poin-total {
  font-size: 0.78rem;
  color: #4ade80;
  margin-top: 0.4rem;
}

/* Grid statistik */
.hasil-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 0.6rem;
  margin-bottom: 1.25rem;
}
.hasil-stat {
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 10px;
  padding: 0.6rem 0.5rem;
}
.hasil-stat-angka {
  font-size: 1.3rem;
  font-weight: 800;
  line-height: 1.2;
}
.hasil-stat-angka.hijau { color: #16a34a; }
.hasil-stat-angka.merah { color: #dc2626; }
.hasil-stat-angka.biru  { color: #2563eb; }
.hasil-stat-label {
  font-size: 0.7rem;
  color: #94a3b8;
  margin-top: 2px;
}

/* Info member */
.hasil-info-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 10px;
  padding: 0.6rem 1rem;
  margin-bottom: 1.25rem;
  font-size: 0.82rem;
  color: #64748b;
}
.hasil-info-row span { font-weight: 600; color: #1e293b; }

/* Tombol selesai */
.btn-selesai {
  display: block;
  width: 100%;
  padding: 0.85rem;
  background: linear-gradient(135deg, #1e3a8a, #2563eb);
  color: #fff;
  border: none;
  border-radius: 12px;
  font-weight: 700;
  font-size: 0.95rem;
  cursor: pointer;
  text-decoration: none;
  transition: opacity 0.15s;
  letter-spacing: 0.3px;
}
.btn-selesai:hover { opacity: 0.9; color: #fff; }
</style>
<?= $this->endSection() ?>

<?= $this->section('pageTitle') ?>Kerjakan Kuis<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
  $totalSoal  = count($questions);
  $totalPoin  = array_sum(array_column($questions, 'points'));
?>

<div class="kuis-wrapper">

  <!-- Header -->
  <div class="kuis-header">
    <div>
      <div class="kuis-judul"><?= esc($quiz['name']) ?></div>
      <div class="kuis-sub">
        <?= esc($quiz['book_title']) ?> · <?= $totalSoal ?> soal · Total <?= $totalPoin ?> poin
      </div>
    </div>
    <div class="kuis-timer" id="timerBox">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke-width="2"
           stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
      </svg>
      <span id="timerTeks">--:--</span>
    </div>
  </div>

  <!-- Progress -->
  <div class="kuis-progress-wrap">
    <div class="kuis-progress-label">
      <span>Progress</span>
      <span id="labelProgress">Soal 1 dari <?= $totalSoal ?></span>
    </div>
    <div class="kuis-progress-bar-bg">
      <div class="kuis-progress-bar-fill" id="progressFill"
           style="width: <?= round(1/$totalSoal*100) ?>%"></div>
    </div>
  </div>

  <!-- Form kuis -->
  <form id="formKuis" action="<?= base_url("member/kuis/{$quiz['id']}/submit") ?>" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="durasi_detik" id="durasiDetik" value="0">
    <input type="hidden" name="loan_id" value="<?= (int)($loanId ?? 0) ?>">

    <?php foreach ($questions as $idx => $q): ?>
      <div class="kartu-soal soal-panel" id="soal-<?= $idx ?>"
           style="display: <?= $idx === 0 ? 'block' : 'none' ?>">

        <div class="nomor-soal">Soal <?= $idx + 1 ?> / <?= $totalSoal ?></div>
        <div class="teks-soal"><?= esc($q['question']) ?></div>

        <div class="opsi-list">
          <?php foreach (['A','B','C','D'] as $opt):
            $key = 'option_' . strtolower($opt);
          ?>
            <label class="opsi-item" id="opsi-<?= $idx ?>-<?= $opt ?>">
              <input type="radio" name="jawaban[<?= $q['id'] ?>]"
                     value="<?= $opt ?>"
                     onchange="pilihOpsi(<?= $idx ?>, '<?= $opt ?>')">
              <div class="opsi-huruf"><?= $opt ?></div>
              <div class="opsi-teks"><?= esc($q[$key]) ?></div>
            </label>
          <?php endforeach; ?>
        </div>

      </div>
    <?php endforeach; ?>

    <!-- Navigasi -->
    <div class="kuis-nav">
      <button type="button" class="btn-prev" id="btnPrev"
              onclick="pindahSoal(-1)" disabled>
        ← Sebelumnya
      </button>
      <button type="button" class="btn-next" id="btnNext"
              onclick="pindahSoal(1)">
        Selanjutnya →
      </button>
      <button type="button" class="btn-submit" id="btnSubmit"
              style="display:none" onclick="konfirmasiSubmit()">
        Selesai & Kirim ✓
      </button>
    </div>

  </form>

</div>

<!-- Modal Hasil -->
<div class="modal-hasil-overlay" id="modalHasil">
  <div class="modal-hasil">

    <!-- Header dinamis -->
    <div class="modal-hasil-header" id="modalHeader">
      <div class="hasil-centang" id="hasilCentang">
        <!-- icon berubah via JS -->
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none"
             stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="20 6 9 17 4 12"/>
        </svg>
      </div>
      <div class="hasil-judul" id="hasilJudul">Kuis Selesai!</div>
      <div class="hasil-subjudul" id="hasilSubjudul">Kerja bagus, terus semangat!</div>
      <div class="hasil-nama-kuis"><?= esc($quiz['name']) ?></div>
    </div>

    <!-- Body -->
    <div class="modal-hasil-body">

      <!-- Poin utama -->
      <div class="hasil-poin-wrap">
        <div class="hasil-poin-label">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="#16a34a">
            <path d="M12 2l2.9 6.3L22 9.2l-5 4.9L18.2 22 12 18.6 5.8 22 7 14.1 2 9.2l7.1-.9L12 2z"/>
          </svg>
          Poin yang kamu dapatkan
        </div>
        <div>
          <span class="hasil-poin-angka" id="hasilPoinAngka">0</span>
          <span class="hasil-poin-satuan"> poin</span>
        </div>
        <div class="hasil-poin-total" id="hasilPoinTotal">dari total 0 poin</div>
      </div>

      <!-- Statistik 3 kolom -->
      <div class="hasil-grid">
        <div class="hasil-stat">
          <div class="hasil-stat-angka hijau" id="hasilBenar">0</div>
          <div class="hasil-stat-label">✓ Benar</div>
        </div>
        <div class="hasil-stat">
          <div class="hasil-stat-angka merah" id="hasilSalah">0</div>
          <div class="hasil-stat-label">✗ Salah</div>
        </div>
        <div class="hasil-stat">
          <div class="hasil-stat-angka biru" id="hasilSkor">0%</div>
          <div class="hasil-stat-label">Skor</div>
        </div>
      </div>

      <!-- Info buku & member -->
      <div class="hasil-info-row">
        <div>Buku <span><?= esc($quiz['book_title']) ?></span></div>
        <div>Anggota <span><?= esc(trim($member['first_name'] . ' ' . $member['last_name'])) ?></span></div>
      </div>

      <!-- Tombol -->
      <a href="<?= base_url('member/pengembalian') ?>" class="btn-selesai">
        Selesai & Kembali →
      </a>

    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
const TOTAL_SOAL   = <?= $totalSoal ?>;
const TOTAL_POIN   = <?= $totalPoin ?>;
const DURASI_DETIK = <?= $quiz['duration_minutes'] * 60 ?>;
const QUIZ_ID      = <?= $quiz['id'] ?>;

let soalAktif  = 0;
let jawaban    = {}; // { question_id: opsi }
let timerSisa  = DURASI_DETIK;
let timerStart = Date.now();
let interval;

// ── Timer ────────────────────────────────────────────────
function startTimer() {
  interval = setInterval(() => {
    timerSisa--;
    document.getElementById('durasiDetik').value = DURASI_DETIK - timerSisa;

    const m = String(Math.floor(timerSisa / 60)).padStart(2, '0');
    const s = String(timerSisa % 60).padStart(2, '0');
    document.getElementById('timerTeks').textContent = `${m}:${s}`;

    if (timerSisa <= 60) {
      document.getElementById('timerBox').classList.add('mepet');
    }
    if (timerSisa <= 0) {
      clearInterval(interval);
      submitKuis();
    }
  }, 1000);
}

// ── Navigasi soal ─────────────────────────────────────────
function pindahSoal(arah) {
  document.getElementById(`soal-${soalAktif}`).style.display = 'none';
  soalAktif += arah;
  document.getElementById(`soal-${soalAktif}`).style.display = 'block';

  document.getElementById('btnPrev').disabled   = soalAktif === 0;
  document.getElementById('btnNext').style.display    = soalAktif < TOTAL_SOAL - 1 ? '' : 'none';
  document.getElementById('btnSubmit').style.display  = soalAktif === TOTAL_SOAL - 1 ? '' : 'none';

  const persen = Math.round((soalAktif + 1) / TOTAL_SOAL * 100);
  document.getElementById('progressFill').style.width = persen + '%';
  document.getElementById('labelProgress').textContent = `Soal ${soalAktif + 1} dari ${TOTAL_SOAL}`;
}

// ── Pilih opsi ───────────────────────────────────────────
function pilihOpsi(idx, opt) {
  // Hapus terpilih di soal ini
  ['A','B','C','D'].forEach(o => {
    document.getElementById(`opsi-${idx}-${o}`)?.classList.remove('terpilih');
  });
  document.getElementById(`opsi-${idx}-${opt}`)?.classList.add('terpilih');
}

// ── Konfirmasi submit ────────────────────────────────────
function konfirmasiSubmit() {
  const belumDijawab = TOTAL_SOAL - Object.keys(jawaban).length;
  if (belumDijawab > 0) {
    if (!confirm(`Masih ada ${belumDijawab} soal yang belum dijawab. Yakin ingin mengirim?`)) return;
  } else {
    if (!confirm('Yakin ingin mengirim jawaban?')) return;
  }
  submitKuis();
}

// ── Submit via AJAX ──────────────────────────────────────
function submitKuis() {
  clearInterval(interval);
  document.getElementById('durasiDetik').value = Math.floor((Date.now() - timerStart) / 1000);

  const form     = document.getElementById('formKuis');
  const formData = new FormData(form);

  fetch(form.action, {
    method: 'POST',
    headers: { 'X-Requested-With': 'XMLHttpRequest' },
    body: formData,
  })
  .then(r => r.json())
  .then(data => tampilHasil(data))
  .catch(() => { form.submit(); }); // fallback jika AJAX gagal
}

// ── Tampil modal hasil ───────────────────────────────────
function tampilHasil(data) {
  // Isi statistik
  document.getElementById('hasilPoinAngka').textContent = data.poin;
  document.getElementById('hasilPoinTotal').textContent = `dari total ${TOTAL_POIN} poin`;
  document.getElementById('hasilBenar').textContent     = data.benar;
  document.getElementById('hasilSalah').textContent     = data.salah;
  document.getElementById('hasilSkor').textContent      = data.skor + '%';

  const header = document.getElementById('modalHeader');

  if (data.skor >= 70) {
    header.className        = 'modal-hasil-header bagus';
    document.getElementById('hasilJudul').textContent    = 'Luar Biasa! 🎉';
    document.getElementById('hasilSubjudul').textContent = 'Kamu berhasil menjawab dengan sangat baik!';
  } else if (data.skor >= 40) {
    header.className        = 'modal-hasil-header cukup';
    document.getElementById('hasilJudul').textContent    = 'Cukup Baik! 👍';
    document.getElementById('hasilSubjudul').textContent = 'Tingkatkan lagi di percobaan berikutnya!';
  } else {
    header.className        = 'modal-hasil-header kurang';
    document.getElementById('hasilJudul').textContent    = 'Terus Belajar! 📚';
    document.getElementById('hasilSubjudul').textContent = 'Jangan menyerah, coba lagi ya!';
  }

  // Animasi angka poin (count up)
  let current = 0;
  const target  = data.poin;
  const step    = Math.ceil(target / 30);
  const counter = setInterval(() => {
    current = Math.min(current + step, target);
    document.getElementById('hasilPoinAngka').textContent = current;
    if (current >= target) clearInterval(counter);
  }, 30);

  document.getElementById('modalHasil').classList.add('tampil');
}

// ── Sinkron jawaban dari radio ke objek jawaban ──────────
document.getElementById('formKuis').addEventListener('change', function(e) {
  if (e.target.type === 'radio') {
    const name = e.target.name; // jawaban[id]
    const id   = name.match(/\d+/)[0];
    jawaban[id] = e.target.value;
  }
});

// ── Init ─────────────────────────────────────────────────
startTimer();

// Cegah keluar halaman tidak sengaja
window.addEventListener('beforeunload', function(e) {
  e.preventDefault();
  e.returnValue = '';
});
</script>
<?= $this->endSection() ?>