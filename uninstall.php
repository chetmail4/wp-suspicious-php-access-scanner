<?php
/**
 * Fired when the plugin is uninstalled.
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Drop custom database tables.
global $wpdb;

$scan_table    = $wpdb->prefix . 'wspas_scan';
$results_table = $wpdb->prefix . 'wspas_results';

$wpdb->query( "DROP TABLE IF EXISTS $scan_table" );
$wpdb->query( "DROP TABLE IF EXISTS $results_table" );

// Delete cached transients.
delete_transient( 'wspas_inventory_cache' );
