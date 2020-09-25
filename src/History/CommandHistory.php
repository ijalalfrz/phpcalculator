<?php

namespace Jakmall\Recruitment\Calculator\History;

use Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;

class CommandHistory implements CommandHistoryManagerInterface
{
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