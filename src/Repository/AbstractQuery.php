<?php

namespace App\Repository;

use PDO;

abstract class AbstractQuery
{
  
    protected static function fetchAll(
        string $sql,
        array $params = []
    ): array {
        $pdo = Database::getConnection();

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

  
    protected static function fetchOne(
        string $sql,
        array $params = []
    ): ?array {
        $pdo = Database::getConnection();

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

   
    protected static function execute(
        string $sql,
        array $params = []
    ): bool {
        $pdo = Database::getConnection();

        $stmt = $pdo->prepare($sql);

        return $stmt->execute($params);
    }

  
    protected static function insert(
        string $sql,
        array $params = []
    ): int {
        $pdo = Database::getConnection();

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn();
    }
}

