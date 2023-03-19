<?php

namespace RocketLauncherCore\Deactivation;

interface HasDeactivatorServiceProviderInterface extends DeactivationServiceProviderInterface
{
    /**
     * @return string[]
     */
    public function get_deactivators(): array;
}
