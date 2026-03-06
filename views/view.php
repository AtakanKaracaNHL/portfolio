<?php
declare(strict_types=1);

require __DIR__ . '/../includes/config.php';
require __DIR__ . '/../includes/helpers.php';
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/guard.php';

$vid = get_int('vid');
$v = require_version_access($pdo, $vid);

$st = $pdo->prepare('SELECT id, version_number, uploaded_at FROM file_versions WHERE file_id = ? ORDER BY version_number DESC');
$st->execute([(int)$v['file_id']]);
$versions = $st->fetchAll();

include __DIR__ . '/../includes/header.php';
?>
<div class="card">
  <h1><?= h((string)$v['title']) ?></h1>
  <div class="muted"><?= h((string)$v['description']) ?></div>

  <div class="grid gridwide">
    <div class="card">
      <h3>Bestandsstatus</h3>
      <?php if ($v['status']==='approved'): ?><span class="badge ok">Goedgekeurd</span>
      <?php elseif ($v['status']==='rejected'): ?><span class="badge no">Afgekeurd / Aanpassing nodig</span>
      <?php else: ?><span class="badge">Ingediend</span>
      <?php endif; ?>
    </div>
    <div class="card">
      <h3>Versies</h3>
      <ul>
        <?php foreach ($versions as $row): ?>
          <li><a href="view.php?vid=<?= (int)$row['id'] ?>">v<?= (int)$row['version_number'] ?> (<?= h((string)$row['uploaded_at']) ?>)</a></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <div class="card">
      <h3>Acties</h3>
      <div class="actions">
        <a class="pill" href="download.php?vid=<?= (int)$v['id'] ?>">Download</a>
        <a class="pill" href="../index.php">Terug</a>
      </div>
    </div>
  </div>
</div>

<div class="viewer">
  <?php
    $mime = (string)$v['mime'];
    $inlineUrl = 'file.php?vid=' . (int)$v['id'];
    if (str_starts_with($mime, 'image/')) {
      echo '<img src="' . h($inlineUrl) . '" alt="viewer" />';
    } elseif ($mime === 'application/pdf') {
      echo '<iframe src="' . h($inlineUrl) . '"></iframe>';
    } elseif (str_starts_with($mime, 'text/')) {
      echo '<iframe src="' . h($inlineUrl) . '"></iframe>';
    } else {
      echo '<div class="card"><p>Preview voor dit type is niet beschikbaar. Gebruik download.</p></div>';
    }
  ?>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
