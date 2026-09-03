<?php

declare(strict_types=1);

namespace App\Services;

use InvalidArgumentException;

/**
 * Résultat typé produit par une stratégie de calcul.
 */
final class ResultatCalculNote
{
    public function __construct(
        private readonly float $noteFinale,
        private readonly bool $penaliteAppliquee
    ) {
        if ($noteFinale < 0 || $noteFinale > 20) {
            throw new InvalidArgumentException(
                'La note finale doit être comprise entre 0 et 20.'
            );
        }
    }

    public function getNoteFinale(): float
    {
        return $this->noteFinale;
    }

    public function isPenaliteAppliquee(): bool
    {
        return $this->penaliteAppliquee;
    }
}
