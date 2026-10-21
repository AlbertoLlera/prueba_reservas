<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: no-referrer');

use App\Controllers\Api\ReservaController;
use App\Core\Config;
use App\Core\Database;
use App\Core\ErrorHandler;
use App\Core\Request;
use App\Core\Response;
use App\Core\Router;
use App\Repositories\ReservaEventoRepository;
use App\Repositories\ReservaRepository;
use App\Services\ReservaService;
use App\Validators\ReservaValidator;

// 1. Config
Config::load(__DIR__ . '/../.env');

// 2. Error handler (lo primero, para capturar todo)
$errorHandler = new ErrorHandler(Config::getBool('APP_DEBUG', false));
$errorHandler->register();

// 3. Conexión a BD
$db = new Database(
    Config::get('DB_HOST'),
    (int) Config::get('DB_PORT'),
    Config::get('DB_NAME'),
    Config::get('DB_USER'),
    Config::get('DB_PASS', '')
);
$pdo = $db->getConnection();

// 4. Repositorios
$reservaRepo = new ReservaRepository($pdo);
$eventoRepo  = new ReservaEventoRepository($pdo);

// 5. Validator
$validator = new ReservaValidator();

// 6. Service
$reservaService = new ReservaService($reservaRepo, $eventoRepo, $validator);

// 7. Controladores
$reservaController = new ReservaController($reservaService);

// 8. Router
$router = new Router();

// 9. Rutas
// Endpoint de diagnóstico
$router->add('GET', '/api/ping', function (Request $req, array $params): void {
    Response::json([
        'ok'        => true,
        'timestamp' => date('c'),
    ], 200)->send();
});

// Reservas (REST)
$router->add('GET',   '/api/reservas',                 [$reservaController, 'index']);
$router->add('GET',   '/api/reservas/{id}',            [$reservaController, 'show']);
$router->add('POST',  '/api/reservas',                 [$reservaController, 'store']);
$router->add('PATCH', '/api/reservas/{id}/estado',     [$reservaController, 'cambiarEstado']);

// 10. Dispatch
$request = Request::fromGlobals();
$router->dispatch($request);