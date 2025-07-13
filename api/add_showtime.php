<?php
header('Content-Type: application/json');
require_once '../db.php';

$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['movie_id'], $data['showtime'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}
$location = isset($data['location']) ? $data['location'] : null;
try {
    $stmt = $pdo->prepare('INSERT INTO showtimes (movie_id, showtime, location) VALUES (?, ?, ?)');
    $stmt->execute([
        $data['movie_id'],
        $data['showtime'],
        $location
    ]);
    echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} 