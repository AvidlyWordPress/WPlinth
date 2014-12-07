<?php

namespace WPlinth;

/**
 * Base class for post types
 * @package WPlinth
 */
abstract class AbstractPostType extends AbstractWPlinthObject {

	protected $args;
	protected $names;
	protected $labels;

	public $meta_boxes = array();

	public function __construct() {
		if ( is_callable( array( $this, 'set_registration_parameters' ) ) ) {
			$this->set_registration_parameters();
		}

		add_action( 'init', array( $this, 'register_type' ), 1 );

		// Set meta_boxes instance variable always, even on front end.
		if ( is_callable( array( $this, 'set_meta_boxes' ) ) ) {
			$this->set_meta_boxes();
		}

		// We support both CMB and Meta Box. Choose one.
		add_filter( 'cmb_meta_boxes',  array( $this, 'register_meta_boxes' ) );
		add_filter( 'rwmb_meta_boxes', array( $this, 'register_meta_boxes' ) );
	}

	public function register_meta_boxes( $meta_boxes ) {
		return array_merge( $meta_boxes, $this->meta_boxes );
	}

	/**
	 * Register the post type
	 * 
	 * @param array $args 
	 * @param array $names 
	 * @param array $labels 
	 */
	public function register_type() {
		$class = get_called_class();
		$type = $class::TYPE;

		// Do nothing if the post type has already been registered.
		if ( post_type_exists( $type ) ) {
			return;
		}

		/**
		 * Default names
		 */
		$default_names = array(
				'name'             => 'Artikkeli',
				'plural'           => 'Artikkelit',
				'partitive'        => 'Artikkelia',
				'partitive_plural' => 'Artikkeleita',
			);
		$names = wp_parse_args( $this->names, $default_names );

		/**
		 * These only work for Finnish. If the original labels are not in Finnish, or are __("localised"),
		 * you'll need to override all the labels in the passed $labels
		 */
		$generated_labels = array(  
			'name'                  => $names[ 'plural' ],
			'singular_name'         => $names[ 'name'   ],
			'add_new'               => 'Lisää uusi',
			'add_new_item'          => 'Lisää uusi ' . mb_strtolower( $names[ 'name'      ] ),
			'edit_item'             => 'Muokkaa '    . mb_strtolower( $names[ 'partitive' ] ),
			'new_item'              => 'Uusi '       . mb_strtolower( $names[ 'name'      ] ),
			'all_items'             => 'Kaikki '     . mb_strtolower( $names[ 'plural'    ] ),
			'view_item'             => 'Näytä '      . mb_strtolower( $names[ 'name'      ] ),
			'search_items'          => 'Etsi '       . mb_strtolower( $names[ 'partitive' ] ),
			'not_found'             => $names[ 'partitive_plural' ] . ' ei löytynyt',
			'not_found_in_trash'    => $names[ 'partitive_plural' ] . ' ei löytynyt roskakorista',
			'parent_item_colon'     => '',
			'menu_name'             => $names[ 'plural' ]
		);
		$labels = wp_parse_args( $this->labels, $generated_labels );

		/**
		 * Default arguments for register_post_type. Override with $args
		 */
		$defaults = array(
				'label'               => $names[ 'plural' ],
				'labels'              => $labels,
				'public'              => true,
				'publicly_queryable'  => true,
				'exclude_from_search' => false,
				'show_ui'             => true, 
				'show_in_menu'        => true, 
				'query_var'           => true,
				'rewrite'             => array( 'slug' => $names[ 'plural' ] ),
				'capability_type'     => 'post',
				'has_archive'         => true, 
				'hierarchical'        => false,
				'menu_position'       => null,
				'supports'            => array( 'title', 'editor', 'author', 'excerpt', 'comments', 'thumbnail' )
			);
		$args = wp_parse_args( $this->args, $defaults );

		register_post_type( $type, $args );
	}
}