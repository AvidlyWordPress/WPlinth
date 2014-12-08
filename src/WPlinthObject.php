<?php

namespace WPlinth;

/**
 * Base class for WPlinth classes
 * 
 * @package WPlinth
 */
abstract class WPlinthObject {

	protected $wp;

	/**
	 * Set wp that can be used to get new WordPress objects
	 * and replaced when needing to mock those.
	 * 
	 * @param object $wp
	 */
	public function set_wp( $wp ) {
		$this->wp = $wp;
	}
}