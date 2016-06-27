<?php

use Symfony\Component\ClassLoader\ApcClassLoader;
use Symfony\Component\HttpFoundation\Request;

/**
 * @var Composer\Autoload\ClassLoader
 */
$loader = require __DIR__.'/../app/autoload.php';
include_once __DIR__.'/../var/bootstrap.php.cache';

if (extension_loaded('apc')) {
    $apcKey = sprintf('%s-%s', $_SERVER['HTTP_HOST'], $_SERVER['SERVER_PORT']);

    $apcLoader = new ApcClassLoader($apcKey, $loader);
    $loader->unregister();
    $apcLoader->register(true);
}

$kernel = new AppKernel(AppKernel::ENV_PRODUCTION, false);
$kernel->loadClassCache();

$kernel = new AppCache($kernel);

// When using the HttpCache, you need to call the method in your front controller instead of relying on the configuration parameter
Request::enableHttpMethodParameterOverride();

$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
