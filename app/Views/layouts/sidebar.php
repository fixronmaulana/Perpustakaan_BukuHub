<?php
/**
 * Sidebar Admin — Portal Petugas
 */
$sidebarNavs = [
  'Home',
  [
    'name' => 'Dashboard',
    'link' => 'admin/dashboard',
    'icon' => 'ti ti-layout-dashboard',
    'key'  => 'dashboard'
  ],
  'Transaksi',
  [
    'name' => 'Peminjaman',
    'link' => 'admin/loans',
    'icon' => 'ti ti-arrows-exchange',
    'key'  => 'loans'
  ],
  [
    'name' => 'Pengembalian',
    'link' => 'admin/returns',
    'icon' => 'ti ti-check',
    'key'  => 'returns'
  ],
  [
    'name' => 'Denda',
    'link' => 'admin/fines',
    'icon' => 'ti ti-report-money',
    'key'  => 'fines'
  ],
  [
    'name' => 'Kunjungan',
    'link' => 'admin/kunjungan',
    'icon' => 'ti ti-door-enter',
    'key'  => 'kunjungan'
  ],
  'Master',
  [
    'name' => 'Anggota',
    'link' => 'admin/members',
    'icon' => 'ti ti-user',
    'key'  => 'members'
  ],
  [
    'name' => 'Buku',
    'link' => 'admin/books',
    'icon' => 'ti ti-book',
    'key'  => 'books'
  ],
  [
    'name' => 'Kuis',
    'link' => 'admin/kuis',
    'icon' => 'ti ti-help-circle',
    'key'  => 'kuis'
  ],
  [
    'name' => 'Kategori',
    'link' => 'admin/categories',
    'icon' => 'ti ti-category-2',
    'key'  => 'categories'
  ],
  [
    'name' => 'Rak',
    'link' => 'admin/racks',
    'icon' => 'ti ti-columns',
    'key'  => 'racks'
  ],
  'Gamifikasi',
  [
    'name' => 'Point Settings',
    'link' => 'admin/pengaturan-poin',
    'icon' => 'ti ti-star',
    'key'  => 'pengaturan-poin'
  ],
  [
    'name' => 'Leaderboard',
    'link' => 'admin/leaderboard',
    'icon' => 'ti ti-trophy',
    'key'  => 'leaderboard'
  ],
  'Notifikasi',
  [
    'name' => 'WA Reminder',
    'link' => 'admin/wa-reminder',
    'icon' => 'ti ti-brand-whatsapp',
    'key'  => 'wa-reminder'
  ],
];

if (auth()->user()->inGroup('superadmin') ?? false) {
  $sidebarNavs = array_merge($sidebarNavs, [
    'Manajemen Akun',
    [
      'name' => 'Admin',
      'link' => 'admin/users',
      'icon' => 'ti ti-user-cog',
      'key'  => 'users'
    ]
  ]);
}

$currentPath = uri_string();
?>

<style>
/* ── Admin Sidebar ── */
.left-sidebar {
  width: 240px !important;
  background: #ffffff !important;
  min-height: 100vh !important;
  position: fixed !important;
  top: 0 !important;
  left: 0 !important;
  display: flex !important;
  flex-direction: column !important;
  border-right: 1px solid rgba(30, 58, 138, 0.12) !important;
  box-shadow: 1px 0 10px rgba(15, 23, 42, 0.07) !important;
  z-index: 100 !important;
  transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1) !important;
  overflow: hidden !important;
  padding: 0 !important;
}

.left-sidebar .sidebar-brand {
  display: flex !important;
  align-items: center !important;
  gap: 14px !important;
  padding: 1.6rem 1.5rem !important;
  background: linear-gradient(135deg, rgba(30, 58, 138, 0.04), rgba(30, 64, 175, 0.02)) !important;
  border-bottom: 1px solid rgba(30, 58, 138, 0.12) !important;
}

.left-sidebar .sidebar-logo {
  width: 44px !important;
  height: 44px !important;
  flex-shrink: 0 !important;
  object-fit: contain !important;
}

