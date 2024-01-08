<?php

namespace LaunchpadCore\Tests\Integration\inc\Container\HasInflectorInterface\classes;

use LaunchpadCore\Container\AbstractServiceProvider;
use LaunchpadCore\Container\HasInflectorInterface;
use LaunchpadCore\Container\InflectorServiceProviderTrait;

class ServiceProvider extends AbstractServiceProvider implements HasInflectorInterface
{

    use InflectorServiceProviderTrait;

    /**
     * @inheritDoc
     */
    protected function define()
    {
        $this->register_service(Inflected::class);
        $this->register_service(InflectorAware::class);
    }

    /**
     * @inheritDoc
     */
    public function get_inflectors(): array
    {
        return [
            InflectorInterface::class => [
                'method' => 'set_inflector',
                'args' => [
                    Inflected::class
                ]
            ]
        ];
    }
}