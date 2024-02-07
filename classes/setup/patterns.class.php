<?php
namespace BigupWeb\Reviews;
/**
 * Patterns Handler.
 * 
 * @package bigup-reviews
 */
use WP_Block_Pattern_Categories_Registry;

class Patterns {

	/**
	 * Pattern categories.
	 *
	 * @var array
	 */
	private $categories = array();

	/**
	 * The patterns array.
	 *
	 * File names inside the `patterns` directory.
	 *
	 * @var array
	 */
	private $patterns = array();


	/**
	 * Patterns constructor.
	 */
	public function __construct() {
		$this->setup_properties();
	}


	/**
	 * Register categories and patterns.
	 *
	 * @return void
	 */
	public function register_all() {
		$this->register_categories();
		$this->register_patterns();
	}


	/**
	 * Setup class properties.
	 *
	 * @return void
	 */
	private function setup_properties() {

		$this->categories = array(
			'bigup-reviews' => array(
				'label' => __( 'Bigup Web: Reviews', 'cpt-review' )
			),
		);

		$path = BIGUPREVIEWS_PATH . 'patterns';
		$files = ( file_exists( $path ) ) ? scandir( $path ) : array();
		if ( is_array( $files ) ) {
			$filenames = preg_replace( '/\..*/', '', $files );
			$this->patterns = array_filter( $filenames );
		}
	}


	/**
	 * Register block patterns categories.
	 *
	 * @return void
	 */
	private function register_categories() {
		foreach ( $this->categories as $slug => $args ) {
			if ( WP_Block_Pattern_Categories_Registry::get_instance()->is_registered( $slug ) ) {
				continue;
			}
			$test = register_block_pattern_category( $slug, $args );
		}
	}


	/**
	 * Register Patterns.
	 *
	 * @return void
	 */
	private function register_patterns() {
		foreach ( $this->patterns as $pattern ) {
			$file = BIGUPREVIEWS_PATH . 'patterns/' . $pattern . '.php';
			if ( ! is_file( $file ) ) {
				continue;
			}
			register_block_pattern(
				'bigup-reviews/' . $pattern,
				require $file
			);
		}
	}
}
