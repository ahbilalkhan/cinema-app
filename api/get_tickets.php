<?php
require_once 'cors.php';
require_once '../db.php';

if (!isset($_GET['user_id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing user_id parameter']);
    exit;
}

try {
    $stmt = $pdo->prepare('SELECT t.*, m.title AS movie_title FROM tickets t JOIN movies m ON t.movie_id = m.id WHERE t.user_id = ? ORDER BY t.purchase_time DESC');
    $stmt->execute([$_GET['user_id']]);
    $tickets = $stmt->fetchAll();
    echo json_encode(['success' => true, 'data' => $tickets]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} 