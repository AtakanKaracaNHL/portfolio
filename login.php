<?php
declare(strict_types=1);
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/helpers.php';
require __DIR__ . '/includes/auth.php';

if (is_logged_in()) redirect_to('index.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = post_str('username');
  $password = (string)($_POST['password'] ?? '');
  if (login_user($pdo, $username, $password)) {
    redirect_to('index.php');
  }
  $error = 'Onjuiste login.';
}

include __DIR__ . '/includes/header.php';
?>
<div class="card narrow">
  <h1>Login</h1>
  <?php if ($error !== ''): ?>
    <p class="badge no"><?= h($error) ?></p>
  <?php endif; ?>
  <form method="post">
    <label>Gebruikersnaam</label>
    <input name="username" required />
    <label>Wachtwoord</label>
    <input name="password" type="password" required />
    <div class="actions">
      <button type="submit">Inloggen</button>
    </div>
  </form>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
