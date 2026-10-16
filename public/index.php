<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

$loader = new FilesystemLoader(__DIR__ . '/../templates');
$twig = new Environment($loader, [
    'cache' => __DIR__ . '/../cache/twig',
    'auto_reload' => true,
]);

echo $twig->render('hola.twig', [
    'mensaje' => 'Hola mundo desde Twig',
    'php_version' => PHP_VERSION,
]);

