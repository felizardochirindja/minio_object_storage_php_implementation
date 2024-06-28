<?php

use Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/..//')->load();

spl_autoload_register(function(string $fullClassName): void {
    $fullClassName = str_replace('\\', DIRECTORY_SEPARATOR, $fullClassName);
    require __DIR__ . '/' . $fullClassName . '.php';
});

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$routes[] = require_once __DIR__ . '/../src/Modules/Storage/platform/web/storage.routes.web.php';
$routes[] = require_once __DIR__ . '/../src/Modules/Storage/platform/api/storage.routes.api.php';

$flaternedRoutes = array_reduce($routes, fn($f, $route) => array_merge($f, $route), []);

foreach ($flaternedRoutes as $route) {
    if ($route['uri'] === $uri && $route['method'] === $method) {
        require_once __DIR__ . '/../src/modules/' . $route['controller'] . '.controller.' . $route['type'] . '.php';
    }
}
