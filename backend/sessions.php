<?php
require_once __DIR__ . '/db/config.php';

$user = require_auth();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
  $stmt = db()->prepare('SELECT id, token, DATE_FORMAT(expires_at, "%Y-%m-%d %H:%i") AS expires_at, user_agent, ip_address, DATE_FORMAT(last_seen, "%Y-%m-%d %H:%i") AS last_seen, DATE_FORMAT(created_at, "%Y-%m-%d %H:%i") AS created_at FROM auth_tokens WHERE user_id = :uid ORDER BY id DESC');
  $stmt->execute([':uid' => $user['id']]);
  $rows = $stmt->fetchAll();
  // Do not leak tokens fully; mask them
  foreach ($rows as &$r) {
    if (!empty($r['token'])) {
      $r['token'] = substr($r['token'], 0, 6) . '…' . substr($r['token'], -4);
    }
  }
  send_json($rows);
}

if ($method === 'DELETE') {
  $id = (int)($_GET['id'] ?? 0);
  if ($id <= 0) send_json(['success' => false, 'message' => 'ID required'], 400);
  $stmt = db()->prepare('DELETE FROM auth_tokens WHERE id = :id AND user_id = :uid');
  $stmt->execute([':id' => $id, ':uid' => $user['id']]);
  send_json(['success' => true]);
}

send_json(['success' => false, 'message' => 'Method Not Allowed'], 405);
?>

