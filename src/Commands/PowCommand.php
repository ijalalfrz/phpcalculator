<?php
namespace Jakmall\Recruitment\Calculator\Commands;


use Illuminate\Console\Command;
use Jakmall\Recruitment\Calculator\Interfaces\ComplexOperatorInterface;
use Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;
use Jakmall\Recruitment\Calculator\Commands\BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Jakmall\Recruitment\Calculator\Models\HistoryModel;

class PowCommand extends BaseCommand implements ComplexOperatorInterface
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

        $this->setCommandVerb('pow');
        $this->setCommandPassiveVerb('exponent');

        $this->signature = $this->getSignature();
        $this->description = $this->getDescription();

        parent::__construct();
    }

    protected function configure()
    {
        $this
            // ...
            ->addArgument('base', InputArgument::REQUIRED, 'The base number')
            ->addArgument('exp', InputArgument::REQUIRED, 'The exponent number')
        ;
    }

    protected function getSignature():string
    {
        return $this->getCommandVerb();
    }


    public function handle(): void
    {
        $input = $this->getInput();
        $number = $input[0];
        $exp = $input[1];
        $description = $this->generateCalculationDescription($input);
        $result = $this->calculate($number, $exp);
        
        # add command to db

        $result_str = $description.' = '.$result;
        $model = new HistoryModel();
        $model->command = \ucfirst($this->getCommandVerb());
        $model->description = $description;
        $model->result = $result;
        $model->output = $result_str;

        $this->service->setDriver('database');
        $id = $this->service->store($model);
        
        $model->id = $id;
        $this->service->setDriver('file');
        $this->service->store($model);

        $this->comment(sprintf('%s = %s', $description, $result));
    }

    public function getDescription():string
    {
        return sprintf('%s all given Number', ucfirst($this->getCommandPassiveVerb()));
    }

    protected function getInput(): array
    {
        $base = $this->argument('base');
        $exp = $this->argument('exp'); 

        return [$base, $exp];
    }

    public function generateCalculationDescription(array $numbers): string
    {
        $operator = $this->getOperator();
        $glue = sprintf(' %s ', $operator);

        return implode($glue, $numbers);
    }
    

    public function getOperator(): string
    {
        return '^';
    }

    
    /**
     * @param int|float $number
     * @param $args
     *
     * @return int|float
     */
    public function calculate($number, $args)
    {
        return pow($number, $args);
    }
}
