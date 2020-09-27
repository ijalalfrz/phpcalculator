<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Component\Console\Application;
use Illuminate\Container\Container;
use Symfony\Component\Console\Tester\CommandTester;
use Jakmall\Recruitment\Calculator\Commands\HistoryListCommand;
use Jakmall\Recruitment\Calculator\History\CommandHistory;

class HistoryListCommandTest extends TestCase
{
    protected function setUp():void
    {
        $this->command_mock = $this->getMockBuilder(CommandHistory::class)
            ->disableOriginalConstructor()
            ->getMock();
 
        
        $application = new Application();
        
        $test_c = new HistoryListCommand($this->command_mock);
        $test_c->setLaravel(new Container());
        $application->add($test_c);
        $command = $application->find('history:list');
        $this->command_tester = new CommandTester($command);

        $this->arr_mock = [
            'id' => 1,
            'command' => 'Add',
            'description' => 'Desc',
            'result' => 'Res',
            'output' => 'Out',
            'time' => 'Time'
        ];
        
    }

    public function testGetAllExecute()
    {
        $this->command_mock
            ->expects($this->once())
            ->method('setDriver')
            ->with('database')
            ->willReturn(true);


        $this->command_mock
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([(object) $this->arr_mock]);

       
        $this->command_tester->execute([
            'commands' => ''
        ]);
        
        $str_compare = $this->cleanString(trim($this->command_tester->getDisplay()));
        $this->assertEquals($str_compare, 'nocommanddescriptionresultoutputtime1AddDescResOutTime');

   
    }

    public function testFilterExecute()
    {

        $this->command_mock
            ->expects($this->once())
            ->method('setDriver')
            ->with('database')
            ->willReturn(true);

        $this->command_mock
            ->expects($this->once())
            ->method('filter')
            ->with([
                'command'=>'add'
            ])
            ->willReturn([(object) $this->arr_mock]);

        // test filter
        $this->command_tester->execute([
            'commands' => 'add'
        ]);
        $str_compare = $this->cleanString(trim($this->command_tester->getDisplay()));        
        $this->assertEquals($str_compare, 'nocommanddescriptionresultoutputtime1AddDescResOutTime');

    }

    public function cleanString($str){
        $str_compare = $str;
        $str_compare = str_replace('-','',$str_compare);
        $str_compare = str_replace('+','',$str_compare);
        $str_compare = str_replace('|','',$str_compare);
        $str_compare = str_replace(' ','',$str_compare);
        $str_compare = str_replace("\n",'',$str_compare);

        return $str_compare;

    }
}