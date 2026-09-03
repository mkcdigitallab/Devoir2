<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;
use InvalidArgumentException;

class CopieExamen extends AbstractDocument
{
    private float $noteBrute;
    private float $noteFinale;
    private bool $penaliteAppliquee = false;
    private DateTime $dateLimite;

    protected function __construct(
        DateTime $dateDepot,
        float $noteBrute,
        DateTime $dateLimite,
        ?int $id = null
    ) {
        parent::__construct($dateDepot, $id);
        $this->setNoteBrute($noteBrute);
        $this->dateLimite = $dateLimite;
        $this->noteFinale = $noteBrute;
    }

    public static function create(
        DateTime $dateDepot,
        float $noteBrute,
        DateTime $dateLimite
    ): self {
        return new self($dateDepot, $noteBrute, $dateLimite);
    }

    public static function fromDatabase(array $data): self
    {
        $copie = new self(
            new DateTime($data['date_depot']),
            (float) $data['note_brute'],
            new DateTime($data['date_limite']),
            (int) $data['id']
        );

        $copie->restaurerResultatCalcul(
            (float) $data['note_finale'],
            (bool) $data['penalite_appliquee']
        );

        return $copie;
    }

    public function getNoteBrute(): float
    {
        return $this->noteBrute;
    }

    public function setNoteBrute(float $noteBrute): void
    {
        if ($noteBrute < 0 || $noteBrute > 20) {
            throw new InvalidArgumentException(
                "La note brute doit être entre 0 et 20, reçu : {$noteBrute}"
            );
        }

        $this->noteBrute = $noteBrute;
        $this->noteFinale = $noteBrute;
        $this->penaliteAppliquee = false;
    }

    public function getNoteFinale(): float
    {
        return $this->noteFinale;
    }

    public function getPenaliteAppliquee(): bool
    {
        return $this->penaliteAppliquee;
    }

    /**
     * Enregistre le résultat produit par une stratégie de calcul.
     * L'entité ne connaît pas la règle de calcul.
     */
    public function appliquerResultatCalcul(float $noteFinale, bool $penaliteAppliquee): void
    {
        if ($noteFinale < 0 || $noteFinale > 20) {
            throw new InvalidArgumentException(
                'La note finale doit être comprise entre 0 et 20.'
            );
        }

        $this->noteFinale = $noteFinale;
        $this->penaliteAppliquee = $penaliteAppliquee;
    }

    /**
     * Restaure l'état déjà enregistré en base de données.
     */
    private function restaurerResultatCalcul(float $noteFinale, bool $penaliteAppliquee): void
    {
        $this->appliquerResultatCalcul($noteFinale, $penaliteAppliquee);
    }

    public function getDateLimite(): DateTime
    {
        return $this->dateLimite;
    }

    public function setDateLimite(DateTime $dateLimite): void
    {
        $this->dateLimite = $dateLimite;
    }

    public function estEnRetard(): bool
    {
        return $this->getDateDepot() > $this->dateLimite;
    }
}
