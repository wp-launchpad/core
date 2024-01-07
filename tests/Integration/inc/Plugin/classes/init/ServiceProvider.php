<?php

namespace LaunchpadCore\Tests\Integration\inc\Plugin\classes\init;

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

    public function get_init_subscribers(): array
    {
        return [
          Subscriber::class
        ];
    }
}