<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Router\Router;

$called = false;
$id = null;

$router = new Router(static function (string $message): void {
    throw new RuntimeException($message);
});

$router->get('/copies/{id}', static function (int $value) use (&$called, &$id): void {
    $called = true;
    $id = $value;
});

$router->dispatch('GET', '/copies/42');

if (!$called || $id !== 42) {
    throw new RuntimeException('Le routeur doit extraire et transmettre le paramètre id.');
}

echo "✓ routeur : OK\n";
