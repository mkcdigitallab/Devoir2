<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Config\Database;
use App\Controllers\CopieExamenController;
use App\Repository\PdoCopieExamenRepository;
use App\Router\Router;
use App\Services\CalculNoteAvecRetardService;
use App\Services\SoumissionCopieService;
use Dotenv\Dotenv;

$root = dirname(__DIR__);

$dotenv = Dotenv::createImmutable($root);
$dotenv->safeLoad();

$connection = Database::getConnection();
$repository = new PdoCopieExamenRepository($connection);
$strategy = new CalculNoteAvecRetardService();
$service = new SoumissionCopieService($strategy, $repository);
$controller = new CopieExamenController($service, $repository);

$router = new Router([$controller, 'notFound']);

$router->get('/', static function (): void {
    header('Location: /copies', true, 302);
    exit;
});
$router->get('/copies', [$controller, 'index']);
$router->get('/copies/create', [$controller, 'create']);
$router->post('/copies', [$controller, 'store']);
$router->get('/copies/{id}', [$controller, 'show']);

$router->dispatch(
    $_SERVER['REQUEST_METHOD'] ?? 'GET',
    $_SERVER['REQUEST_URI'] ?? '/'
);
