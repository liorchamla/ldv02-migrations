<?php

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase {
    public function test_it_gives_us_the_adequate_query() {
        $objet = new Graille\UserSearch();

        $sql = $objet->getSearchQuery();

        $this->assertEquals("SELECT u.id, u.name FROM user AS u", $sql);
    }

    public function test_it_gives_us_a_different_query() {
        $objet = new Graille\UserSearch;

        $sql = $objet->getSearchQuery(true);

        $this->assertEquals("SELECT u.id, u.name, c.title FROM user AS u INNER JOIN category AS c ON c.id = u.category_id", $sql);
    }

    public function test_it_gives_us_a_different_query_with_articles() {
        $objet = new Graille\UserSearch;

        $sql = $objet->getSearchQuery(true, true);

        $this->assertEquals("SELECT u.id, u.name, c.title, a.count FROM user AS u INNER JOIN category AS c ON c.id = u.category_id INNER JOIN article AS a ON a.user_id = u.id", $sql);
    }
}