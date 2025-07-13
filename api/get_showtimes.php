<?php
header('Content-Type: application/json');
require_once '../db.php';

$movie_id = isset($_GET['movie_id']) ? intval($_GET['movie_id']) : 0;
if (!$movie_id) {
    echo json_encode(['success' => false, 'error' => 'Missing or invalid movie_id']);
    exit;
}
try {
    $stmt = $pdo->prepare('SELECT id, showtime, location FROM showtimes WHERE movie_id = ? ORDER BY showtime ASC');
    $stmt->execute([$movie_id]);
    $showtimes = $stmt->fetchAll();
    echo json_encode(['success' => true, 'data' => $showtimes]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} 