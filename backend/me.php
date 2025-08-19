<?php
require_once __DIR__ . '/db/config.php';

$user = require_auth();
send_json([
  'id' => (int)$user['id'],
  'name' => $user['name'],
  'email' => $user['email'],
  'role' => $user['role'],
]);
?>
