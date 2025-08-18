<?php
require_once __DIR__ . '/db/config.php';

$user = require_auth();

$messages = [
  ['id' => 1, 'from' => 'HR', 'time' => 'Today 10:00', 'preview' => 'Company town hall at 4 PM.'],
  ['id' => 2, 'from' => 'IT', 'time' => 'Yesterday 15:22', 'preview' => 'Scheduled maintenance this weekend.'],
];

send_json($messages);
?>

