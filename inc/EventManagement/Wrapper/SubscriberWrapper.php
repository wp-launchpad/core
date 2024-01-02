<?php

namespace LaunchpadCore\EventManagement\Wrapper;

use LaunchpadCore\EventManagement\SubscriberInterface;

class SubscriberWrapper
{
    protected $cache_folder = '';

    protected $use_cache = true;

    public function wrap($object): SubscriberInterface
    {

    }
}