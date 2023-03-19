<?php
namespace RocketLauncherCore\Tests\Unit\inc\Plugin;

use League\Container\Container;
use Mockery;
use RocketLauncherCore\EventManagement\EventManager;
use RocketLauncherCore\Plugin;
use RocketLauncherCore\Tests\Unit\TestCase;
use Brain\Monkey\Functions;
class Test_Load extends TestCase {
    protected $container;
    protected $plugin;
    protected $event_manager;
    protected function setUp(): void
    {
        parent::setUp();
        $this->container = Mockery::mock(Container::class);
        $this->event_manager = Mockery::mock(EventManager::class);
        $this->plugin = new Plugin($this->container, $this->event_manager);
    }

    /**
     * @dataProvider configTestData
     */
    public function testShouldDoAsExpected($configs, $expected) {

        $providers = [];

        foreach ($configs['providers_callback'] as $provider) {
            $provider['provider']->allows($provider['callbacks']);
            $provider['provider']->allows()->get_init_subscribers()->andReturn([]);
            $providers[] = $provider['provider'];
        }

        foreach ($configs['subscribers'] as $subscriber) {
            $this->container->allows()->get(get_class($subscriber))->andReturn($subscriber);
        }

        foreach ($expected['share'] as $key => $value) {
            $this->container->expects()->share($key, $value);
        }
        $this->container->allows()->get('prefix')->andReturn('prefix');
        $this->container->expects()->share('event_manager', $this->event_manager);
        $this->container->allows()->get('event_manager')->andReturn($this->event_manager);

        foreach ($expected['subscribers'] as $subscriber) {
            $this->event_manager->expects()->add_subscriber($subscriber);
        }

        foreach ($expected['providers'] as $provider) {
            $this->container->expects()->addServiceProvider($provider);
        }

        Functions\when('is_admin')->justReturn($configs['is_admin']);
        $this->plugin->load($configs['params'], $providers);
    }
}
