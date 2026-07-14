<?php
namespace WSPAS\Scanner;

use SplFileObject;
use WSPAS\Detector\RuleEngine;
use WSPAS\Support\UriNormalizer;
use WSPAS\Database\Repository;

/**
 * Handles processing of log file chunks.
 */
class ChunkScanner {
	/**
	 * Process a chunk of the log file.
	 *
	 * @param int $offset Byte offset to start reading from.
	 * @param int $max_lines Maximum number of lines to process in this chunk.
	 * @return array Process results.
	 */
	public function process_chunk( int $offset = 0, int $max_lines = 1000, ?string $log_path = null ): array {
		$log_path = $log_path ?: $this->get_log_path();
		if ( ! file_exists( $log_path ) || ! is_readable( $log_path ) ) {
			return [
				'status'  => 'error',
				'message' => 'Log file not found or not readable.',
			];
		}

		$parser      = new ApacheParser();
		$normalizer  = new UriNormalizer();
		$rule_engine = new RuleEngine();
		$repository  = new Repository();

		$file = new SplFileObject( $log_path, 'r' );
		$total_bytes = filesize( $log_path );

		if ( $offset > 0 && $offset < $total_bytes ) {
			$file->fseek( $offset );
		}

		$lines_processed = 0;
		$findings        = 0;
		$scan_id         = 1; // Assuming a single active scan ID for now.

		while ( ! $file->eof() && $lines_processed < $max_lines ) {
			$line = $file->fgets();
			$lines_processed++;
			
			if ( empty( trim( $line ) ) ) {
				continue;
			}

			$entry = $parser->parse_line( $line );
			
			if ( $entry && $entry->status === 200 ) {
				$normalized_path = $normalizer->normalize( $entry->uri );
				
				// Basic check to see if it's a PHP file being accessed.
				if ( strpos( $normalized_path, '.php' ) !== false ) {
					$evaluation = $rule_engine->evaluate( $entry->uri );
					
					// If the evaluation yields a suspicious finding.
					if ( $evaluation['severity'] !== 'Low' && $evaluation['classification'] !== 'Unknown PHP files' ) {
						$findings++;
						$repository->save_result( [
							'scan_id'        => $scan_id,
							'uri'            => $entry->uri,
							'file_path'      => $normalized_path,
							'request_method' => $entry->method,
							'status_code'    => $entry->status,
							'hit_count'      => 1,
							'classification' => $evaluation['classification'],
							'severity'       => $evaluation['severity'],
							'first_seen'     => current_time( 'mysql' ),
							'last_seen'      => current_time( 'mysql' ),
						] );
					}
				}
			}
		}

		$new_offset = $file->ftell();
		$is_complete = $file->eof() || $new_offset >= $total_bytes;

		return [
			'status'    => $is_complete ? 'complete' : 'running',
			'processed' => $new_offset,
			'total'     => $total_bytes,
			'findings'  => $findings,
			'offset'    => $new_offset,
		];
	}
	
	/**
	 * Get the Apache access log path.
	 *
	 * @return string
	 */
	private function get_log_path(): string {
		return get_option( 'wspas_log_path', ABSPATH . 'access.log' );
	}
}
