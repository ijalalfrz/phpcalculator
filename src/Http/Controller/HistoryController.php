<?php

namespace Jakmall\Recruitment\Calculator\Http\Controller;

use Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;
use Illuminate\Http\Request;

class HistoryController
{

    public function __construct(CommandHistoryManagerInterface $history_service)
    {
        $this->history_service = $history_service;
    }
    public function index(Request $req)
    {
        $driver = 'database';
        if ($req->has('driver')) {
            $driver = $req->input('driver');
        }
        $this->history_service->setDriver($driver);
        $data = $this->history_service->findAll();

        return $data;
    }

    public function show($id)
    {
        dd('create show history by id here'.$id);
    }

    public function remove()
    {
        // todo: modify codes to remove history
        dd('create remove history logic here');
    }
}
