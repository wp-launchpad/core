<?php

namespace RocketLauncherCore\Tests\Fixtures\inc\Plugin\classes;

use RocketLauncherCore\EventManagement\SubscriberInterface;

class CommonSubscriber implements SubscriberInterface
{

    public function get_subscribed_events()
    {
        return [];
    }
}
