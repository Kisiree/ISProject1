<?php
require __DIR__ . '/config.php';
require __DIR__ . '/auth.php';
$msg = isset($_GET['registered']) ? 'Account created. Please log in.' : '';
if (isset($_GET["password_changed"])) { $msg = "Password changed. Please log in again."; }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  check_csrf();
  $username = trim(form_value('username'));
  $password = form_value('password');
  $user = false;
  if (preg_match('/\A[A-Za-z0-9_]{3,30}\z/', $username) && $password !== '') {
    $stmt = $pdo->prepare("SELECT username, password_hash FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
  }
  if ($user && password_verify($password, $user['password_hash'])) {
    session_regenerate_id(true);
    unset($_SESSION['csrf_token']);
    $_SESSION['user'] = $user['username'];
    header('Location: dashboard.php'); exit;
  }
  $msg = 'Invalid username or password';
}
?>
<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Login</title></head>
<body>
<h2>Login</h2>
<p><?= escape_html($msg) ?></p>
<form method="post">
  <input type="hidden" name="csrf_token" value="<?= escape_html(csrf_token()) ?>">
  <input name="username" placeholder="Username" aria-label="Username" autocomplete="username" required maxlength="30" pattern="[A-Za-z0-9_]{3,30}"><br>
  <input type="password" name="password" aria-label="Password" autocomplete="current-password" placeholder="Password" required><br>
  <button>Log in</button>
</form>
<a href="signup.php">Create an account</a>
</body></html>
