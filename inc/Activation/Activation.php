<?php

namespace LaunchpadCore\Activation;

use LaunchpadCore\Container\AbstractServiceProvider;
use LaunchpadCore\Container\HasInflectorInterface;
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

        $container = self::$container;

        foreach (self::$params as $key => $value) {
            self::$container->add( $key, $value);
        }

        $providers = array_filter(self::$providers, function ($provider) {
            if(is_string($provider)) {
                $provider = new $provider();
            }

           if(! $provider instanceof ActivationServiceProviderInterface && count($provider->get_inflectors()) === 0) {
               return false;
           }

           return $provider;
        });

        /**
         * Activation providers.
         *
         * @param AbstractServiceProvider[] $providers Providers.
         * @return AbstractServiceProvider[]
         */
        $providers = apply_filters("{$container->get('prefix')}deactivate_providers", $providers);

        $providers = array_map(function ($provider) {
            if(is_string($provider)) {
                return new $provider();
            }
            return $provider;
        }, $providers);

        foreach ($providers as $provider) {
            self::$container->addServiceProvider($provider);
        }

        foreach ( $providers as $service_provider ) {
            if( ! $service_provider instanceof HasInflectorInterface ) {
                continue;
            }
            $service_provider->register_inflectors();
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
