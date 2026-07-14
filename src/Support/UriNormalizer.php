<?php
namespace WSPAS\Support;

/**
 * Normalizes URIs to absolute file paths for checking.
 */
class UriNormalizer {
	/**
	 * Normalize a URI to a local file path.
	 *
	 * @param string $uri The requested URI.
	 * @return string
	 */
	public function normalize( string $uri ): string {
		$parsed = wp_parse_url( $uri );
		$path   = $parsed['path'] ?? '';
		
		// Remove query strings and decode URL entities.
		$path = urldecode( $path );
		
		// Prevent directory traversal attacks conceptually.
		$path = str_replace( '../', '', $path );
		
		// This is a simplified translation to ABSPATH.
		// In a real application, consider subdirectories and rewrites.
		return rtrim( ABSPATH, '/' ) . '/' . ltrim( $path, '/' );
	}
}
