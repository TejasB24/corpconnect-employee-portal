<?php
// Basic DB config and common helpers for CorpConnect Employee Portal

// CORS headers for development; adjust origins as needed
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Expose-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(204);
  exit;
}

// Configuration via user-provided db.php (if present), otherwise environment variables with defaults
$dbHost = '127.0.0.1';
$dbPort = 3306;
$dbName = 'corpconnect';
$dbUser = 'root';
$dbPass = '';
if (file_exists(__DIR__ . '/db.php')) {
  require __DIR__ . '/db.php';
}
// Allow env vars to override
$dbHost = getenv('DB_HOST') ?: $dbHost;
$dbPort = getenv('DB_PORT') ? (int)getenv('DB_PORT') : $dbPort;
$dbName = getenv('DB_NAME') ?: $dbName;
$dbUser = getenv('DB_USER') ?: $dbUser;
$dbPass = getenv('DB_PASS') ?: $dbPass;

/** @var PDO|null */
$__dbInstance = null;

function db(): PDO {
  global $__dbInstance, $dbHost, $dbName, $dbUser, $dbPass;
  if ($__dbInstance instanceof PDO) {
    return $__dbInstance;
  }
  $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";
  $options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
  ];
  try {
    $__dbInstance = new PDO($dsn, $dbUser, $dbPass, $options);
    return $__dbInstance;
  } catch (Throwable $e) {
    // Attempt to create the database automatically if it does not exist
    try {
      $dsnNoDb = "mysql:host={$dbHost};port={$dbPort};charset=utf8mb4";
      $tmp = new PDO($dsnNoDb, $dbUser, $dbPass, $options);
      $tmp->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
      $__dbInstance = new PDO($dsn, $dbUser, $dbPass, $options);
      return $__dbInstance;
    } catch (Throwable $e2) {
      http_response_code(500);
      header('Content-Type: application/json');
      echo json_encode(['success' => false, 'message' => 'Database connection failed', 'error' => $e2->getMessage()]);
      exit;
    }
  }
}

function json_input(): array {
  $raw = file_get_contents('php://input');
  if (!$raw) return [];
  $data = json_decode($raw, true);
  return is_array($data) ? $data : [];
}

function send_json($payload, int $status = 200): void {
  http_response_code($status);
  header('Content-Type: application/json');
  echo json_encode($payload);
  exit;
}

function get_bearer_token(): ?string {
  $headers = function_exists('apache_request_headers') ? apache_request_headers() : [];
  $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? ($_SERVER['HTTP_AUTHORIZATION'] ?? '');
  if (!$authHeader) return null;
  if (stripos($authHeader, 'Bearer ') === 0) {
    return trim(substr($authHeader, 7));
  }
  return null;
}

function hash_password(string $password): string {
  return password_hash($password, PASSWORD_BCRYPT);
}

function verify_password(string $password, string $hash): bool {
  return password_verify($password, $hash);
}

function random_token(int $lengthBytes = 32): string {
  return bin2hex(random_bytes($lengthBytes));
}

function get_user_by_token(?string $token): ?array {
  if (!$token) return null;
  $sql = 'SELECT u.id, u.name, u.email, u.role, t.token, t.expires_at
          FROM auth_tokens t
          JOIN users u ON u.id = t.user_id
          WHERE t.token = :token AND t.expires_at > NOW()';
  $stmt = db()->prepare($sql);
  $stmt->execute([':token' => $token]);
  $user = $stmt->fetch();
  return $user ?: null;
}

function require_auth(): array {
  $token = get_bearer_token();
  $user = get_user_by_token($token);
  if (!$user) {
    send_json(['success' => false, 'message' => 'Unauthorized'], 401);
  }
  return $user;
}

function require_admin(array $user): void {
  if (($user['role'] ?? '') !== 'admin') {
    send_json(['success' => false, 'message' => 'Forbidden (admin only)'], 403);
  }
}

function ensure_tables(): void {
  // Create tables if not exist (idempotent). Use simple statements; production should use migrations.
  $pdo = db();
  $pdo->exec('CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM("admin","employee") NOT NULL DEFAULT "employee",
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

  $pdo->exec('CREATE TABLE IF NOT EXISTS employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    designation VARCHAR(120) DEFAULT NULL,
    department VARCHAR(120) DEFAULT NULL,
    salary DECIMAL(12,2) DEFAULT 0,
    email VARCHAR(190) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');

  $pdo->exec('CREATE TABLE IF NOT EXISTS auth_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(128) NOT NULL UNIQUE,
    expires_at DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (user_id),
    CONSTRAINT fk_auth_tokens_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');
}

// Initialize tables on every request for MVP safety (cheap if exists)
ensure_tables();

function ensure_seed_admin(): void {
  try {
    $pdo = db();
    $count = (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
    if ($count === 0) {
      $stmt = $pdo->prepare('INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)');
      $stmt->execute([
        ':name' => 'Admin',
        ':email' => 'admin@corpconnect.local',
        ':password' => hash_password('admin123'),
        ':role' => 'admin',
      ]);
    }
  } catch (Throwable $e) {
    // ignore seeding failure in runtime
  }
}

ensure_seed_admin();

?>

