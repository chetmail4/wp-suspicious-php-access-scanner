<?php
namespace WSPAS\Database;

/**
 * Handles database operations for scan results.
 */
class Repository {
	/**
	 * Save a scan result.
	 *
	 * @param array $data Data to save.
	 * @return int|false Inserted ID or false on failure.
	 */
	public function save_result( array $data ) {
		global $wpdb;
		$table = $wpdb->prefix . 'wspas_results';
		$result = $wpdb->insert( $table, $data );
		return $result ? $wpdb->insert_id : false;
	}
}
