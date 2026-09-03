<?php

declare(strict_types=1);

namespace App\Services;

use DateTime;

/**
 * Contrat commun à toutes les stratégies de calcul de note.
 */
interface CalculNoteInterface
{
    /**
     * Calcule la note finale à partir de la note brute et des dates.
     */
    public function calculer(
        float $noteBrute,
        DateTime $dateDepot,
        DateTime $dateLimite
    ): float;
}
