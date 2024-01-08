<?php

namespace LaunchpadCore\Tests\Integration\inc\Container\HasInflectorInterface\classes;

trait InflectorTrait
{
    protected $inflector;

    public function set_inflector(Inflected $inflector): void {
        $this->inflector = $inflector;
    }
}