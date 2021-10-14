<?php

use Graille\Migration\History;
use PHPUnit\Framework\MockObject\MockObject;

it("find the last played migration in the database", function () {
    // Setup
    $pdo = new PDO("mysql:host=localhost;dbname=ubouffe_test;charset=utf8", "root", "root");

    // Test si il n'y a aucune migration
    $pdo->query("DELETE FROM migration_status");
    $history = new Graille\Migration\History($pdo);

    expect($history->findLastMigration())->toBeNull();

    // Test si il y a une migration
    $pdo->query("INSERT INTO migration_status SET migration_version = 'm1634213663_migration1'");

    expect($history->findLastMigration())->toBe("m1634213663_migration1");
});

it("should filter migrations to keep only undone migrations", function () {
    // Setup
    $pdo = new PDO("mysql:host=localhost;dbname=ubouffe_test;charset=utf8", "root", "root");
    $pdo->query("DELETE FROM migration_status");

    $history = new History($pdo);

    $undoneMigrations = $history->filterUndoneMigrations([
        'm1634213663_migration1',
        'm1634213690_migration2',
        'm1634213790_migration3',
        'm1634213890_migration4',
        'm1634213990_migration5',
    ]);

    expect($undoneMigrations)->toMatchArray([
        'm1634213663_migration1',
        'm1634213690_migration2',
        'm1634213790_migration3',
        'm1634213890_migration4',
        'm1634213990_migration5',
    ]);

    $pdo->query("INSERT INTO migration_status SET migration_version = 'm1634213790_migration3'");

    $undoneMigrations = $history->filterUndoneMigrations([
        'm1634213663_migration1',
        'm1634213690_migration2',
        'm1634213790_migration3',
        'm1634213890_migration4',
    ]);

    expect($undoneMigrations)->toMatchArray([
        'm1634213890_migration4',
    ]);
});
