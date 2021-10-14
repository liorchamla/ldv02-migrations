<?php

use Graille\QueryBuilder;
use PHPUnit\Framework\TestCase;

class QueryBuilderTest extends TestCase {
    public function test_we_can_get_a_basic_query() {
        // programming by wishful thinking
        $builder = new Graille\QueryBuilder;

        $builder->select('u.name, u.age');
        $builder->select('u.city');

        $builder->from('user', 'u');

        $sql = $builder->getQuery();

        $this->assertEquals('SELECT u.name, u.age, u.city FROM user AS u', $sql);
    }

    public function test_we_can_have_joins() {
        // programming by wishful thinking
        $builder = new Graille\QueryBuilder;

        $builder->select('u.name, u.age');
        $builder->select('u.city');
        $builder->select('c.title, c.id');
        $builder->select('a.views');
        
        $builder->innerJoin('category', 'c', 'c.user_id = u.id');
        $builder->innerJoin('article', 'a', 'a.category_id = c.id');
        
        $builder->from('user', 'u');

        $sql = $builder->getQuery();

        $this->assertEquals('SELECT u.name, u.age, u.city, c.title, c.id, a.views FROM user AS u INNER JOIN category AS c ON c.user_id = u.id INNER JOIN article AS a ON a.category_id = c.id', $sql);
    }

    public function test_we_can_have_left_joins() {
        // programming by wishful thinking
        $builder = new Graille\QueryBuilder;

        $sql = $builder->select('u.name, u.age')
            ->select('u.city')
            ->select('c.title, c.id')
            ->select('a.views')
            ->innerJoin('category', 'c', 'c.user_id = u.id')
            ->leftJoin('article', 'a', 'a.category_id = c.id')
            ->from('user', 'u')
            ->getQuery();

        $this->assertEquals('SELECT u.name, u.age, u.city, c.title, c.id, a.views FROM user AS u INNER JOIN category AS c ON c.user_id = u.id LEFT JOIN article AS a ON a.category_id = c.id', $sql);
    }

    public function test_we_can_get_a_query_with_where() {
        // programming by wishful thinking
        $builder = new Graille\QueryBuilder;

        $builder->select('u.name, u.age');
        $builder->select('u.city');

        $builder->from('user', 'u');

        $builder->andWhere('u.name = "Lior"');
        $builder->andWhere('u.age > 30');

        $sql = $builder->getQuery();

        $this->assertEquals('SELECT u.name, u.age, u.city FROM user AS u WHERE u.name = "Lior" AND u.age > 30', $sql);
    }
}