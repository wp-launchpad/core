<?php

namespace LaunchpadCore\Tests\Integration\inc\Container\PrefixAwareInterface\classes;

use LaunchpadCore\Container\AbstractServiceProvider;

class ServiceProvider extends AbstractServiceProvider
{

    /**
     * @inheritDoc
     */
    protected function define()
    {
        $this->register_service(PrefixAwareClass::class);
    }
}