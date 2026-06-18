<?php
/**
 * Uninstall cleanup for PressVitals Site Auditor.
 *
 * Runs only when the user deletes the plugin from the WordPress admin.
 * Removes every option the plugin created and clears the scheduled cron.
 * Multisite-aware: cleans each site on the network.
 *
 * @package PressVitalsSiteAuditor
 */

// Exit if accessed directly or not during an uninstall request.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * Delete this plugin's options + cron for the current site.
 */
function pvsa_uninstall_cleanup() {
	delete_option( 'pvsa_settings' );
	delete_option( 'pvsa_last_report' );
	delete_option( 'pvsa_token' );

	$timestamp = wp_next_scheduled( 'pvsa_daily_check' );
	if ( $timestamp ) {
		wp_unschedule_event( $timestamp, 'pvsa_daily_check' );
	}
	wp_clear_scheduled_hook( 'pvsa_daily_check' );
}

if ( is_multisite() ) {
	$pvsa_site_ids = get_sites(
		array(
			'fields' => 'ids',
			'number' => 0,
		)
	);
	foreach ( $pvsa_site_ids as $pvsa_site_id ) {
		switch_to_blog( (int) $pvsa_site_id );
		pvsa_uninstall_cleanup();
		restore_current_blog();
	}
} else {
	pvsa_uninstall_cleanup();
}
