<?php

namespace Migrations;

use Graille\Migration\MigrationInterface;
use Graille\Migration\Plan;

class M10001_add_author_to_article implements MigrationInterface
{
    public function execute(Plan $plan)
    {
        $plan->alter('article')
            ->add('author', 'varchar(255)');
    }
}
