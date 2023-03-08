<?php

namespace RocketLauncherCore\Activation;

interface ActivationInterface
{

    /**
     * Executes this method on plugin activation
     *
     * @return void
     */
    public function activate();
}
