<?php

define('RESET_DATABASE', true);
require_once __DIR__ . '/handlers/user.php';
$port = getenv('PORT') ?: 6004;
shell_exec('php -S 0.0.0.0:' . $port . ' handlers/user.php');