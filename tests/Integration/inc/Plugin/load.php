<?php

namespace LaunchpadCore\Tests\Integration\inc\Plugin;

use LaunchpadCore\EventManagement\EventManager;
use LaunchpadCore\EventManagement\Wrapper\SubscriberWrapper;
use LaunchpadCore\Plugin;
use LaunchpadCore\Tests\Integration\TestCase;
use League\Container\Container;

/**
 * @covers \LaunchpadCore\Plugin::load
 */
class Test_load extends TestCase {

    /**
     * @var EventManager
     */
    protected $event_manager;

    public function testShouldDoAsExpected()
    {
        $this->event_manager = new EventManager();

        $event_setup = [
            'common_hook',
            'front_hook',
            'init_hook'
        ];

        $event_not_setup = [
            'admin_hook'
        ];

        $events =array_merge($event_setup, $event_not_setup);

        foreach ($events as $event) {
            $this->assertFalse($this->event_manager->has_callback($event), $event);
        }

        $plugin = new Plugin(new Container(), $this->event_manager, new SubscriberWrapper('test'));
        $plugin->load([
            'prefix' => 'test',
            'version' => '3.16'
        ], [
            \LaunchpadCore\Tests\Integration\inc\Plugin\classes\common\ServiceProvider::class,
            \LaunchpadCore\Tests\Integration\inc\Plugin\classes\admin\ServiceProvider::class,
            \LaunchpadCore\Tests\Integration\inc\Plugin\classes\front\ServiceProvider::class,
            \LaunchpadCore\Tests\Integration\inc\Plugin\classes\init\ServiceProvider::class,
        ]);

        foreach ($event_setup as $event) {
            $this->assertTrue($this->event_manager->has_callback($event), $event);
        }

        foreach ($event_not_setup as $event) {
            $this->assertFalse($this->event_manager->has_callback($event), $event);
        }

    }
}
