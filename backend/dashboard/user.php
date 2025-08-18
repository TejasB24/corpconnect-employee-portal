<?php
require_once __DIR__ . '/../db/config.php';

$user = require_auth();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  send_json([
    'id' => (int)$user['id'],
    'name' => $user['name'],
    'email' => $user['email'],
    'role' => $user['role'],
  ]);
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT' || $_SERVER['REQUEST_METHOD'] === 'PATCH') {
  $data = json_input();
  $name = isset($data['name']) ? trim($data['name']) : $user['name'];
  $email = isset($data['email']) ? strtolower(trim($data['email'])) : $user['email'];
  if ($email !== $user['email'] && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    send_json(['success' => false, 'message' => 'Invalid email'], 400);
  }
  $pdo = db();
  $stmt = $pdo->prepare('UPDATE users SET name = :name, email = :email WHERE id = :id');
  $stmt->execute([':name' => $name, ':email' => $email, ':id' => $user['id']]);
  send_json(['success' => true, 'message' => 'Profile updated']);
}

send_json(['success' => false, 'message' => 'Method Not Allowed'], 405);
?>

