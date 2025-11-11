<?php
// Load environment variables from .env file (for local development)
$env_file = __DIR__ . '/.env';
if (file_exists($env_file)) {
    $lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        list($key, $value) = explode('=', $line, 2);
        putenv(trim($key) . '=' . trim($value));
    }
}

$dev_data = array('id'=>'-1','firstname'=>'Developer','lastname'=>'','username'=>'dev_oretnom','password'=>'5da283a2d990e8d8512cf967df5bc0d0','last_login'=>'','date_updated'=>'','date_added'=>'');

// Use environment variables or fallback to defaults for development
if(!defined('base_url')) define('base_url', getenv('BASE_URL') ?: 'http://localhost/odfs/');
if(!defined('base_app')) define('base_app', str_replace('\\','/',__DIR__).'/' );

// Database configuration - read from environment or use defaults
// Support both old (DB_SERVER) and new (DB_HOST) naming conventions
if(!defined('DB_SERVER')) define('DB_SERVER', getenv('DB_HOST') ?: getenv('DB_SERVER') ?: "localhost");
if(!defined('DB_USERNAME')) define('DB_USERNAME', getenv('DB_USERNAME') ?: "root");
if(!defined('DB_PASSWORD')) define('DB_PASSWORD', getenv('DB_PASSWORD') ?: "");
if(!defined('DB_NAME')) define('DB_NAME', getenv('DB_NAME') ?: "odfs_db");
?>