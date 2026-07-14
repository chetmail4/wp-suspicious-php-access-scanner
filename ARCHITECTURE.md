# ARCHITECTURE.md

# WP Suspicious PHP Access Scanner Architecture

## Goals

-   Production-ready WordPress plugin
-   PHP 7.4+
-   WordPress 6.8+ and 7.x+
-   Object-oriented
-   PSR-4 autoloading
-   WordPress Coding Standards (WPCS)
-   Memory-efficient streaming log parser
-   Read-only access to Apache access logs

## High-Level Flow

``` text
Apache access.log
        |
        v
ApacheParser (SplFileObject)
        |
        v
HTTP 200 Filter
        |
        v
PHP URI Filter
        |
        v
URI Normalizer
        |
        v
WordPress Inventory Lookup
        |
        v
Rule Engine
        |
        v
Aggregate Results
        |
        v
Database
        |
        v
WP_List_Table UI
```

## Modules

### Core

-   Plugin bootstrap
-   Activation/deactivation
-   Dependency container
-   Installer

### Scanner

-   Streaming parser
-   Chunk scanner
-   Progress tracking
-   Scan lock
-   Log entry model

### Inventory

-   Build whitelist from:
    -   WordPress core
    -   Active plugins
    -   Active theme
    -   MU plugins
    -   Drop-ins
-   Cached inventory
-   Manual refresh

### Detector

Rule engine: - CoreRule - PluginRule - ThemeRule - MuPluginRule -
DropinRule - ExistingFileRule - MissingFileRule -
SuspiciousDirectoryRule - SuspiciousFilenameRule - UploadPhpRule

Severity: - Normal - Medium - High

### Database

Tables:

-   wp_wspas_scan
-   wp_wspas_results

Only aggregated results are stored.

### Admin

Pages: - Dashboard - Scan - Results - Settings

Uses: - WP_List_Table - AJAX chunk scanning - CSV export

## Scan Lifecycle

1.  Validate permissions.
2.  Acquire scan lock.
3.  Load inventory cache.
4.  Parse log in chunks.
5.  Normalize URI.
6.  Apply detection rules.
7.  Aggregate duplicate requests.
8.  Save results.
9.  Release scan lock.

## Performance

-   SplFileObject streaming
-   Byte-offset resume
-   Chunk processing
-   Inventory cache
-   File status cache
-   Aggregated database storage

## Security

-   manage_options capability
-   Nonce verification
-   Input sanitization
-   Output escaping
-   Validation
-   No shell execution
-   Read-only log access

## Directory Layout

``` text
src/
├── Admin/
├── Cache/
├── Core/
├── Database/
├── Detector/
├── Export/
├── Inventory/
├── Models/
├── Scanner/
└── Support/
```

## Future Extensions

-   Nginx parser
-   WP-CLI
-   REST API
-   Baseline comparison
-   Scheduled scans
-   Threat intelligence
-   Multisite dashboard
