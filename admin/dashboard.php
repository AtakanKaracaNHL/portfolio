<?php
declare(strict_types=1);
require __DIR__ . '/../includes/config.php';
require __DIR__ . '/../includes/helpers.php';
require __DIR__ . '/../includes/auth.php';
require_admin();
include __DIR__ . '/../includes/header.php';
?>
<div class="grid">
  <div class="card">
    <h1>Beheer</h1>
    <p>Beheerder kan modules beheren, bestanden uploaden, versies toevoegen, opmerkingen beheren en toegang per bezoeker instellen.</p>
    <div class="actions">
      <a class="pill" href="visitors.php">Bezoekers</a>
      <a class="pill" href="modules.php">Modules</a>
      <a class="pill" href="files.php">Bestanden</a>
    </div>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
