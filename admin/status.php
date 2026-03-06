<?php
declare(strict_types=1);
require __DIR__ . '/../includes/config.php';
require __DIR__ . '/../includes/helpers.php';
require __DIR__ . '/../includes/auth.php';
require_admin();

$file_id = get_int('file_id');
$st = $pdo->prepare('SELECT id, title, status FROM files WHERE id = ?');
$st->execute([$file_id]);
$file = $st->fetch();
if (!$file) redirect_to('admin/files.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $status = post_str('status');
  if (!in_array($status, ['submitted','approved','rejected'], true)) $status = 'submitted';
  $st = $pdo->prepare('UPDATE files SET status = ? WHERE id = ?');
  $st->execute([$status, $file_id]);
  redirect_to('admin/files.php');
}

include __DIR__ . '/../includes/header.php';
?>
<div class="card">
  <h1>Status wijzigen</h1>
  <p><strong><?= h((string)$file['title']) ?></strong></p>
  <form method="post">
    <label>Status</label>
    <select name="status">
      <option value="submitted" <?= ($file['status']==='submitted')?'selected':'' ?>>Ingediend</option>
      <option value="approved" <?= ($file['status']==='approved')?'selected':'' ?>>Goedgekeurd</option>
      <option value="rejected" <?= ($file['status']==='rejected')?'selected':'' ?>>Afgekeurd / Aanpassing nodig</option>
    </select>
    <div class="actions">
      <button type="submit">Opslaan</button>
      <a class="pill" href="files.php">Terug</a>
    </div>
  </form>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
