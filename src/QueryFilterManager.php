<?php

namespace WPlinth;

use WP_Query;

/**
 * Container of QueryFilter instances in the system.
 * 
 * @package WPlinth
 */
class QueryFilterManager {

	// Holds an array of QueryFilter objects.
	protected $queryfilters;

	// Consturct the QueryManager object.
	public function __construct( $queryfilters = array() ) {
		$this->queryfilters = $queryfilters;

		// Hook to pre_get_posts.
		add_action( 'pre_get_posts', array( $this, 'filter' ) );
	}

	/**
	  * Run the filters on pre_get_posts filter.
	  * @param \WP_Query $query 
	  */
	public function filter( WP_Query $query ) {

		// Exit early if this is not the main query or we're in the admin.
		if ( ! $query->is_main_query() || is_admin() ) {
			return;
		}

		// Sort QueryFilters by priority, ascending.
		usort( $this->queryfilters, function( $a, $b ) {
			if ( $a::PRIORITY == $b::PRIORITY ) {
				return 0;
			}
		    return ( $a::PRIORITY < $b::PRIORITY ) ? -1 : 1;
		} );

		// Loop through query filters, test and execute.
		foreach ( $this->queryfilters as $qf ) {
			if ( $qf->test( $query ) ) {
				$qf->filter( $query );
			}
		}
	}
}