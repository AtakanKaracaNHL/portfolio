<?php
declare(strict_types=1);

require __DIR__ . '/../includes/config.php';
require __DIR__ . '/../includes/helpers.php';
require __DIR__ . '/../includes/auth.php';

require_login();

$vid = get_int('vid');
if ($vid <= 0) {
    redirect_to('403.php');
}

$st = $pdo->prepare('SELECT original_name FROM file_versions WHERE id = ?');
$st->execute([$vid]);
$v = $st->fetch();

if (!$v) {
    redirect_to('403.php');
}

include __DIR__ . '/../includes/header.php';
?>
<div class="card">
  <h1>Preview</h1>
  <p class="muted"><?= h((string)$v['original_name']) ?></p>

  <div class="actions">
    <a class="pill" href="/index.php">Terug naar de hoofdpagina</a>
    <a class="pill" href="/views/file.php?vid=<?= (int)$vid ?>" target="_blank">Open in nieuw tabblad</a>
  </div>

  <div class="pdfwrap">
    <iframe class="pdf" src="/views/file.php?vid=<?= (int)$vid ?>" title="PDF preview" loading="lazy"></iframe>
  </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>