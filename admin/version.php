<?php
declare(strict_types=1);
require __DIR__ . '/../includes/config.php';
require __DIR__ . '/../includes/helpers.php';
require __DIR__ . '/../includes/auth.php';
require_admin();

$file_id = get_int('file_id');
$error = '';

$st = $pdo->prepare('SELECT id, title FROM files WHERE id = ?');
$st->execute([$file_id]);
$file = $st->fetch();
if (!$file) redirect_to('admin/files.php');

$st = $pdo->prepare('SELECT MAX(version_number) AS v FROM file_versions WHERE file_id = ?');
$st->execute([$file_id]);
$nextV = ((int)($st->fetch()['v'] ?? 0)) + 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (!isset($_FILES['upload']) || ($_FILES['upload']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
    $error = 'Selecteer een bestand.';
  } else {
    $orig = (string)$_FILES['upload']['name'];
    $tmp = (string)$_FILES['upload']['tmp_name'];
    $mime = (string)($_FILES['upload']['type'] ?? 'application/octet-stream');

    $ext = pathinfo($orig, PATHINFO_EXTENSION);
    $stored = bin2hex(random_bytes(16)) . ($ext ? ('.' . $ext) : '');

    $dir = __DIR__ . '/../uploads/' . $file_id;
    if (!is_dir($dir)) mkdir($dir, 0775, true);

    $destName = 'v' . $nextV . '_' . $stored;
    $dest = $dir . '/' . $destName;

    if (!move_uploaded_file($tmp, $dest)) {
      $error = 'Upload mislukt.';
    } else {
      $st = $pdo->prepare('INSERT INTO file_versions (file_id, version_number, stored_name, original_name, mime, uploaded_by) VALUES (?,?,?,?,?,?)');
      $st->execute([$file_id, $nextV, $destName, $orig, $mime, (int)current_user()['id']]);
      redirect_to('admin/files.php');
    }
  }
}

include __DIR__ . '/../includes/header.php';
?>
<div class="card">
  <h1>Nieuwe versie uploaden</h1>
  <p><strong><?= h((string)$file['title']) ?></strong> — v<?= (int)$nextV ?></p>
  <?php if ($error !== ''): ?>
    <p class="badge no"><?= h($error) ?></p>
  <?php endif; ?>
  <form method="post" enctype="multipart/form-data">
    <label>Bestand</label>
    <input type="file" name="upload" required />
    <div class="actions">
      <button type="submit">Upload versie</button>
      <a class="pill" href="files.php">Terug</a>
    </div>
  </form>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
