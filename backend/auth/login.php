<?php
require_once __DIR__ . '/../db/config.php';

$data = json_input();
$email = strtolower(trim($data['email'] ?? ''));
$password = (string)($data['password'] ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
  send_json(['success' => false, 'message' => 'Invalid credentials'], 400);
}

try {
  $pdo = db();
  $stmt = $pdo->prepare('SELECT id, name, email, password, role FROM users WHERE email = :email');
  $stmt->execute([':email' => $email]);
  $user = $stmt->fetch();
  if (!$user || !verify_password($password, $user['password'])) {
    send_json(['success' => false, 'message' => 'Invalid email or password'], 401);
  }

  $token = random_token(32);
  $expiresAt = (new DateTime('+1 day'))->format('Y-m-d H:i:s');
  $ua = isset($_SERVER['HTTP_USER_AGENT']) ? substr((string)$_SERVER['HTTP_USER_AGENT'], 0, 255) : null;
  $ip = $_SERVER['REMOTE_ADDR'] ?? null;
  $insert = $pdo->prepare('INSERT INTO auth_tokens (user_id, token, expires_at, user_agent, ip_address, last_seen) VALUES (:user_id, :token, :expires_at, :user_agent, :ip_address, NOW())');
  $insert->execute([
    ':user_id' => $user['id'],
    ':token' => $token,
    ':expires_at' => $expiresAt,
    ':user_agent' => $ua,
    ':ip_address' => $ip,
  ]);

  send_json([
    'success' => true,
    'token' => $token,
    'user' => [
      'id' => (int)$user['id'],
      'name' => $user['name'],
      'email' => $user['email'],
      'role' => $user['role'],
    ],
  ]);
} catch (Throwable $e) {
  send_json(['success' => false, 'message' => 'Login failed', 'error' => $e->getMessage()], 500);
}
?>