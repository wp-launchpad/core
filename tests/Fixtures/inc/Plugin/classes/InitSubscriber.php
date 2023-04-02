<?php

namespace LaunchpadCore\Tests\Fixtures\inc\Plugin\classes;

use LaunchpadCore\EventManagement\SubscriberInterface;

class InitSubscriber implements SubscriberInterface
{

    public function get_subscribed_events()
    {
        return [];
    }
}
