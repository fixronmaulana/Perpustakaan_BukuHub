<?php

/**
 * Template pager untuk portal anggota
 * Lokasi: app/Views/layouts/member_pager.php
 * Didaftarkan di: app/Config/Pager.php sebagai 'member_pager'
 */

$pager->setSurroundCount(2);

?>

<nav class="pager-member">

  <?php if ($pager->hasPrevious()): ?>
    <a href="<?= $pager->getPrevious() ?>" class="tombol-pager">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
           stroke-linecap="round" stroke-linejoin="round" width="16" height="16">
        <polyline points="15 18 9 12 15 6"/>
      </svg>
    </a>
  <?php else: ?>
    <span class="tombol-pager nonaktif">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
           stroke-linecap="round" stroke-linejoin="round" width="16" height="16">
        <polyline points="15 18 9 12 15 6"/>
      </svg>
    </span>
  <?php endif; ?>

  <?php foreach ($pager->links() as $link): ?>
    <?php if ($link['active']): ?>
      <span class="tombol-pager aktif"><?= $link['title'] ?></span>
    <?php else: ?>
      <a href="<?= $link['uri'] ?>" class="tombol-pager"><?= $link['title'] ?></a>
    <?php endif; ?>
  <?php endforeach; ?>

  <?php if ($pager->hasNext()): ?>
    <a href="<?= $pager->getNext() ?>" class="tombol-pager">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
           stroke-linecap="round" stroke-linejoin="round" width="16" height="16">
        <polyline points="9 18 15 12 9 6"/>
      </svg>
    </a>
  <?php else: ?>
    <span class="tombol-pager nonaktif">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
           stroke-linecap="round" stroke-linejoin="round" width="16" height="16">
        <polyline points="9 18 15 12 9 6"/>
      </svg>
    </span>
  <?php endif; ?>

</nav>