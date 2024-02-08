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

		$Blocks = new Blocks( $this->def );
		add_action( 'init', array( &$Blocks, 'register_all' ), 10, 0 );

		if ( ! array_key_exists( 'customFields', $this->def ) ) {
			return;
		}
		$Metabox_Classic = new Metabox_Classic( $this->def );
		add_action( 'do_meta_boxes', array( &$Metabox_Classic, 'remove_default_meta_box' ), 10, 3 );
		add_action( 'add_meta_boxes', array( &$Metabox_Classic, 'add_custom_meta_box' ), 10, 0 );
		add_action( 'save_post', array( &$Metabox_Classic, 'save_custom_meta_box_data' ), 1, 2 );

		$Metabox = new Metabox( $this->def );
		add_action( 'init', array( &$Metabox, 'setup_custom_fields' ), 11, 0 );

		add_action( 'init', array( new Patterns(), 'register_all' ) );
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
		wp_enqueue_style( 'bigup_reviews_editor_css', BIGUPREVIEWS_URL . 'build/css/bigup-reviews-editor.css', array(), filemtime( BIGUPREVIEWS_PATH . 'build/css/bigup-reviews-editor.css' ), 'all' );
	}
}
