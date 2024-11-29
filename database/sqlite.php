<?php

$databaseFile = __DIR__ . '/../rest_api_php.db';

try {
    $db = new PDO("sqlite:$databaseFile");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //$db->exec("DROP TABLE IF EXISTS users");
    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY,
        name TEXT NOT NULL
    )");

} catch (PDOException $e) {
    echo json_encode(["status" => "Internal Server Error", "code" => 500]);
    exit;
}