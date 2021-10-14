<?php

use Graille\Migration\MigrationFinder;
use Graille\Migration\MigrationRunner;
use Graille\Migration\Plan;
use Graille\Migration\PlanRunner;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Migration\Migration1;
use Tests\Migration\Migration2;

class MigrationRunnerTest extends TestCase
{
    public function test_migrate_will_call_planRunner()
    {
        /** @var PlanRunner|MockObject */
        $planRunner = $this->createMock(PlanRunner::class);
        $planRunner->expects($this->once())->method('setInstances');
        $planRunner->expects($this->once())->method('run');

        /** @var MigrationFinder|MockObject */
        $finder = $this->createMock(MigrationFinder::class);
        $finder->expects($this->once())->method('createInstances');

        $migrationRunner = new MigrationRunner($finder, $planRunner, __DIR__ . '/migrations');

        $migrationRunner->migrate();
    }
}
