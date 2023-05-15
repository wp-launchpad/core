<?php

namespace LaunchpadCore\Tests\Unit\inc\Activation\Activation;

use League\Container\Container;
use Mockery;
use LaunchpadCore\Activation\Activation;
use LaunchpadCore\Tests\Unit\TestCase;

class Test_ActivatePlugin extends TestCase
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
        Activation::set_container($this->container);
        Activation::set_params($config['params']);
        $providers = [];
        foreach ($config['providers'] as $provider) {
            $providers[]= $provider['provider'];
            $provider['provider']->allows($provider['callbacks']);
        }

        Activation::set_providers($providers);

        $this->container->allows()->get('prefix')->andReturn('prefix');

        foreach ($config['activators'] as $activator) {
            $this->container->allows()->get(get_class($activator))->andReturn($activator);
        }

        foreach ($expected['providers'] as $provider) {
            $this->container->expects()->addServiceProvider($provider);

        }

        foreach ($expected['activators'] as $activator) {
            $activator->expects()->activate();
        }

        Activation::activate_plugin();
    }
}
