<?php

use Graille\Migration\History;
use Graille\Migration\Logger;
use Graille\Migration\Plan;
use Graille\Migration\PlanRunner;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Migration\m1634213663_migration1;
use Tests\Migration\m1634213690_migration2;

class PlanRunnerTest extends TestCase
{
    /** @var PDO|MockObject */
    private $pdo;
    /** @var Logger|MockObject */
    private $logger;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->logger = $this->createMock(Logger::class);
    }

    public function test_plan_runner_runs_our_migrations()
    {
        $migration1 = $this->getMockBuilder(m1634213663_migration1::class)
            ->getMock();
        $migration1
            ->expects($this->once())
            ->method('execute')
            ->with($this->callback(function ($parameter) {
                return $parameter instanceof Plan;
            }));

        $migration2 = $this->getMockBuilder(m1634213690_migration2::class)
            ->getMock();
        $migration2
            ->expects($this->once())
            ->method('execute')
            ->with($this->callback(function ($parameter) {
                return $parameter instanceof Plan;
            }));

        $planRunner = new Graille\Migration\PlanRunner($this->pdo, $this->logger);

        $planRunner->setInstances([$migration1, $migration2]);

        $planRunner->run();
    }

    public function test_it_actually_sends_sql()
    {
        $this->pdo->expects($this->exactly(4))->method('query');

        $migration1 = new m1634213663_migration1;
        $migration2 = new m1634213690_migration2;

        $planRunner = new PlanRunner($this->pdo, $this->logger);

        $planRunner->setInstances([$migration1, $migration2]);

        $planRunner->run();
    }

    public function test_it_saves_the_last_migration_ran()
    {
        $pdo = new PDO("mysql:host=localhost;dbname=ubouffe_test", "root", "root");
        $pdo->query("DELETE FROM migration_status; DROP TABLE user;");

        $migration1 = new M1634213663_migration1;
        $migration2 = new M1634213690_migration2;

        $planRunner = new PlanRunner($pdo, $this->logger);

        $planRunner->setInstances([$migration1, $migration2]);

        $planRunner->run();

        $history = new History($pdo);

        $this->assertEquals('M1634213690_migration2', $history->findLastMigration());
    }
}
