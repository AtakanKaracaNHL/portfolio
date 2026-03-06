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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = post_str('action');
  if ($action === 'add') {
    $txt = post_str('comment_text');
    if ($txt !== '') {
      $st = $pdo->prepare('INSERT INTO comments (file_id, comment_text, status) VALUES (?,?,"open")');
      $st->execute([$file_id, $txt]);
    }
  } elseif ($action === 'toggle') {
    $cid = post_int('comment_id');
    $st = $pdo->prepare('UPDATE comments SET status = IF(status="open","done","open") WHERE id = ? AND file_id = ?');
    $st->execute([$cid, $file_id]);
  }
  redirect_to('admin/comments.php?file_id=' . $file_id);
}

$st = $pdo->prepare('SELECT id, comment_text, status, created_at FROM comments WHERE file_id = ? ORDER BY created_at DESC');
$st->execute([$file_id]);
$comments = $st->fetchAll();

include __DIR__ . '/../includes/header.php';
?>
<div class="card">
  <h1>Opmerkingen (alleen beheerder)</h1>
  <p><strong><?= h((string)$file['title']) ?></strong></p>
</div>

<div class="card">
  <h2>Nieuwe opmerking</h2>
  <form method="post">
    <input type="hidden" name="action" value="add" />
    <label>Tekst</label>
    <textarea name="comment_text" required></textarea>
    <div class="actions">
      <button type="submit">Opslaan</button>
      <a class="pill" href="files.php">Terug</a>
    </div>
  </form>
</div>

<div class="card">
  <h2>Overzicht</h2>
  <table class="table">
    <tr><th>Status</th><th>Opmerking</th><th>Datum</th><th>Actie</th></tr>
    <?php foreach ($comments as $c): ?>
      <tr>
        <td><?= ($c['status']==='done') ? '<span class="badge ok">Afgerond</span>' : '<span class="badge">Open</span>' ?></td>
        <td><?= h((string)$c['comment_text']) ?></td>
        <td><?= h((string)$c['created_at']) ?></td>
        <td>
          <form method="post">
            <input type="hidden" name="action" value="toggle" />
            <input type="hidden" name="comment_id" value="<?= (int)$c['id'] ?>" />
            <button type="submit">Toggle</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
