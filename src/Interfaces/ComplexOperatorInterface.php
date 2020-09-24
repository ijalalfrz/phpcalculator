<?php

namespace Jakmall\Recruitment\Calculator\Interfaces;
    
interface ComplexOperatorInterface
{
    public function generateCalculationDescription(array $numbers);
    public function getOperator();
    public function calculate($number1, $number2);
}
