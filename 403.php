<?php
declare(strict_types=1);
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/helpers.php';
require __DIR__ . '/includes/auth.php';
http_response_code(403);
include __DIR__ . '/includes/header.php';
?>
<div class="card">
  <h1>403 – Geen toegang</h1>
  <p>Je hebt geen rechten om deze pagina/bestand te bekijken, of het is niet (meer) gedeeld met jou.</p>
  <div class="actions">
    <a class="pill" href="index.php">Terug naar overzicht</a>
    <?php if (!is_logged_in()): ?>
      <a class="pill" href="login.php">Inloggen</a>
    <?php elseif (is_admin()): ?>
      <a class="pill" href="admin/dashboard.php">Beheer</a>
    <?php endif; ?>
  </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
