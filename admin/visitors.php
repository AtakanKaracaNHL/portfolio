<?php
declare(strict_types=1);
require __DIR__ . '/../includes/config.php';
require __DIR__ . '/../includes/helpers.php';
require __DIR__ . '/../includes/auth.php';
require_admin();

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = post_str('username');
  $password = (string)($_POST['password'] ?? '');
  if ($username !== '' && $password !== '') {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    try {
      $st = $pdo->prepare('INSERT INTO users (username, password_hash, role) VALUES (?,?,"visitor")');
      $st->execute([$username, $hash]);
      $msg = 'Bezoeker aangemaakt.';
    } catch (Exception $e) {
      $msg = 'Kon bezoeker niet aanmaken (bestaat al?).';
    }
  }
}

$users = $pdo->query('SELECT id, username, role, created_at FROM users ORDER BY id DESC')->fetchAll();

include __DIR__ . '/../includes/header.php';
?>
<div class="card">
  <h1>Bezoekers</h1>
  <?php if ($msg !== ''): ?><p class="badge ok"><?= h($msg) ?></p><?php endif; ?>
  <h2>Nieuwe bezoeker</h2>
  <form method="post" class="narrow">
    <label>Gebruikersnaam</label>
    <input name="username" required />
    <label>Wachtwoord</label>
    <input name="password" type="password" required />
    <div class="actions"><button type="submit">Aanmaken</button></div>
  </form>
</div>

<div class="card">
  <h2>Accounts</h2>
  <table class="table">
    <tr><th>ID</th><th>Username</th><th>Role</th><th>Aangemaakt</th></tr>
    <?php foreach ($users as $u): ?>
      <tr>
        <td><?= (int)$u['id'] ?></td>
        <td><?= h((string)$u['username']) ?></td>
        <td><?= h((string)$u['role']) ?></td>
        <td><?= h((string)$u['created_at']) ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
