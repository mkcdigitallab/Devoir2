<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\SoumettreCopieDTO;
use App\Entity\CopieExamen;
use App\Repository\CopieExamenRepositoryInterface;

final class SoumissionCopieService
{
    public function __construct(
        private readonly CalculNoteInterface $calculNote,
        private readonly CopieExamenRepositoryInterface $repository
    ) {
    }

    public function soumettre(SoumettreCopieDTO $dto): CopieExamen
    {
        $resultat = $this->calculNote->calculer(
            $dto->getNoteBrute(),
            $dto->getDateDepot(),
            $dto->getDateLimite()
        );

        $copie = CopieExamen::create(
            $dto->getDateDepot(),
            $dto->getNoteBrute(),
            $dto->getDateLimite()
        );

        $copie->appliquerResultatCalcul(
            $resultat->getNoteFinale(),
            $resultat->isPenaliteAppliquee()
        );

        $this->repository->save($copie);

        return $copie;
    }
}
