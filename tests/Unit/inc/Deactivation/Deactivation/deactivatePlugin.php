<?php

namespace LaunchpadCore\Tests\Unit\inc\Deactivation\Deactivation;

use League\Container\Container;
use Mockery;
use LaunchpadCore\Deactivation\Deactivation;
use LaunchpadCore\Tests\Unit\TestCase;

class Test_DeactivatePlugin extends TestCase
{
    protected $container;

    protected function setUp(): void
    {
        parent::setUp();
        $this->container = Mockery::mock(Container::class);
    }

    /**
     * @dataProvider configTestData
     */
    public function testDoAsExpected($config, $expected) {
        Deactivation::set_container($this->container);
        Deactivation::set_params($config['params']);
        $providers = [];
        foreach ($config['providers'] as $provider) {
            $providers[]= $provider['provider'];
            $provider['provider']->allows($provider['callbacks']);
        }

        Deactivation::set_providers($providers);

        $this->container->allows()->get('prefix')->andReturn('prefix');

        foreach ($config['deactivators'] as $deactivator) {
            $this->container->allows()->get(get_class($deactivator))->andReturn($deactivator);
        }

        foreach ($expected['providers'] as $provider) {
            $this->container->expects()->addServiceProvider($provider);

        }

        foreach ($expected['deactivators'] as $deactivator) {
            $deactivator->expects()->deactivate();
        }

        Deactivation::deactivate_plugin();
    }
}
