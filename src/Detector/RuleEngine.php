<?php
namespace WSPAS\Detector;

/**
 * Evaluates requested URIs against security rules.
 */
class RuleEngine {
	/**
	 * Rules to apply.
	 *
	 * @var array
	 */
	private $rules = [];

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->rules = [
			'SuspiciousDirectory',
			'SuspiciousFilename',
			'UnknownFile',
		];
	}

	/**
	 * Evaluate a URI.
	 *
	 * @param string $uri The requested URI.
	 * @return array
	 */
	public function evaluate( string $uri ): array {
		// Mocked evaluation
		$classification = 'Unknown PHP files';
		$severity       = 'Medium';

		if ( strpos( $uri, '/uploads/' ) !== false ) {
			$classification = 'Suspicious directories';
			$severity       = 'High';
		} elseif ( preg_match( '/(shell|wso|cmd)\.php$/i', $uri ) ) {
			$classification = 'Suspicious filenames';
			$severity       = 'High';
		}

		return [
			'classification' => $classification,
			'severity'       => $severity,
		];
	}
}
