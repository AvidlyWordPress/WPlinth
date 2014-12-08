<?php

namespace WPlinth;

/**
 * Wrapper for creating instances of WordPress classes (WP_Query, WP_Error).
 * Helps with testing by making mocking possible.
 * 
 * @package WPlinth
 */
class WordPressFactory {
	public function get_query( $args ) {
		return new \WP_Query( $args );
	}

	public function get_loopable_query( $args ) {
		return new LoopableQuery( $args );
	}

	public function get_error( $code = '', $message = '', $data = '' ) {
		return new \WP_Error( $code, $message, $data );
	}
}