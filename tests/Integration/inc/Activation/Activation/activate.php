<?php

namespace LaunchpadCore\Tests\Integration\inc\Activation\Activation;

use LaunchpadCore\Activation\Activation;
use LaunchpadCore\Tests\Integration\inc\Activation\Activation\classes\Activator;
use LaunchpadCore\Tests\Integration\inc\Activation\Activation\classes\ServiceProvider;
use LaunchpadCore\Tests\Integration\TestCase;
use League\Container\Container;

/**
 * @covers \LaunchpadCore\Activation\Activation::activate_plugin
 */
class Test_activate extends TestCase {

    protected $container;

    public function testShouldDoAsExpected()
    {
        $this->container = new Container();

        $prefix = 'test';

        $params = [
            'prefix' => $prefix,
            'version' => '3.16'
        ];

        Activation::set_container($this->container);
        Activation::set_params($params);
        Activation::set_providers([
            ServiceProvider::class
        ]);

        Activation::activate_plugin();

        $activator = $this->container->get(Activator::class);
        $this->assertTrue($activator->isCalled());
    }
}
