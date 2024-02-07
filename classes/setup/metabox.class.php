<?php
namespace BigupWeb\Reviews;

/**
 * Register custom meta box for the Gutenberg editor.
 *
 * @package bigup-reviews
 */
class Metabox {

	/**
	 * Custom post type key.
	 *
	 * @var string
	 */
	private $key = '';

	/**
	 * Prefix for storing custom fields in the postmeta table.
	 *
	 * @var string
	 */
	private $prefix = '';

	/**
	 * Custom field definitions.
	 *
	 * @var array
	 */
	private $custom_fields = '';


	/**
	 * Register the post meta for block support.
	 *
	 * The passed definition data is verbosely stored in the class properties before being used
	 * to setup the post meta with WP hooks.
	 */
	public function __construct( $definition ) {
		$this->key           = $definition['key'];
		$this->prefix        = $definition['prefix'];
		$this->custom_fields = $definition['customFields'];
	}


	/**
	 * Register metafield on the post type.
	 */
	public function setup_custom_fields() {
		foreach ( $this->custom_fields as $metafield ) {
			$user_capabilities = $metafield['user_capabilities'];
			$sanitize_callback = Sanitize::get_callback( $metafield['input_type'] );
			register_post_meta(
				$this->key,                                                 // Post type.
				$this->prefix . $this->key . $metafield['suffix'],          // Metafield key.
				array(
					'type'              => $metafield['type'],              // The type of data.
					'description'       => $metafield['description'],       // A description of the data.
					'sanitize_callback' => $sanitize_callback,              // The sanitize callback.
					'show_in_rest'      => $metafield['show_in_rest'],      // Show in REST API. Must be true for Gut.
					'single'            => $metafield['single'],            // Single value or array of values?
					'auth_callback'     => function() use ( $user_capabilities ) {
						return current_user_can( $user_capabilities );
					},
				)
			);
		}
	}
}
