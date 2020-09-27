<?php

use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Component\Console\Application;
use Illuminate\Container\Container;
use Symfony\Component\Console\Tester\CommandTester;
use Jakmall\Recruitment\Calculator\Commands\MultiplyCommand;
use Jakmall\Recruitment\Calculator\History\CommandHistory;

class MultiplyCommandTest extends TestCase
{
    protected function setUp():void
    {
        $this->command_mock = $this->getMockBuilder(CommandHistory::class)
            ->disableOriginalConstructor()
            ->getMock();
 
        
        $application = new Application();
        
        // $test_command = $this->app->make(AddCommand::class);
        // $test_command->setLaravel($test_command);
        $test_c = new MultiplyCommand($this->command_mock);
        $test_c->setLaravel(new Container());
        $application->add($test_c);
        $command = $application->find('multiply');
        $this->command_tester = new CommandTester($command);
        
    }

    public function testExecute()
    {
        $this->command_tester->execute(['numbers'=>[5,5,2]]);

        $this->assertEquals('5 * 5 * 2 = 50', trim($this->command_tester->getDisplay()));
    }
}