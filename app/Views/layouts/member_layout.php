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
  <link rel="shortcut icon" type="image/png" href="<?= base_url('assets/images/logo-perpus3.png') ?>" />
  <?= $this->renderSection('head') ?>
</head>
<body>

<!-- ══ SIDEBAR ══ -->
<aside class="sidebar" id="sidebar">

  <div class="sidebar-brand">
    <img src="<?= base_url('assets/images/logo-perpus3.png') ?>" alt="Logo SMK" class="sidebar-logo">
    <div>
      <div class="sidebar-brand-title">SMK Al-Munawwir</div>
      <div class="sidebar-brand-sub">Portal Anggota</div>
    </div>
  </div>

  <nav class="sidebar-nav">

    <span class="sidebar-label">Menu Utama</span>

    <a href="<?= base_url('member/dashboard') ?>"
       class="sidebar-link <?= ($activeNav ?? '') === 'dashboard' ? 'aktif' : '' ?>">
      <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
      Dashboard
    </a>

    <a href="<?= base_url('member/kartu') ?>"
       class="sidebar-link <?= ($activeNav ?? '') === 'kartu' ? 'aktif' : '' ?>">
      <svg viewBox="0 0 24 24"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg>
      Kartu Anggota
    </a>

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

    <span class="sidebar-label">Koleksi</span>

    <a href="<?= base_url('member/daftarbuku') ?>"
       class="sidebar-link <?= ($activeNav ?? '') === 'daftarbuku' ? 'aktif' : '' ?>">
      <svg viewBox="0 0 24 24"><path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/><path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/></svg>
      Daftar Buku
    </a>

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

    <!-- Kiri: Toggle + Judul -->
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

    <!-- Kanan: Notifikasi + Profil -->
    <div style="display:flex;align-items:center;gap:8px;">

      <!-- Tombol Notifikasi -->
      <div class="notif-wrapper" id="notifWrapper" style="position:relative;">
        <button id="notifTrigger"
                onclick="toggleNotif(event)"
                aria-label="Notifikasi poin"
                style="background:rgba(255,255,255,0.1);border:0.5px solid rgba(255,255,255,0.2);border-radius:8px;width:36px;height:36px;display:flex;align-items:center;justify-content:center;cursor:pointer;position:relative;transition:background 0.15s;">
          <i class="ti ti-bell" style="color:#fff;font-size:18px;" aria-hidden="true"></i>
          <span id="notifBadge"
                style="display:none;position:absolute;top:-4px;right:-4px;background:#ef4444;color:#fff;font-size:9px;font-weight:700;border-radius:999px;min-width:16px;height:16px;line-height:16px;text-align:center;padding:0 4px;border:2px solid transparent;">
            0
          </span>
        </button>

        <!-- Dropdown Notifikasi -->
        <div id="notifDropdown"
             style="display:none;position:absolute;right:0;top:calc(100% + 10px);width:340px;background:#fff;border:0.5px solid #e2e8f0;border-radius:12px;box-shadow:0 8px 32px rgba(0,0,0,0.12);z-index:999;overflow:hidden;">

          <!-- Header dropdown -->
          <div style="padding:14px 16px;border-bottom:0.5px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:8px;">
              <i class="ti ti-bell" style="font-size:16px;color:#6366f1;" aria-hidden="true"></i>
              <span style="font-size:14px;font-weight:600;color:#1e293b;">Notifikasi Poin</span>
              <span id="notifCountChip" style="display:none;background:#eef2ff;color:#4f46e5;font-size:11px;font-weight:600;border-radius:999px;padding:2px 8px;"></span>
            </div>
            <a href="<?= base_url('member/poin') ?>" style="font-size:12px;color:#6366f1;text-decoration:none;font-weight:600;">
              Lihat semua →
            </a>
          </div>

          <!-- List notifikasi -->
          <div id="notifList" style="max-height:380px;overflow-y:auto;">
            <div style="padding:32px 16px;text-align:center;">
              <i class="ti ti-loader-2" style="font-size:28px;color:#cbd5e1;display:block;margin-bottom:8px;" aria-hidden="true"></i>
              <p style="font-size:13px;color:#94a3b8;margin:0;">Memuat notifikasi...</p>
            </div>
          </div>

          <!-- Footer dropdown -->
          <div style="padding:10px 16px;border-top:0.5px solid #f1f5f9;text-align:center;">
            <a href="<?= base_url('member/poin') ?>" style="font-size:12px;color:#6366f1;text-decoration:none;font-weight:600;display:inline-flex;align-items:center;gap:4px;">
              Lihat semua riwayat poin
              <i class="ti ti-arrow-right" style="font-size:12px;" aria-hidden="true"></i>
            </a>
          </div>

        </div>
      </div>

      <!-- Dropdown Profil -->
      <div class="profil-dropdown" id="profilDropdown">

        <button class="profil-trigger" onclick="toggleProfilDropdown(event)" aria-label="Menu Profil">
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
          <div class="profil-info">
            <span class="profil-nama"><?= esc($member['first_name'] ?? 'Anggota') ?></span>
            <span class="profil-email"><?= esc($member['email'] ?? '') ?></span>
          </div>
          <svg class="profil-chevron" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
        </button>

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

    </div>
    <!-- /Kanan -->

  </header>

  <main class="area-halaman">
    <?= $this->renderSection('content') ?>
    <?= $this->include('layouts/member_footer') ?>
  </main>
