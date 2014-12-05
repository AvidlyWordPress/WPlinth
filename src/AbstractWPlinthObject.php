<?php

namespace WPlinth;

/**
 * Base class for WPlinth classes
 * 
 * @package WPlinth
 */
abstract class AbstractWPlinthObject {
	protected $query_builder;

	/**
	 * Set QueryBuilder object (designed to be used for mocking WP_Query)
	 * 
	 * @param QueryBuilder $query_builder
	 */
	public function set_query_builder( QueryBuilderInterface $query_builder ) {
		$this->query_builder = $query_builder;
	}

	public function get_query( $args ) {
		if ( $this->query_builder != null ) {
			return $this->query_builder->build( $args );
		}
		return new \WP_Query( $args );
	}
}