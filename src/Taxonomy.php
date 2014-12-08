<?php

namespace WPlinth;

/**
 * Base class for Taxonomies
 * 
 * @package WPlinth
 */
abstract class Taxonomy {

	protected $types;
	protected $args;

	public function __construct() {
		add_action( 'init', array( $this, 'register_taxonomy' ) );
	}

	public function register_taxonomy() {
		$class = get_called_class();
		$taxonomy = $class::TAXONOMY;

		if ( is_callable( array( $this, 'set_taxonomy_data' ) ) ) {
			$this->set_taxonomy_data();
		}

		register_taxonomy( $taxonomy, $this->types, $this->args );

		/**
		 * Explicitly register taxonomies for each post type
		 */
		foreach ( $this->types as $type ) {
			register_taxonomy_for_object_type( $taxonomy, $type );	
		}

	}
}