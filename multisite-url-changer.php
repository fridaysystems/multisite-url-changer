<?php
/**
 * Plugin Name: Multisite URL Changer
 * Description: Performs a database search-replace when a blog's URL is changed on multisite.
 * Plugin URI: https://github.com/fridaysystems/multisite-url-changer/
 * Author: Corey Salzano
 * Author URI: https://coreysalzano.com/links/
 * Version: 0.2.0
 * Network: true
 *
 * @package multisite-url-changer
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_update_site', 'breakfast_update_site_search_replace', 10, 2 );
/**
 * Executes a wp-cli command to search & replace the sites domain name in the
 * database.
 *
 * @uses shell_exec()
 *
 * @param WP_Site $new_site New site object.
 * @param WP_Site $old_site Old site object.
 * @return void
 */
function breakfast_update_site_search_replace( $new_site, $old_site ) {
	// Is the user changing the site's domain?
	if ( $new_site->domain === $old_site->domain ) {
		// No.
		return;
	}

	// Yes. Perform a search replace on the database.
	$wp_cli_command = sprintf(
		'wp --url=%1$s search-replace %2$s %1$s --all-tables-with-prefix 2>&1',
		$new_site->domain,
		$old_site->domain
	);
	shell_exec( $wp_cli_command );
}
