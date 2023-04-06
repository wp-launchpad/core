<?php

namespace LaunchpadCore\Tests\Fixtures\inc\Deactivation\Deactivation\classes;

use LaunchpadCore\Container\AbstractServiceProvider;
use LaunchpadCore\Deactivation\HasDeactivatorServiceProviderInterface;

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
