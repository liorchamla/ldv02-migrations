<?php

use Graille\Migration\History;
use Graille\Migration\MigrationFinder;
use Graille\Migration\MigrationInterface;
use PHPUnit\Framework\TestCase;
use Tests\Migration\m1634213663_migration1;
use Tests\Migration\m1634213690_migration2;

class MigrationFinderTest extends TestCase
{
    public function test_it_instanciates_classes()
    {
        $pdo = new PDO("mysql:host=localhost;dbname=ubouffe_test;charset=utf8", "root", "root", [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        $pdo->query("DELETE FROM migration_status");

        $finder  = new MigrationFinder(new History($pdo), "Tests\\Migration\\");

        $objects = $finder->createInstances(__DIR__ . '/migrations');

        $migrationsFilesCount = count(glob(__DIR__ . '/migrations/*.php'));

        $this->assertIsArray($objects);
        $this->assertCount($migrationsFilesCount, $objects);

        foreach ($objects as $instance) {
            $this->assertInstanceOf(MigrationInterface::class, $instance);
        }

        $pdo->query("INSERT INTO migration_status SET migration_version = 'M1634213690_migration2'");

        $objects = $finder->createInstances(__DIR__ . '/migrations');

        $this->assertCount(1, $objects);
    }
}
