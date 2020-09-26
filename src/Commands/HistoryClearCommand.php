<?php
namespace Jakmall\Recruitment\Calculator\Commands;


use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Jakmall\Recruitment\Calculator\Interfaces\OperatorInterface;
use Jakmall\Recruitment\Calculator\Commands\BaseCommand;
use Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;

class HistoryClearCommand extends BaseCommand
{
    /**
     * @var string
     */
    protected $signature;

    /**
     * @var string
     */
    protected $description;
    protected $service;

    public function __construct(CommandHistoryManagerInterface $history_service)
    {   
        $this->service = $history_service;
        $this->setCommandVerb('history');

        $this->signature = $this->getSignature();
        $this->description = $this->getDescription();

        parent::__construct();
    }

    public function handle(): void
    {
        $this->service->setDriver('database');
        $clear_db = $this->service->clearAll();
        $this->service->setDriver('file');
        $clear_file = $this->service->clearAll(); 
        
        if ($clear_db && $clear_file) {
            $this->info("History cleared!");
        }
        
    }
 
    protected function getSignature():string
    {
        return sprintf('%s:clear', $this->getCommandVerb());
    }

    public function getDescription():string
    {
        return sprintf('Clear saved %s', $this->getCommandVerb());
    }


}
