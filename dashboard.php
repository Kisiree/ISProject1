<?php
require 'config.php';
if (!isset($_SESSION['user'])) { header('Location: login.php'); exit; }
?>
<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>Dashboard</title></head>
<body>
<h2>Welcome, <?= htmlspecialchars($_SESSION['user']) ?>!</h2>
<a href="logout.php">Log out</a>
</body></html>
