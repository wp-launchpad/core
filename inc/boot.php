<?php

namespace RocketLauncherCore;

use League\Container\Container;
use RocketLauncherCore\Activation\Activation;
use RocketLauncherCore\Deactivation\Deactivation;

defined( 'ABSPATH' ) || exit;

/**
 * Boot the plugin.
 *
 * @param string $plugin_launcher_file Launch file from your plugin.
 * @return void
 */
function boot(string $plugin_launcher_file) {

    $plugin_root_dir = dirname($plugin_launcher_file);

    if ( file_exists( $plugin_root_dir . 'vendor/autoload.php' ) ) {
        require $plugin_root_dir . 'vendor/autoload.php';
    }


    $params = require_once $plugin_root_dir . '/configs/parameters.php';
    $providers = require_once $plugin_root_dir . '/configs/providers.php';

    /**
     * Tell WP what to do when plugin is loaded.
     *
     */
    add_action( 'plugins_loaded',  function() use ($params, $providers) {
        // Nothing to do if autosave.
        if ( defined( 'DOING_AUTOSAVE' ) ) {
            return;
        }

        $wp_rocket = new Plugin(
            new Container()
        );

        $wp_rocket->load( $params, $providers );

        // Call defines and functions.
    } );

    Deactivation::set_params($params);
    Deactivation::set_providers($providers);

    register_deactivation_hook( $plugin_launcher_file, [ Deactivation::class, 'deactivate_plugin' ] );

    Activation::set_params($params);
    Activation::set_providers($providers);

    register_activation_hook( $plugin_launcher_file, [ Activation::class, 'activate_plugin' ] );
}
