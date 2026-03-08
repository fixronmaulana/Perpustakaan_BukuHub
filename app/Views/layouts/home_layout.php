<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Google Fonts — load di head agar tidak blocking render -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">

  <!-- CSS global home -->
  <link rel="stylesheet" href="<?= base_url('assets/css/home.css') ?>">

  <!-- Slot untuk tambahan head per-halaman (title, css khusus, dll) -->
  <?= $this->renderSection('head') ?>
</head>

<body>

  <!-- Slot untuk konten utama tiap halaman -->
  <?= $this->renderSection('content') ?>

  <!-- Scripts dasar (bootstrap js, dll jika ada) -->
  <?= $this->include('imports/scripts/basic_scripts') ?>

  <!-- Slot untuk script tambahan per-halaman -->
  <?= $this->renderSection('scripts') ?>
<script>
  /* ── Navbar scroll effect ── */
  (function () {
    const navbar = document.querySelector('.bilah-navigasi');
    if (!navbar) return;

    function cekGulir() {
      if (window.scrollY > 30) {
        navbar.classList.add('menggulir');
      } else {
        navbar.classList.remove('menggulir');
      }
    }

    // Cek langsung saat halaman load (kalau refresh di tengah halaman)
    cekGulir();
    window.addEventListener('scroll', cekGulir, { passive: true });
  })();
</script>
</body>

</html>