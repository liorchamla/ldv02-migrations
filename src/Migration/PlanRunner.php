<?php

namespace Graille\Migration;

use PDO;

class PlanRunner
{
    private array $instances = [];
    private PDO $pdo;
    private Logger $logger;

    public function __construct(PDO $pdo, Logger $logger)
    {
        $this->pdo = $pdo;
        $this->logger = $logger;
    }

    public function setInstances(array $instances = [])
    {
        $this->instances = $instances;
        return $this;
    }

    public function run()
    {
        $this->logger->log("LANCEMENT DES MIGRATIONS :\n\n");

        foreach ($this->instances as $instance) {
            $plan = new Plan;
            $instance->execute($plan);
            $sql = $plan->getSQL();
            $this->logger->log("🛠️ EXECUTION DE LA REQUETE : $sql \n");
            $this->pdo->query($sql);

            $migrationName = get_class($instance);
            $migrationNameParts = explode('\\', $migrationName);
            $migrationName = array_pop($migrationNameParts);

            $this->pdo->query("DELETE FROM migration_status; INSERT INTO migration_status SET migration_version = '$migrationName'");
        }

        $this->logger->log("\nFIN DES MIGRATIONS 👍\n");
    }
}
