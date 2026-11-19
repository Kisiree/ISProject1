<?php
require __DIR__ . '/auth.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    http_response_code(405);
    exit;
}

session_start();
check_csrf();
$_SESSION = [];
session_destroy();
header('Location: login.php', true, 303);
exit;
