<?php
namespace Jakmall\Recruitment\Calculator\Commands;


use Illuminate\Console\Command;
use Jakmall\Recruitment\Calculator\Interfaces\OperatorInterface;
use Jakmall\Recruitment\Calculator\Commands\BaseCommand;

class SubstractCommand extends BaseCommand implements OperatorInterface
{
    /**
     * @var string
     */
    protected $signature;

    /**
     * @var string
     */
    protected $description;

    public function __construct()
    {
        $this->setCommandVerb('substract');
        $this->setCommandPassiveVerb('substracted');

        $this->signature = $this->getSignature();
        $this->description = $this->getDescription();

        parent::__construct();
    }

    public function handle(): void
    {
        $numbers = $this->getInput();
        $description = $this->generateCalculationDescription($numbers);
        $result = $this->calculateAll($numbers);

        $this->comment(sprintf('%s = %s', $description, $result));
    }

    protected function getInput(): array
    {
        return $this->argument('numbers');
    }

    public function generateCalculationDescription(array $numbers): string
    {
        $operator = $this->getOperator();
        $glue = sprintf(' %s ', $operator);

        return implode($glue, $numbers);
    }

    public function getOperator(): string
    {
        return '-';
    }

    /**
     * @param array $numbers
     *
     * @return float|int
     */
    public function calculateAll(array $numbers)
    {
        $number = array_pop($numbers);

        if (count($numbers) <= 0) {
            return $number;
        }

        return $this->calculate($this->calculateAll($numbers), $number);
    }

    /**
     * @param int|float $number1
     * @param int|float $number2
     *
     * @return int|float
     */
    public function calculate($number1, $number2)
    {
        return $number1 - $number2;
    }
}
