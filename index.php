<?php
declare(strict_types=1);

require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/helpers.php';
require __DIR__ . '/includes/auth.php';

require_login();

$yearId = get_int('year');
$moduleId = get_int('module');
$status = get_str('status');

$years = $pdo->query('SELECT id, label FROM years ORDER BY id')->fetchAll();
$modules = $pdo->query('SELECT id, year_id, name FROM modules ORDER BY year_id, name')->fetchAll();

$params = [];
$where = [];

if ($yearId > 0) { $where[] = 'm.year_id = ?'; $params[] = $yearId; }
if ($moduleId > 0) { $where[] = 'm.id = ?'; $params[] = $moduleId; }
if ($status !== '') { $where[] = 'f.status = ?'; $params[] = $status; }

$sql = "
  SELECT f.id, f.title, f.description, f.status, f.module_id, m.name AS module_name, m.year_id, y.label AS year_label,
         (SELECT MAX(version_number) FROM file_versions WHERE file_id=f.id) AS latest_version,
         (SELECT id FROM file_versions WHERE file_id=f.id ORDER BY version_number DESC LIMIT 1) AS latest_version_id
  FROM files f
  JOIN modules m ON m.id = f.module_id
  JOIN years y ON y.id = m.year_id
";

if (!is_admin()) {
  $sql .= " JOIN file_access a ON a.file_id=f.id AND a.visitor_id=? AND a.can_view=1 ";
  array_unshift($params, (int)current_user()['id']);
}

if ($where) {
  $sql .= ' WHERE ' . implode(' AND ', $where);
}

$sql .= ' ORDER BY m.year_id, m.name, f.created_at DESC';

$st = $pdo->prepare($sql);
$st->execute($params);
$files = $st->fetchAll();

include __DIR__ . '/includes/header.php';
?>

<div class="card">
  <h1>Portfolio overzicht</h1>
  <p>Structuur: Jaar → Categorie (module) → Bestanden. Bezoekers zien alleen bestanden waarvoor jij toegang geeft.</p>
</div>

<div class="card">
  <h2>Filters</h2>
  <form method="get" class="kv">
    <div>Jaar</div>
    <div>
      <select name="year">
        <option value="0">Alle jaren</option>
        <?php foreach ($years as $y): ?>
          <option value="<?= (int)$y['id'] ?>" <?= ((int)$y['id'] === $yearId) ? 'selected' : '' ?>><?= h($y['label']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div>Module</div>
    <div>
      <select name="module">
        <option value="0">Alle modules</option>
        <?php foreach ($modules as $m): ?>
          <option value="<?= (int)$m['id'] ?>" <?= ((int)$m['id'] === $moduleId) ? 'selected' : '' ?>>J<?= (int)$m['year_id'] ?> — <?= h($m['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div>Status</div>
    <div>
      <select name="status">
        <option value="">Alle status</option>
        <option value="ingediend" <?= ($status==='ingediend') ? 'selected' : '' ?>>Ingediend</option>
        <option value="goedgekeurd" <?= ($status==='goedgekeurd') ? 'selected' : '' ?>>Goedgekeurd</option>
        <option value="afgekeurd" <?= ($status==='afgekeurd') ? 'selected' : '' ?>>Afgekeurd / Aanpassing nodig</option>
      </select>
    </div>

    <div></div>
    <div><button type="submit">Toepassen</button></div>
  </form>
</div>

<div class="card">
  <h2>Bestanden</h2>
  <?php if (!$files): ?>
    <p>Geen bestanden gevonden.</p>
  <?php else: ?>
    <table class="table">
      <tr>
        <th>Jaar</th>
        <th>Module</th>
        <th>Titel</th>
        <th>Status</th>
        <th>Versie</th>
        <th>Bekijken</th>
      </tr>
      <?php foreach ($files as $f): ?>
        <tr>
          <td><?= h((string)$f['year_label']) ?></td>
          <td><?= h((string)$f['module_name']) ?></td>
          <td>
            <div><strong><?= h((string)$f['title']) ?></strong></div>
            <div><?= h((string)$f['description']) ?></div>
          </td>
          <td><span class="badge"><?= h((string)$f['status']) ?></span></td>
          <td>v<?= (int)$f['latest_version'] ?></td>
          <td>
            <div class="actions">
              <a class="pill" href="views/preview.php?vid=<?= (int)$f['latest_version_id'] ?>">Preview</a>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
