<?php

namespace RocketLauncherCore\Tests\Fixtures\inc\Deactivation\Deactivation\classes;

use RocketLauncherCore\Container\AbstractServiceProvider;
use RocketLauncherCore\Deactivation\HasDeactivatorServiceProviderInterface;

class DeactivatorServiceProvider extends AbstractServiceProvider implements HasDeactivatorServiceProviderInterface
{

    protected function define()
    {

    }

    public function get_deactivators(): array
    {
        return [];
    }
}
