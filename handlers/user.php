<?php

include __DIR__ . '/../database/sqlite.php';

function handleReadUsers() {
    global $db;
    $stmt = $db->query("SELECT * FROM users");
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'OK',
        'code' => 200,
        'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)
    ]);
}

function handleCreateUser($data) {
    if (!isset($data['name'])) {
        header('Content-Type: application/json');
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
        echo json_encode([
            'status' => 'Created',
            'code' => 201
        ]);
    }
}

function handleUpdateUser($data) {
    if (!isset($data['name']) || !isset($data['id'])) {
        header('Content-Type: application/json');
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
        echo json_encode([
            'status' => 'OK',
            'code' => 200
        ]);
    }
}

function handleDeleteUser($data) {
    if (!isset($data['id'])) {
        header('Content-Type: application/json');
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
        echo json_encode([
            'status' => 'OK',
            'code' => 200
        ]);
    }
}

// Map HTTP Methods
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
        echo json_encode([
            'status' => 'Method not allowed',
            'code' => 405,
            'data' => $data
        ]);
        break;
}