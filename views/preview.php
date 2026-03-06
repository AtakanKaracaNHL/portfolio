<?php
declare(strict_types=1);

require __DIR__ . '/../includes/config.php';
require __DIR__ . '/../includes/auth.php';

require_login();

$vid = (int)($_GET['vid'] ?? 0);
if ($vid <= 0) { header("Location: /403.php"); exit; }

$st = $pdo->prepare("SELECT original_name FROM file_versions WHERE id = ?");
$st->execute([$vid]);
$v = $st->fetch();

if (!$v) { header("Location: /403.php"); exit; }

include __DIR__ . '/../includes/header.php';
?>
<div class="card">
  <h1>Preview</h1>
  <p class="muted"><?= htmlspecialchars((string)$v['original_name']) ?></p>

  <div class="actions">
  <a class="pill" href="/portfolio/index.php">Terug naar de hoofdpagina</a>
  </div>

  <div class="pdfwrap">
    <iframe class="pdf" src="/portfolio/views/file.php?vid=<?= $vid ?>" title="PDF preview" loading="lazy"></iframe>
  </div>

</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
