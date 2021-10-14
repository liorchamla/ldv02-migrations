<?php

namespace Graille;

class UserSearch {
    public function getSearchQuery($withCategory = false, $withArticles = false) {
        $builder = new QueryBuilder();

        $builder->select('u.id, u.name');
        $builder->from('user', 'u');

        if($withCategory) {
            $builder->select('c.title');
            $builder->innerJoin('category', 'c', 'c.id = u.category_id');
        }

        if($withArticles) {
            $builder->select('a.count');
            $builder->innerJoin('article', 'a', 'a.user_id = u.id');
        }

        return $builder->getQuery();
    }
}