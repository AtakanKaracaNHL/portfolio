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

if (!$file) {
  redirect_to('admin/files.php');
}

$visitors = $pdo->query("SELECT id, username FROM users WHERE role='visitor' ORDER BY username")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $apply_all = isset($_POST['apply_all']);

  $pdo->beginTransaction();

  if ($apply_all) {
    $pdo->exec('DELETE FROM file_access');

    $allFiles = $pdo->query('SELECT id FROM files')->fetchAll();

    foreach ($visitors as $v) {
      $visitorId = (int)$v['id'];
      $key = 'v_' . $visitorId;

      if (isset($_POST[$key])) {
        foreach ($allFiles as $f) {
          $st = $pdo->prepare('INSERT INTO file_access (visitor_id, file_id, can_view) VALUES (?,?,1)');
          $st->execute([$visitorId, (int)$f['id']]);
        }
      }
    }
  } else {
    $pdo->prepare('DELETE FROM file_access WHERE file_id=?')->execute([$file_id]);

    foreach ($visitors as $v) {
      $visitorId = (int)$v['id'];
      $key = 'v_' . $visitorId;

      if (isset($_POST[$key])) {
        $st = $pdo->prepare('INSERT INTO file_access (visitor_id, file_id, can_view) VALUES (?,?,1)');
        $st->execute([$visitorId, $file_id]);
      }
    }
  }

  $pdo->commit();
  redirect_to('admin/files.php');
}

$st = $pdo->prepare('SELECT visitor_id FROM file_access WHERE file_id=? AND can_view=1');
$st->execute([$file_id]);
$allowed = array_fill_keys(array_map(fn($r) => (int)$r['visitor_id'], $st->fetchAll()), true);

include __DIR__ . '/../includes/header.php';
?>
<div class="card">
  <h1>Toegang instellen</h1>
  <p><strong><?= h((string)$file['title']) ?></strong></p>

  <form method="post">
    <label>
      <input type="checkbox" id="check_all_visitors" />
      Selecteer alle bezoekers
    </label>

    <label>
      <input type="checkbox" name="apply_all" />
      Pas deze selectie toe op alle bestanden
    </label>

    <table class="table">
      <tr><th>Bezoeker</th><th>Mag bekijken</th></tr>
      <?php foreach ($visitors as $v): $id = (int)$v['id']; ?>
        <tr>
          <td><?= h((string)$v['username']) ?></td>
          <td>
            <input
              type="checkbox"
              class="visitor-checkbox"
              name="v_<?= $id ?>"
              <?= isset($allowed[$id]) ? 'checked' : '' ?>
            />
          </td>
        </tr>
      <?php endforeach; ?>
    </table>

    <div class="actions">
      <button type="submit">Opslaan</button>
      <a class="pill" href="files.php">Terug</a>
    </div>
  </form>
</div>

<script>
document.getElementById('check_all_visitors').addEventListener('change', function () {
  const checkboxes = document.querySelectorAll('.visitor-checkbox');
  checkboxes.forEach(function (checkbox) {
    checkbox.checked = document.getElementById('check_all_visitors').checked;
  });
});
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>