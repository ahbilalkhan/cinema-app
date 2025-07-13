<?php
require_once 'cors.php';
require_once '../db.php';

$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['id'], $data['title'], $data['description'], $data['release_date'], $data['duration'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

try {
    $stmt = $pdo->prepare('UPDATE movies SET title = ?, description = ?, release_date = ?, duration = ? WHERE id = ?');
    $stmt->execute([
        $data['title'],
        $data['description'],
        $data['release_date'],
        $data['duration'],
        $data['id']
    ]);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} 