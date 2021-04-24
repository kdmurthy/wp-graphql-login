<?php
/**
 * Plugin Name: WPGraphQL Extensions for Login and Logout
 * Plugin URI: https://github.com/kdmurthy/wp-graphql-login
 * Description: Support wpLogin and wpLogout graphql mutations
 * Author: Dakshinamurthy Karra
 * Author URI: https://github.com/kdmurthy/wp-graphql-login
 * Version: 0.1.0
 * Text Domain: wp-graphql-login
 * Requires at least: 4.7.0
 * Tested up to: 4.7.1
 *
 * @package extensions
 */

namespace WPGraphQL\Extensions\WpLogin;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WPGRAPHQL_LOGIN_VERSION', '0.1.0' );
define( 'WPGRAPHQL_LOGIN_WPGRAPHQL_MINIMUM_VERSION', '1.3.5' );

/*
 * Boot the plugin
 */
add_action(
	'plugins_loaded',
	function () {
		if ( class_exists( 'WPGraphQL' ) && \version_compare( WPGRAPHQL_VERSION, WPGRAPHQL_LOGIN_WPGRAPHQL_MINIMUM_VERSION ) >= 0 ) {
			require_once \dirname( __FILE__ ) . '/class-login.php';
		} else {
			add_action(
				'admin_notices',
				function() {
					?>
					<div class="error fade">
						<p>
							<strong><?php esc_html_e( 'NOTICE', 'wp-graphql-login' ); ?>:</strong> WPGraphQL Extensions for Login and Logout <?php echo esc_html( WPGRAPHQL_LOGIN_VERSION ); ?> <?php esc_html_e( 'requires a minimum of', 'wp-graphql-login' ); ?>
							<strong>WP GraphQL <?php echo esc_html( WPGRAPHQL_LOGIN_WPGRAPHQL_MINIMUM_VERSION ); ?>+</strong> <?php esc_html_e( 'to function. Please install and activate the plugin', 'wp-graphql-login' ); ?>
						</p>
					</div>
					<?php
				}
			);
		}
	}
);
