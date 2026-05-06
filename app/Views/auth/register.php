<?= $this->extend('layouts/home_layout') ?>

<?= $this->section('head') ?>
<title><?= lang('Auth.register') ?></title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="register-page">
  <div class="register-card">

    <!-- Header -->
    <div class="register-head">
      <div class="register-logo">
        <img src="<?= base_url('assets/images/logo-smk.png') ?>" alt="Logo SMK AL-Munawwir">
      </div>
      <div class="register-school">SMK AL-munawwir</div>
      <div class="register-tagline">Buat akun admin perpustakaan baru</div>
    </div>

    <div class="register-divider"></div>

    <div class="register-title"><?= lang('Auth.register') ?></div>

    <!-- Alerts -->
    <?php if (session('error') !== null) : ?>
      <div class="register-alert register-alert-error"><?= session('error') ?></div>
    <?php elseif (session('errors') !== null) : ?>
      <div class="register-alert register-alert-error">
        <?php if (is_array(session('errors'))) : ?>
          <?php foreach (session('errors') as $error) : ?><?= $error ?><br><?php endforeach ?>
        <?php else : ?>
          <?= session('errors') ?>
        <?php endif ?>
      </div>
    <?php endif ?>

    <!-- Form -->
    <form action="<?= base_url('admin/users') ?>" method="post">
      <?= csrf_field() ?>

      <!-- Email -->
      <div class="register-field">
        <label for="email"><?= lang('Auth.email') ?></label>
        <input
          type="email" id="email" name="email"
          inputmode="email" autocomplete="email"
          placeholder="contoh@email.com"
          value="<?= old('email') ?>" required
        >
      </div>

      <!-- Username -->
      <div class="register-field">
        <label for="username"><?= lang('Auth.username') ?></label>
        <input
          type="text" id="username" name="username"
          inputmode="text" autocomplete="username"
          placeholder="<?= lang('Auth.username') ?>"
          value="<?= old('username') ?>" required
        >
      </div>

      <!-- Password -->
      <div class="register-field">
        <label for="password"><?= lang('Auth.password') ?></label>
        <input
          type="password" id="password" name="password"
          inputmode="text" autocomplete="new-password"
          placeholder="••••••••" required
        >
      </div>

      <!-- Password Confirm -->
      <div class="register-field">
        <label for="password_confirm"><?= lang('Auth.passwordConfirm') ?></label>
        <input
          type="password" id="password_confirm" name="password_confirm"
          inputmode="text" autocomplete="new-password"
          placeholder="••••••••" required
        >
      </div>

      <button type="submit" class="register-btn"><?= lang('Auth.register') ?></button>

      <!--
      <p class="register-extra">
        <?= lang('Auth.haveAccount') ?>
        <a href="<?= url_to('login') ?>"><?= lang('Auth.login') ?></a>
      </p>
      -->

    </form>

    <a href="<?= base_url('admin/users') ?>" class="register-back">← Kembali ke Daftar Admin</a>

  </div>
</div>

<?= $this->endSection() ?>