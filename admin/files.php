<?php
declare(strict_types=1);
require __DIR__ . '/../includes/config.php';
require __DIR__ . '/../includes/helpers.php';
require __DIR__ . '/../includes/auth.php';
require_admin();

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
  SELECT f.id, f.title, f.description, f.status, f.created_at,
         m.name AS module_name, m.year_id, y.label AS year_label,
         (SELECT MAX(version_number) FROM file_versions WHERE file_id=f.id) AS latest_version,
         (SELECT id FROM file_versions WHERE file_id=f.id ORDER BY version_number DESC LIMIT 1) AS latest_version_id
  FROM files f
  JOIN modules m ON m.id = f.module_id
  JOIN years y ON y.id = m.year_id
";
if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
$sql .= ' ORDER BY m.year_id, m.name, f.created_at DESC';

$st = $pdo->prepare($sql);
$st->execute($params);
$files = $st->fetchAll();

include __DIR__ . '/../includes/header.php';
?>
<div class="card">
  <h1>Bestanden</h1>
  <div class="actions">
    <a class="pill" href="upload.php">Nieuw bestand uploaden</a>
  </div>
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
        <option value="">Alle</option>
        <option value="submitted" <?= ($status==='submitted')?'selected':'' ?>>Ingediend</option>
        <option value="approved" <?= ($status==='approved')?'selected':'' ?>>Goedgekeurd</option>
        <option value="rejected" <?= ($status==='rejected')?'selected':'' ?>>Afgekeurd</option>
      </select>
    </div>

    <div></div>
    <div class="actions">
      <button type="submit">Toepassen</button>
      <a class="pill" href="files.php">Reset</a>
    </div>
  </form>
</div>

<div class="card">
  <h2>Overzicht</h2>
  <table class="table">
    <tr>
      <th>Jaar</th><th>Module</th><th>Titel</th><th>Status</th><th>Versie</th><th>Acties</th>
    </tr>
    <?php foreach ($files as $f): ?>
      <tr>
        <td><?= h($f['year_label']) ?></td>
        <td><?= h($f['module_name']) ?></td>
        <td>
          <strong><?= h($f['title']) ?></strong>
          <div class="muted"><?= h((string)$f['description']) ?></div>
        </td>
        <td>
          <?php if ($f['status']==='approved'): ?><span class="badge ok">Goedgekeurd</span>
          <?php elseif ($f['status']==='rejected'): ?><span class="badge no">Afgekeurd</span>
          <?php else: ?><span class="badge">Ingediend</span>
          <?php endif; ?>
        </td>
        <td><?= (int)($f['latest_version'] ?? 0) ?></td>
        <td>
          <div class="actions">
            <a class="pill" href="access.php?file_id=<?= (int)$f['id'] ?>">Toegang</a>
            <a class="pill" href="version.php?file_id=<?= (int)$f['id'] ?>">Nieuwe versie</a>
            <a class="pill" href="status.php?file_id=<?= (int)$f['id'] ?>">Status</a>
            <?php if (!empty($f['latest_version_id'])): ?>
             <a class="pill" href="../views/preview.php?vid=<?= (int)$f['latest_version_id'] ?>">Preview</a>
              <a class="pill" href="../views/view.php?vid=<?= (int)$f['latest_version_id'] ?>">Open</a>
            <?php endif; ?>
            <a class="pill" href="comments.php?file_id=<?= (int)$f['id'] ?>">Opmerkingen</a>
          </div>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
