<?php
require 'config.php';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $email    = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
  $password = $_POST['password'] ?? '';
  if (!preg_match('/^[A-Za-z0-9_]{3,30}$/', $username)) $msg = 'Invalid username';
  elseif (!$email) $msg = 'Invalid email';
  elseif (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/\d/', $password))
    $msg = 'Password needs 8+ chars, one uppercase letter and one number';
  else {
    try {
      $stmt = $pdo->prepare("INSERT INTO users (username,email,password_hash) VALUES (?,?,?)");
      $stmt->execute([$username, $email, password_hash($password, PASSWORD_DEFAULT)]);
      header('Location: login.php?registered=1'); exit;
    } catch (PDOException $e) { $msg = 'Username or email already exists'; }
  }
}
?>
<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Sign up</title></head>
<body>
<h2>Sign up</h2>
<p style="color:red"><?= htmlspecialchars($msg) ?></p>
<form method="post">
  <input name="username" placeholder="Username" required pattern="[A-Za-z0-9_]{3,30}"><br>
  <input type="email" name="email" placeholder="Email" required><br>
  <input type="password" name="password" placeholder="Password" required minlength="8"><br>
  <button>Create account</button>
</form>
<a href="login.php">Already have an account? Log in</a>
</body></html>
