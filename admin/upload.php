<?php
declare(strict_types=1);
require __DIR__ . '/../includes/config.php';
require __DIR__ . '/../includes/helpers.php';
require __DIR__ . '/../includes/auth.php';
require_admin();

$modules = $pdo->query('SELECT id, year_id, name FROM modules ORDER BY year_id, name')->fetchAll();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $module_id = post_int('module_id');
  $title = post_str('title');
  $description = post_str('description');

  if ($module_id <= 0 || $title === '') {
    $error = 'Vul module en titel in.';
  } elseif (!isset($_FILES['upload'])) {
    $error = 'Geen upload ontvangen.';
  } else {
    $uploadError = (int)($_FILES['upload']['error'] ?? UPLOAD_ERR_NO_FILE);

    if ($uploadError !== UPLOAD_ERR_OK) {
      if ($uploadError === UPLOAD_ERR_NO_FILE) {
        $error = 'Selecteer een bestand.';
      } elseif ($uploadError === UPLOAD_ERR_INI_SIZE || $uploadError === UPLOAD_ERR_FORM_SIZE) {
        $error = 'Bestand is te groot om te uploaden.';
      } elseif ($uploadError === UPLOAD_ERR_PARTIAL) {
        $error = 'Bestand is maar gedeeltelijk geüpload.';
      } elseif ($uploadError === UPLOAD_ERR_NO_TMP_DIR) {
        $error = 'Tijdelijke uploadmap ontbreekt.';
      } elseif ($uploadError === UPLOAD_ERR_CANT_WRITE) {
        $error = 'Bestand kon niet naar de server worden geschreven.';
      } elseif ($uploadError === UPLOAD_ERR_EXTENSION) {
        $error = 'Upload is gestopt door een PHP-extensie.';
      } else {
        $error = 'Onbekende uploadfout.';
      }
    } else {
      $st = $pdo->prepare('INSERT INTO files (module_id, title, description, status) VALUES (?,?,?,"submitted")');
      $st->execute([$module_id, $title, $description]);
      $file_id = (int)$pdo->lastInsertId();

      $orig = (string)$_FILES['upload']['name'];
      $tmp = (string)$_FILES['upload']['tmp_name'];
      $mime = (string)($_FILES['upload']['type'] ?? 'application/octet-stream');

      $ext = strtolower((string)pathinfo($orig, PATHINFO_EXTENSION));
      $stored = bin2hex(random_bytes(16)) . ($ext !== '' ? ('.' . $ext) : '');

      $dir = __DIR__ . '/../uploads/' . $file_id;
      if (!is_dir($dir) && !mkdir($dir, 0775, true)) {
        $error = 'Uploadmap kon niet worden aangemaakt.';
      } else {
        $dest = $dir . '/v1_' . $stored;

        if (!move_uploaded_file($tmp, $dest)) {
          $error = 'Upload mislukt.';
        } else {
          $st = $pdo->prepare('INSERT INTO file_versions (file_id, version_number, stored_name, original_name, mime, uploaded_by) VALUES (?,?,?,?,?,?)');
          $st->execute([$file_id, 1, 'v1_' . $stored, $orig, $mime, (int)current_user()['id']]);
          redirect_to('admin/files.php');
        }
      }
    }
  }
}

include __DIR__ . '/../includes/header.php';
?>
<div class="card">
  <h1>Nieuw bestand uploaden</h1>
  <?php if ($error !== ''): ?>
    <p class="badge no"><?= h($error) ?></p>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <label>Module</label>
    <select name="module_id" required>
      <option value="">-- kies --</option>
      <?php foreach ($modules as $m): ?>
        <option value="<?= (int)$m['id'] ?>">J<?= (int)$m['year_id'] ?> — <?= h($m['name']) ?></option>
      <?php endforeach; ?>
    </select>

    <label>Titel</label>
    <input name="title" required />

    <label>Omschrijving</label>
    <textarea name="description"></textarea>

    <label>Bestand</label>
    <input type="file" name="upload" required />

    <div class="actions">
      <button type="submit">Upload</button>
      <a class="pill" href="files.php">Terug</a>
    </div>
  </form>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>