<?php
/**
 * Class Login
 * Main entry point into the plugin. Hooks on to graphql_register_types to add Login and Logout mutations
 *
 * @package WPGraphQL\Extensions\Login
 */

namespace WPGraphQL\Extensions\Login;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Login - Login/Logout support for WPGraphQL using wordpress login cookies
 */
class Login {

	/**
	 * Constructor
	 * Register mutations
	 */
	public function __construct() {
		register_graphql_mutation(
			'login',
			array(
				'inputFields'         => array(
					'login'    => array(
						'type'        => array( 'non_null' => 'String' ),
						'description' => __( 'Input your username/email.' ),
					),
					'password' => array(
						'type'        => array( 'non_null' => 'String' ),
						'description' => __( 'Input your password.' ),
					),
				),
				'outputFields'        => array(
					'status' => array(
						'type'        => 'String',
						'description' => 'Login operation status',
						'resolve'     => function( $payload ) {
							return $payload['status'];
						},
					),
				),
				'mutateAndGetPayload' => function( $input ) {
					$user = wp_signon(
						array(
							'user_login'    => wp_unslash( $input['login'] ),
							'user_password' => $input['password'],
						),
						true
					);

					if ( is_wp_error( $user ) ) {
						throw new \GraphQL\Error\UserError( ! empty( $user->get_error_code() ) ? $user->get_error_code() : 'invalid login' );
					}

					return array( 'status' => 'SUCCESS' );
				},
			)
		);
		register_graphql_mutation(
			'logout',
			array(
				'inputFields'         => array(),
				'outputFields'        => array(
					'status' => array(
						'type'        => 'String',
						'description' => 'Logout result',
						'resolve'     => function( $payload ) {
							return $payload['status'];
						},
					),
				),
				'mutateAndGetPayload' => function() {
					wp_logout(); // This destroys the WP Login cookie.
					return array( 'status' => 'SUCCESS' );
				},
			)
		);
	}

}

/**
 * Bootup the plugin
 */
add_action(
	'graphql_register_types',
	function () {
		new Login();
	}
);

/**
 * Update response headers
 */
add_filter(
	'graphql_response_headers_to_send',
	function( $headers ) {
		$http_origin = get_http_origin();

		$headers['Access-Control-Allow-Origin'] = $http_origin;

		// Tells browsers to expose the response to frontend JavaScript code when the request credentials mode is "include".
		$headers['Access-Control-Allow-Credentials'] = 'true';

		return $headers;
	},
	20
);
