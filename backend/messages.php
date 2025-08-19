<?php
require_once __DIR__ . '/db/config.php';

$user = require_auth();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
  $stmt = db()->query('SELECT id, sender AS `from`, DATE_FORMAT(created_at, "%Y-%m-%d %H:%i") AS `time`, preview FROM messages ORDER BY id DESC');
  send_json($stmt->fetchAll());
}

// Only admins can create/update/delete messages
if (($user['role'] ?? '') !== 'admin') {
  send_json(['success' => false, 'message' => 'Forbidden (admin only)'], 403);
}

if ($method === 'POST') {
  $data = json_input();
  $sender = trim($data['from'] ?? $data['sender'] ?? 'System');
  $preview = trim($data['preview'] ?? '');
  if ($preview === '') send_json(['success' => false, 'message' => 'Message preview required'], 400);
  $stmt = db()->prepare('INSERT INTO messages (sender, preview) VALUES (:sender, :preview)');
  $stmt->execute([':sender' => $sender, ':preview' => $preview]);
  send_json(['success' => true, 'id' => (int)db()->lastInsertId()], 201);
}

if ($method === 'PUT' || $method === 'PATCH') {
  $id = (int)($_GET['id'] ?? 0);
  if ($id <= 0) send_json(['success' => false, 'message' => 'ID required'], 400);
  $data = json_input();
  $fields = [];
  $params = [':id' => $id];
  if (array_key_exists('from', $data) || array_key_exists('sender', $data)) { $fields[] = 'sender = :sender'; $params[':sender'] = trim((string)($data['from'] ?? $data['sender'])); }
  if (array_key_exists('preview', $data)) { $fields[] = 'preview = :preview'; $params[':preview'] = trim((string)$data['preview']); }
  if (!$fields) send_json(['success' => false, 'message' => 'No fields to update'], 400);
  $sql = 'UPDATE messages SET ' . implode(', ', $fields) . ' WHERE id = :id';
  $stmt = db()->prepare($sql);
  $stmt->execute($params);
  send_json(['success' => true]);
}

if ($method === 'DELETE') {
  $id = (int)($_GET['id'] ?? 0);
  if ($id <= 0) send_json(['success' => false, 'message' => 'ID required'], 400);
  $stmt = db()->prepare('DELETE FROM messages WHERE id = :id');
  $stmt->execute([':id' => $id]);
  send_json(['success' => true]);
}

send_json(['success' => false, 'message' => 'Method Not Allowed'], 405);
?>

