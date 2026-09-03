<?php

declare(strict_types=1);

namespace App\Controllers;

use App\DTO\SoumettreCopieDTO;
use App\Repository\CopieExamenRepositoryInterface;
use App\Services\SoumissionCopieService;
use InvalidArgumentException;
use Throwable;

final class CopieExamenController
{
    public function __construct(
        private readonly SoumissionCopieService $soumissionService,
        private readonly CopieExamenRepositoryInterface $repository
    ) {
    }

    public function create(): void
    {
        $old = [];
        $error = null;
        require __DIR__ . '/../../templates/copies/create.html.php';
    }

    public function store(array $postData): void
    {
        $old = $postData;

        try {
            $dto = SoumettreCopieDTO::fromFormData($postData);
            $copie = $this->soumissionService->soumettre($dto);

            header('Location: /copies/' . $copie->getId(), true, 303);
            exit;
        } catch (InvalidArgumentException $e) {
            http_response_code(422);
            $error = $e->getMessage();
        } catch (Throwable $e) {
            http_response_code(500);
            $error = 'Une erreur interne est survenue.';
        }

        require __DIR__ . '/../../templates/copies/create.html.php';
    }

    public function index(): void
    {
        $copies = $this->repository->findAll();
        require __DIR__ . '/../../templates/copies/index.html.php';
    }

    public function show(int $id): void
    {
        $copie = $this->repository->findById($id);

        if ($copie === null) {
            http_response_code(404);
            $message = 'La copie demandée est introuvable.';
            require __DIR__ . '/../../templates/errors/404.html.php';
            return;
        }

        require __DIR__ . '/../../templates/copies/show.html.php';
    }

    public function notFound(string $message = 'Page introuvable.'): void
    {
        http_response_code(404);
        require __DIR__ . '/../../templates/errors/404.html.php';
    }
}
