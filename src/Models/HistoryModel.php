<?php
namespace Jakmall\Recruitment\Calculator\Models;

use Jakmall\Recruitment\Calculator\Interfaces\StorageConnectionInterface;

class HistoryModel
{
    public $command;
    public $description;
    public $result;
    public $output;
    public $time;

    public function __construct(StorageConnectionInterface $storage)
    {
        $this->storage = $storage;
    }

    public function insert()
    {
        try{
            $this->storage->insert($this);
        } catch (Throwable $e)
        {

        }
    }



}