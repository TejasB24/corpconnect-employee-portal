<?php
// Simple router for PHP built-in server
// Usage: php -S 0.0.0.0:8080 router.php (from the backend directory)

// Serve existing files directly
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$filePath = __DIR__ . $uri;
if ($uri !== '/' && file_exists($filePath) && !is_dir($filePath)) {
  return false; // Let the server handle it (e.g., CSS, images)
}

// Health/Index for convenience
if ($uri === '/' || $uri === '/health' || $uri === '/status') {
  header('Content-Type: application/json');
  echo json_encode([
    'name' => 'CorpConnect API',
    'status' => 'ok',
    'endpoints' => ['/auth/login','/auth/register','/auth/logout','/me','/employees','/projects','/messages'],
  ]);
  exit;
}

// CORS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
  header('Access-Control-Allow-Headers: Content-Type, Authorization');
  http_response_code(204);
  exit;
}

// Map clean paths to actual PHP scripts
$routes = [
  '/me' => __DIR__ . '/me.php',
  '/projects' => __DIR__ . '/projects.php',
  '/messages' => __DIR__ . '/messages.php',
  '/employees' => __DIR__ . '/employees.php',
  '/auth/login' => __DIR__ . '/auth/login.php',
  '/auth/register' => __DIR__ . '/auth/register.php',
  '/auth/logout' => __DIR__ . '/auth/logout.php',
  '/auth/forgot-password' => __DIR__ . '/auth/forgot-password.php',
  '/auth/forgot' => __DIR__ . '/auth/forgot-password.php',
];

if (isset($routes[$uri])) {
  require $routes[$uri];
  exit;
}

// Fallback 404
header('Content-Type: application/json');
http_response_code(404);
echo json_encode(['success' => false, 'message' => 'Not Found', 'path' => $uri]);
exit;
?>
