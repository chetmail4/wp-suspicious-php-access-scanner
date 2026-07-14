<?php
namespace WSPAS\Export;

/**
 * Handles CSV export of scan results.
 */
class CsvExporter {
	/**
	 * Export results to CSV.
	 *
	 * @param array $results Data to export.
	 */
	public function export( array $results ): void {
		header( 'Content-Type: text/csv' );
		header( 'Content-Disposition: attachment; filename="wspas-results.csv"' );
		
		$output = fopen( 'php://output', 'w' );
		fputcsv( $output, [ 'URI', 'File Path', 'Method', 'Status', 'Hits', 'Classification', 'Severity', 'First Seen', 'Last Seen' ] );
		
		foreach ( $results as $row ) {
			fputcsv( $output, $row );
		}
		
		fclose( $output );
		exit;
	}
}
