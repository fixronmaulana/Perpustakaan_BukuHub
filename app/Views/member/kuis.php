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
  background: rgba(0,0,0,0.5);
  z-index: 9999;
  align-items: center;
  justify-content: center;
}
.modal-hasil-overlay.tampil { display: flex; }
.modal-hasil {
  background: #fff;
  border-radius: 16px;
  padding: 2rem;
  max-width: 440px;
  width: 90%;
  text-align: center;
  box-shadow: 0 20px 60px rgba(0,0,0,0.2);
  animation: popIn 0.3s ease;
}
@keyframes popIn {
  from { transform: scale(0.85); opacity: 0; }
  to   { transform: scale(1);    opacity: 1; }
}
.hasil-ikon { font-size: 3.5rem; margin-bottom: 0.75rem; }
.hasil-judul { font-size: 1.2rem; font-weight: 800; color: #0d1b3e; margin-bottom: 0.5rem; }
.hasil-poin  {
  font-size: 2.5rem;
  font-weight: 800;
  color: var(--navy-main);
  margin: 0.75rem 0;
}
.hasil-poin span { font-size: 1rem; font-weight: 600; color: var(--teks-redup); display: block; }
.hasil-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 0.75rem;
  margin: 1rem 0 1.5rem;
}
.hasil-stat {
  background: #f8fafc;
  border-radius: 10px;
  padding: 0.75rem;
}
.hasil-stat-angka { font-size: 1.4rem; font-weight: 700; }
.hasil-stat-label { font-size: 0.75rem; color: var(--teks-redup); margin-top: 2px; }
.btn-selesai {
  display: block;
  width: 100%;
  padding: 0.75rem;
  background: var(--navy-main);
  color: #fff;
  border: none;
  border-radius: 10px;
  font-weight: 700;
  font-size: 0.95rem;
  cursor: pointer;
  text-decoration: none;
}
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
    <div class="hasil-ikon" id="hasilIkon">🎉</div>
    <div class="hasil-judul" id="hasilJudul">Kuis Selesai!</div>
    <div class="hasil-poin" id="hasilPoin">
      0 <span>poin diperoleh</span>
    </div>
    <div class="hasil-grid">
      <div class="hasil-stat">
        <div class="hasil-stat-angka text-success" id="hasilBenar">0</div>
        <div class="hasil-stat-label">Jawaban Benar</div>
      </div>
      <div class="hasil-stat">
        <div class="hasil-stat-angka text-danger" id="hasilSalah">0</div>
        <div class="hasil-stat-label">Jawaban Salah</div>
      </div>
      <div class="hasil-stat">
        <div class="hasil-stat-angka" id="hasilTotal">0</div>
        <div class="hasil-stat-label">Total Soal</div>
      </div>
      <div class="hasil-stat">
        <div class="hasil-stat-angka text-primary" id="hasilSkor">0%</div>
        <div class="hasil-stat-label">Skor</div>
      </div>
    </div>
    <a href="<?= base_url('member/pengembalian') ?>" class="btn-selesai" id="btnSelesai">
      Kembali ke Pengembalian
    </a>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
const TOTAL_SOAL   = <?= $totalSoal ?>;
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
  document.getElementById('hasilPoin').innerHTML =
    `${data.poin} <span>poin diperoleh</span>`;
  document.getElementById('hasilBenar').textContent = data.benar;
  document.getElementById('hasilSalah').textContent = data.salah;
  document.getElementById('hasilTotal').textContent = data.total;
  document.getElementById('hasilSkor').textContent  = data.skor + '%';

  if (data.skor >= 70) {
    document.getElementById('hasilIkon').textContent  = '🎉';
    document.getElementById('hasilJudul').textContent = 'Luar Biasa!';
  } else if (data.skor >= 40) {
    document.getElementById('hasilIkon').textContent  = '👍';
    document.getElementById('hasilJudul').textContent = 'Cukup Baik!';
  } else {
    document.getElementById('hasilIkon').textContent  = '📚';
    document.getElementById('hasilJudul').textContent = 'Terus Belajar!';
  }

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