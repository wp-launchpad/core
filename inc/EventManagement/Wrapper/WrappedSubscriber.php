<?php

namespace LaunchpadCore\EventManagement\Wrapper;

use LaunchpadCore\EventManagement\SubscriberInterface;

class WrappedSubscriber implements SubscriberInterface
{

    protected $object;

    protected $events;

    /**
     * @param $object
     */
    public function __construct($object, array $events = [])
    {
        $this->object = $object;
        $this->events = $events;
    }

    /**
     * @inheritDoc
     */
    public function get_subscribed_events()
    {
        return $this->events;
    }

    public function __call($name, $arguments)
    {
        if( ! method_exists( $this, $name ) ) {
            return $this->object->{$name}(...$arguments);
        }

        return $this->{$name}(...$arguments);
    }
}