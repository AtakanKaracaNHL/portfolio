<?php
declare(strict_types=1);
require __DIR__ . '/../includes/config.php';
require __DIR__ . '/../includes/helpers.php';
require __DIR__ . '/../includes/auth.php';
require_admin();

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $year_id = post_int('year_id');
  $name = post_str('name');
  $desc = post_str('description');
  if ($year_id >= 1 && $year_id <= 4 && $name !== '') {
    $st = $pdo->prepare('INSERT INTO modules (year_id, name, description) VALUES (?,?,?)');
    $st->execute([$year_id, $name, $desc]);
    $msg = 'Module aangemaakt.';
  }
}

$years = $pdo->query('SELECT id, label FROM years ORDER BY id')->fetchAll();
$modules = $pdo->query('SELECT m.id, m.year_id, y.label AS year_label, m.name, m.description FROM modules m JOIN years y ON y.id=m.year_id ORDER BY m.year_id, m.name')->fetchAll();

include __DIR__ . '/../includes/header.php';
?>
<div class="card">
  <h1>Modules</h1>
  <p>Modules horen altijd bij één jaar (Jaar 1 t/m Jaar 4).</p>
  <?php if ($msg !== ''): ?><p class="badge ok"><?= h($msg) ?></p><?php endif; ?>
  <h2>Nieuwe module</h2>
  <form method="post">
    <label>Jaar</label>
    <select name="year_id" required>
      <?php foreach ($years as $y): ?>
        <option value="<?= (int)$y['id'] ?>"><?= h($y['label']) ?></option>
      <?php endforeach; ?>
    </select>
    <label>Naam</label>
    <input name="name" required />
    <label>Omschrijving</label>
    <textarea name="description"></textarea>
    <div class="actions"><button type="submit">Opslaan</button></div>
  </form>
</div>

<div class="card">
  <h2>Bestaande modules</h2>
  <table class="table">
    <tr><th>Jaar</th><th>Module</th><th>Omschrijving</th></tr>
    <?php foreach ($modules as $m): ?>
      <tr>
        <td><?= h($m['year_label']) ?></td>
        <td><?= h($m['name']) ?></td>
        <td><?= h((string)$m['description']) ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
