<?php

namespace LaunchpadCore\Tests\Integration\inc\Container\HasInflectorInterface;

use LaunchpadCore\EventManagement\EventManager;
use LaunchpadCore\EventManagement\Wrapper\SubscriberWrapper;
use LaunchpadCore\Plugin;
use LaunchpadCore\Tests\Integration\inc\Container\HasInflectorInterface\classes\Inflected;
use LaunchpadCore\Tests\Integration\inc\Container\HasInflectorInterface\classes\InflectorAware;
use LaunchpadCore\Tests\Integration\inc\Container\HasInflectorInterface\classes\ServiceProvider;
use LaunchpadCore\Tests\Integration\TestCase;
use League\Container\Container;

/**
 * @covers \LaunchpadCore\Container\HasInflectorInterface::get_inflectors
 */
class Test_getInflectors extends TestCase {

    /**
     * @var EventManager
     */
    protected $event_manager;

    public function testShouldDoAsExpected()
    {
        $this->event_manager = new EventManager();

        $prefix = 'test';

        $container = new Container();

        $plugin = new Plugin($container, $this->event_manager, new SubscriberWrapper($prefix));
        $plugin->load([
            'prefix' => $prefix,
            'version' => '3.16'
        ], [
            ServiceProvider::class
        ]);

        $this->assertInstanceOf(Inflected::class, $container->get(InflectorAware::class)->get_inflector());
    }
}
