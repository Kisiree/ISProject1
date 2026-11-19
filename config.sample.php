<?php
$pdo = new PDO(
  "mysql:host=localhost;dbname=CAT2;charset=utf8mb4",
  "appuser", "YOUR_DB_PASSWORD",
  [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);
session_start();
