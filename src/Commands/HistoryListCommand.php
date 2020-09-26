<?php
namespace Jakmall\Recruitment\Calculator\Commands;


use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Jakmall\Recruitment\Calculator\Interfaces\OperatorInterface;
use Jakmall\Recruitment\Calculator\Commands\BaseCommand;
use Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;
use Jakmall\Recruitment\Calculator\Models\HistoryModel;


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
        $this->model = new HistoryModel();

        parent::__construct();
    }

    public function handle(): void
    {
        $input = $this->getInput();
        $this->service->setDriver($input['driver']);

        if ($input['filter']) {

            $filter['command'] =  $input['filter'];
            $data = $this->service->filter($filter);
        } else {
            $data = $this->service->findAll();
        }


        $header = array_keys($this->model->getColumn());
        array_shift($header);
        $new_header = array_unshift($header, "no");

        $arr_data = [];
        $no = 1;
        if ($data) {
            foreach($data as $d)
            {               
                array_push($arr_data, [$no, $d->command, $d->description, $d->result, $d->output, $d->time]);
                
                $no++;
            }
            $this->table($header, $arr_data);
        }else{
            $this->info("History is empty.");
        }
        
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
