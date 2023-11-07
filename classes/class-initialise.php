<?php
namespace BigupWeb\CPT_Review;

/**
 * Initialise.
 *
 * @package cpt-review
 */
class Initialise {

	/**
	 * Relative path to the definition JSON file.
	 *
	 * @var string
	 */
	private $definition_path = 'data/review-definition.json';


	/**
	 * Setup this plugin.
	 *
	 * Get and check definition, then call functions to register CPT and custom fields.
	 * All action hooks for this plugin should be registered here to manage sequence.
	 */
	public function __construct() {
		$def = $this->get_definition();

		if ( ! is_array( $def ) || ! array_key_exists( 'key', $def ) ) {
			return;
		}

		$cpt = new Custom_Post_Type( $def );
		add_action( 'init', array( $cpt, 'register' ), 0, 1 );

		if ( ! array_key_exists( 'customFields', $def ) ) {
			return;
		}

		$classic = new Editor_Classic( $def );
		add_action( 'do_meta_boxes', array( &$classic, 'remove_default_meta_box' ), 10, 3 );
		add_action( 'add_meta_boxes', array( &$classic, 'add_custom_meta_box' ), 10, 0 );
		add_action( 'save_post', array( &$classic, 'save_custom_meta_box_data' ), 1, 2 );

		$gutenberg = new Editor_Gutenberg( $def );
		add_action( 'init', array( &$gutenberg, 'setup_custom_fields' ), 11, 0 );
		add_filter( 'allowed_block_types_all', array( &$gutenberg, 'allowed_block_types' ), 25, 2 );

		// Enable WP custom fields even if ACF is installed.
		add_filter( 'acf/settings/remove_wp_meta_box', '__return_false' );

		add_action( 'enqueue_block_editor_assets', array( &$this, 'bigup_cpt_review_enqueue_scripts' ) );
	}


	/**
	 * Get JSON definition, decode and return.
	 */
	private function get_definition() {
		$json       = Util::get_contents( CPTREV_DIR . $this->definition_path );
		$definition = json_decode( $json, true );
		return $definition;
	}


	/**
	 * Enqueue scripts for this plugin.
	 */
	public function bigup_cpt_review_enqueue_scripts() {
		wp_register_script( 'bigup_cpt_review_js', CPTREV_URL . 'build/metaboxPlugin.js', array(), filemtime( CPTREV_DIR . 'build/metaboxPlugin.js' ), true );
		wp_enqueue_script( 'bigup_cpt_review_js' );
	}
}
