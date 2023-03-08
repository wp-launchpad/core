<?php

namespace RocketLauncherCore\Activation;

interface HasActivatorServiceProviderInterface
{
    /**
     * @return string[]
     */
    public function get_activators(): array;
}
