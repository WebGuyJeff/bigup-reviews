<?php
namespace BigupWeb\CPT_Review;

/**
 * Register custom meta box for the classic editor.
 *
 * @package bigup-cpt-review
 */
class Editor_Classic {

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
	 * Metabox ID.
	 *
	 * @var string
	 */
	private $metabox_id = '';

	/**
	 * Custom field definitions.
	 *
	 * @var array
	 */
	private $fields = '';


	/**
	 * Register the custom meta box.
	 *
	 * The passed definition data is verbosely stored in the class properties before being used
	 * to setup the meta box with WP hooks.
	 */
	public function __construct( $definition ) {
		$this->key        = $definition['key'];
		$this->prefix     = $definition['prefix'];
		$this->metabox_id = $definition['metaboxID'];
		$this->fields     = $definition['customFields'];
	}


	/**
	 * Remove default custom fields meta box.
	 */
	public function remove_default_meta_box( $type, $context, $post ) {
		foreach ( array( 'normal', 'advanced', 'side' ) as $context ) {
			remove_meta_box( 'postcustom', $this->key, $context );
		}
	}


	/**
	 * Create new custom fields meta box.
	 */
	public function add_custom_meta_box() {
		add_meta_box(
			$this->metabox_id,                          // Unique ID.
			__( 'Custom Fields' ),                      // Box title.
			array( &$this, 'output_custom_meta_box' ),  // Markup callback.
			$this->key,                                 // Post type.
			'normal',                                   // Edit screen position (normal || side || advanced).
			'high',                                     // Priority within the position set above.
			array( '__back_compat_meta_box' => true ),  // hide the meta box in Gutenberg.
		);
	}


	/**
	 * Display the custom fields meta box.
	 */
	public function output_custom_meta_box() {
		global $post;
		?>
		<div class="form-wrap">
			<?php wp_nonce_field( $this->metabox_id, $this->metabox_id . '_wpnonce', false, true ); ?>
			<table class="form-table" role="presentation">
				<tbody>
					<?php
					foreach ( $this->fields as $field ) {
						$field['id'] = $this->prefix . $this->key . $field['suffix'];
						echo '<tr>';
						echo '<th scope="row">';
						echo '<label for="' . $field['id'] . '"><b>' . $field['label'] . '</b></label>';
						echo '</th>';
						echo '<td>';

						$value = get_post_meta( $post->ID, $field['id'], true );
						echo Get_Input::markup( $field, $value );

						if ( $field['description'] ) {
							echo '<p>' . $field['description'] . '</p>';
						}
						echo '</td>';
						echo '</tr>';
					} // foreach END.
					?>
				</tbody>
			</table>
		</div>
		<?php
	}


	/**
	 * Save the new custom field values.
	 */
	public function save_custom_meta_box_data( $post_id, $post ) {
		if ( ! isset( $_POST[ $this->metabox_id . '_wpnonce' ] )
			|| ! wp_verify_nonce( $_POST[ $this->metabox_id . '_wpnonce' ], $this->metabox_id )
			|| ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		foreach ( $this->fields as $field ) {
			$field['id'] = $this->prefix . $this->key . $field['suffix'];
			if ( isset( $_POST[ $field['id'] ] ) && trim( $_POST[ $field['id'] ] ) ) {
				$value = $_POST[ $field['id'] ];
				update_post_meta( $post_id, $field['id'], $value );
			} else {
				delete_post_meta( $post_id, $field['id'] );
			}
		}
	}
}
