<?php

namespace RocketLauncherCore\Deactivation;

interface DeactivationInterface
{
    /**
     * Executes this method on plugin deactivation
     *
     * @return void
     */
    public function deactivate();
}
