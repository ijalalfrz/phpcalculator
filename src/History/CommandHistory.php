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

    public function getModel()
    {
        return $this->history;
    }

    public function findAll(): array
    {
        return ['test','test2','test3'];
    }

    public function log($command): bool
    {
        return True;
    }

    public function clearAll():bool
    {
        return True;
    }

}