<?php

namespace Tests\Migration;

use Graille\Migration\MigrationInterface;
use Graille\Migration\Plan;

class M1634213790_migration3 implements MigrationInterface
{
    public function execute(Plan $plan)
    {
        $plan->drop('user');
    }
}
