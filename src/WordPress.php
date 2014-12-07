<?php

namespace WPlinth;

/**
 * Wrapper for creating WordPress classes. Helps with testing by making mocking possible.
 * 
 * @package WPlinth
 */
class WordPress {
	public function get_query( $args ) {
		return new \WP_Query( $args );
	}

	public function get_loopable_query( $args ) {
		return new \Loopable_Query( $args );
	}

	public function get_error( $code = '', $message = '', $data = '' ) {
		return new \WP_Error( $code, $message, $data );
	}
}