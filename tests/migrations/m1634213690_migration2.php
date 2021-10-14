<?php

namespace Tests\Migration;

use Graille\Migration\MigrationInterface;
use Graille\Migration\Plan;

class M1634213690_migration2 implements MigrationInterface
{
    public function execute(Plan $plan)
    {
        $plan->alter('user')
            ->add('city', 'varchar(255)');
    }
}
