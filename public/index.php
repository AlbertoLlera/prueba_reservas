<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Config;
use App\Core\ErrorHandler;
use App\Core\Request;
use App\Core\Response;
use App\Core\Router;

// 1. Cargar configuración
Config::load(__DIR__ . '/../.env');

// 2. Registrar manejador de errores ANTES de cualquier otra cosa
$errorHandler = new ErrorHandler(Config::getBool('APP_DEBUG', false));
$errorHandler->register();

// 3. Crear request
$request = Request::fromGlobals();

// 4. Configurar router
$router = new Router();

// 5. Definir rutas
$router->add('GET', '/api/ping', function (Request $req, array $params): void {
    Response::json([
        'ok' => true,
        'timestamp' => date('c'),
        'path' => $req->getPath(),
    ], 200)->send();
});

// 6. Despachar
$router->dispatch($request);