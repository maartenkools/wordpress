<?php

/**
 * Created by PhpStorm.
 * User: Maarten Kools
 * Date: 9/6/2016
 * Time: 9:35 PM
 */
class AntoniusBavo_Agenda_Rest {
	private $namespace = 'antoniusbavo-agenda/1.0';

	public function register_routes() {
		register_rest_route( $this->namespace,
			'/view',
			array(
				'methods'  => WP_REST_Server::READABLE,
				'callback' => array( $this, 'view' ),
				'args'     => array()
			) );

		register_rest_route( $this->namespace,
			'/view/(?P<id>\d+)',
			array(
				'methods'  => WP_REST_Server::READABLE,
				'callback' => array( $this, 'view' ),
				'args'     => array(
					'id' => array(
						'type'              => 'integer',
						'sanitize_callback' => 'absint',
						'validate_callback' => function ( $param, $request, $key ) {
							return is_numeric( $param );
						}
					)
				)
			) );

		register_rest_route( $this->namespace,
			'/add',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'add' ),
				'args'                => array(),
				'permission_callback' => array( $this, 'is_administrator' )
			) );
	}

	public function view( $request ) {
		return array(
			'id' => get_current_user_id()
		);
	}

	public function add( $request ) {
		$body = $request->get_json_params();

		
	}

	public function is_administrator() {
		return current_user_can( 'manage_options' );
	}
}
