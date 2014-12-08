<?php

namespace WPlinth;

use WP_Query;

/**
 * Base class for query filters run on pre_get_posts.
 * 
 * @package WPlinth
 */
abstract class QueryFilter {

	// Priority level. Lower numbers correspond with earlier execution.
	const PRIORITY = 10;

	/**
	 * Test if the QueryFilter shoud be executed.
	 * @param WP_Query $query The WordPress query
	 * @return bool Weather the filter shoud be run for this query
	 */
	abstract function test( WP_Query $query );

	/**
	 * The function that modifies the main query before it is run.
	 * @param WP_Query $query The main query.
	 */
	abstract function filter( WP_Query $query );

}