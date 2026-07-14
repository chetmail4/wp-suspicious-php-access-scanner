<?php
namespace WSPAS\Cache;

/**
 * Handles caching of file status and inventory.
 */
class FileCache {
	/**
	 * Get a cached value.
	 *
	 * @param string $key Cache key.
	 * @return mixed|false
	 */
	public function get( string $key ) {
		return get_transient( 'wspas_' . $key );
	}

	/**
	 * Set a cached value.
	 *
	 * @param string $key Cache key.
	 * @param mixed $value Value to cache.
	 * @param int $expiration Time to live in seconds.
	 * @return bool
	 */
	public function set( string $key, $value, int $expiration = 3600 ): bool {
		return set_transient( 'wspas_' . $key, $value, $expiration );
	}
}
