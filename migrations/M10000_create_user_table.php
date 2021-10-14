<?php

namespace Migrations;

use Graille\Migration\MigrationInterface;
use Graille\Migration\Plan;

class M10000_create_user_table implements MigrationInterface
{
    public function execute(Plan $plan)
    {
        $plan->create('article')
            ->add('title', 'varchar(255)')
            ->add('description', 'text')
            ->id();
    }
}
