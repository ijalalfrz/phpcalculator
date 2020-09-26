<?php

namespace Jakmall\Recruitment\Calculator\Http\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;

class CalculatorController
{

    public function __construct(CommandHistoryManagerInterface $history_service)
    {
        $this->history_service = $history_service;
    }

    public function calculate(Request $req, $action)
    {
        $this->history_service->setDriver('database');

        $action_filter = ['add','multiply','pow','substract','divide'];
        if (\in_array($action,$action_filter)) {

            $number = '';
            if ($req->input) {
                foreach($req->input as $num) {
                    $number = $number.$num.' ';
                }
            }

            $cmd = $action.' '.$number;
            if ($this->history_service->log($cmd)) {
                $last_data = $this->history_service->lastData();
                
                $res = [
                    'command' => $last_data->command,
                    'operation' => $last_data->description,
                    'result' => (int) $last_data->result
                ];
            } else {
                $res = [
                    'code' => 400,
                    'message' => 'Error during execute command'
                ];
            }
            
        } else {
            $res = [
                'code' => 422,
                'message' => 'Action parameter unavailable!'
            ];

        }
        $ress = new Response($res, $res['code']??200);
        return $ress;
    }
}
