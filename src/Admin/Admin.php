<?php
namespace WSPAS\Admin;

/**
 * Handles admin area initialization.
 */
class Admin {
	/**
	 * Initialize admin hooks.
	 */
	public function init(): void {
		add_action( 'admin_menu', [ $this, 'add_plugin_menu' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'wp_ajax_wspas_start_scan', [ $this, 'ajax_start_scan' ] );
	}

	/**
	 * Add plugin menu pages.
	 */
	public function add_plugin_menu(): void {
		add_management_page(
			__( 'Suspicious Access Scanner', 'wp-suspicious-php-access-scanner' ),
			__( 'Suspicious Access Scanner', 'wp-suspicious-php-access-scanner' ),
			'manage_options',
			'wspas-dashboard',
			[ $this, 'render_dashboard' ]
		);
	}

	/**
	 * Register plugin settings.
	 */
	public function register_settings(): void {
		register_setting( 'wspas_options', 'wspas_log_path', [
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => ABSPATH . 'access.log',
		] );
	}

	/**
	 * Enqueue admin scripts and styles.
	 *
	 * @param string $hook The current admin page.
	 */
	public function enqueue_scripts( string $hook ): void {
		if ( 'tools_page_wspas-dashboard' !== $hook ) {
			return;
		}

		// Enqueue scripts (assuming we will build a JS file for AJAX scanning later).
	}

	/**
	 * Render the dashboard page.
	 */
	public function render_dashboard(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		
		$log_path = get_option( 'wspas_log_path', ABSPATH . 'access.log' );
		$is_readable = file_exists( $log_path ) && is_readable( $log_path );
		// echo $log_path;

		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'WP Suspicious PHP Access Scanner', 'wp-suspicious-php-access-scanner' ); ?></h1>
			<p><?php esc_html_e( 'Scan your Apache access logs to identify suspicious PHP file executions.', 'wp-suspicious-php-access-scanner' ); ?></p>
			
			<div class="card" style="margin-bottom: 20px;">
				<h2><?php esc_html_e( 'Configuration', 'wp-suspicious-php-access-scanner' ); ?></h2>
				<form method="post" action="options.php">
					<?php settings_fields( 'wspas_options' ); ?>
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Apache Access Log Path', 'wp-suspicious-php-access-scanner' ); ?></th>
							<td>
								<input type="text" name="wspas_log_path" value="<?php echo esc_attr( $log_path ); ?>" class="regular-text" />
								<p class="description">
									<?php esc_html_e( 'Absolute path to your Apache access log file.', 'wp-suspicious-php-access-scanner' ); ?><br/>
									<?php esc_html_e( 'Example: /var/log/apache2/access.log', 'wp-suspicious-php-access-scanner' ); ?>
								</p>
							</td>
						</tr>
					</table>
					<?php submit_button(); ?>
				</form>
			</div>

			<div class="card">
				<h2><?php esc_html_e( 'Run Scan', 'wp-suspicious-php-access-scanner' ); ?></h2>
				
				<?php if ( ! $is_readable ) : ?>
					<div class="notice notice-error inline">
						<p><strong><?php esc_html_e( 'Error: Log file is not readable or does not exist.', 'wp-suspicious-php-access-scanner' ); ?></strong></p>
						<p><?php esc_html_e( 'The PHP process (usually running as www-data) does not have read permissions for the specified access log file.', 'wp-suspicious-php-access-scanner' ); ?></p>
						<p><?php esc_html_e( 'To fix this, you can either:', 'wp-suspicious-php-access-scanner' ); ?></p>
						<ul style="list-style-type: disc; margin-left: 20px;">
							<li><?php esc_html_e( 'Change the group ownership of the log file to allow the web server to read it.', 'wp-suspicious-php-access-scanner' ); ?></li>
							<li><?php esc_html_e( 'Add the web server user to the group that owns the log file (e.g., adm).', 'wp-suspicious-php-access-scanner' ); ?></li>
							<li><?php esc_html_e( 'Create a cron job that periodically copies the log file to a location readable by the web server (e.g., inside the WordPress uploads directory, securely protected by .htaccess).', 'wp-suspicious-php-access-scanner' ); ?></li>
						</ul>
					</div>
				<?php else : ?>
					<div class="notice notice-success inline">
						<p><?php esc_html_e( 'Log file is readable. You can start the scan.', 'wp-suspicious-php-access-scanner' ); ?></p>
					</div>
					<button type="button" id="wspas-start-scan" class="button button-primary" style="margin-top: 10px;">
						<?php esc_html_e( 'Start Scan', 'wp-suspicious-php-access-scanner' ); ?>
					</button>
					<div id="wspas-scan-progress" style="margin-top: 15px; display: none;">
						<p><?php esc_html_e( 'Scanning...', 'wp-suspicious-php-access-scanner' ); ?> <span id="wspas-scan-percent">0%</span></p>
					</div>
				<?php endif; ?>
			</div>
			
			<div class="card" style="margin-top: 20px;">
				<h2><?php esc_html_e( 'Results', 'wp-suspicious-php-access-scanner' ); ?></h2>
				<p id="wspas-scan-results"><?php esc_html_e( 'Scan results will appear here.', 'wp-suspicious-php-access-scanner' ); ?></p>
			</div>
		</div>
		<script>
		jQuery(document).ready(function($) {
			$('#wspas-start-scan').on('click', function() {
				var $btn = $(this);
				var $progress = $('#wspas-scan-progress');
				var $percent = $('#wspas-scan-percent');
				var $results = $('#wspas-scan-results');
				
				$btn.prop('disabled', true);
				$progress.show();
				$percent.text('0%');
				$results.text('Scanning in progress...');

				function doScan(offset) {
					$.post(ajaxurl, {
						action: 'wspas_start_scan',
						nonce: '<?php echo wp_create_nonce( 'wspas_scan_nonce' ); ?>',
						offset: offset
					}, function(response) {
						if (response.success) {
							var data = response.data;
							var pct = 100;
							if (data.total > 0) {
								pct = Math.round((data.processed / data.total) * 100);
							}
							if (pct > 100) pct = 100;
							
							$percent.text(pct + '%');
							$results.text('Found ' + data.findings + ' suspicious requests so far.');
							
							if (data.status === 'running') {
								doScan(data.offset);
							} else {
								$btn.prop('disabled', false);
								$progress.find('p').html('<strong>Scan Complete!</strong>');
								$results.html('Scan finished. Total suspicious findings: <strong>' + data.findings + '</strong>');
							}
						} else {
							alert('Scan failed: ' + response.data);
							$btn.prop('disabled', false);
						}
					}).fail(function() {
						alert('An error occurred during the scan.');
						$btn.prop('disabled', false);
					});
				}
				
				doScan(0);
			});
		});
		</script>
		<?php
	}

	/**
	 * AJAX handler for starting a scan.
	 */
	public function ajax_start_scan(): void {
		check_ajax_referer( 'wspas_scan_nonce', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized' );
		}

		$offset = isset( $_POST['offset'] ) ? intval( $_POST['offset'] ) : 0;

		// Initialize scan process.
		$scanner = new \WSPAS\Scanner\ChunkScanner();
		$result = $scanner->process_chunk( $offset );

		wp_send_json_success( $result );
	}
}
