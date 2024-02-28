<?php

namespace LaunchpadCore\EventManagement\Wrapper;

use LaunchpadCore\EventManagement\SubscriberInterface;
use Psr\Container\ContainerInterface;

class WrappedSubscriber implements SubscriberInterface
{
    /**
     * @var string
     */
    protected $object;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var array
     */
    protected $events;

    /**
     * @var string
     */
    protected $instance;

    /**
     * @param $object
     * @param array $events
     */
    public function __construct( ContainerInterface $container, string $object, array $events = [] )
    {
        $this->container = $container;
        $this->object = $object;
        $this->events = $events;
    }

    /**
     * @inheritDoc
     */
    public function get_subscribed_events(): array
    {
        return $this->events;
    }

    public function __call($name, $arguments)
    {


        if( method_exists( $this, $name ) ) {
            return $this->{$name}(...$arguments);
        }

        if( ! $this->instance) {
            $this->instance = $this->container->get($this->object);
        }

        return $this->instance->{$name}(...$arguments);
    }
}