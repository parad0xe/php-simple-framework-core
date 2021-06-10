<?php


namespace Parad0xeSimpleFramework\Core\Database\Builder\Query;


use PDOStatement;

interface QueryInterface
{
    public function getQuery(): PDOStatement;
    public function executeQuery(PDOStatement $query): bool;
    public function clear();
}
