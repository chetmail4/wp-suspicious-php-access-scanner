=== WP Suspicious PHP Access Scanner ===
Contributors: Chetmail4
Tags: security, scanner, log parser, apache
Requires at least: 6.8
Tested up to: 7.0
Stable tag: 1.0.0
Requires PHP: 7.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Scans Apache access logs to identify successfully executed PHP files (HTTP 200 only) and classify them as known or suspicious.

== Description ==
A production-oriented WordPress plugin that scans Apache access logs to identify successfully executed PHP files (HTTP 200 only) and classify them as known or suspicious.

== Installation ==
1. Upload the plugin files to the `/wp-content/plugins/wp-suspicious-php-access-scanner` directory, or install the plugin through the WordPress plugins screen directly.
2. Run `composer install` in the plugin directory to install dependencies if not already bundled.
3. Activate the plugin through the 'Plugins' screen in WordPress.
4. Navigate to Tools -> Suspicious Access Scanner to configure and run your first scan.

== Changelog ==
= 1.0.0 =
* Initial release.
