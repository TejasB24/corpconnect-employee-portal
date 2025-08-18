<?php
require_once __DIR__ . '/../db/config.php';

$token = get_bearer_token();
if ($token) {
  $stmt = db()->prepare('DELETE FROM auth_tokens WHERE token = :token');
  $stmt->execute([':token' => $token]);
}

send_json(['success' => true, 'message' => 'Logged out']);
?>

