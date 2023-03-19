<?php

namespace RocketLauncherCore\Tests\Fixtures\inc\Activation\Activation\classes;

use RocketLauncherCore\Container\AbstractServiceProvider;
use RocketLauncherCore\Activation\HasActivatorServiceProviderInterface;

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
