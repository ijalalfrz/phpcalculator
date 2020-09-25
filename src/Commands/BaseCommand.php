<?php
namespace Jakmall\Recruitment\Calculator\Commands;


use Illuminate\Console\Command;

class BaseCommand extends Command
{
    protected $verb;
    protected $passiveVerb;

    protected function setCommandVerb($verb)
    {
        $this->verb = $verb;
    }

    protected function getCommandVerb():string
    {
        return $this->verb;
    }

    protected function getCommandPassiveVerb():string
    {
        return $this->passiveVerb;
    }

    protected function setCommandPassiveVerb($passiveVerb)
    {
        $this->passiveVerb = $passiveVerb;
    }


    protected function getSignature():string
    {
        return sprintf(
            '%s {numbers* : The numbers to be %s}',
            $this->getCommandVerb(),
            $this->getCommandPassiveVerb()
        );
    }

    public function getDescription():string
    {
        return sprintf('%s all given Numbers', ucfirst($this->getCommandVerb()));
    }
}