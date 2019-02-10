<?php

/**
 * Plugin name: Auth Header Tester
 */

add_action( 'rest_api_init', 'aht_rest_api_init' );

function aht_rest_api_init() {
	// Some hosts that run PHP in FastCGI mode won't be given the Authentication header.
	register_rest_route( 'aht/v1', '/test-basic-authorization-header/', array(
		'methods' => WP_REST_Server::ALLMETHODS,
		'callback' => 'aht_rest_test_basic_authorization_header',
	) );
}

function aht_rest_test_basic_authorization_header() {
	$response = array();

	if ( isset( $_SERVER['PHP_AUTH_USER'] ) ) {
		$response['PHP_AUTH_USER'] = $_SERVER['PHP_AUTH_USER'];
	}

	if ( isset( $_SERVER['PHP_AUTH_PW'] ) ) {
		$response['PHP_AUTH_PW'] = $_SERVER['PHP_AUTH_PW'];
	}

	if ( empty( $response ) ) {
		return new WP_Error( 'no-credentials', __( 'No HTTP Basic Authorization credentials were found submitted with this request.' ), array( 'status' => 404 ) );
	}

	return $response;
}

add_action( 'admin_enqueue_scripts', 'aht_admin_enqueue_scripts' );
function aht_admin_enqueue_scripts() {
	wp_enqueue_script( 'aht', plugin_dir_url( __FILE__ ) . 'auth-header-tester.js', array( 'jquery' ), '1.0', true );
	wp_localize_script(
		'aht',
		'aht',
		array(
			'root'      => esc_url_raw( rest_url() ),
			'namespace' => 'aht/v1',
			'text'      => array(
				'no_credentials' => __( 'Due to a potential server misconfiguration, it seems that HTTP Basic Authorization may not work for the REST API on this site: `Authorization` headers are not being sent to WordPress by the web server. This is generally a FastCGI issue.' ),
			),
		)
	);
}
