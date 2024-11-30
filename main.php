<?php

define('RESET_DATABASE', true);
require_once __DIR__ . '/handlers/user.php';

shell_exec('php -S localhost:6004 handlers/user.php');