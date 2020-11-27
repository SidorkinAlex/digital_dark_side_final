<?php
require_once  __DIR__.'/../vendor/autoload.php';

# Load environment variables with Dotenv if .env.test is present.
if (file_exists(__DIR__ . '/../.env.test')) {
    $dotenv = Dotenv\Dotenv::create(__DIR__ . '/../', '.env.test');
    $dotenv->overload();
}

if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}

require_once __DIR__.'/../php_version.php';
