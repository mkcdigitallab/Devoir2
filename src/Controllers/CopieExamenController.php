<?php

namespace App\Controllers;

use App\DTO\SoumettreCopieDTO;
use App\Entity\CopieExamen;
use App\Repository\CopieExamenRepository;
use InvalidArgumentException;

/**
 * Contrôleur responsable de la gestion des soumissions de copies d'examen
 */
class CopieExamenController
{
    private CopieExamenRepository $repository;

    public function __construct()
    {
        $this->repository = new CopieExamenRepository();
    }

    /**
     * Affiche le formulaire de soumission
     */
    public function afficherFormulaire(): void
    {
        include __DIR__ . '/../../templates/config/formulaire-copie.html.php';
    }

    /**
     * Traite la soumission d'une copie
     * 
     * Workflow :
     * 1. Convertir les données du formulaire via le DTO
     * 2. Créer l'entité CopieExamen
     * 3. Enregistrer en base de données
     * 
     * @param array $postData Données brutes de $_POST
     * @return array ['success' => bool, 'message' => string, 'id' => ?int]
     */
    public function soumettreCopie(array $postData): array
    {
        try {
            // Étape 1 : Convertir les données via le DTO
            $dto = SoumettreCopieDTO::fromFormData($postData);

            // Étape 2 : Créer l'entité CopieExamen
            $copie = CopieExamen::create(
                $dto->getDateDepot(),
                $dto->getNoteBrute(),
                $dto->getDateLimite()
            );

            // Étape 3 : Enregistrer en base de données
            $id = $this->repository->save($copie);

            return [
                'success' => true,
                'message' => 'Copie enregistrée avec succès',
                'id' => $id,
                'data' => $dto->toArray(),
            ];
        } catch (InvalidArgumentException $e) {
            return [
                'success' => false,
                'message' => 'Données invalides: ' . $e->getMessage(),
                'id' => null,
            ];
        } catch (\PDOException $e) {
            return [
                'success' => false,
                'message' => 'Erreur base de données: ' . $e->getMessage(),
                'id' => null,
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Erreur serveur: ' . $e->getMessage(),
                'id' => null,
            ];
        }
    }

    /**
     * Affiche la liste des copies
     */
    public function afficherListe(): void
    {
        $copies = $this->repository->findAll();
        // À implémenter : affichage de la liste
    }

    /**
     * Affiche les détails d'une copie
     */
    public function afficherDetail(int $id): void
    {
        $copie = $this->repository->findById($id);
        if (!$copie) {
            // À implémenter : affichage d'une erreur 404
            return;
        }
        // À implémenter : affichage des détails
    }
}
