<?php

namespace Jakmall\Recruitment\Calculator\Interfaces;
    
interface OperatorInterface
{
    public function generateCalculationDescription(array $numbers);
    public function getOperator();
    public function calculateAll(array $numbers);
    public function calculate($number1, $number2);
}
