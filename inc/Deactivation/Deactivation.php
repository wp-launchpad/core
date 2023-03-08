<?php

namespace RocketLauncherCore\Deactivation;

use League\Container\Container;
use RocketLauncherCore\Activation\ActivationServiceProviderInterface;

class Deactivation
{

    protected static $providers = [];

    protected static $params = [];

    public static function set_providers(array $providers) {
        self::$providers = $providers;
    }

    public static function set_params(array $params) {
        self::$params = $params;
    }

    /**
     * Performs these actions during the plugin deactivation
     *
     * @return void
     */
    public static function deactivate_plugin() {
        $container = new Container();

        foreach (self::$params as $key => $value) {
            $container->add( $key, $value);
        }

        $providers = array_filter(self::$providers, function ($provider) {
            $instance = new $provider();
            if(! $instance instanceof DeactivationServiceProviderInterface) {
                return false;
            }
            return $instance;
        });

        foreach ($providers as $provider) {
            $container->addServiceProvider($provider);
        }

        foreach ($providers as $provider) {
            if(! $provider instanceof HasDeactivatorServiceProviderInterface) {
                continue;
            }

            foreach ( $provider->get_deactivators() as $activator ) {
                $activator_instance = $container->get( $activator );
                if(! $activator_instance instanceof DeactivationInterface) {
                    continue;
                }
                $activator_instance->deactivate();
            }
        }
    }
}
