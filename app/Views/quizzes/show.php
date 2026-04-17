<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Kelola Soal — <?= esc($quiz['name']) ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php if (session()->getFlashdata('msg')): ?>
  <div class="pb-2">
    <div class="alert <?= (session()->getFlashdata('error') ?? false) ? 'alert-danger' : 'alert-success' ?> alert-dismissible fade show">
      <?= session()->getFlashdata('msg') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  </div>
<?php endif; ?>

<!-- Info kuis -->
<div class="card mb-3">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
      <div>
        <a href="<?= base_url('admin/kuis') ?>" class="btn btn-outline-primary btn-sm mb-2">
          <i class="ti ti-arrow-left"></i> Kembali
        </a>
        <h5 class="fw-semibold mb-1"><?= esc($quiz['name']) ?></h5>
        <p class="text-muted mb-1">
          <i class="ti ti-book me-1"></i><?= esc($quiz['book_title']) ?>
          <span class="mx-2">·</span>
          <i class="ti ti-clock me-1"></i><?= $quiz['duration_minutes'] ?> menit
          <span class="mx-2">·</span>
          <i class="ti ti-refresh me-1"></i>Maks. <?= $quiz['max_attempts'] ?>x
          <span class="mx-2">·</span>
          <i class="ti ti-star me-1"></i>Poin per soal:
          <b><?= $poinPerSoal ?></b>
          <span class="text-muted small">(100 ÷ <?= count($questions) ?: '?' ?> soal)</span>
          <span class="mx-2">·</span>
          <?php if ($quiz['is_active']): ?>
            <span class="badge bg-success">Aktif</span>
          <?php else: ?>
            <span class="badge bg-secondary">Nonaktif</span>
          <?php endif; ?>
        </p>
        <?php if (!empty($quiz['description'])): ?>
          <p class="text-muted small mb-0"><?= esc($quiz['description']) ?></p>
        <?php endif; ?>
      </div>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahSoal">
        <i class="ti ti-plus"></i> Tambah Soal
      </button>
    </div>
  </div>
</div>

<!-- Tabel soal -->
<div class="card">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="card-title fw-semibold mb-0">
        Daftar Soal
        <span class="badge bg-primary ms-2"><?= count($questions) ?> soal</span>
      </h5>
    </div>

    <?php if (empty($questions)): ?>
      <div class="text-center py-5 text-muted">
        <i class="ti ti-help-circle" style="font-size:3rem"></i>
        <p class="mt-2">Belum ada soal. Klik <b>Tambah Soal</b> untuk mulai membuat pertanyaan.</p>
      </div>
    <?php else: ?>
      <div class="accordion" id="accordionSoal">
        <?php $no = 1; foreach ($questions as $q): ?>
          <div class="accordion-item border mb-2 rounded">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed fw-semibold" type="button"
                      data-bs-toggle="collapse"
                      data-bs-target="#soal<?= $q['id'] ?>">
                <span class="badge bg-secondary me-2"><?= $no++ ?></span>
                <?= esc(mb_strimwidth($q['question'], 0, 80, '...')) ?>
                <span class="badge bg-<?= ['A'=>'success','B'=>'primary','C'=>'warning','D'=>'danger'][$q['correct_answer']] ?> ms-2">
                  Jawaban: <?= $q['correct_answer'] ?>
                </span>
              </button>
            </h2>
            <div id="soal<?= $q['id'] ?>" class="accordion-collapse collapse">
              <div class="accordion-body">
                <p class="fw-semibold mb-3"><?= esc($q['question']) ?></p>
                <div class="row g-2 mb-3">
                  <?php foreach (['A','B','C','D'] as $opt):
                    $key    = 'option_' . strtolower($opt);
                    $isTrue = $q['correct_answer'] === $opt;
                  ?>
                    <div class="col-12 col-md-6">
                      <div class="p-2 rounded border <?= $isTrue ? 'border-success' : '' ?>"
                           style="<?= $isTrue ? 'background:#d1e7dd' : '' ?>">
                        <span class="badge <?= $isTrue ? 'bg-success' : 'bg-secondary' ?> me-2"><?= $opt ?></span>
                        <?= esc($q[$key]) ?>
                        <?php if ($isTrue): ?>
                          <i class="ti ti-check text-success ms-1"></i>
                        <?php endif; ?>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
                <div class="d-flex gap-2">
                  <button class="btn btn-sm btn-primary"
                          onclick="bukaEditSoal(<?= htmlspecialchars(json_encode($q), ENT_QUOTES) ?>)">
                    <i class="ti ti-edit"></i> Edit
                  </button>
                  <form action="<?= base_url("admin/kuis/{$quiz['id']}/soal/{$q['id']}") ?>" method="post"
                        onsubmit="return confirm('Hapus soal ini?')">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-sm btn-danger">
                      <i class="ti ti-trash"></i> Hapus
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

  </div>
