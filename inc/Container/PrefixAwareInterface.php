<?php

namespace LaunchpadCore\Container;

interface PrefixAwareInterface
{
    public function set_prefix(string $prefix): void;
}