<?php

define('RESET_DATABASE', false);
require_once __DIR__ . '/../database/sqlite.php';
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (preg_match('#^/users/?$#', $requestUri)) {
    function handleReadUsers()
    {
        global $db;
        $stmt = $db->query("SELECT * FROM users");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($users as &$user) {
            $user['id'] = (int)$user['id'];
        }
        header('Content-Type: application/json');
        http_response_code(200);
        echo json_encode([
            'status' => 'OK',
            'code' => 200,
            'data' => $users
        ]);
    }

    function handleCreateUser($data)
    {
        if (!isset($data['name']) || trim($data['name']) === '') {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode([
                'status' => 'Bad Request',
                'code' => 400,
                'errors' => 'Missing \'name\' parameter'
            ]);
        } else {
            global $db;
            $name = $data['name'];
            $stmt = $db->prepare("INSERT INTO users (name) VALUES (:name)");
            $stmt->bindParam(':name', $name);
            $stmt->execute();
            header('Content-Type: application/json');
            http_response_code(201);
            echo json_encode([
                'status' => 'Created',
                'code' => 201
            ]);
        }
    }

    function handleUpdateUser($data)
    {
        if (!isset($data['name']) || trim($data['name']) === '' || !isset($data['id']) || trim($data['id']) === '') {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode([
                'status' => 'Bad Request',
                'code' => 400,
                'errors' => 'Missing \'id\' or \'name\' parameter'
            ]);
        } else {
            global $db;
            $name = $data['name'];
            $id = $data['id'];
            $stmt = $db->prepare("UPDATE users SET name = :name WHERE id = :id");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'status' => 'OK',
                'code' => 200
            ]);
        }
    }

    function handleDeleteUser($data)
    {
        if (!isset($data['id']) || trim($data['id']) === '') {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode([
                'status' => 'Bad Request',
                'code' => 400,
                'errors' => 'Missing \'id\' parameter'
            ]);
        } else {
            global $db;
            $id = $data['id'];
            $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'status' => 'OK',
                'code' => 200
            ]);
        }
    }

    $method = $_SERVER['REQUEST_METHOD'];
    parse_str(file_get_contents('php://input'), $data);
    switch ($method) {
        case 'GET':
            handleReadUsers();
            break;
        case 'POST':
            handleCreateUser($data);
            break;
        case 'PUT':
            handleUpdateUser($data);
            break;
        case 'DELETE':
            handleDeleteUser($data);
            break;
        default:
            header('Content-Type: application/json');
            http_response_code(405);
            echo json_encode([
                'status' => 'Method Not Allowed',
                'code' => 405
            ]);
            break;
    }
} else {
    header("Content-Type: application/json");
    http_response_code(404);
    echo json_encode([
        'status' => 'Not Found',
        'code' => 404
    ]);
}