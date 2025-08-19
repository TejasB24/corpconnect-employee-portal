<?php
// Simple seeding script for demo data
// Run: php seed.php
require_once __DIR__ . '/db/config.php';

$pdo = db();

// Create or fetch admin
$adminEmail = 'admin@corpconnect.local';
$stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email');
$stmt->execute([':email' => $adminEmail]);
$adminId = $stmt->fetchColumn();
if (!$adminId) {
  $pdo->prepare('INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)')
      ->execute([
        ':name' => 'Admin',
        ':email' => $adminEmail,
        ':password' => hash_password('admin123'),
        ':role' => 'admin',
      ]);
  $adminId = (int)$pdo->lastInsertId();
}

// Employees
$employees = [
  ['Tejas Patel','Engineer','IT',75000,'tejas@corp.com'],
  ['Ava Singh','HR Manager','HR',82000,'ava@corp.com'],
  ['Liam Chen','Finance Analyst','Finance',88000,'liam@corp.com'],
];
foreach ($employees as [$name,$designation,$department,$salary,$email]) {
  $s = $pdo->prepare('SELECT id FROM employees WHERE email = :email');
  $s->execute([':email' => $email]);
  if (!$s->fetchColumn()) {
    $pdo->prepare('INSERT INTO employees (name, designation, department, salary, email) VALUES (:name,:designation,:department,:salary,:email)')
        ->execute([
          ':name' => $name,
          ':designation' => $designation,
          ':department' => $department,
          ':salary' => $salary,
          ':email' => $email,
        ]);
  }
}

// Projects
$projects = [
  ['Onboarding Revamp', 120000, 'active'],
  ['Intranet Upgrade', 75000, 'planning'],
  ['Security Audit', 50000, 'active'],
];
foreach ($projects as [$title,$budget,$status]) {
  $s = $pdo->prepare('SELECT id FROM projects WHERE title = :title');
  $s->execute([':title' => $title]);
  if (!$s->fetchColumn()) {
    $pdo->prepare('INSERT INTO projects (title, budget, status) VALUES (:title,:budget,:status)')
        ->execute([':title' => $title, ':budget' => $budget, ':status' => $status]);
  }
}

// Messages
$messages = [
  ['HR', 'Company town hall at 4 PM.'],
  ['IT', 'Scheduled maintenance this weekend.'],
];
foreach ($messages as [$sender,$preview]) {
  $pdo->prepare('INSERT INTO messages (sender, preview) VALUES (:sender,:preview)')
      ->execute([':sender' => $sender, ':preview' => $preview]);
}

echo "Seed complete\n";
?>
