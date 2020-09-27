<?php
namespace Jakmall\Recruitment\Calculator\Models;

use Jakmall\Recruitment\Calculator\Interfaces\StorageConnectionInterface;

class HistoryModel
{
    private $table_name = 'history';
    public $id;
    public $command;
    public $description;
    public $result;
    public $output;
    public $time;

    public function __construct(StorageConnectionInterface $storage = NULL)
    {
        if ($storage)
        {
            $this->storage = $storage;
            $this->storage->createTable($this->table_name, $this->getColumn());
        }
    }

    public function getColumn()
    {
        return [
            'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT',
            'command' => 'VARCHAR(255) NOT NULL',
            'description' => 'VARCHAR(255) NOT NULL',
            'result' => 'INTEGER NOT NULL',
            'output' => 'VARCHAR(255) NOT NULL',
            'time' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'
        ];

    }

    public function insert()
    {
        try{
            $data = [$this->id, $this->command, $this->description, $this->result, $this->output, $this->time];
            $data = array_combine(array_keys($this->getColumn()), $data);

            return $this->storage->insert($this->table_name, $data);
        } catch (Throwable $e)
        {
            throw $e;
        }
    }

    public function getAll()
    {
        
        $data = $this->storage->selectAllColumn($this->table_name);

        return $data;
    }

    public function deleteAll()
    {
        return $this->storage->deleteAllData($this->table_name);
    }

    public function filterBy($column_data)
    {
        $data = $this->storage->filterByColumn($this->table_name, $column_data);
        return $data;
    }

    public function getLastData()
    {
        $data = $this->storage->getLastInsertedData($this->table_name);
        return $data;
    }

    public function getById($id)
    {
        $data = $this->storage->filterById($this->table_name, $id);
        return $data;
    }

    public function deleteById($id)
    {
        $data = $this->storage->deleteById($this->table_name, $id);
        return $data;
    }
    
}