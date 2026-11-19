<?php
require __DIR__ . '/config.php';
require __DIR__ . '/auth.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $current = form_value('current_password');
    $new = form_value('new_password');
    if (!valid_new_password($new)) {
        $message = 'Use at least 8 characters and at most 72 bytes, with an uppercase letter and a number.';
    } elseif ($new !== form_value('confirm_password')) {
        $message = 'The new passwords do not match.';
    } else {
        $lookup = $pdo->prepare('SELECT id, password_hash FROM users WHERE username = ?');
        $lookup->execute([$_SESSION['user']]);
        $user = $lookup->fetch();
        if (!$user || !password_verify($current, $user['password_hash'])) {
            $message = 'The current password is incorrect.';
        } elseif (password_verify($new, $user['password_hash'])) {
            $message = 'Choose a different password.';
        } else {
            $update = $pdo->prepare('UPDATE users SET password_hash = ? WHERE id = ? AND BINARY password_hash = ?');
            $update->execute([password_hash($new, PASSWORD_DEFAULT), $user['id'], $user['password_hash']]);
            if ($update->rowCount() === 1) {
                $_SESSION = [];
                session_destroy();
                header('Location: login.php?password_changed=1', true, 303);
                exit;
            }
            $message = 'Your password changed during this request. Sign in again and retry.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="utf-8"><title>Change password</title></head>
<body>
<h2>Change password</h2>
<p role="status"><?= escape_html($message) ?></p>
<form method="post">
    <input type="hidden" name="csrf_token" value="<?= escape_html(csrf_token()) ?>">
    <p><label>Current password <input type="password" name="current_password" autocomplete="current-password" required></label></p>
    <p><label>New password <input type="password" name="new_password" autocomplete="new-password" required minlength="8" maxlength="72" pattern="(?=.*[A-Z])(?=.*[0-9]).{8,72}" aria-describedby="requirements"></label></p>
    <p id="requirements">Use at least 8 characters and at most 72 bytes, with an uppercase letter and a number.</p>
    <p><label>Confirm new password <input type="password" name="confirm_password" autocomplete="new-password" required minlength="8" maxlength="72"></label></p>
    <button>Change password</button>
</form>
<a href="dashboard.php">Back to dashboard</a>
</body>
</html>
