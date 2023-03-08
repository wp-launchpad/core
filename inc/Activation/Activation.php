<?php

namespace RocketLauncherCore\Activation;

use League\Container\Container;

class Activation
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
     * Performs these actions during the plugin activation
     *
     * @return void
     */
    public static function activate_plugin() {
        $container = new Container();

        foreach (self::$params as $key => $value) {
            $container->add( $key, $value);
        }

        $providers = array_filter(self::$providers, function ($provider) {
           $instance = new $provider();
           if(! $instance instanceof ActivationServiceProviderInterface) {
               return false;
           }
           return $instance;
        });

        foreach ($providers as $provider) {
            $container->addServiceProvider($provider);
        }

        foreach ($providers as $provider) {
            if(! $provider instanceof HasActivatorServiceProviderInterface) {
                continue;
            }

            foreach ( $provider->get_activators() as $activator ) {
                $activator_instance = $container->get( $activator );
                if(! $activator_instance instanceof ActivationInterface) {
                    continue;
                }
                $activator_instance->activate();
            }
        }
    }
}
