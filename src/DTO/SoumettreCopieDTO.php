<?php

namespace App\DTO;

use DateTime;
use InvalidArgumentException;

/**
 * DTO pour transporter les données de soumission d'une copie d'examen
 * 
 * Responsabilités :
 * - Recevoir les valeurs brutes du formulaire (chaînes de caractères)
 * - Convertir et valider les données
 * - Fournir des getters avec types garantis
 * 
 * Non-responsabilités :
 * - Enregistrer la copie en base de données
 * - Calculer la note finale
 * - Produire du HTML
 */
class SoumettreCopieDTO
{
    private float $noteBrute;
    private DateTime $dateDepot;
    private DateTime $dateLimite;

    /**
     * Constructeur privé - Forcer l'utilisation de la factory
     */
    private function __construct(
        float $noteBrute,
        DateTime $dateDepot,
        DateTime $dateLimite
    ) {
        $this->validateNoteBrute($noteBrute);
        $this->noteBrute = $noteBrute;
        $this->dateDepot = $dateDepot;
        $this->dateLimite = $dateLimite;
    }

    /**
     * Factory method - Crée un DTO à partir de données brutes du formulaire
     * 
     * @param array $data Données du formulaire contenant :
     *                     - 'note_brute' (string ou float)
     *                     - 'date_depot' (string au format Y-m-d ou DateTime)
     *                     - 'date_limite' (string au format Y-m-d ou DateTime)
     * 
     * @return self
     * @throws InvalidArgumentException Si les données sont invalides ou manquantes
     */
    public static function fromFormData(array $data): self
    {
        // Valider la présence des champs obligatoires
        $required = ['note_brute', 'date_depot', 'date_limite'];
        foreach ($required as $field) {
            if (!isset($data[$field]) || $data[$field] === '') {
                throw new InvalidArgumentException("Le champ '$field' est obligatoire");
            }
        }

        try {
            // Convertir la note brute
            $noteBrute = self::convertToFloat($data['note_brute']);

            // Convertir les dates
            $dateDepot = self::convertToDateTime($data['date_depot']);
            $dateLimite = self::convertToDateTime($data['date_limite']);

            return new self($noteBrute, $dateDepot, $dateLimite);
        } catch (InvalidArgumentException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new InvalidArgumentException(
                "Erreur lors de la conversion des données: " . $e->getMessage()
            );
        }
    }

    /**
     * Convertit une chaîne de caractères en float
     * 
     * @param mixed $value La valeur à convertir
     * @return float
     * @throws InvalidArgumentException Si la conversion échoue
     */
    private static function convertToFloat(mixed $value): float
    {
        // Si c'est déjà un float ou int, retourner directement
        if (is_numeric($value)) {
            $converted = (float)$value;
            if (!is_finite($converted)) {
                throw new InvalidArgumentException("La note n'est pas un nombre valide");
            }
            return $converted;
        }

        throw new InvalidArgumentException(
            "La note brute doit être numérique, reçu: " . gettype($value)
        );
    }

    /**
     * Convertit une valeur en DateTime
     * 
     * @param mixed $value La valeur à convertir (string ou DateTime)
     * @return DateTime
     * @throws InvalidArgumentException Si la conversion échoue
     */
    private static function convertToDateTime(mixed $value): DateTime
    {
        // Si c'est déjà un DateTime, retourner directement
        if ($value instanceof DateTime) {
            return $value;
        }

        // Si c'est une chaîne, essayer de la convertir
        if (is_string($value)) {
            try {
                return new DateTime($value);
            } catch (\Exception $e) {
                throw new InvalidArgumentException(
                    "Le format de date n'est pas valide: $value. " .
                        "Formats acceptés: Y-m-d, Y-m-d H:i:s, etc."
                );
            }
        }

        throw new InvalidArgumentException(
            "La date doit être une chaîne ou un objet DateTime, reçu: " . gettype($value)
        );
    }

    /**
     * Valide que la note brute est dans la plage acceptable
     * 
     * @param float $note
     * @throws InvalidArgumentException Si la note est invalide
     */
    private function validateNoteBrute(float $note): void
    {
        if ($note < 0 || $note > 20) {
            throw new InvalidArgumentException(
                "La note brute doit être entre 0 et 20, reçu: $note"
            );
        }
    }

    /**
     * Récupère la note brute convertie
     */
    public function getNoteBrute(): float
    {
        return $this->noteBrute;
    }

    /**
     * Récupère la date de dépôt convertie
     */
    public function getDateDepot(): DateTime
    {
        return $this->dateDepot;
    }

    /**
     * Récupère la date limite convertie
     */
    public function getDateLimite(): DateTime
    {
        return $this->dateLimite;
    }

    /**
     * Vérifie si la copie a été déposée en retard
     */
    public function estEnRetard(): bool
    {
        return $this->dateDepot > $this->dateLimite;
    }

    /**
     * Retourne les données en tableau (utile pour les logs)
     */
    public function toArray(): array
    {
        return [
            'note_brute' => $this->noteBrute,
            'date_depot' => $this->dateDepot->format('Y-m-d H:i:s'),
            'date_limite' => $this->dateLimite->format('Y-m-d H:i:s'),
            'en_retard' => $this->estEnRetard(),
        ];
    }
}
