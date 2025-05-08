<?php
// public/index.php

require __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Core\DB;

use App\Controllers\UsuarioController;

$router = new Router();
DB::init();

$router->add('GET', '/', [new UsuarioController(), 'create']);
$router->add('GET', '/terminos-y-condiciones', [new UsuarioController(), 'term_and_conditions']);
$router->add('POST', '/registrar', [new UsuarioController(), 'store']);
$router->add('GET', '/verificacion', [new UsuarioController(), 'verification']);
$router->add('POST', '/verificacion-2', [new UsuarioController(), 'verification_code']);
$router->add('GET', '/configuracion', [new UsuarioController(), 'get_company_config']);


$requestUri = strtok($_SERVER["REQUEST_URI"], '?');
$requestMethod = $_SERVER["REQUEST_METHOD"];

$router->dispatch($requestUri, $requestMethod);
