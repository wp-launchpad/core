<?php

namespace LaunchpadCore\Tests\Integration\inc\Deactivation\Deactivation\classes;

use LaunchpadCore\Container\AbstractServiceProvider;
use LaunchpadCore\Deactivation\HasDeactivatorServiceProviderInterface;

class ServiceProvider extends AbstractServiceProvider implements HasDeactivatorServiceProviderInterface
{

    protected $provides = [
        Deactivator::class
    ];

    /**
     * @inheritDoc
     */
    protected function define()
    {
    }

    public function register()
    {
       $this->getLeagueContainer()->share(Deactivator::class);
    }

    /**
     * @inheritDoc
     */
    public function get_deactivators(): array
    {
       return [
           Deactivator::class
       ];
    }
}