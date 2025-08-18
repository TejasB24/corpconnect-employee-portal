<?php
require_once __DIR__ . '/db/config.php';

$authUser = require_auth();
$method = $_SERVER['REQUEST_METHOD'];

// Admin-only for list/create/update/delete; non-admin can GET own profile by email match if provided
$isAdmin = ($authUser['role'] ?? '') === 'admin';

if ($method === 'GET') {
  $pdo = db();
  if ($isAdmin) {
    $search = trim($_GET['q'] ?? '');
    if ($search !== '') {
      $stmt = $pdo->prepare('SELECT * FROM employees WHERE name LIKE :q OR department LIKE :q ORDER BY id DESC');
      $stmt->execute([':q' => "%{$search}%"]);
    } else {
      $stmt = $pdo->query('SELECT * FROM employees ORDER BY id DESC');
    }
    send_json($stmt->fetchAll());
  } else {
    // For employees, allow viewing an employee record linked by email if exists
    $stmt = $pdo->prepare('SELECT * FROM employees WHERE email = :email LIMIT 1');
    $stmt->execute([':email' => $authUser['email']]);
    $row = $stmt->fetch();
    if (!$row) { send_json([]); }
    send_json([$row]);
  }
}

if (!$isAdmin) {
  send_json(['success' => false, 'message' => 'Forbidden (admin only)'], 403);
}

if ($method === 'POST') {
  $data = json_input();
  $name = trim($data['name'] ?? '');
  if ($name === '') send_json(['success' => false, 'message' => 'Name is required'], 400);
  $stmt = db()->prepare('INSERT INTO employees (name, designation, department, salary, email) VALUES (:name, :designation, :department, :salary, :email)');
  $stmt->execute([
    ':name' => $name,
    ':designation' => trim($data['designation'] ?? ''),
    ':department' => trim($data['department'] ?? ''),
    ':salary' => (float)($data['salary'] ?? 0),
    ':email' => strtolower(trim($data['email'] ?? '')),
  ]);
  $id = (int)db()->lastInsertId();
  send_json(['success' => true, 'id' => $id], 201);
}

if ($method === 'PUT' || $method === 'PATCH') {
  $id = (int)($_GET['id'] ?? 0);
  if ($id <= 0) send_json(['success' => false, 'message' => 'ID required'], 400);
  $data = json_input();
  $fields = [];
  $params = [':id' => $id];
  foreach (['name','designation','department','email'] as $f) {
    if (array_key_exists($f, $data)) { $fields[] = "$f = :$f"; $params[":$f"] = trim((string)$data[$f]); }
  }
  if (array_key_exists('salary', $data)) { $fields[] = 'salary = :salary'; $params[':salary'] = (float)$data['salary']; }
  if (!$fields) send_json(['success' => false, 'message' => 'No fields to update'], 400);
  $sql = 'UPDATE employees SET ' . implode(', ', $fields) . ' WHERE id = :id';
  $stmt = db()->prepare($sql);
  $stmt->execute($params);
  send_json(['success' => true]);
}

if ($method === 'DELETE') {
  $id = (int)($_GET['id'] ?? 0);
  if ($id <= 0) send_json(['success' => false, 'message' => 'ID required'], 400);
  $stmt = db()->prepare('DELETE FROM employees WHERE id = :id');
  $stmt->execute([':id' => $id]);
  send_json(['success' => true]);
}

send_json(['success' => false, 'message' => 'Method Not Allowed'], 405);
?>

