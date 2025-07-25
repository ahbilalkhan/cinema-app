<?php
header('Content-Type: application/json');
require_once '../db.php';

$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['user_id'], $data['movie_id'], $data['seat_number'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}
$showtime_id = isset($data['showtime_id']) ? $data['showtime_id'] : null;
$location = isset($data['location']) ? $data['location'] : null;
try {
    $stmt = $pdo->prepare('INSERT INTO tickets (user_id, movie_id, showtime_id, seat_number, location) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([
        $data['user_id'],
        $data['movie_id'],
        $showtime_id,
        $data['seat_number'],
        $location
    ]);
    echo json_encode(['success' => true, 'ticket_id' => $pdo->lastInsertId()]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} 