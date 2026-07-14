<?php
/**
 * Plugin Name: WP Suspicious PHP Access Scanner
 * Description: A production-oriented WordPress plugin that scans Apache access logs to identify successfully executed PHP files (HTTP 200 only) and classify them as known or suspicious.
 * Version: 1.0.0
 * Requires at least: 6.8
 * Requires PHP: 7.4
 * Author: Chetmail4
 * License: GPL-2.0-or-later
 * Text Domain: wp-suspicious-php-access-scanner
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define plugin constants.
define( 'WSPAS_VERSION', '1.0.0' );
define( 'WSPAS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WSPAS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Require Composer autoloader or fallback to custom autoloader.
if ( file_exists( WSPAS_PLUGIN_DIR . 'vendor/autoload.php' ) ) {
	require_once WSPAS_PLUGIN_DIR . 'vendor/autoload.php';
} else {
	spl_autoload_register( function ( $class ) {
		$prefix = 'WSPAS\\';
		$base_dir = WSPAS_PLUGIN_DIR . 'src/';

		$len = strlen( $prefix );
		if ( strncmp( $prefix, $class, $len ) !== 0 ) {
			return;
		}

		$relative_class = substr( $class, $len );
		$file = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

		if ( file_exists( $file ) ) {
			require $file;
		}
	} );
}

/**
 * Initialize the plugin.
 */
function wspas_init() {
	if ( class_exists( '\WSPAS\Core\Plugin' ) ) {
		$plugin = \WSPAS\Core\Plugin::get_instance();
		$plugin->init();
	}
}
add_action( 'plugins_loaded', 'wspas_init' );

/**
 * Plugin activation hook.
 */
register_activation_hook( __FILE__, function () {
	if ( class_exists( '\WSPAS\Core\Installer' ) ) {
		\WSPAS\Core\Installer::activate();
	}
} );

/**
 * Plugin deactivation hook.
 */
register_deactivation_hook( __FILE__, function () {
	if ( class_exists( '\WSPAS\Core\Installer' ) ) {
		\WSPAS\Core\Installer::deactivate();
	}
} );
