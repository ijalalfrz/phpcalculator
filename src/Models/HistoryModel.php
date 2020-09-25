<?php
namespace Jakmall\Recruitment\Calculator\Models;

use Jakmall\Recruitment\Calculator\Interfaces\StorageConnectionInterface;

class HistoryModel
{
    private $table_name = 'history';
    public $command;
    public $description;
    public $result;
    public $output;
    public $time;

    public function __construct(StorageConnectionInterface $storage)
    {
        $this->storage = $storage;
        $this->storage->createTable($this->table_name, $this->getColumn());
    }

    public function getColumn()
    {
        return [
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
            $data = [$this->command, $this->description, $this->result, $this->output, $this->time];
            $this->storage->insert($this->table_name, $this->getColumn(), $data);
        } catch (Throwable $e)
        {
            throw $e;
        }
    }



}