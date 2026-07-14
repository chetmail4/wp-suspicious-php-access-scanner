<?php
namespace WSPAS\Scanner;

use SplFileObject;
use WSPAS\Models\LogEntry;

/**
 * Parses Apache access logs.
 */
class ApacheParser {
	/**
	 * Parse a log line.
	 *
	 * @param string $line A single line from the access log.
	 * @return LogEntry|null
	 */
	public function parse_line( string $line ): ?LogEntry {
		// Basic combined log format regex.
		$pattern = '/^(\S+) \S+ \S+ \[([^\]]+)\] "(\S+) (\S+) \S+" (\d{3}) (\d+|-)/';
		
		if ( preg_match( $pattern, $line, $matches ) ) {
			return new LogEntry( [
				'ip'       => $matches[1],
				'datetime' => $matches[2],
				'method'   => $matches[3],
				'uri'      => $matches[4],
				'status'   => (int) $matches[5],
				'size'     => $matches[6] === '-' ? 0 : (int) $matches[6],
			] );
		}
		
		return null;
	}
}
