<?php

declare(strict_types=1);

namespace App\Services;

use DateTime;
use InvalidArgumentException;

/**
 * Stratégie appliquant une pénalité de deux points en cas de retard.
 */
final class CalculNoteAvecRetardService implements CalculNoteInterface
{
    private const PENALITE_RETARD = 2.0;

    public function calculer(
        float $noteBrute,
        DateTime $dateDepot,
        DateTime $dateLimite
    ): ResultatCalculNote {
        if ($noteBrute < 0 || $noteBrute > 20) {
            throw new InvalidArgumentException('La note brute doit être entre 0 et 20.');
        }

        $enRetard = $dateDepot > $dateLimite;
        $noteFinale = $enRetard
            ? max(0.0, $noteBrute - self::PENALITE_RETARD)
            : $noteBrute;

        return new ResultatCalculNote($noteFinale, $enRetard);
    }
}
