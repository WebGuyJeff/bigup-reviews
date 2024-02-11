import { __ } from '@wordpress/i18n'
import PropTypes from 'prop-types'
import { PanelBody, PanelRow } from '@wordpress/components'
import { useSelect } from '@wordpress/data'
import { useEntityProp } from '@wordpress/core-data'
import { InnerBlocks, useBlockProps, InspectorControls } from '@wordpress/block-editor'
import metadata from './block.json'
import json from '../../../data/review-definition'
const { prefix, label, key, customFields } = json

/**
 * Editor Handler.
 * 
 * This block provides a meta field for a custom post type. The block should never exist in the
 * custom post content, as it must be available to the query loop templates for layout customization
 * in the editor.
 * 
 *  - Content fields are only editable in the post type editor via the metabox panel.
 *  - Block attributes are only editable in all other editor contexts.
 * 
 * Inputs, values and setters are generated dynamically so that custom fields can be defined in
 * an external JSON file. The long-term plan is to enable users to define which fields they want to
 * include with the post type.
 *
 * This block consumes context from the query loop. See "usesContext" in block.json.
 * @link: https://github.com/WordPress/gutenberg/blob/trunk/packages/block-library/src/post-excerpt/block.json
 */
export default function Edit( {
	isSelected,
	context: {
		postId,
		postType,
		queryId
	}
	} ) {

	const blockProps = useBlockProps()

	const ALLOWED_BLOCKS = [
		'bigup-reviews/review-name',
		'bigup-reviews/review-avatar',
		'bigup-reviews/review-date',
		'bigup-reviews/review-rating',
		'bigup-reviews/review-source-url',
		'core/post-title',
		'core/post-content',
		'core/columns',
		'core/group',
		'core/heading',
		'core/paragraph',
		'core/spacer'
	]

	// Fallback to check if we're in the post editor for this CPT.
	const isPostEditorContext = ( key === useSelect( select => select( 'core/editor' ).getCurrentPostType() ) )
	if ( isPostEditorContext ) return (
		<p>{ `Error: This block cannot be used inside the ${label} post type!` }</p>
	)

	// Fallback to check if the block has been used outside of a query loop for the CPT.
	const isDescendentOfQueryLoop = Number.isFinite( queryId )
	const isCorrectPostType = ( key === postType )
	const isValidContext = ( isDescendentOfQueryLoop && isCorrectPostType )
	if ( ! isValidContext ) return (
		<p>{ `Error: This block must be used inside a ${label.toLowerCase()} post type query loop!` }</p>
	)

	const [ meta ] = useEntityProp( 'postType', postType, 'meta', postId )
	let field = {}
	customFields.forEach( customField => {
		if ( customField[ 'block_name' ] === metadata.name ) {
			field = customField
			field.metaKey = prefix + key + customField.suffix
			field.value = meta[ field.metaKey ]
		}
	} )

	const postEditUri = 'post.php?post=' + postId + '&action=edit'

	// Create a dropdown to select a single fixed review to display.
	const reviews = wp.data.select( 'core' ).getEntityRecords( 'postType', 'reviews' )

	return (
		<>
			{ isSelected &&
				<InspectorControls>
					<PanelBody 
						title={ __( 'Settings' ) }
						initialOpen={true}
					>
						<PanelRow>
							<a
								href={ postEditUri }
							>
								{ __( 'Edit this review' ) }
							</a>
						</PanelRow>
					</PanelBody>
				</InspectorControls>
			}

			<div { ...blockProps }>
				<InnerBlocks allowedBlocks={ ALLOWED_BLOCKS } />
			</div>
		</>
	)
}

Edit.propTypes = {
	context: PropTypes.object,
	isSelected: PropTypes.bool,
}
