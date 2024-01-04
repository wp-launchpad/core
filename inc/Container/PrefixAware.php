<?php

namespace LaunchpadCore\Container;

trait PrefixAware
{
    /**
     * @var string
     */
    protected $prefix;

    public function set_prefix(string $prefix): void {
        $this->prefix = $prefix;
    }
}