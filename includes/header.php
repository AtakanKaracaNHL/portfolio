<?php
declare(strict_types=1);

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/auth.php';

$base = project_base();
$u = current_user();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Online Portfolio</title>
  <link rel="stylesheet" href="<?= h($base) ?>/assets/styles.css" />
</head>
<body>
<header class="topbar">
  <div class="container">
    <div class="brand"><a href="<?= h($base) ?>/index.php">Portfolio</a></div>
    <nav class="nav">
      <a href="<?= h($base) ?>/index.php">Overzicht</a>
      <?php if ($u): ?>
        <?php if (is_admin()): ?>
          <a class="pill" href="<?= h($base) ?>/admin/dashboard.php">Beheer</a>
        <?php endif; ?>
        <a class="pill" href="<?= h($base) ?>/logout.php">Logout (<?= h((string)$u['username']) ?>)</a>
      <?php else: ?>
        <a class="pill" href="<?= h($base) ?>/login.php">Login</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main class="container">
