<?php
require_once 'cors.php';
require_once '../db.php';

$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['user_id'], $data['movie_id'], $data['seat_number'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

try {
    $stmt = $pdo->prepare('INSERT INTO tickets (user_id, movie_id, seat_number) VALUES (?, ?, ?)');
    $stmt->execute([
        $data['user_id'],
        $data['movie_id'],
        $data['seat_number']
    ]);
    echo json_encode(['success' => true, 'ticket_id' => $pdo->lastInsertId()]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} 