</div>

<?= $this->renderSection('scripts') ?>

<script>
// Toggle Sidebar 
document.addEventListener('click', function(e) {
  const sidebar = document.getElementById('sidebar');
  const toggle  = document.querySelector('.tombol-toggle-sidebar');
  if (sidebar && sidebar.classList.contains('terbuka')
      && !sidebar.contains(e.target)
      && toggle && !toggle.contains(e.target)) {
    sidebar.classList.remove('terbuka');
  }
});

// Toggle Dropdown Profil 
function toggleProfilDropdown(e) {
  e.stopPropagation();
  document.getElementById('profilDropdown').classList.toggle('active');
  document.getElementById('notifDropdown').style.display = 'none';
}

document.addEventListener('click', function(e) {
  const profil = document.getElementById('profilDropdown');
  if (profil && profil.classList.contains('active') && !profil.contains(e.target)) {
    profil.classList.remove('active');
  }
});

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    document.getElementById('profilDropdown')?.classList.remove('active');
    document.getElementById('notifDropdown').style.display = 'none';
  }
});

// Notifikasi Poin 
const ikonNotif = {
  visit:         'ti-building',
  loan:          'ti-arrows-exchange',
  return_ontime: 'ti-check',
  return_late:   'ti-clock-x',
  quiz:          'ti-school'
};

const labelNotif = {
  visit:         'Kunjungan Perpustakaan',
  loan:          'Peminjaman Buku',
  return_ontime: 'Pengembalian Tepat Waktu',
  return_late:   'Pengembalian Terlambat',
  quiz:          'Kuis Buku'
};

// Key localStorage per member
const NOTIF_KEY = 'notif_last_read_<?= $member['id'] ?? 0 ?>';

function getLastRead() {
  return parseInt(localStorage.getItem(NOTIF_KEY) || '0');
}

function tandaiSudahDibaca() {
  localStorage.setItem(NOTIF_KEY, Date.now().toString());
}

function toggleNotif(e) {
  e.stopPropagation();
  const dropdown = document.getElementById('notifDropdown');
  const isOpen   = dropdown.style.display === 'block';
  document.getElementById('profilDropdown')?.classList.remove('active');
  if (isOpen) {
    dropdown.style.display = 'none';
  } else {
    dropdown.style.display = 'block';
    // Tandai sudah dibaca + sembunyikan badge
    tandaiSudahDibaca();
    sembunyikanBadge();
    muatNotifikasi();
  }
}

function sembunyikanBadge() {
  const badge     = document.getElementById('notifBadge');
  const countChip = document.getElementById('notifCountChip');
  badge.style.display     = 'none';
  countChip.style.display = 'none';
}

function tampilkanBadge(jumlah) {
  if (jumlah <= 0) return;
  const badge = document.getElementById('notifBadge');
  badge.textContent   = jumlah;
  badge.style.display = 'inline-block';
}

