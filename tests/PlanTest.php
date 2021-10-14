<?php

use Graille\Migration\Plan;
use PHPUnit\Framework\TestCase;

class PlanTest extends TestCase
{
    public function test_it_can_create_sql_from_instructions()
    {
        $plan = new Graille\Migration\Plan;

        $plan->create('user')
            ->add('name', 'varchar(255)')
            ->add('city', 'varchar(255)')
            ->id();

        $sql = $plan->getSQL();

        $this->assertEquals('CREATE TABLE user (name varchar(255) NOT NULL, city varchar(255) NOT NULL, id INT PRIMARY KEY AUTO_INCREMENT)', $sql);
    }

    public function test_it_can_alter_a_table()
    {
        $plan = new Plan;

        $plan->alter('user')
            ->change('name', 'text');

        $sql = $plan->getSQL();

        $this->assertEquals('ALTER TABLE user MODIFY name text NOT NULL', $sql);

        $plan->rename('city', 'ville', 'text')
            ->add('age', 'int');

        $sql = $plan->getSQL();

        $this->assertEquals('ALTER TABLE user MODIFY name text NOT NULL, CHANGE city ville text NOT NULL, ADD age int NOT NULL', $sql);

        $plan = new Plan;

        $plan->alter('user')
            ->rename('name', 'first_name', 'varchar(255)');

        $this->assertEquals(
            'ALTER TABLE user CHANGE name first_name varchar(255) NOT NULL',
            $plan->getSQL()
        );
    }

    public function test_it_can_drop_a_table()
    {
        $plan = new Plan;

        $plan->drop('user');

        $this->assertEquals('DROP TABLE user', $plan->getSQL());
    }
}
