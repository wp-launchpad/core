<?php

namespace LaunchpadCore\Tests\Integration\inc\Container\HasInflectorInterface\classes;

class InflectorAware implements InflectorInterface
{
    use InflectorTrait;

    public function get_inflector()
    {
        return $this->inflector;
    }
}