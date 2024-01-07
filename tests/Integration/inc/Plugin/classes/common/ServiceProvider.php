<?php

namespace LaunchpadCore\Tests\Integration\inc\Plugin\classes\common;

use LaunchpadCore\Container\AbstractServiceProvider;

class ServiceProvider extends AbstractServiceProvider
{

    /**
     * @inheritDoc
     */
    protected function define()
    {
        $this->register_service(Subscriber::class);
    }

    public function get_common_subscribers(): array
    {
        return [
          Subscriber::class
        ];
    }
}