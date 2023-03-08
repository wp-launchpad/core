<?php

namespace RocketLauncherCore\Deactivation;

interface HasDeactivatorServiceProviderInterface
{
    /**
     * @return string[]
     */
    public function get_deactivators(): array;
}
