<?php
/**
 * PHPUnit bootstrap for the WordPress test suite.
 *
 * Set up a throwaway WordPress install first:
 *   bin/install-wp-tests.sh wordpress_test root '' localhost latest
 * then run `composer test` (or `phpunit`).
 *
 * @package PressVitalsSiteAuditor
 */

$_tests_dir = getenv( 'WP_TESTS_DIR' );

if ( ! $_tests_dir ) {
	$_tmp_dir   = getenv( 'TMPDIR' ) ? getenv( 'TMPDIR' ) : '/tmp';
	$_tests_dir = rtrim( $_tmp_dir, '/\\' ) . '/wordpress-tests-lib';
}

// Yoast polyfills (required by modern WP core test suite).
$_polyfills = dirname( __DIR__ ) . '/vendor/yoast/phpunit-polyfills/phpunitpolyfills-autoload.php';
if ( file_exists( $_polyfills ) && ! getenv( 'WP_TESTS_PHPUNIT_POLYFILLS_PATH' ) ) {
	putenv( 'WP_TESTS_PHPUNIT_POLYFILLS_PATH=' . dirname( __DIR__ ) . '/vendor/yoast/phpunit-polyfills' );
}

$_functions = $_tests_dir . '/includes/functions.php';
if ( ! file_exists( $_functions ) ) {
	echo "Could not find the WordPress test suite at {$_tests_dir}." . PHP_EOL; // phpcs:ignore
	echo 'Run bin/install-wp-tests.sh first, or set WP_TESTS_DIR.' . PHP_EOL;   // phpcs:ignore
	exit( 1 );
}

require_once $_functions;

/**
 * Load the plugin under test before WordPress finishes booting.
 */
function _pvsa_manually_load_plugin() {
	require dirname( __DIR__ ) . '/pressvitals-site-auditor.php';
}
tests_add_filter( 'muplugins_loaded', '_pvsa_manually_load_plugin' );

require $_tests_dir . '/includes/bootstrap.php';
