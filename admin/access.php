<?php
declare(strict_types=1);
require __DIR__ . '/../includes/config.php';
require __DIR__ . '/../includes/helpers.php';
require __DIR__ . '/../includes/auth.php';
require_admin();

$file_id = get_int('file_id');
$st = $pdo->prepare('SELECT id, title FROM files WHERE id = ?');
$st->execute([$file_id]);
$file = $st->fetch();
if (!$file) redirect_to('admin/files.php');

$visitors = $pdo->query("SELECT id, username FROM users WHERE role='visitor' ORDER BY username")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $pdo->beginTransaction();
  $pdo->prepare('DELETE FROM file_access WHERE file_id=?')->execute([$file_id]);
  foreach ($visitors as $v) {
    $key = 'v_' . (int)$v['id'];
    if (isset($_POST[$key])) {
      $st = $pdo->prepare('INSERT INTO file_access (visitor_id, file_id, can_view) VALUES (?,?,1)');
      $st->execute([(int)$v['id'], $file_id]);
    }
  }
  $pdo->commit();
  redirect_to('admin/files.php');
}

$st = $pdo->prepare('SELECT visitor_id FROM file_access WHERE file_id=? AND can_view=1');
$st->execute([$file_id]);
$allowed = array_fill_keys(array_map(fn($r)=> (int)$r['visitor_id'], $st->fetchAll()), true);

include __DIR__ . '/../includes/header.php';
?>
<div class="card">
  <h1>Toegang instellen</h1>
  <p><strong><?= h((string)$file['title']) ?></strong></p>
  <form method="post">
    <table class="table">
      <tr><th>Bezoeker</th><th>Mag bekijken</th></tr>
      <?php foreach ($visitors as $v): $id=(int)$v['id']; ?>
        <tr>
          <td><?= h((string)$v['username']) ?></td>
          <td><input type="checkbox" name="v_<?= $id ?>" <?= isset($allowed[$id]) ? 'checked' : '' ?> /></td>
        </tr>
      <?php endforeach; ?>
    </table>
    <div class="actions">
      <button type="submit">Opslaan</button>
      <a class="pill" href="files.php">Terug</a>
    </div>
  </form>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
