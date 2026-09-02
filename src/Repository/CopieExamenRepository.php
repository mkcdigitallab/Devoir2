<?php

namespace App\Repository;

use App\Config\Database;
use App\Entity\CopieExamen;
use DateTime;
use PDO;


class CopieExamenRepository extends AbstractQuery
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }

 
    public function save(CopieExamen $copie): int
    {
        $stmt = $this->connection->prepare(
            'INSERT INTO copie_examen (date_depot, note_brute, note_finale, penalite_appliquee, date_limite)
             VALUES (?, ?, ?, ?, ?)'
        );

        $stmt->execute([
            $copie->getDateDepot()->format('Y-m-d H:i:s'),
            $copie->getNoteBrute(),
            $copie->getNoteFinale(),
            (int)$copie->getPenaliteAppliquee(),
            $copie->getDateLimite()->format('Y-m-d H:i:s'),
        ]);

        return (int)$this->connection->lastInsertId();
    }

   
    public function findById(int $id): ?CopieExamen
    {
        $stmt = $this->connection->prepare(
            'SELECT * FROM copie_examen WHERE id = ?'
        );
        $stmt->execute([$id]);

        $data = $stmt->fetch();
        if (!$data) {
            return null;
        }

        return CopieExamen::fromDatabase($data);
    }

   
    public function findAll(): array
    {
        $stmt = $this->connection->query('SELECT * FROM copie_examen ORDER BY date_depot DESC');
        $results = $stmt->fetchAll();

        return array_map(fn($data) => CopieExamen::fromDatabase($data), $results);
    }

    public function update(CopieExamen $copie): bool
    {
        if ($copie->getId() === null) {
            return false;
        }

        $stmt = $this->connection->prepare(
            'UPDATE copie_examen SET note_brute = ?, note_finale = ?, penalite_appliquee = ?, date_limite = ? WHERE id = ?'
        );

        return $stmt->execute([
            $copie->getNoteBrute(),
            $copie->getNoteFinale(),
            (int)$copie->getPenaliteAppliquee(),
            $copie->getDateLimite()->format('Y-m-d H:i:s'),
            $copie->getId(),
        ]);
    }


    public function delete(int $id): bool
    {
        $stmt = $this->connection->prepare('DELETE FROM copie_examen WHERE id = ?');
        return $stmt->execute([$id]);
    }
}