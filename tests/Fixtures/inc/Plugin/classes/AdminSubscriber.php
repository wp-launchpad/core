<?php
namespace RocketLauncherCore\Tests\Fixtures\inc\Plugin\classes;

use RocketLauncherCore\EventManagement\SubscriberInterface;

class AdminSubscriber implements SubscriberInterface {

    public function get_subscribed_events()
    {
        return [];
    }
}
