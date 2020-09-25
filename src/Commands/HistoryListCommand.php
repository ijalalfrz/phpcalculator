<?php
namespace Jakmall\Recruitment\Calculator\Commands;


use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Jakmall\Recruitment\Calculator\Interfaces\OperatorInterface;
use Jakmall\Recruitment\Calculator\Commands\BaseCommand;
use Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;


class HistoryListCommand extends BaseCommand
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
        $input = $this->getInput();
        $this->service->setDriver($input['driver']);
        // $description = $this->generateCalculationDescription($numbers);
        // $result = $this->calculateAll($numbers);

        // $this->comment(sprintf('%s = %s', $description, $result));
    }

    protected function getInput(): array
    {   
        return [
            'driver' => $this->option('driver'),
            'filter' => $filter_command = $this->argument('commands')
        ];
    }

    protected function configure()
    {
        $this->addOption('--driver', '-D', InputOption::VALUE_OPTIONAL, 'Driver for storage connection','database');
        $this->addArgument('commands', InputArgument::IS_ARRAY, 'Filter history by commands');
    }
  
    protected function getSignature():string
    {
        return sprintf('%s:list', $this->getCommandVerb());
    }

    public function getDescription():string
    {
        return sprintf('Show calculator %s', $this->getCommandVerb());
    }


}
