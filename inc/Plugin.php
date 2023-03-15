<?php

namespace RocketLauncherCore;

use RocketLauncherCore\Container\ServiceProviderInterface;
use League\Container\Container;
use RocketLauncherCore\EventManagement\EventManager;
use RocketLauncherCore\EventManagement\SubscriberInterface;

class Plugin
{
    /**
     * Instance of Container class.
     *
     * @var Container instance
     */
    private $container;

    /**
     * Instance of the event manager.
     *
     * @var EventManager
     */
    private $event_manager;

    /**
     * Creates an instance of the Plugin.
     *
     * @param Container $container     Instance of the container.
     */
    public function __construct( Container $container ) {
        $this->container = $container;
    }

    /**
     * Returns the Rocket container instance.
     *
     * @return Container
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
        $this->event_manager = new EventManager();

        foreach ($params as $key => $value) {
            $this->container->share( $key, $value );
        }

        add_filter( "{$this->container->get('prefix')}container", [ $this, 'get_container' ] );

        $this->container->share( 'event_manager', $this->event_manager );
        foreach ( $providers as $service_provider ) {
            $this->container->addServiceProvider( $service_provider );
        }

        foreach ($providers as $service_provider ) {
            $service_provider_instance = new $service_provider();
            $this->load_init_subscribers( $service_provider_instance );
        }

        foreach ($providers as $service_provider ) {
            $service_provider_instance = new $service_provider();
            $this->load_subscribers( $service_provider_instance );
        }
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

        if ( empty( $subscribers ) ) {
            return;
        }

        foreach ( $subscribers as $subscriber ) {
            $subscriber_object = $this->container->get( $subscriber );
            if ( $subscriber_object instanceof SubscriberInterface ) {
                $this->container->get( 'event_manager' )->add_subscriber( $subscriber_object );
            }
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

        if( is_admin() ) {
            $subscribers = array_merge($subscribers, $service_provider_instance->get_front_subscribers());
        } else {
            $subscribers = array_merge($subscribers, $service_provider_instance->get_admin_subscribers());
        }

        $subscribers = apply_filters( "{$this->container->get('prefix')}load_subscribers", $subscribers, $service_provider_instance );

        if ( empty( $subscribers ) ) {
            return;
        }

        foreach ( $subscribers as $subscriber ) {
            $subscriber_object = $this->container->get( $subscriber );
            if ( $subscriber_object instanceof SubscriberInterface ) {
                $this->container->get( 'event_manager' )->add_subscriber( $subscriber_object );
            }
        }
    }
}
