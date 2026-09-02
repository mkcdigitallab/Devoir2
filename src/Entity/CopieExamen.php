<?php

namespace App\Entity;

use DateTime;


class CopieExamen extends AbstractDocument
{

    private float $noteBrute;


    private float $noteFinale;


    private bool $penaliteAppliquee;


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
            (float)$data['note_brute'],
            new DateTime($data['date_limite']),
            (int)$data['id']
        );

        if ($data['penalite_appliquee']) {
            $copie->appliquerPenalite(0);
        }

        return $copie;
    }


    public function getNoteBrute(): float
    {
        return $this->noteBrute;
    }


    public function setNoteBrute(float $noteBrute): void
    {
        if ($noteBrute < 0 || $noteBrute > 20) {
            throw new \InvalidArgumentException(
                "La note brute doit être entre 0 et 20, reçu: {$noteBrute}"
            );
        }
        $this->noteBrute = $noteBrute;
        $this->calculerNoteFinale();
    }

    public function getNoteFinale(): float
    {
        return $this->noteFinale;
    }


    public function getPenaliteAppliquee(): bool
    {
        return $this->penaliteAppliquee;
    }


    public function appliquerPenalite(float $penalite): void
    {
        $this->penaliteAppliquee = true;
        $this->calculerNoteFinale();
    }


    public function getDateLimite(): DateTime
    {
        return $this->dateLimite;
    }


    public function setDateLimite(DateTime $dateLimite): void
    {
        $this->dateLimite = $dateLimite;
    }


    private function calculerNoteFinale(): void
    {
        $finale = $this->noteBrute;
        if ($this->penaliteAppliquee) {
            $finale -= 2;
        }
        $this->noteFinale = max(0, $finale);
    }


    public function estEnRetard(): bool
    {
        return $this->getDateDepot() > $this->dateLimite;
    }
}