<?php
// healthz.php - quick DB connectivity check
// This file intentionally does NOT echo secrets. It only reports basic connectivity information.
header('Content-Type: text/plain');

$host = getenv('DB_HOST') ?: getenv('DB_SERVER') ?: '127.0.0.1';
$port = getenv('DB_PORT') ?: getenv('MYSQLPORT') ?: getenv('MYSQL_PORT') ?: 3306;
$user = getenv('DB_USERNAME') ?: getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASSWORD') ?: '';
$db   = getenv('DB_NAME') ?: 'odfs_db';

$mysqli = @new mysqli($host, $user, $pass, $db, (int)$port);
if ($mysqli->connect_errno) {
    http_response_code(500);
    echo "DB_CONNECT_ERROR: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error . "\n";
    echo "Host: {$host}:{$port}\n";
    echo "DB: {$db}\n";
    exit;
}

echo "OK - DB connection successful\n";
echo "Host: {$host}:{$port}\n";
echo "DB: {$db}\n";
$mysqli->close();

?>
