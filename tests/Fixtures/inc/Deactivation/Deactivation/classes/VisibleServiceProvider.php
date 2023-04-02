<?php

namespace LaunchpadCore\Tests\Fixtures\inc\Deactivation\Deactivation\classes;

use LaunchpadCore\Container\AbstractServiceProvider;
use LaunchpadCore\Deactivation\DeactivationServiceProviderInterface;

class VisibleServiceProvider extends AbstractServiceProvider implements DeactivationServiceProviderInterface
{

    protected function define()
    {

    }
}
