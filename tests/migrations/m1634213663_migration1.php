<?php

namespace Tests\Migration;

use Graille\Migration\MigrationInterface;
use Graille\Migration\Plan;

class M1634213663_migration1 implements MigrationInterface
{
    public function execute(Plan $plan)
    {
        $plan->create('user')
            ->add('name', 'varchar(255)');
    }
}
