<header class="app-header">
  <nav class="navbar navbar-expand-lg navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item d-block d-xl-none">
        <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
          <i class="ti ti-menu-2"></i>
        </a>
      </li>
    </ul>
    <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
      <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end gap-2" id="headerCollapse">
        
        <!-- Tombol: Ajukan Peminjaman -->
        <li class="nav-item" id="navBtn">
          <a href="<?= base_url('admin/loans/new/members/search'); ?>" target="_blank" class="btn btn-primary text-nowrap">
            <i class="ti ti-plus d-xl-none"></i>
            <span class="d-none d-xl-inline">Ajukan peminjaman</span>
          </a>
        </li>
        
        <!-- Tombol: Pengembalian Buku -->
        <li class="nav-item" id="navBtn">
          <a href="<?= base_url('admin/returns/new/search'); ?>" class="btn btn-outline-primary text-nowrap">
            <i class="ti ti-arrow-back d-xl-none"></i>
            <span class="d-none d-xl-inline">Pengembalian buku</span>
          </a>
        </li>
        
        <!-- Tombol: Bayar Denda -->
        <li class="nav-item" id="navBtn">
          <a href="<?= base_url('admin/fines/returns/search'); ?>" class="btn btn-outline-warning text-nowrap">
            <i class="ti ti-cash d-xl-none"></i>
            <span class="d-none d-xl-inline">Bayar denda</span>
          </a>
        </li>
        
        <!-- Tombol: Pengaturan Denda (Superadmin Only) -->
        <?php if (auth()->user()->inGroup('superadmin')) : ?>
          <li class="nav-item" id="navBtn">
            <a href="<?= base_url('admin/fines/settings'); ?>" class="btn btn-outline-danger text-nowrap">
              <i class="ti ti-settings d-xl-none"></i>
              <span class="d-none d-xl-inline">Pengaturan Denda</span>
            </a>
          </li>
        <?php endif; ?>
        
        <!-- Dropdown Profil -->
        <li class="nav-item dropdown">
          <a class="nav-link nav-icon-hover position-relative" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
            <?php
              $user = auth()->user();
              $inisial = '';
              if (!empty($user->first_name)) $inisial .= strtoupper(substr(trim($user->first_name), 0, 1));
              if (!empty($user->last_name))  $inisial .= strtoupper(substr(trim($user->last_name), 0, 1));
              $inisial = $inisial ?: strtoupper(substr($user->username ?? 'A', 0, 1));
              $adaFoto = !empty($user->foto_profil) && file_exists(ROOTPATH . 'public/uploads/foto_profil/' . ($user->foto_profil ?? ''));
            ?>
            
            <!-- Container Avatar -->
            <div class="avatar-wrapper" style="position: relative; width: 100%; height: 100%;">
              <?php if ($adaFoto): ?>
                <!-- Tampilkan Foto -->
                <img src="<?= base_url('uploads/foto_profil/' . esc($user->foto_profil)) ?>" 
                     alt="Foto Profil" 
                     class="avatar-image"
                     style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%; display: block;"
                     onerror="this.style.display='none'; document.getElementById('avatar-fallback-<?= $user->id ?>').style.display='flex';">
              <?php endif; ?>
              
              <!-- Fallback: Inisial atau Icon User -->
              <div id="avatar-fallback-<?= $user->id ?>" 
                   class="avatar-fallback"
                   style="width: 100%; height: 100%; border-radius: 50%; background: rgba(255,255,255,0.2); display: <?= $adaFoto ? 'none' : 'flex'; ?>; align-items: center; justify-content: center; color: #ffffff;">
                <?php if (!empty($inisial) && $inisial !== 'A'): ?>
                  <span style="font-weight: 700; font-size: 1rem;"><?= esc($inisial) ?></span>
                <?php else: ?>
                  <i class="ti ti-user" style="font-size: 1.4rem; stroke-width: 2; color: #ffffff;"></i>
                <?php endif; ?>
              </div>
            </div>
          </a>
          
          <!-- Dropdown Menu -->
          <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" style="min-width: 240px;" aria-labelledby="drop2">
            <div class="message-body">
              <h5>Profil</h5>
              <span>username: <b><?= esc($user->username); ?></b></span>
              <span>email: <b><?= esc($user->email); ?></b></span>
              <span>level: 
                <?php
                $userGroup = auth()->user()->getGroups()[0];
                if ($userGroup === 'superadmin') : ?>
                  <span class="badge bg-success"><?= esc($userGroup); ?></span>
                <?php elseif ($userGroup === 'admin') : ?>
                  <span class="badge bg-primary"><?= esc($userGroup); ?></span>
                <?php else : ?>
                  <span class="badge bg-secondary"><?= esc($userGroup); ?></span>
                <?php endif; ?>
              </span>
              
              <!-- 🔴 LOGOUT BUTTON - SVG INLINE (PASTI MUNCUL) -->
              <a href="<?= base_url('logout'); ?>" class="btn btn-outline-danger">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                  <polyline points="16 17 21 12 16 7"></polyline>
                  <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
                <span>Logout</span>
              </a>
              
            </div>
          </div>
        </li>
        
      </ul>
    </div>
  </nav>
</header>
<!--  Header End -->