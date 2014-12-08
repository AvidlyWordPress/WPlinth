<?php

namespace WPlinth;

/**
 * Represents a connection between posts (or users). Uses Posts 2 Posts plugin.
 * 
 * @package WPlinth
 */
abstract class Connection {

	public function __construct() {
		add_action( 'p2p_init', array( $this, 'register_connections' ) );
	}

	public function register_connections() {
		if ( is_callable( array( $this, 'set_connection_type' ) ) ) {
			$this->set_connection_type();
		}

		if ( property_exists( $this, 'connection_type' ) ) {
			p2p_register_connection_type( $this->connection_type );
		}
	}

}