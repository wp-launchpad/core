<?php

namespace LaunchpadCore\Tests\Integration\inc\Plugin\classes\admin;

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

    public function get_admin_subscribers(): array
    {
        return [
            Subscriber::class
        ];
    }
}