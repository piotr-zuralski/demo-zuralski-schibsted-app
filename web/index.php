<?php

$appEnv = ((getenv('APP_ENV')) ? getenv('APP_ENV') : ((getenv('APPLICATION_ENV')) ? getenv('APPLICATION_ENV') : 'prod'));
if (!file_exists('./app_' . $appEnv . '.php')) {
    $appEnv = 'prod';
}
if (!defined('APPLICATION_ENV') && !empty($appEnv)) {
    define('APPLICATION_ENV', $appEnv);
}

require_once './app_' . $appEnv . '.php';
