<?php

namespace LaunchpadCore\Tests\Integration\inc\Deactivation\Deactivation;

use LaunchpadCore\Deactivation\Deactivation;
use LaunchpadCore\Tests\Integration\inc\Deactivation\Deactivation\classes\Deactivator;
use LaunchpadCore\Tests\Integration\inc\Deactivation\Deactivation\classes\ServiceProvider;
use LaunchpadCore\Tests\Integration\TestCase;
use League\Container\Container;

/**
 * @covers \LaunchpadCore\Deactivation\DeactivationInterface::deactivate
 */
class Test_deactivatePlugin extends TestCase {

    public function testShouldDoAsExpected()
    {
        $this->container = new Container();

        $prefix = 'test';

        $params = [
            'prefix' => $prefix,
            'version' => '3.16'
        ];

        Deactivation::set_container($this->container);
        Deactivation::set_params($params);
        Deactivation::set_providers([
            ServiceProvider::class
        ]);

        Deactivation::deactivate_plugin();

        $activator = $this->container->get(Deactivator::class);
        $this->assertTrue($activator->isCalled());
    }
}
