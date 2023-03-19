<?php

namespace RocketLauncherCore\Tests\Unit\inc\Activation\Activation;

use League\Container\Container;
use Mockery;
use RocketLauncherCore\Activation\Activation;
use RocketLauncherCore\Tests\Unit\TestCase;

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
