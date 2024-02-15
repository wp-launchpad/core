<?php

namespace LaunchpadCore\Tests\Integration\inc\Container\PrefixAwareInterface;

use LaunchpadCore\EventManagement\EventManager;
use LaunchpadCore\EventManagement\Wrapper\SubscriberWrapper;
use LaunchpadCore\Plugin;
use LaunchpadCore\Tests\Integration\inc\Container\PrefixAwareInterface\classes\PrefixAwareClass;
use LaunchpadCore\Tests\Integration\inc\Container\PrefixAwareInterface\classes\ServiceProvider;
use LaunchpadCore\Tests\Integration\TestCase;
use League\Container\Container;

/**
 * @covers \LaunchpadCore\Container\PrefixAwareInterface::set_prefix
 */
class Test_setPrefix extends TestCase {


    /**
     * @var EventManager
     */
    protected $event_manager;

    public function testShouldDoAsExpected()
    {
        $this->event_manager = new EventManager();

        $prefix = 'test';

        $container = new Container();

        $plugin = new Plugin($container, $this->event_manager, new SubscriberWrapper($container, $prefix));
        $plugin->load([
            'prefix' => $prefix,
            'version' => '3.16'
        ], [
                ServiceProvider::class
        ]);

        $this->assertSame($prefix, $container->get(PrefixAwareClass::class)->get_prefix());
    }
}
