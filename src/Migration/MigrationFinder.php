<?php

namespace Graille\Migration;

class MigrationFinder
{
    private History $history;
    private string $namespace;

    public function __construct(History $history, string $namespace)
    {
        $this->history = $history;
        $this->namespace = $namespace;
    }

    private function findMigrations(string $path): array
    {
        return array_map(
            fn ($file) => ucfirst(basename($file, '.php')),
            glob($path . '/*.php')
        );
    }

    public function createInstances(string $path): array
    {
        $classNames = $this->findMigrations($path);
        $classNames = $this->history->filterUndoneMigrations($classNames);

        $objects = [];

        foreach ($classNames as $className) {
            $fullClassName = $this->namespace . $className;
            $objects[] = new $fullClassName;
        }

        return $objects;
    }
}
