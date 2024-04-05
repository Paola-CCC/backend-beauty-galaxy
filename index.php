<?php

header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Origin: http://localhost:4200');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, HEAD');
header('Content-Type: application/json');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/src/Utils/config.php';

// Pistache
use App\Routing\Router;

$router = new Router();
$router->getAllRoutes();
echo $router->run();