</div>

<!-- ── Modal Tambah Soal (tanpa input poin) ── -->
<div class="modal fade" id="modalTambahSoal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="<?= base_url("admin/kuis/{$quiz['id']}/soal") ?>" method="post">
        <?= csrf_field() ?>
        <div class="modal-header">
          <h5 class="modal-title fw-semibold">Tambah Soal</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="alert alert-info py-2 small mb-3">
            <i class="ti ti-info-circle me-1"></i>
            Poin per soal dihitung otomatis: <b>100 ÷ jumlah soal</b>. Setelah soal ini ditambah poin akan dihitung ulang.
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Pertanyaan <span class="text-danger">*</span></label>
            <textarea name="question" class="form-control" rows="3"
                      placeholder="Tulis pertanyaan disini..." required></textarea>
          </div>
          <div class="row g-3 mb-3">
            <?php foreach (['A','B','C','D'] as $opt): ?>
              <div class="col-12 col-md-6">
                <label class="form-label fw-semibold">Opsi <?= $opt ?> <span class="text-danger">*</span></label>
                <input type="text" name="option_<?= strtolower($opt) ?>"
                       class="form-control" placeholder="Jawaban opsi <?= $opt ?>" required>
              </div>
            <?php endforeach; ?>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Jawaban Benar <span class="text-danger">*</span></label>
            <select name="correct_answer" class="form-select" required>
              <option value="">-- Pilih --</option>
              <option value="A">A</option>
              <option value="B">B</option>
              <option value="C">C</option>
              <option value="D">D</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">
            <i class="ti ti-check me-1"></i> Simpan Soal
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ── Modal Edit Soal (tanpa input poin) ── -->
<div class="modal fade" id="modalEditSoal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="formEditSoal" method="post">
        <?= csrf_field() ?>
        <div class="modal-header">
          <h5 class="modal-title fw-semibold">Edit Soal</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label fw-semibold">Pertanyaan <span class="text-danger">*</span></label>
            <textarea name="question" id="editQuestion" class="form-control" rows="3" required></textarea>
          </div>
          <div class="row g-3 mb-3">
            <?php foreach (['A','B','C','D'] as $opt): ?>
              <div class="col-12 col-md-6">
                <label class="form-label fw-semibold">Opsi <?= $opt ?></label>
                <input type="text" name="option_<?= strtolower($opt) ?>"
                       id="editOption<?= $opt ?>" class="form-control" required>
              </div>
            <?php endforeach; ?>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Jawaban Benar</label>
            <select name="correct_answer" id="editCorrect" class="form-select" required>
              <option value="A">A</option>
              <option value="B">B</option>
              <option value="C">C</option>
              <option value="D">D</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">
            <i class="ti ti-check me-1"></i> Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function bukaEditSoal(q) {
  document.getElementById('editQuestion').value  = q.question;
  document.getElementById('editOptionA').value   = q.option_a;
  document.getElementById('editOptionB').value   = q.option_b;
  document.getElementById('editOptionC').value   = q.option_c;
  document.getElementById('editOptionD').value   = q.option_d;
  document.getElementById('editCorrect').value   = q.correct_answer;
  document.getElementById('formEditSoal').action =
    '<?= base_url("admin/kuis/{$quiz['id']}/soal/") ?>' + q.id + '/edit';
  new bootstrap.Modal(document.getElementById('modalEditSoal')).show();
}
</script>
<?= $this->endSection() ?>