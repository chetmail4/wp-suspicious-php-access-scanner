<?php
namespace WSPAS\Models;

/**
 * Represents a single log entry.
 */
class LogEntry {
	public string $ip;
	public string $datetime;
	public string $method;
	public string $uri;
	public int $status;
	public int $size;

	/**
	 * Constructor.
	 *
	 * @param array $data Parsed log data.
	 */
	public function __construct( array $data ) {
		$this->ip       = $data['ip'] ?? '';
		$this->datetime = $data['datetime'] ?? '';
		$this->method   = $data['method'] ?? '';
		$this->uri      = $data['uri'] ?? '';
		$this->status   = (int) ( $data['status'] ?? 0 );
		$this->size     = (int) ( $data['size'] ?? 0 );
	}
}
