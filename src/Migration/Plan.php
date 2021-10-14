<?php

namespace Graille\Migration;

class Plan
{
    public const PROCEDURE_CREATE = 1;
    public const PROCEDURE_ALTER = 2;
    public const PROCEDURE_DROP = 3;

    private string $table = "";
    private array $fields = [];
    private array $modifications = [];
    private array $renames = [];
    private int $procedure = self::PROCEDURE_CREATE;

    public function create(string $tableName)
    {
        $this->table = $tableName;
        $this->procedure = self::PROCEDURE_CREATE;

        return $this;
    }

    public function drop(string $tableName)
    {
        $this->table = $tableName;
        $this->procedure = self::PROCEDURE_DROP;
    }

    public function alter(string $tableName)
    {
        $this->table = $tableName;
        $this->procedure = self::PROCEDURE_ALTER;

        return $this;
    }

    public function change(string $fieldName, string $type)
    {
        $this->modifications[$fieldName] = "$type NOT NULL";
        return $this;
    }

    public function rename(string $fieldName, string $newFieldName, string $type)
    {
        $this->renames[$fieldName] = "$newFieldName $type NOT NULL";
        return $this;
    }

    public function add(string $fieldName, string $type)
    {
        $this->fields[$fieldName] = "$type NOT NULL";

        return $this;
    }

    public function id()
    {
        $this->fields['id'] = "INT PRIMARY KEY AUTO_INCREMENT";
        return $this;
    }

    public function getSQL(): string
    {
        if ($this->procedure === self::PROCEDURE_DROP) {
            return "DROP TABLE {$this->table}";
        }

        $fields = [];

        foreach ($this->fields as $fieldName => $type) {
            $fields[] = "$fieldName $type";
        }

        if ($this->procedure === self::PROCEDURE_CREATE) {
            return sprintf(
                "CREATE TABLE %s (%s)",
                $this->table,
                implode(', ', $fields)
            );
        }

        $modifications = [];

        foreach ($this->modifications as $fieldName => $type) {
            $modifications[] = "MODIFY $fieldName $type";
        }

        $renames = [];

        foreach ($this->renames as $fieldName => $type) {
            $renames[] = "CHANGE $fieldName $type";
        }

        $fields = [];

        foreach ($this->fields as $fieldName => $type) {
            $fields[] = "ADD $fieldName $type";
        }

        $sql = sprintf(
            "ALTER TABLE %s",
            $this->table,
        );

        $parts = [
            implode(', ', $modifications), // ""
            implode(', ', $renames), // ""
            implode(', ', $fields) // ""
        ];

        $parts = array_filter($parts, fn ($part) => $part !== "");

        if ($parts) {
            $sql .= " " . implode(", ", $parts);
        }

        return $sql;
    }
}
