<?php

function form_value(string $field): string
{
    $value = $_POST[$field] ?? '';
    return is_string($value) ? $value : '';
}

function escape_html(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function valid_new_password(string $password): bool
{
    return strlen($password) <= 72
        && preg_match('/\A(?=[^\x00]*[A-Z])(?=[^\x00]*[0-9])[^\x00]{8,}\z/u', $password) === 1;
}

function csrf_token(): string
{
    return $_SESSION['csrf_token'] ??= bin2hex(random_bytes(32));
}

function check_csrf(): void
{
    if (!hash_equals(csrf_token(), form_value('csrf_token'))) {
        http_response_code(403);
        exit('This form has expired. Reload the page and try again.');
    }
}
