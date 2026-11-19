<?php
require 'config.php';
$msg = isset($_GET['registered']) ? 'Account created. Please log in.' : '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->execute([trim($_POST['username'] ?? '')]);
  $user = $stmt->fetch();
  if ($user && password_verify($_POST['password'] ?? '', $user['password_hash'])) {
    session_regenerate_id(true);
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
<p><?= htmlspecialchars($msg) ?></p>
<form method="post">
  <input name="username" placeholder="Username" required><br>
  <input type="password" name="password" placeholder="Password" required><br>
  <button>Log in</button>
</form>
<a href="signup.php">Create an account</a>
</body></html>
