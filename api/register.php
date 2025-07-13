<?php

require_once __DIR__ . '/cors.php';
require_once __DIR__ . '/../db.php';

// Get the posted data from the request body
$data = json_decode(file_get_contents("php://input"));

// Basic validation for presence of fields
if (!isset($data->username) || !isset($data->email) || !isset($data->password)) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields: username, email, and password are required.']);
    exit;
}

$username = trim($data->username);
$email = trim($data->email);
$password = $data->password; // No trim on password, user might want spaces

// Further validation
if (empty($username) || empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(['error' => 'All fields are required and cannot be empty.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid email format.']);
    exit;
}

// Hash the password for security
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

try {
    // Prepare and execute the SQL statement to insert the new user
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$username, $email, $hashed_password]);

    http_response_code(201); // Created
    echo json_encode(['message' => 'User registered successfully.']);

} catch (PDOException $e) {
    // Check for duplicate entry (SQLSTATE 23000 is for integrity constraint violation)
    if ($e->getCode() == 23000) {
        http_response_code(409); // Conflict
        echo json_encode(['error' => 'Username or email already exists.']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Registration failed: ' . $e->getMessage()]);
    }
}