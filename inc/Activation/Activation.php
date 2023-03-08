<?php

namespace RocketLauncherCore\Activation;

class Activation
{

    protected static $providers = [];

    public static function set_providers(array $providers) {
        self::$providers = $providers;
    }

    /**
     * Performs these actions during the plugin activation
     *
     * @return void
     */
    public static function activate_plugin() {

    }
}
