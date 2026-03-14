<?php
$user    = auth()->user();
$inisial = '';
if (!empty($user->first_name)) $inisial .= strtoupper(substr(trim($user->first_name), 0, 1));
if (!empty($user->last_name))  $inisial .= strtoupper(substr(trim($user->last_name),  0, 1));
$inisial = $inisial ?: strtoupper(substr($user->username ?? 'A', 0, 1));
$adaFoto = !empty($user->foto_profil)
  && file_exists(ROOTPATH . 'public/uploads/foto_profil/' . $user->foto_profil);

$userGroup = auth()->user()->getGroups()[0] ?? 'admin';
?>

<header class="app-header" id="appHeader">
  <nav class="navbar navbar-expand-lg">

    <!-- ── Kiri: Toggle + Info halaman ── -->
    <div class="header-kiri">

      <!-- Toggle button — memanggil toggleSidebar() dari sidebar.php -->
      <button class="sidebar-toggle-btn" onclick="toggleSidebar()" aria-label="Toggle Sidebar">
        <i class="ti ti-menu-2"></i>
      </button>

      <div class="header-greeting">
        <div class="header-judul" id="headerJudul">Dashboard</div>
        <div class="header-tgl"><?= date('l, d F Y') ?></div>
      </div>
    </div>

    <!-- ── Kanan: Tombol aksi + Avatar dropdown ── -->
    <div class="header-kanan" id="navbarNav">

      <!-- Ajukan Peminjaman -->
      <a href="<?= base_url('admin/loans/new/members/search') ?>"
         target="_blank"
         class="header-btn btn-putih"
         title="Ajukan Peminjaman">
        <i class="ti ti-plus"></i>
        <span>Ajukan Peminjaman</span>
      </a>

      <!-- Pengembalian Buku -->
      <a href="<?= base_url('admin/returns/new/search') ?>"
         class="header-btn btn-outline-putih"
         title="Pengembalian Buku">
        <i class="ti ti-arrow-back"></i>
        <span>Pengembalian</span>
      </a>

      <!-- Bayar Denda -->
      <a href="<?= base_url('admin/fines/returns/search') ?>"
         class="header-btn btn-outline-kuning"
         title="Bayar Denda">
        <i class="ti ti-cash"></i>
        <span>Bayar Denda</span>
      </a>

      <!-- Pengaturan Denda (superadmin) -->
      <?php if (auth()->user()->inGroup('superadmin')) : ?>
      <a href="<?= base_url('admin/fines/settings') ?>"
         class="header-btn btn-outline-merah"
         title="Pengaturan Denda">
        <i class="ti ti-settings"></i>
        <span>Pengaturan Denda</span>
      </a>
      <?php endif; ?>

      <!-- ── Avatar + Dropdown ── -->
      <div class="profil-admin" id="profilAdmin">

        <button class="profil-admin-trigger" onclick="toggleProfilAdmin(event)" aria-label="Menu Profil">

          <!-- Avatar -->
          <div class="profil-admin-avatar">
            <?php if ($adaFoto) : ?>
              <img src="<?= base_url('uploads/foto_profil/' . esc($user->foto_profil)) ?>"
                   alt="Foto"
                   class="profil-admin-foto"
                   onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
              <span class="profil-admin-inisial" style="display:none"><?= esc($inisial) ?></span>
            <?php else : ?>
              <span class="profil-admin-inisial"><?= esc($inisial) ?></span>
            <?php endif; ?>
          </div>

          <!-- Nama + role -->
          <div class="profil-admin-info">
            <span class="profil-admin-nama">
              <?= esc(trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: $user->username) ?>
            </span>
            <span class="profil-admin-role"><?= esc($userGroup) ?></span>
          </div>

          <!-- Chevron -->
          <svg class="profil-admin-chevron" viewBox="0 0 24 24" width="13" height="13">
            <polyline points="6 9 12 15 18 9"/>
          </svg>

        </button>

        <!-- Dropdown -->
        <div class="profil-admin-dropdown" id="profilAdminDropdown">

          <!-- Info akun -->
          <div class="pad-info">
            <div class="pad-avatar">
              <?php if ($adaFoto) : ?>
                <img src="<?= base_url('uploads/foto_profil/' . esc($user->foto_profil)) ?>"
                     alt="Foto" class="profil-admin-foto"
                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                <span class="profil-admin-inisial" style="display:none"><?= esc($inisial) ?></span>
              <?php else : ?>
                <span class="profil-admin-inisial"><?= esc($inisial) ?></span>
              <?php endif; ?>
            </div>
            <div class="pad-detail">
              <div class="pad-nama">
                <?= esc(trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: $user->username) ?>
              </div>
              <div class="pad-email"><?= esc($user->email ?? '') ?></div>
              <span class="pad-badge pad-badge-<?= $userGroup === 'superadmin' ? 'hijau' : 'biru' ?>">
                <?= esc($userGroup) ?>
              </span>
            </div>
          </div>

          <div class="pad-divider"></div>

          <a href="<?= base_url('logout') ?>" class="pad-item pad-keluar">
            <i class="ti ti-logout"></i> Keluar
          </a>

        </div>
      </div><!-- /.profil-admin -->

    </div><!-- /#navbarNav -->
  </nav>
</header>

<script>
// ── Dropdown Profil ──
function toggleProfilAdmin(e) {
  e.stopPropagation();
  const wrap     = document.getElementById('profilAdmin');
  const dropdown = document.getElementById('profilAdminDropdown');
  wrap.classList.toggle('aktif');
  dropdown.classList.toggle('terbuka');
}

document.addEventListener('click', function(e) {
  const wrap = document.getElementById('profilAdmin');
  if (wrap && !wrap.contains(e.target)) {
    wrap.classList.remove('aktif');
    document.getElementById('profilAdminDropdown').classList.remove('terbuka');
  }
});

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    document.getElementById('profilAdmin')?.classList.remove('aktif');
    document.getElementById('profilAdminDropdown')?.classList.remove('terbuka');
  }
});

// ── Set judul halaman dari <title> atau h1 pertama ──
document.addEventListener('DOMContentLoaded', function() {
  const h1 = document.querySelector('main h1, .page-title, h1');
  const el = document.getElementById('headerJudul');
  if (el && h1) el.textContent = h1.textContent.trim();
});
</script>