<?php
/**
 * List of sidebar navigations
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
    'name' => 'Kategori',
    'link' => '/admin/categories',
    'icon' => 'ti ti-category-2'
  ],
  [
    'name' => 'Rak',
    'link' => '/admin/racks',
    'icon' => 'ti ti-columns'
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

// Deteksi URL aktif
$currentPath = '/' . ltrim(current_url(true)->getPath(), '/');
?>

<!-- Sidebar Start -->
<aside class="left-sidebar" id="left-sidebar">

  <!-- Brand -->
  <div class="sidebar-brand">
    <img src="<?= base_url('assets/images/logo-smk.png') ?>" alt="Logo SMK" class="sidebar-logo">
    <div>
      <div class="sidebar-brand-title">SMK Al-Munawwir</div>
      <div class="sidebar-brand-sub">Portal Petugas</div>
    </div>
  </div>

  <!-- Sidebar Navigation -->
  <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
    <ul id="sidebarnav">

      <?php foreach ($sidebarNavs as $nav) : ?>

        <?php if (gettype($nav) === 'string') : ?>
          <!-- Label section -->
          <li class="nav-small-cap">
            <span class="hide-menu"><?= esc($nav) ?></span>
          </li>

        <?php else : ?>
          <?php
            // Cek apakah link ini aktif
            $isActive = str_starts_with($currentPath, $nav['link']);
            $activeClass = $isActive ? ' active' : '';
          ?>
          <li class="sidebar-item<?= $isActive ? ' selected' : '' ?>">
            <a class="sidebar-link<?= $activeClass ?>"
               href="<?= base_url($nav['link']) ?>"
               aria-expanded="false">
              <span>
                <i class="<?= esc($nav['icon']) ?>"></i>
              </span>
              <span class="hide-menu"><?= esc($nav['name']) ?></span>
            </a>
          </li>

        <?php endif; ?>

      <?php endforeach; ?>

    </ul>
  </nav>
  <!-- End Sidebar Navigation -->

</aside>
<!-- Sidebar End -->