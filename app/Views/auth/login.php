<?= $this->extend('layouts/home_layout') ?>

<?= $this->section('head') ?>
<title><?= lang('Auth.login') ?> — Perpustakaan SMK</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="halaman-masuk">
  <div class="kartu-masuk">

    <!-- Bagian Atas: Logo & Nama Sekolah -->
    <div class="kepala-masuk">
      <div class="logo-masuk">
        <img src="<?= base_url('assets/images/logo-perpus3.png') ?>" alt="Logo SMK AL-Munawwir">
      </div>
      <!-- <div class="nama-sekolah">SMK AL-munawwir</div> -->
      <div class="tagline-masuk"> <span style="color: #1e3a8a; font-size: 1rem; font-weight:700;">Selamat Datang</span><br> Masuk untuk akses layanan perpustakaan kami.</div>
    </div>

    <div class="pemisah-masuk"></div>

    <div class="judul-masuk"><?= lang('Auth.login') ?></div>

    <!-- Pesan Error / Sukses -->
    <?php if (session('error') !== null) : ?>
      <div class="pesan-masuk pesan-error"><?= session('error') ?></div>
    <?php elseif (session('errors') !== null) : ?>
      <div class="pesan-masuk pesan-error">
        <?php if (is_array(session('errors'))) : ?>
          <?php foreach (session('errors') as $error) : ?><?= $error ?><br><?php endforeach ?>
        <?php else : ?>
          <?= session('errors') ?>
        <?php endif ?>
      </div>
    <?php endif ?>

    <?php if (session('message') !== null) : ?>
      <div class="pesan-masuk pesan-sukses"><?= session('message') ?></div>
    <?php endif ?>

    <!-- Form Login -->
    <form action="<?= url_to('login') ?>" method="post">
      <?= csrf_field() ?>

      <div class="grup-input">
        <label for="username">Username</label>
        <input
          type="text" id="username" name="username"
          inputmode="text" autocomplete="username"
          placeholder="Masukkan No. Identitas (NIS/NISN/NIK)"
          value="<?= old('username') ?>" required
        >
      </div>

      <div class="grup-input">
        <label for="password"><?= lang('Auth.password') ?></label>
        <input
          type="password" id="password" name="password"
          inputmode="text" autocomplete="current-password"
          placeholder="••••••••" required
        >
      </div>

      <?php if (setting('Auth.sessionConfig')['allowRemembering']) : ?>
        <div class="baris-ingat">
          <input type="checkbox" id="remember" name="remember"
            <?php if (old('remember')) : ?>checked<?php endif ?>>
          <label for="remember"><?= lang('Auth.rememberMe') ?></label>
        </div>
      <?php else : ?>
        <div style="height:.7rem"></div>
      <?php endif ?>

      <button type="submit" class="tombol-submit-masuk"><?= lang('Auth.login') ?></button>

      <?php if (setting('Auth.allowMagicLinkLogins')) : ?>
        <p class="tautan-ekstra">
          <?= lang('Auth.forgotPassword') ?>
          <a href="<?= url_to('magic-link') ?>"><?= lang('Auth.useMagicLink') ?></a>
        </p>
      <?php endif ?>

      <?php /* if (setting('Auth.allowRegistration')) : ?>
        <p class="tautan-ekstra">
          <?= lang('Auth.needAccount') ?>
          <a href="<?= url_to('register') ?>"><?= lang('Auth.register') ?></a>
        </p>
      <?php endif */ ?>

    </form>

    <a href="<?= base_url() ?>" class="tautan-kembali">Kembali ke Beranda →</a>

  </div>
</div>

<?= $this->endSection() ?>