<?php

namespace WPlinth;

/**
 * Base class for WPlinth classes
 * 
 * @package WPlinth
 */
interface QueryBuilderInterface {
	function build( array $args );
}