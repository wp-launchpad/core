<?php

namespace RocketLauncherCore\Tests\Fixtures\inc\Plugin\classes;

use RocketLauncherCore\EventManagement\SubscriberInterface;

class FrontSubscriber implements SubscriberInterface
{

    public function get_subscribed_events()
    {
        return [];
    }
}
