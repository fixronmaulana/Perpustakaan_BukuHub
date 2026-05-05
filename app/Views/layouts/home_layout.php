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

    cekGulir();
    window.addEventListener('scroll', cekGulir, { passive: true });
  })();

  /* ── Burger Menu ── */
  function toggleMenu() {
    const menu   = document.getElementById('daftarMenu');
    const burger = document.getElementById('tombolBurger');
    menu.classList.toggle('terbuka');
    burger.classList.toggle('aktif');
  }

  // Tutup menu saat klik di luar navbar
  document.addEventListener('click', function(e) {
    const menu   = document.getElementById('daftarMenu');
    const burger = document.getElementById('tombolBurger');
    const nav    = document.querySelector('.bilah-navigasi');
    if (menu && burger && nav && !nav.contains(e.target)) {
      menu.classList.remove('terbuka');
      burger.classList.remove('aktif');
    }
  });

  // Tutup menu saat klik salah satu link
  document.querySelectorAll('.daftar-menu a').forEach(function(link) {
    link.addEventListener('click', function() {
      const menu   = document.getElementById('daftarMenu');
      const burger = document.getElementById('tombolBurger');
      if (menu)   menu.classList.remove('terbuka');
      if (burger) burger.classList.remove('aktif');
    });
  });
</script>

</body>

</html>