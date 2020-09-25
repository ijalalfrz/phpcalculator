<?php

namespace Jakmall\Recruitment\Calculator\History;


use Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;
use Jakmall\Recruitment\Calculator\Storage\SQLiteStorage;
use Jakmall\Recruitment\Calculator\Models\HistoryModel;



class CommandHistory implements CommandHistoryManagerInterface
{
    private $driver = [];
    private $driver_type;
    private $history;
    public function __construct($config)
    {
        $sqlite_driver = new SQLiteStorage($config['sqlite_path']);

        $this->driver['database'] ??= $sqlite_driver;
    }

    public function setDriver($driver)
    {
        $this->driver_type = $driver;
        $this->driver[$driver]->connect();
        $this->history = new HistoryModel($this->driver[$driver]);
    }


    public function store(HistoryModel $data)
    {
        $this->history->command = $data->command;
        $this->history->description = $data->description;
        $this->history->result = $data->result;
        $this->history->output = $data->output;
        $this->history->time = date("Y-m-d H:i:s");
        $this->history->insert();

    }

    public function findAll(): array
    {
        $data = $this->history->getAll();
        return $data;
    }

    public function filter($column): array
    {
        $data = $this->history->filterBy($column);
        return $data;
    }

    public function log($command): bool
    {
        return True;
    }

    public function clearAll():bool
    {
        return $this->history->deleteAll();
    }

}