<?php
require_once __DIR__ . '/db/config.php';

// Protected endpoint returning placeholder data
$user = require_auth();

$projects = [
  ['id' => 1, 'title' => 'Onboarding Revamp', 'budget' => 120000, 'status' => 'active'],
  ['id' => 2, 'title' => 'Intranet Upgrade', 'budget' => 75000, 'status' => 'planning'],
  ['id' => 3, 'title' => 'Security Audit', 'budget' => 50000, 'status' => 'active'],
];

send_json($projects);
?>

