<?php
namespace BigupWeb\Reviews;

/**
 * Register Gutenberg blocks.
 *
 * @package bigup-reviews
 */
class Blocks {

	/**
	 * Blocks root relative path.
	 *
	 * @var string
	 */
	const BIGUPREVIEWS_BLOCKS_PATH = BIGUPREVIEWS_PATH . 'build/blocks/';

	/**
	 * Block directory names.
	 * 
	 * @var array
	 */
	private array $names = array();

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
	 * Setup the class.
	 */
	public function __construct( $definition ) {
		$dir_children        = is_dir( self::BIGUPREVIEWS_BLOCKS_PATH ) ? scandir( self::BIGUPREVIEWS_BLOCKS_PATH ) : array();
		$this->names         = array_filter( preg_replace( '/\..*/', '', $dir_children ) );
		$this->key           = $definition['key'];
		$this->prefix        = $definition['prefix'];
		$this->custom_fields = $definition['customFields'];
	}


	/**
	 * Register all blocks.
	 */
	public function register_all() {
		if ( count( $this->names ) === 0 ) {
			error_log( 'Bigup Reviews ERROR: No child directories detected in block directory. Please check blocks exist in {self::BIGUPREVIEWS_BLOCKS_PATH}' );
			return;
		}
		foreach ( $this->names as $name ) {
			$result = register_block_type_from_metadata(
				self::BIGUPREVIEWS_BLOCKS_PATH . $name,
				array( 'render_callback' => array( $this,'dynamic_render_callback' ) )
			);

			if ( false === $result ) {
				error_log( "Bigup Reviews ERROR: Block registration failed for '{$name}'" );

			}
		}
	}


	/**
	 * Dynamic server-side render callback.
	 *
	 * Builds markup for the dynamic content when called by the render_callback of register_block_type().
	 *
	 * @param array $attributes Attributes that relate to the block.
	 * @param array $content Content to be inserted into the markup.
	 * @param array $block Registered block definition and settings.
	 *
	 * @link https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/creating-dynamic-blocks/
	 */
	public function dynamic_render_callback( $attributes, $content, $block ) {

		// Check if the calling block has a matching custom field, then get it's value.
		$field = array();
		$value = '';
		foreach ( $this->custom_fields as $custom_field ) {
			if ( $block->name === $custom_field['block_name'] ) {
				$field           = $custom_field;
				$context_post_id = $block->context['postId'];
				$meta_key        = $this->prefix . $this->key . $field['suffix'];
				$value           = get_post_meta( $context_post_id, $meta_key, true );
			}
		}

		// Build and return the front-end block markup.
		$block_attrs = get_block_wrapper_attributes();
		$output      = '';

		switch ( $block->name ) {

			// The parent block review wrapper.
			case 'bigup-reviews/review':

				// Need to add innerBlocks here.

				$output .= '<div ' . $block_attrs . '></div>';
				break;

			case 'bigup-reviews/review-name':
				if ( ! empty( $value ) ) {
					$output .= '<p ' . $block_attrs . '><em> ~ ' . esc_html( $value ) . '</em></p>';
				}
				break;

			case 'bigup-reviews/review-date':
				if ( ! empty( $value ) ) {
					$output .= '<p ' . $block_attrs . '><em> ~ ' . esc_html( $value ) . '</em></p>';
				}
				break;

			case 'bigup-reviews/review-source-url':
				if ( ! empty( $value ) ) {
					$output .= '<a ' . $block_attrs . ' ' .
					'style="borderStyle:none; borderWidth:0px;" ' .
					'href="' . esc_url( $value ) . '"' .
					'rel="noreferrer"' .
					'target="_blank"' .
					'>' .
					$attributes['linkText'] .
					'</a>';
				}
				break;

			case 'bigup-reviews/review-rating':
				if ( ! empty( $value ) ) {

error_log( serialize( $block_attrs ) );

					$output .= <<<RATING
						<div class="ratingControl">
							<input
								class="ratingControl_input"
								style="--value: {$value}"
								type={ 'range' }
								readOnly
							/>
						</div>
					RATING;
				}
				break;
			
			case 'bigup-reviews/review-avatar':
				if ( ! empty( $value ) ) {
					$attachment_id  = $value;
					$url            = wp_get_attachment_url( $attachment_id );
					$ext            = pathinfo( $url, PATHINFO_EXTENSION );
					$style          = 'style="display:inline-block;"';
					$attrs          = get_block_wrapper_attributes();
					$markup         = '';

					// SVG.
					if ( 'svg' === $ext ) {
						$markup = "<svg" .
							" data-src={$url}" .
							" width={$attributes['width']}" .
							" height={$attributes['height']}" .
							" data-loading='lazy' data-cache='disabled'" .
							"></svg>";

					// Non-SVG image.
					} else {
						$markup = wp_get_attachment_image( 
							$attachment_id,                          // Attachment id.
							$size = 'bigup_service_icon',            // Size.
							$icon = true,                            // Treat image as an icon.
							$attr = array(
								'alt' => $field['label'] .  ' icon', // alt text.
							),
						);
					}

					if ( strlen( $markup ) > 0 ) {
						$output .= '<div ' . $style . ' ' . $attrs . '>' . $markup . '</div>';
					}
				}
				break;
		}

		return $output;
	}
}
