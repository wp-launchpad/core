<?php

namespace LaunchpadCore\Tests\Integration\inc\Activation\Activation\classes;

use LaunchpadCore\Activation\ActivationInterface;

class Activator implements ActivationInterface
{

    protected $called = false;

    /**
     * @inheritDoc
     */
    public function activate()
    {
        $this->called = true;
    }

    public function isCalled(): bool
    {
        return $this->called;
    }
}