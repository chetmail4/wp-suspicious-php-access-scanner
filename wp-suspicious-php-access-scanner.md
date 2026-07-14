# WP Suspicious PHP Access Scanner

## Overview

A production-oriented WordPress plugin that scans Apache access logs to
identify successfully executed PHP files (HTTP 200 only) and classify
them as known or suspicious.

## Compatibility

-   WordPress: 6.8+ and 7.x+
-   PHP: 7.4+
-   Apache: 2.4+

## Default Behavior

-   Scan Apache `access.log`
-   HTTP status 200 only
-   `.php` requests only
-   GET and POST requests
-   Streaming parser using `SplFileObject`
-   Read-only log access
-   Gzip rotated logs: disabled by default

## Detection

-   WordPress Core
-   Active Plugins
-   Active Theme
-   MU Plugins
-   Drop-ins
-   Unknown PHP files
-   Deleted PHP files
-   Suspicious directories (`uploads`, `cache`, `tmp`, `backup`, etc.)
-   Suspicious filenames (`shell.php`, `wso.php`, `r57.php`, `cmd.php`,
    etc.)

Severity: - Normal - Medium - High

## Admin UI

Tools → PHP Access Scanner - Dashboard - Scan - Results - Settings

Features: - WP_List_Table - Search - Filters - Pagination - CSV export

## Architecture

-   Object-Oriented PHP
-   PSR-4 autoloading
-   WPCS compliant
-   Strict types
-   Namespaces
-   Dependency injection where appropriate

## Scanner Pipeline

Apache access.log → Streaming parser → HTTP 200 filter → PHP filter →
URI normalization → Inventory lookup → Rule engine → Result aggregation
→ Database → Results UI

## Inventory

Builds a whitelist of: - WordPress core - Active plugins - Active
theme - MU plugins - Drop-ins

Cached and refreshable from the admin UI.

## Performance

-   Chunked scanning
-   Byte-offset resume
-   Scan lock
-   File status cache
-   Inventory cache
-   Aggregated results (not every log line)

## Database

Suggested tables: - wp_wspas_scan - wp_wspas_results

Stores scan metadata and aggregated findings only.

## Security

-   `manage_options` capability
-   Nonce verification
-   Input sanitization
-   Output escaping
-   Validation
-   No shell execution (`exec`, `shell_exec`, `system`, `passthru`)
-   Read-only log access

## Disabled by Default

-   Gzip rotated logs
-   Scheduled scans
-   Email alerts
-   GeoIP
-   VirusTotal
-   AbuseIPDB
-   Nginx support

## Planned Future Features

-   Nginx log support
-   WP-CLI
-   REST API
-   Baseline comparison
-   Real-time monitoring
-   Scheduled scans
-   Threat intelligence integration
-   Multisite dashboard

## Project Structure

``` text
wp-suspicious-php-access-scanner/
├── assets/
├── languages/
├── templates/
├── src/
│   ├── Admin/
│   ├── Cache/
│   ├── Core/
│   ├── Database/
│   ├── Detector/
│   ├── Export/
│   ├── Inventory/
│   ├── Models/
│   ├── Scanner/
│   └── Support/
├── composer.json
├── readme.txt
├── uninstall.php
└── wp-suspicious-php-access-scanner.php
```