// Saat halaman load: hitung notif belum dibaca
function cekNotifBelumDibaca() {
  fetch('<?= base_url('member/notifikasi') ?>')
    .then(r => r.json())
    .then(data => {
      if (!data || data.length === 0) return;
      const lastRead  = getLastRead();
      const belumDibaca = data.filter(n => {
        return new Date(n.created_at).getTime() > lastRead;
      });
      tampilkanBadge(belumDibaca.length);
    })
    .catch(() => {});
}

function muatNotifikasi() {
  const list = document.getElementById('notifList');
  list.innerHTML = `
    <div style="padding:32px 16px;text-align:center;">
      <i class="ti ti-loader-2" style="font-size:28px;color:#cbd5e1;display:block;margin-bottom:8px;"></i>
      <p style="font-size:13px;color:#94a3b8;margin:0;">Memuat notifikasi...</p>
    </div>`;

  fetch('<?= base_url('member/notifikasi') ?>')
    .then(r => r.json())
    .then(data => {
      const countChip = document.getElementById('notifCountChip');

      if (!data || data.length === 0) {
        countChip.style.display = 'none';
        list.innerHTML = `
          <div style="padding:32px 16px;text-align:center;">
            <i class="ti ti-bell-off" style="font-size:32px;color:#cbd5e1;display:block;margin-bottom:8px;"></i>
            <p style="font-size:13px;color:#94a3b8;margin:0;">Belum ada notifikasi</p>
          </div>`;
        return;
      }

      countChip.textContent   = data.length + ' aktivitas';
      countChip.style.display = 'inline-block';

      list.innerHTML = data.map(n => {
        const isPos     = n.points >= 0;
        const label     = labelNotif[n.activity_type] ?? n.activity_type;
        const ikon      = ikonNotif[n.activity_type]  ?? 'ti-star';
        const tgl       = new Date(n.created_at).toLocaleDateString('id-ID', {
          day: '2-digit', month: 'short', year: 'numeric',
          hour: '2-digit', minute: '2-digit'
        });
        const warnaTeks = isPos ? '#15803d' : '#b91c1c';
        const warnaBg   = isPos ? '#dcfce7'  : '#fee2e2';
        const ikonBg    = isPos ? '#f0fdf4'  : '#fef2f2';
        const ikonClr   = isPos ? '#16a34a'  : '#dc2626';
        const poin      = (isPos ? '+' : '−') + Math.abs(n.points);

        return `
          <div style="padding:12px 16px;border-bottom:0.5px solid #f1f5f9;display:flex;align-items:flex-start;gap:12px;">
            <div style="width:36px;height:36px;border-radius:8px;background:${ikonBg};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
              <i class="ti ${ikon}" style="font-size:16px;color:${ikonClr};"></i>
            </div>
            <div style="flex:1;min-width:0;">
              <div style="font-size:13px;font-weight:600;color:#1e293b;">${label}</div>
              ${n.description ? `<div style="font-size:12px;color:#64748b;margin-top:2px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${n.description}</div>` : ''}
              <div style="font-size:11px;color:#94a3b8;margin-top:4px;">${tgl}</div>
            </div>
            <span style="background:${warnaBg};color:${warnaTeks};font-size:12px;font-weight:700;border-radius:8px;padding:3px 8px;flex-shrink:0;margin-top:2px;">${poin}</span>
          </div>`;
      }).join('');
    })
    .catch(() => {
      document.getElementById('notifList').innerHTML = `
        <div style="padding:32px 16px;text-align:center;">
          <i class="ti ti-wifi-off" style="font-size:28px;color:#fca5a5;display:block;margin-bottom:8px;"></i>
          <p style="font-size:13px;color:#ef4444;margin:0;">Gagal memuat notifikasi</p>
        </div>`;
    });
}

// Tutup notif dropdown jika klik di luar
document.addEventListener('click', function(e) {
  const wrapper  = document.getElementById('notifWrapper');
  const dropdown = document.getElementById('notifDropdown');
  if (wrapper && !wrapper.contains(e.target)) {
    dropdown.style.display = 'none';
  }
});

// Jalankan cek badge saat halaman load
cekNotifBelumDibaca();
</script>

</body>
</html>