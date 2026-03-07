<?= $this->extend('layouts/home_layout') ?>

<?= $this->section('head') ?>
<title><?= lang('Auth.login') ?></title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  :root {
    --navy: #0d1b3e;
    --blue: #3b5bdb;
    --blue-h: #2f4ec0;
    --white: #fff;
    --bg: #dde3f0;
    --text: #1a2340;
    --muted: #7a88a8;
    --border: #cdd5e8;
    --input-bg: #f3f5fb;
    --err: #c0392b;
    --ok: #1a7a4a;
  }

  * { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    font-family: 'DM Sans', sans-serif;
    background: var(--bg);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
  }

  /* ── Card ── */
  .lc {
    background: var(--white);
    border-radius: 16px;
    box-shadow: 0 8px 36px rgba(13,27,62,0.15);
    width: 340px;           /* lebar tetap */
    padding: 1.6rem 1.8rem 1.4rem;
  }

  /* ── Head ── */
  .lc-head {
    text-align: center;
    margin-bottom: 0.9rem;
  }

  .lc-icon {
    width: 72px; height: 72px;
    border-radius: 50%;
    overflow: hidden;
    margin: 0 auto 0.55rem;
    box-shadow: 0 3px 14px rgba(13,27,62,0.25);
    border: 2px solid var(--border);
  }

  .lc-icon img {
    width: 100%; height: 100%;
    object-fit: cover; display: block;
  }

  .lc-school { font-size: 0.88rem; font-weight: 700; color: var(--text); }
  .lc-tag    { font-size: 0.74rem; color: var(--muted); margin-top: 2px; }

  .lc-sep {
    height: 1px; background: var(--border);
    margin: 0.85rem 0;
  }

  .lc-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.2rem; font-weight: 700;
    color: var(--text); text-align: center;
    margin-bottom: 0.9rem;
  }

  /* ── Alert ── */
  .lc-alert {
    border-radius: 7px; padding: 7px 11px;
    font-size: 0.78rem; margin-bottom: 0.75rem; line-height: 1.45;
  }
  .lc-alert-err { background:#fff0f0; border:1px solid #f5c2c2; color:var(--err); }
  .lc-alert-ok  { background:#f0fff6; border:1px solid #a8e6c3; color:var(--ok); }

  /* ── Field ── */
  .fg { margin-bottom: 0.6rem; }

  .fg label {
    display: block;
    font-size: 0.75rem; font-weight: 600;
    color: var(--text); margin-bottom: 0.28rem;
  }

  .fg input {
    width: 100%;
    padding: 8px 11px;
    background: var(--input-bg);
    border: 1.5px solid var(--border);
    border-radius: 8px;
    font-size: 0.85rem;
    font-family: 'DM Sans', sans-serif;
    color: var(--text); outline: none;
    transition: border-color .18s, box-shadow .18s, background .18s;
  }

  .fg input:focus {
    border-color: var(--blue);
    background: var(--white);
    box-shadow: 0 0 0 3px rgba(59,91,219,.1);
  }

  .fg input::placeholder { color: #b8c3d8; }

  /* ── Remember ── */
  .rem {
    display: flex; align-items: center; gap: 6px;
    margin: 0.45rem 0 0.9rem;
  }

  .rem input { width:14px; height:14px; accent-color: var(--blue); cursor:pointer; }
  .rem label { font-size: 0.78rem; color: var(--muted); cursor: pointer; }

  /* ── Button ── */
  .btn-login {
    width: 100%; padding: 10px;
    background: var(--blue); color: var(--white);
    border: none; border-radius: 8px;
    font-size: 0.88rem; font-weight: 600;
    font-family: 'DM Sans', sans-serif;
    cursor: pointer;
    transition: background .18s, transform .13s;
  }
  .btn-login:hover  { background: var(--blue-h); transform: translateY(-1px); }
  .btn-login:active { transform: translateY(0); }

  /* ── Links ── */
  .lc-extra {
    text-align: center; font-size: 0.78rem;
    color: var(--muted); margin-top: 0.55rem;
  }
  .lc-extra a { color: var(--blue); text-decoration: none; font-weight: 600; }
  .lc-extra a:hover { color: var(--blue-h); }

  .back {
    display: block; text-align: center;
    margin-top: 0.85rem;
    font-size: 0.78rem; font-weight: 500;
    color: var(--blue); text-decoration: none;
    transition: color .18s;
  }
  .back:hover { color: var(--blue-h); }
</style>
<?= $this->endSection() ?>

<?= $this->section('back') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="lc">

  <!-- Branding -->
  <div class="lc-head">
    <div class="lc-icon"><img src="<?= base_url('assets/images/logo-smk.jpg') ?>" alt="Logo SMK AL-Munawwir"></div>
    <div class="lc-school">SMK AL-munawwir</div>
    <div class="lc-tag">Masuk untuk mengakses layanan perpustakaan</div>
  </div>

  <div class="lc-sep"></div>

  <div class="lc-title"><?= lang('Auth.login') ?></div>

  <!-- Alerts -->
  <?php if (session('error') !== null) : ?>
    <div class="lc-alert lc-alert-err"><?= session('error') ?></div>
  <?php elseif (session('errors') !== null) : ?>
    <div class="lc-alert lc-alert-err">
      <?php if (is_array(session('errors'))) : ?>
        <?php foreach (session('errors') as $error) : ?><?= $error ?><br><?php endforeach ?>
      <?php else : ?><?= session('errors') ?>
      <?php endif ?>
    </div>
  <?php endif ?>
  <?php if (session('message') !== null) : ?>
    <div class="lc-alert lc-alert-ok"><?= session('message') ?></div>
  <?php endif ?>

  <!-- Form -->
  <form action="<?= url_to('login') ?>" method="post">
    <?= csrf_field() ?>

    <div class="fg">
      <label for="email"><?= lang('Auth.email') ?></label>
      <input type="email" id="email" name="email"
        inputmode="email" autocomplete="email"
        placeholder="contoh@email.com"
        value="<?= old('email') ?>" required />
    </div>

    <div class="fg">
      <label for="password"><?= lang('Auth.password') ?></label>
      <input type="password" id="password" name="password"
        inputmode="text" autocomplete="current-password"
        placeholder="••••••••" required />
    </div>

    <?php if (setting('Auth.sessionConfig')['allowRemembering']) : ?>
      <div class="rem">
        <input type="checkbox" id="remember" name="remember"
          <?php if (old('remember')) : ?>checked<?php endif ?>>
        <label for="remember"><?= lang('Auth.rememberMe') ?></label>
      </div>
    <?php else : ?>
      <div style="height:.7rem"></div>
    <?php endif ?>

    <button type="submit" class="btn-login"><?= lang('Auth.login') ?></button>

    <?php if (setting('Auth.allowMagicLinkLogins')) : ?>
      <p class="lc-extra">
        <?= lang('Auth.forgotPassword') ?>
        <a href="<?= url_to('magic-link') ?>"><?= lang('Auth.useMagicLink') ?></a>
      </p>
    <?php endif ?>

    <?php /* if (setting('Auth.allowRegistration')) : ?>
      <p class="lc-extra"><?= lang('Auth.needAccount') ?> <a href="<?= url_to('register') ?>"><?= lang('Auth.register') ?></a></p>
    <?php endif */ ?>

  </form>

  <a href="<?= base_url() ?>" class="back">Kembali ke Beranda →</a>

</div>

<?= $this->endSection() ?>