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
use App\Controllers\Web\ReservaWebController;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;


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

$reservaRepo = new ReservaRepository($pdo);
$eventoRepo  = new ReservaEventoRepository($pdo);

$validator = new ReservaValidator();

$reservaService = new ReservaService($reservaRepo, $eventoRepo, $validator);

$reservaController = new ReservaController($reservaService);


$loader = new FilesystemLoader(__DIR__ . '/../templates');
$twig = new Environment($loader, [
    'cache' => __DIR__ . '/../cache/twig',
    'auto_reload' => true,
]);

$reservaWebController = new ReservaWebController($reservaService, $eventoRepo, $twig);

$router = new Router();


$router->add('GET', '/api/ping', function (Request $req, array $params): void {
    Response::json([
        'ok'        => true,
        'timestamp' => date('c'),
    ], 200)->send();
});


$router->add('GET',   '/api/reservas',                 [$reservaController, 'index']);
$router->add('GET',   '/api/reservas/{id}',            [$reservaController, 'show']);
$router->add('POST',  '/api/reservas',                 [$reservaController, 'store']);
$router->add('PATCH', '/api/reservas/{id}/estado',     [$reservaController, 'cambiarEstado']);


$request = Request::fromGlobals();

$router->add('GET', '/reservas',              [$reservaWebController, 'index']);
$router->add('GET', '/reservas/crear',        [$reservaWebController, 'crear']); // Ojo: antes que {id}
$router->add('GET', '/reservas/{id}',         [$reservaWebController, 'show']);

$router->dispatch($request);