<?php
namespace BigupWeb\Reviews;

/**
 * Initialise.
 *
 * @package bigup-reviews
 */
class Init {

	/**
	 * Relative path to the definition JSON file.
	 *
	 * @var string
	 */
	private $definition_path = 'data/review-definition.json';

	/**
	 * Stores the definition data.
	 */
	private array $def = array();


	/**
	 * Populate the properties of this class.
	 */
	public function __construct() {
		$this->def = $this->get_definition();
	}


	/**
	 * Setup this plugin by registering hooks.
	 */
	public function setup() {

		if ( ! is_array( $this->def ) || ! array_key_exists( 'key', $this->def ) ) {
			return;
		}

		$Custom_Post_Type = new Custom_Post_Type( $this->def );
		add_action( 'init', array( &$Custom_Post_Type, 'register' ), 0, 1 );
		add_filter( 'allowed_block_types_all', array( &$Custom_Post_Type, 'allowed_block_types' ), 25, 2 );

		// Opt-in to allow WP to selectively load and inline front end block styles.
		// Commented while debugging - needs testing.
		// add_filter( 'should_load_separate_core_block_assets', '__return_true' );

		$Blocks = new Blocks( $this->def );
		add_action( 'init', array( &$Blocks, 'register_all' ), 10, 0 );

		if ( ! array_key_exists( 'customFields', $this->def ) ) {
			error_log( 'BigupWeb\Reviews error: Could not retrieve post type definition' );
			return;
		}

		// Setup classic editor metabox.
		$Metabox_Classic = new Metabox_Classic( $this->def );
		add_action( 'do_meta_boxes', array( &$Metabox_Classic, 'remove_default_meta_box' ), 10, 3 );
		add_action( 'add_meta_boxes', array( &$Metabox_Classic, 'add_custom_meta_box' ), 10, 0 );
		add_action( 'save_post', array( &$Metabox_Classic, 'save_custom_meta_box_data' ), 1, 2 );

		// Setup gutenberg metabox.
		$Metabox = new Metabox( $this->def );
		add_action( 'init', array( &$Metabox, 'setup_custom_fields' ), 11, 0 );

		// Setup post list custom columns. Note the hook names that include the target post type name.
		add_filter( 'manage_review_posts_columns', array( $this, 'add_post_list_custom_columns' ), 10, 1 );
		add_action( 'manage_review_posts_custom_column', array( $this, 'define_post_list_custom_columns_data' ), 10, 2 );
		add_filter( 'manage_edit-review_sortable_columns', array( $this, 'make_post_list_custom_columns_sortable' ), 10, 1 );
		add_action( 'pre_get_posts', array( $this, 'define_post_list_custom_columns_sorting' ), 10, 1 );

		// Register patterns.
		add_action( 'init', array( new Patterns(), 'register_all' ) );

		// Register scripts and styles.
		add_action( 'enqueue_block_editor_assets', array( &$this, 'editor_scripts_and_styles' ) );

		// Enable WP custom fields even if ACF is installed.
		add_filter( 'acf/settings/remove_wp_meta_box', '__return_false' );
	}


	/**
	 * Get JSON definition, decode and return.
	 */
	private function get_definition() {
		$json       = Util::get_contents( BIGUPREVIEWS_PATH . $this->definition_path );
		$definition = json_decode( $json, true );
		return $definition;
	}


	/**
	 * Enqueue scripts for this plugin.
	 */
	public function editor_scripts_and_styles() {
		wp_enqueue_script( 'bigup_reviews_editor_js', BIGUPREVIEWS_URL . 'build/js/bigup-reviews-editor.js', array(), filemtime( BIGUPREVIEWS_PATH . 'build/js/bigup-reviews-editor.js' ), true );
	}


	/**
	 * Hook custom columns for the post list.
	 */
	public function add_post_list_custom_columns( $columns ) {
		$new_columns = array_merge( $columns, array(
			'name' => __( 'Name', 'bigup-reviews' ),
			'rating' => __( 'Rating', 'bigup-reviews' )
		) );

		// Move built-in columns to the end by removing and re-adding to the array.
		$categories = $new_columns[ 'categories' ];
		$tags = $new_columns[ 'tags' ];
		$date = $new_columns[ 'date' ];
		unset( $new_columns[ 'categories' ] );
		unset( $new_columns[ 'tags' ] );
		unset( $new_columns[ 'date' ] );
		$new_columns[ 'categories' ] = $categories;
		$new_columns[ 'tags' ] = $tags;
		$new_columns[ 'date' ] = $date;

		return $new_columns;
	}


	/**
	 * Configure data for the post list custom columns.
	 */
	public function define_post_list_custom_columns_data( $column_key, $post_id ) {
		if ( $column_key === 'name' ) {
			$name = get_post_meta( $post_id, '_bigup_review_name', true );
			if ( $name ) {
				echo $name;
			}
		} elseif ( $column_key === 'rating' ) {
			$rating = get_post_meta( $post_id, '_bigup_review_rating', true );
			if ( $rating ) {
				echo $rating;
			}
		}
	}


	/**
	 * Make custom post list columns sortable.
	 */
	public function make_post_list_custom_columns_sortable( $columns ) {
		$columns[ 'name' ] = 'name';
		$columns[ 'rating' ] = 'rating';
		return $columns;
	}


	/**
	 * Define custom columns SQL query post filter and sorting method.
	 * 
	 * We want to sort posts but not exclude any even if they have an empty value on the meta column
	 * being sorted. So we add conditions in the queries to match posts where the key 'EXISTS' or
	 * 'NOT EXISTS', then sort by either alphabetical ('meta_value') or numerical ('meta_value_num')
	 * order. This results in empty values being at the end of the sorted list, but not hidden.
	 */
	public function define_post_list_custom_columns_sorting( $query ) {
		$orderby = $query->get( 'orderby' );

		// Column: 'name'.
		if ( $orderby == 'name' ) {
			$query->set( 'meta_query', array(
				// OR to match any, AND to match all.
				'relation' => 'OR',
				// Include posts that have the meta.
				array(
					'key' => '_bigup_review_name',
					'compare' => 'EXISTS',
				),
				// Include posts that don't have the meta.
				array(
					'key' => '_bigup_review_name',
					'compare' => 'NOT EXISTS',
				),
			) );
			$query->set( 'orderby', 'meta_value' );
		}

		// Column: 'rating'.
		if ( $orderby == 'rating' ) {
			$query->set( 'meta_query', array(
				'relation' => 'OR',
				array(
					'key' => '_bigup_review_rating',
					'compare' => 'EXISTS',
				),
				array(
					'key' => '_bigup_review_rating',
					'compare' => 'NOT EXISTS',
				),
			) );
			$query->set( 'orderby', 'meta_value_num' );
		}
	}
}
