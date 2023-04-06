<?php

namespace LaunchpadCore\Deactivation;

use Psr\Container\ContainerInterface;

class Deactivation
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
     * Performs these actions during the plugin deactivation
     *
     * @return void
     */
    public static function deactivate_plugin() {

        foreach (self::$params as $key => $value) {
            self::$container->add( $key, $value);
        }

        $providers = array_filter(self::$providers, function ($provider) {
            if(is_string($provider)) {
                $provider = new $provider();
            }

            if(! $provider instanceof DeactivationServiceProviderInterface) {
                return false;
            }

            return $provider;
        });

        foreach ($providers as $provider) {
            self::$container->addServiceProvider($provider);
        }

        foreach ($providers as $provider) {
            if(! $provider instanceof HasDeactivatorServiceProviderInterface) {
                continue;
            }

            foreach ( $provider->get_deactivators() as $deactivator ) {
                $deactivator_instance = self::$container->get( $deactivator );
                if(! $deactivator_instance instanceof DeactivationInterface) {
                    continue;
                }
                $deactivator_instance->deactivate();
            }
        }
    }
}
