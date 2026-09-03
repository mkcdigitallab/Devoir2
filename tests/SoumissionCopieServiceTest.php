<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\DTO\SoumettreCopieDTO;
use App\Entity\CopieExamen;
use App\Repository\CopieExamenRepositoryInterface;
use App\Services\CalculNoteAvecRetardService;
use App\Services\SoumissionCopieService;

final class FakeCopieExamenRepository implements CopieExamenRepositoryInterface
{
    public ?CopieExamen $saved = null;

    public function save(CopieExamen $copie): int
    {
        $this->saved = $copie;
        $copie->setId(1);
        return 1;
    }

    public function findAll(): array { return []; }
    public function findById(int $id): ?CopieExamen { return null; }
}

$repository = new FakeCopieExamenRepository();
$service = new SoumissionCopieService(
    new CalculNoteAvecRetardService(),
    $repository
);

$dto = SoumettreCopieDTO::fromFormData([
    'noteBrute' => '15',
    'dateDepot' => '2026-09-04 10:00:00',
    'dateLimite' => '2026-09-03 23:59:59',
]);

$copie = $service->soumettre($dto);

if ($copie->getId() !== 1) {
    throw new RuntimeException('Le repository doit enregistrer la copie.');
}

if ($copie->getNoteFinale() !== 13.0) {
    throw new RuntimeException('La stratégie doit calculer la note finale.');
}

if (!$copie->getPenaliteAppliquee()) {
    throw new RuntimeException('La pénalité doit être enregistrée sur la copie.');
}

echo "✓ service de soumission : OK\n";
