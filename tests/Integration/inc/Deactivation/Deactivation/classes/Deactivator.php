<?php

namespace LaunchpadCore\Tests\Integration\inc\Deactivation\Deactivation\classes;

use LaunchpadCore\Deactivation\DeactivationInterface;

class Deactivator implements DeactivationInterface
{

    protected $called = false;

    /**
     * @inheritDoc
     */
    public function deactivate()
    {
        $this->called = true;
    }

    public function isCalled(): bool
    {
        return $this->called;
    }

}