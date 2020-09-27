<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Component\Console\Application;
use Illuminate\Container\Container;
use Symfony\Component\Console\Tester\CommandTester;
use Jakmall\Recruitment\Calculator\Commands\DivideCommand;
use Jakmall\Recruitment\Calculator\History\CommandHistory;

class DivideCommandTest extends TestCase
{
    protected function setUp():void
    {
        $this->command_mock = $this->getMockBuilder(CommandHistory::class)
            ->disableOriginalConstructor()
            ->getMock();
 
        
        $application = new Application();
        
        $test_c = new DivideCommand($this->command_mock);
        $test_c->setLaravel(new Container());
        $application->add($test_c);
        $command = $application->find('divide');
        $this->command_tester = new CommandTester($command);
        
    }

    public function testExecute()
    {
        $this->command_tester->execute(['numbers'=>[5,5]]);

        $this->assertEquals('5 / 5 = 1', trim($this->command_tester->getDisplay()));
    }
}