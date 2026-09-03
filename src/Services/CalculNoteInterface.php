<?php

declare(strict_types=1);

namespace App\Services;

use DateTime;

/**
 * Contrat commun à toutes les stratégies de calcul de note.
 */
interface CalculNoteInterface
{
    public function calculer(
        float $noteBrute,
        DateTime $dateDepot,
        DateTime $dateLimite
    ): ResultatCalculNote;
}
