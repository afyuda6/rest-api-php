<?php

$db = new PDO('sqlite:' . __DIR__ . '/../rest_api_php.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (defined('RESET_DATABASE') && RESET_DATABASE) {
    $db->exec("DROP TABLE IF EXISTS users");
}

$tableCheck = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='users'");
if ($tableCheck->fetch() === false) {
    $db->exec("CREATE TABLE users (
        id INTEGER PRIMARY KEY,
        name TEXT NOT NULL
    )");
}