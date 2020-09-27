<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Component\Console\Application;
use Illuminate\Container\Container;
use Symfony\Component\Console\Tester\CommandTester;
use Jakmall\Recruitment\Calculator\Commands\HistoryClearCommand;
use Jakmall\Recruitment\Calculator\History\CommandHistory;

class HistoryClearCommandTest extends TestCase
{
    protected function setUp():void
    {
        $this->command_mock = $this->getMockBuilder(CommandHistory::class)
            ->disableOriginalConstructor()
            ->getMock();
 
        
        $application = new Application();
        
        $test_c = new HistoryClearCommand($this->command_mock);
        $test_c->setLaravel(new Container());
        $application->add($test_c);
        $command = $application->find('history:clear');
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

    public function testExecute()
    {
        $this->command_mock
            ->expects($this->exactly(2))
            ->method('setDriver')
            ->with($this->logicalOr(
                $this->equalTo('database'),
                $this->equalTo('file')
            ))
            ->willReturn(true);


        $this->command_mock
            ->expects($this->exactly(2))
            ->method('clearAll')
            ->will($this->onConsecutiveCalls(true,true));

       
        $this->command_tester->execute(['']);
        
        // $str_compare = $this->cleanString(trim($this->command_tester->getDisplay()));
        $this->assertEquals(trim($this->command_tester->getDisplay()), "History cleared!");

   
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