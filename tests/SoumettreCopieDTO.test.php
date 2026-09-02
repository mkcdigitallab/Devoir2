<?php

namespace App\Tests;

use App\DTO\SoumettreCopieDTO;
use DateTime;
use InvalidArgumentException;

/**
 * Tests pour le DTO SoumettreCopieDTO
 * 
 * Vérifie :
 * - Conversion correcte des données du formulaire
 * - Validation des valeurs
 * - Gestion des erreurs
 */
class SoumettreCopieDTOTest
{
    private int $successCount = 0;
    private int $failureCount = 0;
    private array $failures = [];

    public function run(): void
    {
        echo "\n====== Tests SoumettreCopieDTO ======\n\n";

        $this->testConversionValide();
        $this->testNoteManquante();
        $this->testDateManquante();
        $this->testNoteInvalide();
        $this->testDateInvalide();
        $this->testNoteTropGrande();
        $this->testNoteTropPetite();
        $this->testEnRetard();
        $this->testToArray();

        $this->afficherResultats();
    }

    /**
     * Test : Conversion valide avec données correctes
     */
    private function testConversionValide(): void
    {
        $this->test('Conversion valide', function () {
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
        });
    }

    /**
     * Test : Note manquante dans les données
     */
    private function testNoteManquante(): void
    {
        $this->test('Note manquante', function () {
            $data = [
                'date_depot' => '2025-01-20 14:30:00',
                'date_limite' => '2025-01-25 23:59:59',
            ];

            try {
                SoumettreCopieDTO::fromFormData($data);
                throw new \Exception('Aurait dû lancer une exception');
            } catch (InvalidArgumentException $e) {
                assert(str_contains($e->getMessage(), 'note_brute'), 'Message d\'erreur');
            }
        });
    }

    /**
     * Test : Date manquante dans les données
     */
    private function testDateManquante(): void
    {
        $this->test('Date manquante', function () {
            $data = [
                'note_brute' => '15',
                'date_depot' => '2025-01-20 14:30:00',
            ];

            try {
                SoumettreCopieDTO::fromFormData($data);
                throw new \Exception('Aurait dû lancer une exception');
            } catch (InvalidArgumentException $e) {
                assert(str_contains($e->getMessage(), 'date_limite'), 'Message d\'erreur');
            }
        });
    }

    /**
     * Test : Note invalide (non-numérique)
     */
    private function testNoteInvalide(): void
    {
        $this->test('Note invalide', function () {
            $data = [
                'note_brute' => 'abc',
                'date_depot' => '2025-01-20 14:30:00',
                'date_limite' => '2025-01-25 23:59:59',
            ];

            try {
                SoumettreCopieDTO::fromFormData($data);
                throw new \Exception('Aurait dû lancer une exception');
            } catch (InvalidArgumentException $e) {
                assert(str_contains($e->getMessage(), 'numérique'), 'Message d\'erreur');
            }
        });
    }

    /**
     * Test : Date invalide
     */
    private function testDateInvalide(): void
    {
        $this->test('Date invalide', function () {
            $data = [
                'note_brute' => '15',
                'date_depot' => '2025-01-32 14:30:00', // Jour invalide
                'date_limite' => '2025-01-25 23:59:59',
            ];

            try {
                SoumettreCopieDTO::fromFormData($data);
                throw new \Exception('Aurait dû lancer une exception');
            } catch (InvalidArgumentException $e) {
                assert(str_contains($e->getMessage(), 'date'), 'Message d\'erreur');
            }
        });
    }

    /**
     * Test : Note trop grande (> 20)
     */
    private function testNoteTropGrande(): void
    {
        $this->test('Note trop grande', function () {
            $data = [
                'note_brute' => '25',
                'date_depot' => '2025-01-20 14:30:00',
                'date_limite' => '2025-01-25 23:59:59',
            ];

            try {
                SoumettreCopieDTO::fromFormData($data);
                throw new \Exception('Aurait dû lancer une exception');
            } catch (InvalidArgumentException $e) {
                assert(str_contains($e->getMessage(), '0 et 20'), 'Message d\'erreur');
            }
        });
    }

    /**
     * Test : Note trop petite (< 0)
     */
    private function testNoteTropPetite(): void
    {
        $this->test('Note trop petite', function () {
            $data = [
                'note_brute' => '-5',
                'date_depot' => '2025-01-20 14:30:00',
                'date_limite' => '2025-01-25 23:59:59',
            ];

            try {
                SoumettreCopieDTO::fromFormData($data);
                throw new \Exception('Aurait dû lancer une exception');
            } catch (InvalidArgumentException $e) {
                assert(str_contains($e->getMessage(), '0 et 20'), 'Message d\'erreur');
            }
        });
    }

    /**
     * Test : Détection de retard
     */
    private function testEnRetard(): void
    {
        $this->test('Détection de retard', function () {
            $data = [
                'note_brute' => '15',
                'date_depot' => '2025-01-26 10:00:00', // Après la limite
                'date_limite' => '2025-01-25 23:59:59',
            ];

            $dto = SoumettreCopieDTO::fromFormData($data);
            assert($dto->estEnRetard(), 'Doit être en retard');
        });
    }

    /**
     * Test : Conversion en tableau
     */
    private function testToArray(): void
    {
        $this->test('Conversion en tableau', function () {
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
        });
    }

    /**
     * Exécute un test et enregistre le résultat
     */
    private function test(string $name, callable $test): void
    {
        try {
            $test();
            echo "✓ $name\n";
            $this->successCount++;
        } catch (AssertionError $e) {
            echo "✗ $name - Assertion: {$e->getMessage()}\n";
            $this->failureCount++;
            $this->failures[] = $name;
        } catch (\Throwable $e) {
            echo "✗ $name - {$e->getMessage()}\n";
            $this->failureCount++;
            $this->failures[] = $name;
        }
    }

    /**
     * Affiche un résumé des résultats
     */
    private function afficherResultats(): void
    {
        $total = $this->successCount + $this->failureCount;
        echo "\n======================================\n";
        echo "Résultats: $this->successCount/$total réussis\n";

        if ($this->failureCount > 0) {
            echo "\n❌ Échecs:\n";
            foreach ($this->failures as $failure) {
                echo "  - $failure\n";
            }
        } else {
            echo "✅ Tous les tests sont passés!\n";
        }
        echo "======================================\n\n";
    }
}
