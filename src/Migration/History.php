<?php

namespace Graille\Migration;

use PDO;

class History
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findLastMigration(): ?string
    {
        $result = $this->pdo->query("SELECT migration_version FROM migration_status");
        $row = $result->fetch();

        if (!$row) {
            return null;
        }

        return $row['migration_version'];
    }

    public function filterUndoneMigrations(array $classNames): array
    {
        $lastMigration = $this->findLastMigration();

        if (!$lastMigration) {
            return $classNames;
        }

        $index = array_search($lastMigration, $classNames);

        return array_slice($classNames, $index + 1);
    }
}
