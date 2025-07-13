<?php
header('Content-Type: application/json');
require_once '../db.php';

try {
    $stmt = $pdo->query('SELECT t.id, u.username, m.title, t.seat_number, t.location, s.showtime, t.purchase_time FROM tickets t JOIN users u ON t.user_id = u.id JOIN movies m ON t.movie_id = m.id LEFT JOIN showtimes s ON t.showtime_id = s.id ORDER BY t.purchase_time DESC');
    $tickets = $stmt->fetchAll();
    echo json_encode(['success' => true, 'data' => $tickets]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} 