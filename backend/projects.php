<?php
require_once __DIR__ . '/db/config.php';

$user = require_auth();
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
  $stmt = db()->query('SELECT id, title, budget, status FROM projects ORDER BY id DESC');
  send_json($stmt->fetchAll());
}

// Only admins can create/update/delete projects
if (($user['role'] ?? '') !== 'admin') {
  send_json(['success' => false, 'message' => 'Forbidden (admin only)'], 403);
}

if ($method === 'POST') {
  $data = json_input();
  $title = trim($data['title'] ?? '');
  if ($title === '') send_json(['success' => false, 'message' => 'Title required'], 400);
  $budget = (float)($data['budget'] ?? 0);
  $status = $data['status'] ?? 'active';
  $stmt = db()->prepare('INSERT INTO projects (title, budget, status) VALUES (:title, :budget, :status)');
  $stmt->execute([':title' => $title, ':budget' => $budget, ':status' => $status]);
  send_json(['success' => true, 'id' => (int)db()->lastInsertId()], 201);
}

if ($method === 'PUT' || $method === 'PATCH') {
  $id = (int)($_GET['id'] ?? 0);
  if ($id <= 0) send_json(['success' => false, 'message' => 'ID required'], 400);
  $data = json_input();
  $fields = [];
  $params = [':id' => $id];
  foreach (['title','status'] as $f) {
    if (array_key_exists($f, $data)) { $fields[] = "$f = :$f"; $params[":$f"] = trim((string)$data[$f]); }
  }
  if (array_key_exists('budget', $data)) { $fields[] = 'budget = :budget'; $params[':budget'] = (float)$data['budget']; }
  if (!$fields) send_json(['success' => false, 'message' => 'No fields to update'], 400);
  $sql = 'UPDATE projects SET ' . implode(', ', $fields) . ' WHERE id = :id';
  $stmt = db()->prepare($sql);
  $stmt->execute($params);
  send_json(['success' => true]);
}

if ($method === 'DELETE') {
  $id = (int)($_GET['id'] ?? 0);
  if ($id <= 0) send_json(['success' => false, 'message' => 'ID required'], 400);
  $stmt = db()->prepare('DELETE FROM projects WHERE id = :id');
  $stmt->execute([':id' => $id]);
  send_json(['success' => true]);
}

send_json(['success' => false, 'message' => 'Method Not Allowed'], 405);
?>

