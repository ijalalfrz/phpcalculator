<?php
namespace Jakmall\Recruitment\Calculator\Storage;

use Jakmall\Recruitment\Calculator\Interfaces\StorageConnectionInterface;

class SQLiteConnection implements StorageConnectionInterface
{
    private $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    private function connect()
    {
        // Todo
    }
}
 