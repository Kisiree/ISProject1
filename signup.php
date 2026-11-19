<?php
require __DIR__ . '/config.php';
require __DIR__ . '/auth.php';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  check_csrf();
  $username = trim(form_value('username'));
  $email    = filter_var(trim(form_value('email')), FILTER_VALIDATE_EMAIL);
  $password = form_value('password');
  if (!preg_match('/\A[A-Za-z0-9_]{3,30}\z/', $username)) $msg = 'Invalid username';
  elseif (!$email || strlen($email) > 100) $msg = 'Invalid email';
  elseif (!valid_new_password($password))
    $msg = 'Use at least 8 characters and at most 72 bytes, including an uppercase letter and a number.';
  else {
    try {
      $stmt = $pdo->prepare("INSERT INTO users (username,email,password_hash) VALUES (?,?,?)");
      $stmt->execute([$username, $email, password_hash($password, PASSWORD_DEFAULT)]);
      header('Location: login.php?registered=1'); exit;
    } catch (PDOException $e) {
      if ((int) ($e->errorInfo[1] ?? 0) === 1062) {
        $msg = 'Username or email already exists';
      } else {
        error_log('Signup database error: ' . $e->getCode());
        $msg = 'Account creation is temporarily unavailable.';
      }
    }
  }
}
?>
<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Sign up</title></head>
<body>
<h2>Sign up</h2>
<p style="color:red"><?= escape_html($msg) ?></p>
<form method="post">
  <input type="hidden" name="csrf_token" value="<?= escape_html(csrf_token()) ?>">
  <input name="username" placeholder="Username" aria-label="Username" autocomplete="username" required maxlength="30" pattern="[A-Za-z0-9_]{3,30}"><br>
  <input type="email" name="email" placeholder="Email" aria-label="Email" autocomplete="email" required maxlength="100"><br>
  <input type="password" name="password" id="new-password" aria-label="Password" autocomplete="new-password" placeholder="Password" required minlength="8" maxlength="72" pattern="(?=.*[A-Z])(?=.*[0-9]).{8,72}" aria-describedby="password-guidance">
  <button type="button" id="show-password" hidden aria-controls="new-password">Show password</button>
  <p id="password-guidance" aria-live="polite">Use at least 8 characters and at most 72 bytes, with an uppercase letter and a number.</p>
  <button>Create account</button>
</form>
<a href="login.php">Already have an account? Log in</a>
<script src="signup.js" defer></script>
</body></html>
