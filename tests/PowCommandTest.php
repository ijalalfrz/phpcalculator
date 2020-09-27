<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Symfony\Component\Console\Application;
use Illuminate\Container\Container;
use Symfony\Component\Console\Tester\CommandTester;
use Jakmall\Recruitment\Calculator\Commands\PowCommand;
use Jakmall\Recruitment\Calculator\History\CommandHistory;

class PowCommandTest extends TestCase
{
    protected function setUp():void
    {
        $this->command_mock = $this->getMockBuilder(CommandHistory::class)
            ->disableOriginalConstructor()
            ->getMock();
 
        
        $application = new Application();
        
        // $test_command = $this->app->make(AddCommand::class);
        // $test_command->setLaravel($test_command);
        $test_c = new PowCommand($this->command_mock);
        $test_c->setLaravel(new Container());
        $application->add($test_c);
        $command = $application->find('pow');
        $this->command_tester = new CommandTester($command);
        
    }

    public function testExecute()
    {
        $this->command_tester->execute(['base'=>5, 'exp'=>2]);

        $this->assertEquals('5 ^ 2 = 25', trim($this->command_tester->getDisplay()));
    }
}