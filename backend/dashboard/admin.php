<?php
require_once __DIR__ . '/../db/config.php';

$user = require_auth();
require_admin($user);

$method = $_SERVER['REQUEST_METHOD'];

// Employee CRUD under /dashboard/admin.php?resource=employees or path-based routing via query
$resource = $_GET['resource'] ?? '';

if ($resource === 'employees') {
  if ($method === 'GET') {
    $pdo = db();
    $search = trim($_GET['q'] ?? '');
    if ($search !== '') {
      $stmt = $pdo->prepare('SELECT * FROM employees WHERE name LIKE :q OR department LIKE :q ORDER BY id DESC');
      $stmt->execute([':q' => "%{$search}%"]);
    } else {
      $stmt = $pdo->query('SELECT * FROM employees ORDER BY id DESC');
    }
    $rows = $stmt->fetchAll();
    send_json($rows);
  }

  if ($method === 'POST') {
    $data = json_input();
    $name = trim($data['name'] ?? '');
    if ($name === '') send_json(['success' => false, 'message' => 'Name is required'], 400);
    $pdo = db();
    $stmt = $pdo->prepare('INSERT INTO employees (name, designation, department, salary, email) VALUES (:name, :designation, :department, :salary, :email)');
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
}

// Fallback minimal admin info
send_json(['success' => true, 'message' => 'Admin endpoint', 'user' => $user]);
?>