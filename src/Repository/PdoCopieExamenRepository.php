<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\CopieExamen;
use PDO;

final class PdoCopieExamenRepository implements CopieExamenRepositoryInterface
{
    public function __construct(private readonly PDO $connection)
    {
    }

    public function save(CopieExamen $copie): int
    {
        $sql = 'INSERT INTO copie_examen
            (date_depot, note_brute, note_finale, penalite_appliquee, date_limite)
            VALUES (:date_depot, :note_brute, :note_finale, :penalite_appliquee, :date_limite)
            RETURNING id';

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            ':date_depot' => $copie->getDateDepot()->format('Y-m-d H:i:s'),
            ':note_brute' => $copie->getNoteBrute(),
            ':note_finale' => $copie->getNoteFinale(),
            ':penalite_appliquee' => $copie->getPenaliteAppliquee(),
            ':date_limite' => $copie->getDateLimite()->format('Y-m-d H:i:s'),
        ]);

        $id = $stmt->fetchColumn();
        if ($id === false) {
            throw new \RuntimeException('Impossible de récupérer l\'identifiant de la copie.');
        }

        $copie->setId((int) $id);
        return (int) $id;
    }

    public function findAll(): array
    {
        $stmt = $this->connection->query(
            'SELECT id, date_depot, note_brute, note_finale, penalite_appliquee, date_limite
             FROM copie_examen ORDER BY date_depot DESC, id DESC'
        );

        return array_map(
            static fn(array $data): CopieExamen => CopieExamen::fromDatabase($data),
            $stmt->fetchAll()
        );
    }

    public function findById(int $id): ?CopieExamen
    {
        if ($id <= 0) {
            return null;
        }

        $stmt = $this->connection->prepare(
            'SELECT id, date_depot, note_brute, note_finale, penalite_appliquee, date_limite
             FROM copie_examen WHERE id = :id'
        );
        $stmt->execute([':id' => $id]);

        $data = $stmt->fetch();
        return $data === false ? null : CopieExamen::fromDatabase($data);
    }
}
