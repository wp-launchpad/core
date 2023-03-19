<?php

namespace RocketLauncherCore\Activation;

interface HasActivatorServiceProviderInterface extends ActivationServiceProviderInterface
{
    /**
     * @return string[]
     */
    public function get_activators(): array;
}
