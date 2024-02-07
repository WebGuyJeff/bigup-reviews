<?php
namespace BigupWeb\Reviews;

/**
 * Register custom post type.
 *
 * @package bigup-reviews
 */
class Custom_Post_Type {

	/**
	 * Custom post type key.
	 *
	 * @var string
	 */
	private $key = '';

	/**
	 * CPT definition formatted for the `$args` paramater of `register_post_type()`.
	 *
	 * @var array
	 */
	private $definition = '';

	/**
	 * Enabled taxonomies.
	 *
	 * @var array
	 */
	private $taxonomies = '';

	/**
	 * Menu icon.
	 *
	 * @var string
	 */
	private $icon = '';


	/**
	 * Register a custom post type.
	 *
	 * The passed CPT definition data is verbosely stored in the class properties before being used
	 * to register the CPT and hooks to integrate it into WP.
	 */
	public function __construct( $definition ) {
		$this->key        = $definition['key'];
		$this->definition = $definition['definition'];
		$this->taxonomies = $definition['definition']['taxonomies'];

		// Get menu icon svg and convert it to a data url.
		$svg        = Util::get_contents( BIGUPREVIEWS_PATH . 'assets/svg/cpt-review-menu-icon.svg' );
		$base64     = base64_encode( $svg );
		$data_url   = 'data:image/svg+xml;base64,' . $base64;
		$this->icon = $data_url;
	}


	/**
	 * Register the custom post type.
	 */
	public function register() {
		// Override the menu icon.
		$this->definition['menu_icon'] = $this->icon;
		register_post_type(
			$this->key,
			$this->definition
		);
		if ( in_array( 'category', $this->taxonomies, true ) ) {
			register_taxonomy_for_object_type( 'category', $this->key );
		}
		if ( in_array( 'post_tag', $this->taxonomies, true ) ) {
			register_taxonomy_for_object_type( 'post_tag', $this->key );
		}
	}


	/**
	 * Filter the allowed blocks for this post type.
	 *
	 * @param array $allowed_blocks The allowed blocks
	 * @param array $editor_context The editor context
	 */
	public function allowed_block_types( $allowed_blocks, $editor_context ) {
		$post_type = ( !! $editor_context->post ) ? $editor_context->post->post_type : false;
		if ( $post_type && $this->key === $post_type ) {
			$allowed_blocks = array(
				'core/paragraph',
				'core/list',
				'core/list-item',
			);
			return $allowed_blocks;
		} else {
			return;
		}
	}
}
