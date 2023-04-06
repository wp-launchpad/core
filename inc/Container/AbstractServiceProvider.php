<?php

namespace LaunchpadCore\Container;

use League\Container\Definition\DefinitionInterface;
use League\Container\ServiceProvider\AbstractServiceProvider as LeagueServiceProvider;

abstract class AbstractServiceProvider extends LeagueServiceProvider implements ServiceProviderInterface {

    /**
     * Services to load.
     *
     * @var array
     */
    protected $services_to_load = [];

    protected $loaded = false;

    /**
     * Return IDs provided by the Service Provider.
     *
     * @return string[]
     */
    public function declares(): array {
        return $this->provides;
    }

    /**
     * {@inheritdoc}
     */
    public function provides(string $alias): bool
    {
        if( ! $this->loaded ) {
            $this->loaded = true;
            $this->define();
        }

        return parent::provides($alias);
    }

    /**
     * Return IDs from front subscribers.
     *
     * @return string[]
     */
    public function get_front_subscribers(): array {
        return [];
    }

    /**
     * Return IDs from admin subscribers.
     *
     * @return string[]
     */
    public function get_admin_subscribers(): array {
        return [];
    }

    /**
     * Return IDs from common subscribers.
     *
     * @return string[]
     */
    public function get_common_subscribers(): array {
        return [];
    }

    /**
     * Return IDs from init subscribers.
     *
     * @return string[]
     */
    public function get_init_subscribers(): array {
        return [];
    }

    /**
    * @param string $class
    * @param callable(DefinitionInterface $class_defintion): void|null $method
    * @return void
    */
    public function register_service(string $class, callable $method = null, string $concrete = '') {

        $this->services_to_load[] = [
            'class' => $class,
            'concrete' => $concrete,
            'method' => $method
        ];

        if( ! in_array( $class, $this->provides, true ) ) {
            $this->provides[] = $class;
        }
    }

    /**
     * Define classes.
     *
     * @return mixed
     */
   abstract protected function define();

    public function register()
    {
        foreach ($this->services_to_load as $service) {
            $class = '' === $service['concrete'] ? $service['class'] : $service['concrete'];
            $class_registration = $this->getContainer()->add($service['class'], $class);

            if( ! $service['method'] ) {
                continue;
            }

            $service['method']($class_registration);
        }
    }
}
