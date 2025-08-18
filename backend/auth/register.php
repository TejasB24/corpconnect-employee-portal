<?php
require_once __DIR__ . '/../db/config.php';

$data = json_input();
$name = trim($data['name'] ?? '');
$email = strtolower(trim($data['email'] ?? ''));
$password = (string)($data['password'] ?? '');
$role = $data['role'] ?? 'employee';
if ($role !== 'admin') { $role = 'employee'; }

if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
  send_json(['success' => false, 'message' => 'Invalid input'], 400);
}

try {
  $pdo = db();
  $stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email');
  $stmt->execute([':email' => $email]);
  if ($stmt->fetch()) {
    send_json(['success' => false, 'message' => 'Email already registered'], 409);
  }

  $hash = hash_password($password);
  $insert = $pdo->prepare('INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)');
  $insert->execute([
    ':name' => $name,
    ':email' => $email,
    ':password' => $hash,
    ':role' => $role,
  ]);

  send_json(['success' => true, 'message' => 'Registered successfully']);
} catch (Throwable $e) {
  send_json(['success' => false, 'message' => 'Registration failed', 'error' => $e->getMessage()], 500);
}
?>

