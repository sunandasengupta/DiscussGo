<?php
// Load environment variables from .env file (for local development)
$env_file = __DIR__ . '/.env';
if (file_exists($env_file)) {
    $lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        list($key, $value) = explode('=', $line, 2);
        putenv(trim($key) . '=' . trim($value));
    }
}

$dev_data = array('id'=>'-1','firstname'=>'Developer','lastname'=>'','username'=>'dev_oretnom','password'=>'5da283a2d990e8d8512cf967df5bc0d0','last_login'=>'','date_updated'=>'','date_added'=>'');

// BASE URL: prefer environment variable. In production (Render) require it.
if (!defined('base_url')) {
    $env_base = getenv('BASE_URL');
    if ($env_base) {
        define('base_url', rtrim($env_base, '/') . '/');
    } else {
        // When running on Render, the RENDER env var is set. Fail loudly to avoid localhost usage.
        if (getenv('RENDER')) {
            die('BASE_URL environment variable is required in production.');
        }
        define('base_url', 'http://localhost/odfs/');
    }
}

if (!defined('base_app')) define('base_app', str_replace('\\','/',__DIR__).'/' );

// Database configuration - support DATABASE_URL (mysql://user:pass@host:port/db) commonly used by providers
$db_url = getenv('DATABASE_URL') ?: getenv('MYSQL_URL') ?: getenv('CLEARDB_DATABASE_URL') ?: '';
if ($db_url) {
    $parts = parse_url($db_url);
    if ($parts !== false) {
        $db_host = isset($parts['host']) ? $parts['host'] : null;
        $db_port = isset($parts['port']) ? $parts['port'] : null;
        $db_user = isset($parts['user']) ? $parts['user'] : null;
        $db_pass = isset($parts['pass']) ? $parts['pass'] : null;
        $db_name = isset($parts['path']) ? ltrim($parts['path'], '/') : null;
        if ($db_host) putenv('DB_HOST='.$db_host);
        if ($db_port) putenv('DB_PORT='.$db_port);
        if ($db_user) putenv('DB_USERNAME='.$db_user);
        if ($db_pass) putenv('DB_PASSWORD='.$db_pass);
        if ($db_name) putenv('DB_NAME='.$db_name);
    }
}

// Support both old (DB_SERVER) and new (DB_HOST) naming conventions
// In production require DB_HOST (or parsed DATABASE_URL)
if(!defined('DB_SERVER')) {
    $env_dbhost = getenv('DB_HOST') ?: getenv('DB_SERVER');
    if ($env_dbhost) {
        define('DB_SERVER', $env_dbhost);
    } else {
        if (getenv('RENDER')) die('DB_HOST environment variable is required in production.');
        define('DB_SERVER', 'localhost');
    }
}
if(!defined('DB_USERNAME')) define('DB_USERNAME', getenv('DB_USERNAME') ?: getenv('DB_USER') ?: 'root');
if(!defined('DB_PASSWORD')) define('DB_PASSWORD', getenv('DB_PASSWORD') ?: '');
if(!defined('DB_NAME')) define('DB_NAME', getenv('DB_NAME') ?: 'odfs_db');
// Database port: support multiple env var names (DB_PORT, MYSQLPORT, MYSQL_PORT)
if(!defined('DB_PORT')) define('DB_PORT', getenv('DB_PORT') ?: getenv('MYSQLPORT') ?: getenv('MYSQL_PORT') ?: 3306);

?>