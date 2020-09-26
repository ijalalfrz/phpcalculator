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

    public function remove(Request $req, $id)
    {

        $this->history_service->setDriver('database');
        $data_sql = $this->history_service->show($id);
        $this->history_service->setDriver('file');
        $data_csv = $this->history_service->show($id);

        if ($data_sql && $data_csv) {
            $this->history_service->setDriver('database');
            $remove_sql = $this->history_service->remove($id);
            $this->history_service->setDriver('file');
            $remove_file = $this->history_service->remove($id);
            
            if ($remove_file && $remove_sql) {
                $res = NULL;             
            } else {
                $res = [
                    'code' => 400,
                    'message' => 'Error removing data'
                ];
            }
        } else{
            $res = [
                'code' => 404,
                'message' => 'Data not found'
            ];
        }

        $ress = new Response($res, $res['code']??204);
        return $ress;
    }
}
