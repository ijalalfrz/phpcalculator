<?php

namespace Jakmall\Recruitment\Calculator\Http\Controller;

use Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


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

    public function show(Request $req, $id)
    {
        $driver = 'database';
        if ($req->has('driver')) {
            $driver = $req->input('driver');
        }
        $this->history_service->setDriver($driver);
        $data = $this->history_service->show($id);
        if ($data) {
            $res = (array) $data;
        } else{
            $res = [
                'code' => 404,
                'message' => 'Data not found'
            ];
        }

        $ress = new Response($res, $res['code']??200);
        return $ress;

    }

    public function remove()
    {
        // todo: modify codes to remove history
        dd('create remove history logic here');
    }
}
