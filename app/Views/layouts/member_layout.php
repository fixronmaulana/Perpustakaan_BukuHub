<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/member.css') ?>">
  <?= $this->renderSection('head') ?>
</head>
<body>

<!-- ══ SIDEBAR ══ -->
<aside class="sidebar" id="sidebar">

  <!-- Brand -->
  <div class="sidebar-brand">
    <img src="<?= base_url('assets/images/logo-smk.png') ?>" alt="Logo SMK" class="sidebar-logo">
    <div>
      <div class="sidebar-brand-title">SMK Al-Munawwir</div>
      <div class="sidebar-brand-sub">Portal Anggota</div>
    </div>
  </div>

  <nav class="sidebar-nav">

    <!-- MENU UTAMA -->
    <span class="sidebar-label">Menu Utama</span>

    <a href="<?= base_url('member/dashboard') ?>"
       class="sidebar-link <?= ($activeNav ?? '') === 'dashboard' ? 'aktif' : '' ?>">
      <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
      Dashboard
    </a>

    <a href="<?= base_url('member/kartu') ?>"
       class="sidebar-link <?= ($activeNav ?? '') === 'kartu' ? 'aktif' : '' ?>">
      <svg viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
      Kartu Perpustakaan
    </a>

    <!-- AKTIVITAS -->
    <span class="sidebar-label">Aktivitas</span>

    <a href="<?= base_url('member/peminjaman') ?>"
       class="sidebar-link <?= ($activeNav ?? '') === 'peminjaman' ? 'aktif' : '' ?>">
      <i class="ti ti-arrows-exchange"></i>
      Peminjaman
    </a>

    <a href="<?= base_url('member/pengembalian') ?>"
       class="sidebar-link <?= ($activeNav ?? '') === 'pengembalian' ? 'aktif' : '' ?>">
      <i class="ti ti-check"></i>
      Pengembalian
    </a>

    <a href="<?= base_url('member/kunjungan') ?>"
       class="sidebar-link <?= ($activeNav ?? '') === 'kunjungan' ? 'aktif' : '' ?>">
      <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
      Kunjungan
    </a>

    <!-- KOLEKSI -->
    <span class="sidebar-label">Koleksi</span>

    <a href="<?= base_url('member/daftarbuku') ?>"
       class="sidebar-link <?= ($activeNav ?? '') === 'daftarbuku' ? 'aktif' : '' ?>">
      <svg viewBox="0 0 24 24"><path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/><path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/></svg>
      Daftar Buku
    </a>

    <!-- GAMIFIKASI -->
    <span class="sidebar-label">Gamifikasi</span>

    <a href="<?= base_url('member/poin') ?>"
       class="sidebar-link <?= ($activeNav ?? '') === 'poin' ? 'aktif' : '' ?>">
      <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
      Poin Saya
    </a>

    <a href="<?= base_url('member/leaderboard') ?>"
       class="sidebar-link <?= ($activeNav ?? '') === 'leaderboard' ? 'aktif' : '' ?>">
      <i class="ti ti-trophy"></i>
      Leaderboard
    </a>
  </nav>
</aside>
<!-- ══ KONTEN UTAMA ══ -->
<div class="member-konten">

  <header class="topbar">
    <div style="display:flex;align-items:center;">
      <button class="tombol-toggle-sidebar"
              onclick="document.getElementById('sidebar').classList.toggle('terbuka')">
        <svg viewBox="0 0 24 24"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
      </button>
      <div class="topbar-kiri">
        <h2><?= $this->renderSection('pageTitle') ?></h2>
        <p><?php
          $jam = (int) date('H');
          if      ($jam >= 5  && $jam < 12) echo 'Good Morning';
          elseif  ($jam >= 12 && $jam < 15) echo 'Good Afternoon';
          elseif  ($jam >= 15 && $jam < 18) echo 'Good Evening';
          else                               echo 'Good Night';
        ?>, <?= isset($member['first_name']) ? esc($member['first_name']) : 'Anggota' ?></p>
      </div>
    </div>
    <!-- Dropdown Profil -->
    <div class="profil-dropdown" id="profilDropdown">
      
      <!-- Trigger: Avatar + Nama + Email -->
      <button class="profil-trigger" onclick="toggleProfilDropdown(event)" aria-label="Menu Profil">
        
        <!-- Avatar: Foto atau Inisial -->
        <?php
          $inisial = '';
          if (!empty($member['first_name'])) $inisial .= strtoupper(substr(trim($member['first_name']), 0, 1));
          $inisial = $inisial ?: 'A';
          $adaFoto = !empty($member['foto_profil']);
        ?>
        
        <?php if ($adaFoto): ?>
          <img src="<?= base_url('uploads/foto_profil/' . esc($member['foto_profil'])) ?>" 
               alt="Foto Profil" 
               class="profil-avatar"
               onerror="this.onerror=null; this.closest('.profil-trigger').querySelector('.profil-inisial').style.display='flex'; this.style.display='none';">
        <?php endif; ?>
        
        <div class="profil-inisial" style="<?= $adaFoto ? 'display:none;' : 'display:flex;' ?>">
          <?= esc($inisial) ?>
        </div>
        
        <!-- Info Nama + Email -->
        <div class="profil-info">
          <span class="profil-nama"><?= esc($member['first_name'] ?? 'Anggota') ?></span>
          <span class="profil-email"><?= esc($member['email'] ?? '') ?></span>
        </div>
        
        <!-- Chevron Icon -->
        <svg class="profil-chevron" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
        
      </button>
      
      <!-- Dropdown Menu -->
      <div class="dropdown-menu" id="dropdownMenu">
        <a href="<?= base_url('member/profil') ?>" class="dropdown-item">
          <svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          Profil Saya
        </a>
        <a href="<?= url_to('logout') ?>" class="dropdown-item item-keluar">
          <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
          Keluar
        </a>
      </div>
      
    </div>
  </header>

  <main class="area-halaman">
    <?= $this->renderSection('content') ?>
  </main>

</div>

<?= $this->renderSection('scripts') ?>
<script>
  document.addEventListener('click', function(e) {
    const sidebar = document.getElementById('sidebar');
    const toggle  = document.querySelector('.tombol-toggle-sidebar');
    if (sidebar && sidebar.classList.contains('terbuka')
        && !sidebar.contains(e.target)
        && toggle && !toggle.contains(e.target)) {
      sidebar.classList.remove('terbuka');
    }
  });
</script>
<script>
// Toggle Dropdown Profil
function toggleProfilDropdown(e) {
  e.stopPropagation();
  const dropdown = document.getElementById('profilDropdown');
  dropdown.classList.toggle('active');
}

// Tutup dropdown jika klik di luar
document.addEventListener('click', function(e) {
  const dropdown = document.getElementById('profilDropdown');
  const trigger  = document.querySelector('.profil-trigger');
  
  if (dropdown && dropdown.classList.contains('active')
      && !dropdown.contains(e.target)) {
    dropdown.classList.remove('active');
  }
  
  // Toggle sidebar logic (existing)
  const sidebar = document.getElementById('sidebar');
  const toggle  = document.querySelector('.tombol-toggle-sidebar');
  if (sidebar && sidebar.classList.contains('terbuka')
      && !sidebar.contains(e.target)
      && toggle && !toggle.contains(e.target)) {
    sidebar.classList.remove('terbuka');
  }
});

// Tutup dropdown dengan tombol Escape
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    const dropdown = document.getElementById('profilDropdown');
    if (dropdown) dropdown.classList.remove('active');
  }
});
</script>
</body>
</html>