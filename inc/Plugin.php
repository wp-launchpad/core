<?php

namespace LaunchpadCore;

use LaunchpadCore\Container\AbstractServiceProvider;
use LaunchpadCore\Container\HasInflectorInterface;
use LaunchpadCore\EventManagement\Wrapper\SubscriberWrapper;
use Psr\Container\ContainerInterface;
use LaunchpadCore\Container\IsOptimizableServiceProvider;
use LaunchpadCore\Container\ServiceProviderInterface;
use LaunchpadCore\EventManagement\EventManager;
use LaunchpadCore\EventManagement\SubscriberInterface;

class Plugin
{
    /**
     * Instance of Container class.
     *
     * @var ContainerInterface instance
     */
    private $container;

    /**
     * Instance of the event manager.
     *
     * @var EventManager
     */
    private $event_manager;

    /**
     * @var SubscriberWrapper
     */
    private $subscriber_wrapper;

    /**
     * Creates an instance of the Plugin.
     *
     * @param ContainerInterface $container     Instance of the container.
     */
    public function __construct( ContainerInterface $container, EventManager $event_manager, SubscriberWrapper $subscriber_wrapper ) {
        $this->container = $container;
        $this->event_manager = $event_manager;
        $this->subscriber_wrapper = $subscriber_wrapper;
    }

    /**
     * Returns the Rocket container instance.
     *
     * @return ContainerInterface
     */
    public function get_container() {
        return $this->container;
    }

    /**
     * Loads the plugin into WordPress.
     *
     * @param array<string,mixed> $params Parameters to pass to the container.
     *
     * @return void
     *
     */
    public function load(array $params, array $providers = []) {

        foreach ($params as $key => $value) {
            $this->container->share( $key, $value );
        }

        /**
         * Runs before the plugin is loaded.
         */
        do_action("{$this->container->get('prefix')}before_load");

        add_filter( "{$this->container->get('prefix')}container", [ $this, 'get_container' ] );

        $this->container->share( 'event_manager', $this->event_manager );

        $providers = array_map(function ($class) {
            if(is_string($class)) {
                return new $class;
            }

            return $class;
        }, $providers);

        $providers = $this->optimize_service_providers( $providers );

        foreach ( $providers as $service_provider ) {
            $this->container->addServiceProvider( $service_provider );
        }

        foreach ( $providers as $service_provider ) {
            if( ! $service_provider instanceof HasInflectorInterface ) {
                continue;
            }
            $service_provider->register_inflectors();
        }

        foreach ($providers as $service_provider ) {
            $this->load_init_subscribers( $service_provider );
        }

        foreach ($providers as $service_provider ) {
            $this->load_subscribers( $service_provider );
        }

        /**
         * Runs after the plugin is loaded.
         */
        do_action("{$this->container->get('prefix')}after_load");
    }

    /**
     * Optimize service providers to keep only the ones we need to load.
     *
     * @param ServiceProviderInterface[] $providers Providers given to the plugin.
     *
     * @return ServiceProviderInterface[]
     */
    protected function optimize_service_providers(array $providers): array {
        $optimized_providers = [];

        foreach ($providers as $provider) {
            if( ! $provider instanceof IsOptimizableServiceProvider ) {
                $optimized_providers[] = $provider;
                continue;
            }
            $subscribers = array_merge($provider->get_common_subscribers(), $provider->get_init_subscribers(), is_admin() ? $provider->get_admin_subscribers() : $provider->get_front_subscribers());

            /**
             * Plugin Subscribers from a provider.
             *
             * @param SubscriberInterface[] $subscribers Subscribers.
             * @param AbstractServiceProvider $provider Provider.
             *
             * @return SubscriberInterface[]
             */
            $subscribers = apply_filters("{$this->container->get('prefix')}load_provider_subscribers", $subscribers, $provider);

            if( count( $subscribers ) === 0 ) {
                continue;
            }

            $optimized_providers[] = $provider;
        }

        return $optimized_providers;
    }

    /**
     * Load list of event subscribers from service provider.
     *
     * @param ServiceProviderInterface $service_provider_instance Instance of service provider.
     *
     * @return void
     */
    private function load_init_subscribers( ServiceProviderInterface $service_provider_instance ) {
        $subscribers = $service_provider_instance->get_init_subscribers();

        /**
         * Plugin Init Subscribers.
         *
         * @param SubscriberInterface[] $subscribers Subscribers.
         *
         * @return SubscriberInterface[]
         */
        $subscribers = apply_filters("{$this->container->get('prefix')}load_init_subscribers", $subscribers);

        if ( empty( $subscribers ) ) {
            return;
        }

        foreach ( $subscribers as $subscriber ) {
            $subscriber_object = $this->container->get( $subscriber );
            if ( ! $subscriber_object instanceof SubscriberInterface ) {
                $subscriber_object = $this->subscriber_wrapper->wrap($subscriber_object);
            }

            $this->event_manager->add_subscriber( $subscriber_object );
        }
    }

    /**
     * Load list of event subscribers from service provider.
     *
     * @param ServiceProviderInterface $service_provider_instance Instance of service provider.
     *
     * @return void
     */
    private function load_subscribers( ServiceProviderInterface $service_provider_instance ) {

        $subscribers = $service_provider_instance->get_common_subscribers();

        if( ! is_admin() ) {
            $subscribers = array_merge($subscribers, $service_provider_instance->get_front_subscribers());
        } else {
            $subscribers = array_merge($subscribers, $service_provider_instance->get_admin_subscribers());
        }

        /**
         * Plugin Subscribers.
         *
         * @param SubscriberInterface[] $subscribers Subscribers.
         * @param AbstractServiceProvider $service_provider_instance Provider.
         *
         * @return SubscriberInterface[]
         */
        $subscribers = apply_filters( "{$this->container->get('prefix')}load_subscribers", $subscribers, $service_provider_instance );

        if ( empty( $subscribers ) ) {
            return;
        }

        foreach ( $subscribers as $subscriber ) {
            $subscriber_object = $this->container->get( $subscriber );
            if ( ! $subscriber_object instanceof SubscriberInterface ) {
                $subscriber_object = $this->subscriber_wrapper->wrap($subscriber_object);
            }

            $this->event_manager->add_subscriber( $subscriber_object );
        }
    }
}
