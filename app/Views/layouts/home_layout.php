<!DOCTYPE html>
<html lang="id">

<head>
  <?= $this->include('layouts/head') ?>

  <!-- Extra head e.g title -->
  <?= $this->renderSection('head') ?>

  <link rel="stylesheet" href="<?= base_url('assets/css/home.css'); ?>">

  <style>
    /* Reset padding bawaan agar hero bisa full-width/height */
    body {
      padding: 0;
      margin: 0;
    }
    /* Hilangkan wrapper pembatas dari layout lama */
    .page-wrapper,
    .body-wrapper,
    .container {
      all: unset;
      display: block;
    }
  </style>
</head>

<body class="position-relative">

  <!-- Main content langsung, tanpa container pembatas -->
  <div id="main-wrapper">
    <?= $this->renderSection('back') ?>
    
    <!-- Main content section (hero, dll) -->
    <?= $this->renderSection('content') ?>

    <!-- Footer tetap di bawah -->
    <?= $this->include('layouts/footer') ?>
  </div>

  <!-- Scripts -->
  <?= $this->include('imports/scripts/basic_scripts') ?>

  <!-- Extra scripts -->
  <?= $this->renderSection('scripts') ?>
</body>

</html>