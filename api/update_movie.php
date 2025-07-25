<?php
header('Content-Type: application/json');
require_once '../db.php';

$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['id'], $data['title'], $data['description'], $data['release_date'], $data['duration'], $data['trailer_url'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

try {
    $stmt = $pdo->prepare('UPDATE movies SET title = ?, description = ?, release_date = ?, duration = ?, trailer_url = ? WHERE id = ?');
    $stmt->execute([
        $data['title'],
        $data['description'],
        $data['release_date'],
        $data['duration'],
        $data['trailer_url'],
        $data['id']
    ]);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} 