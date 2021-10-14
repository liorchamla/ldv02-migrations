<?php

namespace Graille;

class QueryBuilder {
    private array $selects = [];
    private string $from = '';
    private array $joins = [];
    private array $wheres = [];

    public function select(string $select) {
        $this->selects[] = $select;

        return $this;
    }

    public function from(string $table, string $alias) {
        $this->from = "$table AS $alias";

        return $this;
    }

    public function innerJoin(string $table, string $alias, string $join) {
        $this->joins[] = sprintf(
            "INNER JOIN %s AS %s ON %s",
            $table,
            $alias,
            $join
        );

        return $this;
    }

    public function leftJoin(string $table, string $alias, string $join) {
        $this->joins[] = sprintf(
            "LEFT JOIN %s AS %s ON %s",
            $table,
            $alias,
            $join
        );

        return $this;
    }

    public function andWhere(string $condition) {
        $this->wheres[] = $condition;

        return $this;
    }

    public function getQuery(): string {
        
        $sql = sprintf(
            "SELECT %s FROM %s",
            implode(', ', $this->selects),
            $this->from
        );

        if($this->joins) {
            $sql .= ' ' . implode(' ', $this->joins);
        }

        if($this->wheres) {
            $sql .= ' WHERE ' . implode(' AND ', $this->wheres);
        }

        return $sql;
    }
}