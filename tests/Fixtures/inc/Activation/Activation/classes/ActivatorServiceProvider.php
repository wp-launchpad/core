<?php

namespace LaunchpadCore\Tests\Fixtures\inc\Activation\Activation\classes;

use LaunchpadCore\Container\AbstractServiceProvider;
use LaunchpadCore\Activation\HasActivatorServiceProviderInterface;

class ActivatorServiceProvider extends AbstractServiceProvider implements HasActivatorServiceProviderInterface
{

    protected function define()
    {

    }

    public function get_activators(): array
    {
        return [];
    }
}
