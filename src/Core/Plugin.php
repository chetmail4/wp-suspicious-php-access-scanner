<?php
namespace WSPAS\Core;

/**
 * Main plugin class.
 */
class Plugin {
	/**
	 * Instance of this class.
	 *
	 * @var Plugin|null
	 */
	private static $instance = null;

	/**
	 * Get the singleton instance.
	 *
	 * @return Plugin
	 */
	public static function get_instance(): Plugin {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Initialize the plugin.
	 */
	public function init(): void {
		$this->load_dependencies();
		$this->setup_hooks();
	}

	/**
	 * Load plugin dependencies.
	 */
	private function load_dependencies(): void {
		// Initialize container/modules here if needed.
	}

	/**
	 * Setup WordPress hooks.
	 */
	private function setup_hooks(): void {
		if ( is_admin() ) {
			$admin = new \WSPAS\Admin\Admin();
			$admin->init();
		}
	}
}
