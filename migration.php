<?php

use Graille\Migration\History;
use Graille\Migration\Logger;
use Graille\Migration\MigrationFinder;
use Graille\Migration\MigrationRunner;
use Graille\Migration\PlanRunner;

require __DIR__ . '/vendor/autoload.php';

$pdo = new PDO("mysql:host=localhost;dbname=ubouffe_test;charset=utf8", "root", "root");

$logger = new Logger;

$runner = new MigrationRunner(
    new MigrationFinder(new History($pdo), "Migrations\\"),
    new PlanRunner($pdo, $logger),
    __DIR__ . '/tests/migrations/'
);

$runner->migrate();
