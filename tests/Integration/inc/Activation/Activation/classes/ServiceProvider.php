<?php

namespace LaunchpadCore\Tests\Integration\inc\Activation\Activation\classes;

use LaunchpadCore\Activation\HasActivatorServiceProviderInterface;
use LaunchpadCore\Container\AbstractServiceProvider;

class ServiceProvider extends AbstractServiceProvider implements HasActivatorServiceProviderInterface
{

    protected $provides = [
        Activator::class
    ];

    public function register()
    {
        $this->getLeagueContainer()->share(Activator::class);
    }

    /**
     * @inheritDoc
     */
    public function get_activators(): array
    {
        return [
            Activator::class,
        ];
    }

    protected function define() {}
}