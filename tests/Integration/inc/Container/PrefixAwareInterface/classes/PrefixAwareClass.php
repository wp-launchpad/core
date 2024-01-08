<?php

namespace LaunchpadCore\Tests\Integration\inc\Container\PrefixAwareInterface\classes;

use LaunchpadCore\Container\PrefixAware;
use LaunchpadCore\Container\PrefixAwareInterface;

class PrefixAwareClass implements PrefixAwareInterface
{
    use PrefixAware;

    public function get_prefix()
    {
        return $this->prefix;
    }
}