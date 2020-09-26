<?php
namespace Jakmall\Recruitment\Calculator\Interfaces;


interface StorageBLLInterface
{
    public function createTable($table, $attribute);
    public function selectAllColumn($table);
    public function filterByColumn($table, $column);
    public function deleteAllData($table);
    public function insert($table, Array $data);
}