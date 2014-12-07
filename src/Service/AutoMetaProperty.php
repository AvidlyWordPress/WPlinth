<?php

namespace WPlinth\Service;

/**
 * Adds post meta as post object properties automatically.
 * 
 * @package Erp
 */
class AutoMetaProperty {

	/**
	 * Initiates actions.
	 * @param PostTypeManager $post_type_manager for getting meta filed definitions for post types.
	 * 
	 * @uses add_action()
	 */
	public function __construct( $post_type_manager ) {
		$this->post_type_manager = $post_type_manager;
		add_action( 'loop_start', array( $this, 'maybe_attach_meta' ) );
	}

	/**
	  * Attach meta filed values to post objects.
	  * @param WP_Query $query 
	  */ 
	public function maybe_attach_meta( $query ) {
		// Do nothing, if post meta cache was not updated,
		// to prevent hurting performance.
		if ( ! $query->query_vars['update_post_meta_cache'] ) {
			return;
		}

		foreach ( $query->posts as $post ) {
			$type = $this->post_type_manager->get_by_name( $post->post_type );

			// This post type is not being managed by post type manager.
			if ( null === $type ) {
				continue;
			}

			// Loop through meta boxes of the post type
			foreach ( $type->meta_boxes as $meta_box ) {
				// Loop through the fields of the meta box
				foreach ( $meta_box['fields'] as $field ) {
					// Determine if the field is repeatable
					$repeatable = false;
					if ( isset( $field['repeatable'] ) && $field['repeatable'] === true ) {
						$repeatable = true;
					}
					$meta = $this->get_meta( $post->ID, $field['id'], $repeatable );
					$post->$field['id'] = $meta;
				}
			}
		}
	}

	/**
	 * Get meta value(s) for a posts field.
	 * 
	 * @param type $post_id Post ID
	 * @param type $meta_key Meta key
	 * @param bool $repeatable If the field is repeatable
	 * @return mixed Array of values if the field is repeatable,
	 * otherwise a single value or null if not found.
	 * 
	 * @uses get_post_meta()
	 */
	protected function get_meta( $post_id, $meta_key, $repeatable ) {
		$meta = get_post_meta( $post_id, $meta_key );

		// If this meta field is not repeatable
		if ( ! $repeatable ) {
			// Get the single value
			if ( count( $meta ) > 0 ) {
				$meta = $meta[0];
			} else {
				// No value found, return null.
				$meta = null;
			}
		}
		return $meta;
	}

}