.left-sidebar .sidebar-brand-title {
  font-size: 0.875rem !important;
  font-weight: 800 !important;
  color: #1e3a8a !important;
  line-height: 1.25 !important;
  letter-spacing: 0.2px !important;
}

.left-sidebar .sidebar-brand-sub {
  font-size: 0.7rem !important;
  color: #64748b !important;
  margin-top: 2px !important;
  font-weight: 500 !important;
}

.left-sidebar .admin-nav {
  list-style: none !important;
  padding: 0.5rem 0.65rem 1.5rem !important;
  display: flex !important;
  flex-direction: column !important;
  overflow-y: auto !important;
  gap: 2px !important;
  flex: 1 !important;
  margin: 0 !important;
}

.left-sidebar .admin-nav-label {
  font-size: 0.68rem !important;
  font-weight: 700 !important;
  color: #94a3b8 !important;
  text-transform: uppercase !important;
  letter-spacing: 1px !important;
  padding: 1rem 2.1rem 0.4rem !important;
  display: block !important;
}

.left-sidebar .admin-nav-link {
  display: flex !important;
  align-items: center !important;
  gap: 11px !important;
  padding: 10px 14px !important;
  border-radius: 10px !important;
  color: #1a1a2e !important;
  text-decoration: none !important;
  font-size: 0.875rem !important;
  font-weight: 500 !important;
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
  position: relative !important;
  line-height: 1.4 !important;
  margin: 0 1.2rem !important;
  background: transparent !important;
  box-shadow: none !important;
}

.left-sidebar .admin-nav-link i {
  font-size: 1.1rem !important;
  flex-shrink: 0 !important;
  width: 18px !important;
  text-align: center !important;
}

.left-sidebar .admin-nav-link:hover {
  background: rgba(30, 58, 138, 0.06) !important;
  color: #1e3a8a !important;
  transform: translateX(2px) !important;
}

.left-sidebar .admin-nav-link.aktif {
  background: linear-gradient(135deg, rgba(30, 64, 175, 0.12), rgba(59, 130, 246, 0.08)) !important;
  color: #1e3a8a !important;
  font-weight: 600 !important;
  box-shadow: inset 3px 0 0 #1e3a8a !important;
  transform: none !important;
}

@media (max-width: 992px) {
  .left-sidebar {
    transform: translateX(-100%) !important;
  }
  .left-sidebar.terbuka {
    transform: translateX(0) !important;
  }
}
</style>

<!-- Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="tutupSidebar()"></div>

<!-- Sidebar Start -->
<aside class="left-sidebar" id="leftSidebar">

  <!-- Brand -->
  <div class="sidebar-brand">
    <img src="<?= base_url('assets/images/logo-perpus3.png') ?>" alt="Logo SMK" class="sidebar-logo">
    <div>
      <div class="sidebar-brand-title">SMK Al-Munawwir</div>
      <div class="sidebar-brand-sub">Portal Petugas</div>
    </div>
  </div>

  <!-- Navigation -->
  <ul class="admin-nav">
    <?php foreach ($sidebarNavs as $nav) : ?>

      <?php if (is_string($nav)) : ?>
        <span class="admin-nav-label"><?= esc($nav) ?></span>

      <?php else :
        $isActive = str_starts_with($currentPath, $nav['link']);
      ?>
        <li>
          <a href="<?= base_url($nav['link']) ?>"
             class="admin-nav-link <?= $isActive ? 'aktif' : '' ?>">
            <i class="<?= esc($nav['icon']) ?>"></i>
            <span><?= esc($nav['name']) ?></span>
          </a>
        </li>

      <?php endif; ?>

    <?php endforeach; ?>
  </ul>

</aside>
<!-- Sidebar End -->

<script>
function toggleSidebar() {
  const sidebar = document.getElementById('leftSidebar');
  const overlay = document.getElementById('sidebarOverlay');
  const isOpen  = sidebar.classList.contains('terbuka');
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

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') tutupSidebar();
});
</script>