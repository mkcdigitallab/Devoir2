<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Services\CalculNoteAvecRetardService;
use DateTime;

$strategie = new CalculNoteAvecRetardService();

$tests = [
    'dépôt à temps' => fn () => $strategie->calculer(
        15.0,
        new DateTime('2026-09-03 10:00:00'),
        new DateTime('2026-09-03 23:59:59')
    ) === 15.0,

    'dépôt en retard' => fn () => $strategie->calculer(
        15.0,
        new DateTime('2026-09-04 10:00:00'),
        new DateTime('2026-09-03 23:59:59')
    ) === 13.0,

    'note finale jamais négative' => fn () => $strategie->calculer(
        1.0,
        new DateTime('2026-09-04 10:00:00'),
        new DateTime('2026-09-03 23:59:59')
    ) === 0.0,
];

$success = 0;

foreach ($tests as $name => $test) {
    if (!$test()) {
        throw new RuntimeException("Test échoué : {$name}");
    }

    echo "✓ {$name}\n";
    $success++;
}

echo "\n{$success}/" . count($tests) . " tests réussis.\n";
