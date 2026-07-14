<?php
namespace WSPAS\Core;

/**
 * Handles plugin activation and deactivation.
 */
class Installer {
	/**
	 * Activate the plugin.
	 */
	public static function activate(): void {
		self::create_tables();
		flush_rewrite_rules();
	}

	/**
	 * Deactivate the plugin.
	 */
	public static function deactivate(): void {
		flush_rewrite_rules();
	}

	/**
	 * Create database tables.
	 */
	private static function create_tables(): void {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();
		$scan_table      = $wpdb->prefix . 'wspas_scan';
		$results_table   = $wpdb->prefix . 'wspas_results';

		$sql = "CREATE TABLE $scan_table (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			status varchar(20) NOT NULL DEFAULT 'running',
			started_at datetime NOT NULL,
			completed_at datetime DEFAULT NULL,
			log_file varchar(255) NOT NULL,
			bytes_processed bigint(20) unsigned NOT NULL DEFAULT 0,
			total_bytes bigint(20) unsigned NOT NULL DEFAULT 0,
			PRIMARY KEY  (id)
		) $charset_collate;

		CREATE TABLE $results_table (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			scan_id bigint(20) unsigned NOT NULL,
			uri varchar(2048) NOT NULL,
			file_path varchar(2048) NOT NULL,
			request_method varchar(10) NOT NULL,
			status_code int(3) NOT NULL,
			hit_count int(11) unsigned NOT NULL DEFAULT 1,
			classification varchar(50) NOT NULL,
			severity varchar(20) NOT NULL,
			first_seen datetime NOT NULL,
			last_seen datetime NOT NULL,
			PRIMARY KEY  (id),
			KEY scan_id (scan_id)
		) $charset_collate;";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
	}
}
