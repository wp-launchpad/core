<?php

namespace LaunchpadCore;

use LaunchpadCore\EventManagement\Wrapper\SubscriberWrapper;
use LaunchpadCore\Container\PrefixAwareInterface;
use League\Container\Container;
use LaunchpadCore\Activation\Activation;
use LaunchpadCore\Deactivation\Deactivation;
use LaunchpadCore\EventManagement\EventManager;
use League\Container\ReflectionContainer;

defined( 'ABSPATH' ) || exit;

/**
 * Boot the plugin.
 *
 * @param string $plugin_launcher_file Launch file from your plugin.
 * @return void
 */
function boot(string $plugin_launcher_file) {

    $plugin_root_dir = dirname($plugin_launcher_file) . '/';

    if ( file_exists( $plugin_root_dir . 'vendor/autoload.php' ) ) {
        require $plugin_root_dir . 'vendor/autoload.php';
    }

    if ( file_exists( $plugin_root_dir . 'vendor-prefixed/autoload.php' ) ) {
        require $plugin_root_dir . 'vendor-prefixed/autoload.php';
    }


    $params = require_once $plugin_root_dir . 'configs/parameters.php';
    $providers = require_once $plugin_root_dir . 'configs/providers.php';

    /**
     * Loads plugin translations
     *
     * @return void
     */

    add_action( 'plugins_loaded', function() use ($params, $plugin_launcher_file) {
        // Load translations from the languages directory.
        $locale = get_locale();

        // This filter is documented in /wp-includes/l10n.php.
        $locale = apply_filters( 'plugin_locale', $locale, $params['translation_key'] ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals
        load_textdomain( $params['translation_key'], WP_LANG_DIR .  key_exists('is_mu_plugin', $params) && $params['is_mu_plugin'] ? '/mu-plugins/' : '/plugins/' . $params['translation_key'] . '-' . $locale . '.mo' );
        if( key_exists('is_mu_plugin', $params) && $params['is_mu_plugin'] ) {
            load_plugin_textdomain( $params['translation_key'], false, dirname( plugin_basename( $plugin_launcher_file ) ) . '/languages/' );
            return;
        }
        load_muplugin_textdomain( $params['translation_key'], dirname( plugin_basename( $plugin_launcher_file ) ) . '/languages/' );
    } );

    /**
     * Tell WP what to do when plugin is loaded.
     *
     */
    add_action( 'plugins_loaded',  function() use ($params, $providers) {
        // Nothing to do if autosave.
        if ( defined( 'DOING_AUTOSAVE' ) ) {
            return;
        }

        $prefix = key_exists('prefix', $params) ? $params['prefix'] : '';

        $container = new Container();

        $has_reflection_as_fallback = key_exists( 'reflection_as_fallback', $params ) && $params['reflection_as_fallback'];

        if ( $has_reflection_as_fallback ) {
            $container->delegate( new ReflectionContainer() );
        }

        $wp_rocket = new Plugin(
            $container,
            new EventManager(),
            new SubscriberWrapper($prefix)
        );

        $wp_rocket->load( $params, $providers );
    } );

    Deactivation::set_container(new Container());
    Deactivation::set_params($params);
    Deactivation::set_providers($providers);

    register_deactivation_hook( $plugin_launcher_file, [ Deactivation::class, 'deactivate_plugin' ] );

    Activation::set_container(new Container());
    Activation::set_params($params);
    Activation::set_providers($providers);

    register_activation_hook( $plugin_launcher_file, [ Activation::class, 'activate_plugin' ] );
}
