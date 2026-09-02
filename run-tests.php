#!/usr/bin/env php
<?php

/**
 * Runner de tests pour la Partie 4
 * Exécute les tests du DTO SoumettreCopieDTO
 */

require_once __DIR__ . '/vendor/autoload.php';

// Charger les variables d'environnement
loadEnv(__DIR__ . '/.env');

function loadEnv(string $path): void
{
    if (!file_exists($path)) {
        throw new RuntimeException("Le fichier .env n'a pas été trouvé: $path");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) {
            continue;
        }

        if (str_contains($line, '=')) {
            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            if ((str_starts_with($value, '"') && str_ends_with($value, '"')) ||
                (str_starts_with($value, "'") && str_ends_with($value, "'"))
            ) {
                $value = substr($value, 1, -1);
            }

            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }
}

use App\DTO\SoumettreCopieDTO;
use DateTime;
use InvalidArgumentException;

echo "\n🧪 Exécution des tests - Partie 4\n";
echo "==================================\n";

// Tests pour SoumettreCopieDTO
runAllTests();

function runAllTests(): void
{
    $successCount = 0;
    $failureCount = 0;
    $failures = [];

    // Test 1: Conversion valide
    try {
        $data = [
            'note_brute' => '15.5',
            'date_depot' => '2025-01-20 14:30:00',
            'date_limite' => '2025-01-25 23:59:59',
        ];

        $dto = SoumettreCopieDTO::fromFormData($data);

        assert($dto->getNoteBrute() === 15.5, 'Note brute');
        assert($dto->getDateDepot()->format('Y-m-d') === '2025-01-20', 'Date dépôt');
        assert($dto->getDateLimite()->format('Y-m-d') === '2025-01-25', 'Date limite');
        assert(!$dto->estEnRetard(), 'Pas en retard');

        echo "✓ Conversion valide\n";
        $successCount++;
    } catch (\Throwable $e) {
        echo "✗ Conversion valide - {$e->getMessage()}\n";
        $failureCount++;
        $failures[] = 'Conversion valide';
    }

    // Test 2: Note manquante
    try {
        $data = [
            'date_depot' => '2025-01-20 14:30:00',
            'date_limite' => '2025-01-25 23:59:59',
        ];

        try {
            SoumettreCopieDTO::fromFormData($data);
            throw new Exception('Aurait dû lancer une exception');
        } catch (InvalidArgumentException $e) {
            assert(str_contains($e->getMessage(), 'note_brute'), 'Message d\'erreur');
        }

        echo "✓ Note manquante\n";
        $successCount++;
    } catch (\Throwable $e) {
        echo "✗ Note manquante - {$e->getMessage()}\n";
        $failureCount++;
        $failures[] = 'Note manquante';
    }

    // Test 3: Date manquante
    try {
        $data = [
            'note_brute' => '15',
            'date_depot' => '2025-01-20 14:30:00',
        ];

        try {
            SoumettreCopieDTO::fromFormData($data);
            throw new Exception('Aurait dû lancer une exception');
        } catch (InvalidArgumentException $e) {
            assert(str_contains($e->getMessage(), 'date_limite'), 'Message d\'erreur');
        }

        echo "✓ Date manquante\n";
        $successCount++;
    } catch (\Throwable $e) {
        echo "✗ Date manquante - {$e->getMessage()}\n";
        $failureCount++;
        $failures[] = 'Date manquante';
    }

    // Test 4: Note invalide
    try {
        $data = [
            'note_brute' => 'abc',
            'date_depot' => '2025-01-20 14:30:00',
            'date_limite' => '2025-01-25 23:59:59',
        ];

        try {
            SoumettreCopieDTO::fromFormData($data);
            throw new Exception('Aurait dû lancer une exception');
        } catch (InvalidArgumentException $e) {
            assert(str_contains($e->getMessage(), 'numérique'), 'Message d\'erreur');
        }

        echo "✓ Note invalide\n";
        $successCount++;
    } catch (\Throwable $e) {
        echo "✗ Note invalide - {$e->getMessage()}\n";
        $failureCount++;
        $failures[] = 'Note invalide';
    }

    // Test 5: Note trop grande
    try {
        $data = [
            'note_brute' => '25',
            'date_depot' => '2025-01-20 14:30:00',
            'date_limite' => '2025-01-25 23:59:59',
        ];

        try {
            SoumettreCopieDTO::fromFormData($data);
            throw new Exception('Aurait dû lancer une exception');
        } catch (InvalidArgumentException $e) {
            assert(str_contains($e->getMessage(), '0 et 20'), 'Message d\'erreur');
        }

        echo "✓ Note trop grande\n";
        $successCount++;
    } catch (\Throwable $e) {
        echo "✗ Note trop grande - {$e->getMessage()}\n";
        $failureCount++;
        $failures[] = 'Note trop grande';
    }

    // Test 6: Note trop petite
    try {
        $data = [
            'note_brute' => '-5',
            'date_depot' => '2025-01-20 14:30:00',
            'date_limite' => '2025-01-25 23:59:59',
        ];

        try {
            SoumettreCopieDTO::fromFormData($data);
            throw new Exception('Aurait dû lancer une exception');
        } catch (InvalidArgumentException $e) {
            assert(str_contains($e->getMessage(), '0 et 20'), 'Message d\'erreur');
        }

        echo "✓ Note trop petite\n";
        $successCount++;
    } catch (\Throwable $e) {
        echo "✗ Note trop petite - {$e->getMessage()}\n";
        $failureCount++;
        $failures[] = 'Note trop petite';
    }

    // Test 7: Détection de retard
    try {
        $data = [
            'note_brute' => '15',
            'date_depot' => '2025-01-26 10:00:00',
            'date_limite' => '2025-01-25 23:59:59',
        ];

        $dto = SoumettreCopieDTO::fromFormData($data);
        assert($dto->estEnRetard(), 'Doit être en retard');

        echo "✓ Détection de retard\n";
        $successCount++;
    } catch (\Throwable $e) {
        echo "✗ Détection de retard - {$e->getMessage()}\n";
        $failureCount++;
        $failures[] = 'Détection de retard';
    }

    // Test 8: Conversion en tableau
    try {
        $data = [
            'note_brute' => '15.5',
            'date_depot' => '2025-01-20 14:30:00',
            'date_limite' => '2025-01-25 23:59:59',
        ];

        $dto = SoumettreCopieDTO::fromFormData($data);
        $array = $dto->toArray();

        assert(isset($array['note_brute']), 'note_brute présente');
        assert(isset($array['date_depot']), 'date_depot présente');
        assert(isset($array['date_limite']), 'date_limite présente');
        assert(isset($array['en_retard']), 'en_retard présente');

        echo "✓ Conversion en tableau\n";
        $successCount++;
    } catch (\Throwable $e) {
        echo "✗ Conversion en tableau - {$e->getMessage()}\n";
        $failureCount++;
        $failures[] = 'Conversion en tableau';
    }

    // Afficher les résultats
    $total = $successCount + $failureCount;
    echo "\n======================================\n";
    echo "Résultats: $successCount/$total réussis\n";

    if ($failureCount > 0) {
        echo "\n❌ Échecs:\n";
        foreach ($failures as $failure) {
            echo "  - $failure\n";
        }
    } else {
        echo "✅ Tous les tests sont passés!\n";
    }
    echo "======================================\n\n";
}
