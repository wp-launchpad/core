<?php

namespace LaunchpadCore\Activation;

use Psr\Container\ContainerInterface;

class Activation
{

    protected static $providers = [];

    protected static $params = [];

    protected static $container;

    public static function set_providers(array $providers) {
        self::$providers = $providers;
    }

    public static function set_params(array $params) {
        self::$params = $params;
    }

    public static function set_container(ContainerInterface $container) {
        self::$container = $container;
    }

    /**
     * Performs these actions during the plugin activation
     *
     * @return void
     */
    public static function activate_plugin() {

        foreach (self::$params as $key => $value) {
            self::$container->add( $key, $value);
        }

        $providers = array_filter(self::$providers, function ($provider) {
            if(is_string($provider)) {
                $provider = new $provider();
            }

           if(! $provider instanceof ActivationServiceProviderInterface) {
               return false;
           }

           return $provider;
        });

        foreach ($providers as $provider) {
            self::$container->addServiceProvider($provider);
        }

        foreach ($providers as $provider) {
            if(! $provider instanceof HasActivatorServiceProviderInterface) {
                continue;
            }

            foreach ( $provider->get_activators() as $activator ) {
                $activator_instance = self::$container->get( $activator );
                if(! $activator_instance instanceof ActivationInterface) {
                    continue;
                }
                $activator_instance->activate();
            }
        }
    }
}
