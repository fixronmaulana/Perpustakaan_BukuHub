<?php
/**
 * Sidebar Admin — Portal Petugas
 */
$sidebarNavs = [
  'Home',
  [
    'name' => 'Dashboard',
    'link' => '/admin/dashboard',
    'icon' => 'ti ti-layout-dashboard'
  ],
  'Transaksi',
  [
    'name' => 'Peminjaman',
    'link' => '/admin/loans',
    'icon' => 'ti ti-arrows-exchange'
  ],
  [
    'name' => 'Pengembalian',
    'link' => '/admin/returns',
    'icon' => 'ti ti-check'
  ],
  [
    'name' => 'Denda',
    'link' => '/admin/fines',
    'icon' => 'ti ti-report-money'
  ],
  [
    'name' => 'Kunjungan',
    'link' => '/admin/kunjungan',
    'icon' => 'ti ti-door-enter'
  ],
  'Master',
  [
    'name' => 'Anggota',
    'link' => '/admin/members',
    'icon' => 'ti ti-user'
  ],
  [
    'name' => 'Buku',
    'link' => '/admin/books',
    'icon' => 'ti ti-book'
  ],
  [
  'name' => 'Kuis',
  'link' => '/admin/kuis',
  'icon' => 'ti ti-help-circle'
  ],
  [
    'name' => 'Kategori',
    'link' => '/admin/categories',
    'icon' => 'ti ti-category-2'
  ],
  [
    'name' => 'Rak',
    'link' => '/admin/racks',
    'icon' => 'ti ti-columns'
  ],
  'Gamifikasi',
  [
    'name' => 'Point Settings',
    'link' => '/admin/pengaturan-poin',
    'icon' => 'ti ti-star'
  ],
];

if (auth()->user()->inGroup('superadmin') ?? false) {
  $sidebarNavs = array_merge($sidebarNavs, [
    'Manajemen Akun',
    [
      'name' => 'Admin',
      'link' => '/admin/users',
      'icon' => 'ti ti-user-cog'
    ]
  ]);
}

$currentPath = '/' . ltrim(current_url(true)->getPath(), '/');
?>

<!-- Overlay (klik untuk tutup sidebar di mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="tutupSidebar()"></div>

<!-- Sidebar Start -->
<aside class="left-sidebar" id="leftSidebar">

  <!-- Brand -->
  <div class="sidebar-brand">
    <img src="<?= base_url('assets/images/logo-smk.png') ?>" alt="Logo SMK" class="sidebar-logo">
    <div>
      <div class="sidebar-brand-title">SMK Al-Munawwir</div>
      <div class="sidebar-brand-sub">Portal Petugas</div>
    </div>
  </div>

  <!-- Navigation -->
  <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
    <ul id="sidebarnav">

      <?php foreach ($sidebarNavs as $nav) : ?>

        <?php if (gettype($nav) === 'string') : ?>
          <li class="nav-small-cap">
            <span class="hide-menu"><?= esc($nav) ?></span>
          </li>

        <?php else :
          $isActive   = str_starts_with($currentPath, $nav['link']);
          $activeClass = $isActive ? ' active' : '';
        ?>
          <li class="sidebar-item<?= $isActive ? ' selected' : '' ?>">
            <a class="sidebar-link<?= $activeClass ?>"
               href="<?= base_url($nav['link']) ?>"
               aria-expanded="false">
              <span><i class="<?= esc($nav['icon']) ?>"></i></span>
              <span class="hide-menu"><?= esc($nav['name']) ?></span>
            </a>
          </li>

        <?php endif; ?>

      <?php endforeach; ?>

    </ul>
  </nav>

</aside>
<!-- Sidebar End -->

<script>
// ── Toggle sidebar (dipanggil dari tombol di header/topbar) ──
function toggleSidebar() {
  const sidebar  = document.getElementById('leftSidebar');
  const overlay  = document.getElementById('sidebarOverlay');
  const isOpen   = sidebar.classList.contains('terbuka');

  if (isOpen) {
    tutupSidebar();
  } else {
    sidebar.classList.add('terbuka');
    overlay.classList.add('aktif');
    document.body.style.overflow = 'hidden';
  }
}

function tutupSidebar() {
  const sidebar = document.getElementById('leftSidebar');
  const overlay = document.getElementById('sidebarOverlay');
  sidebar.classList.remove('terbuka');
  overlay.classList.remove('aktif');
  document.body.style.overflow = '';
}

// Tutup dengan tombol Escape
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') tutupSidebar();
});
</script>