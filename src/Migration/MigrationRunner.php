<?php

namespace Graille\Migration;

class MigrationRunner
{
    private PlanRunner $planRunner;
    private string $path = "";
    private MigrationFinder $finder;

    public function __construct(MigrationFinder $finder, PlanRunner $planRunner, string $path)
    {
        $this->planRunner = $planRunner;
        $this->finder = $finder;
        $this->path = $path;
    }

    public function migrate()
    {
        $instances = $this->finder->createInstances($this->path);

        $this->planRunner->setInstances($instances);
        $this->planRunner->run();
    }
}
