<?php

namespace LaunchpadCore\Tests\Integration\inc\Container\HasInflectorInterface\classes;

interface InflectorInterface
{
    public function set_inflector(Inflected $inflector): void;
}