import { __ } from '@wordpress/i18n'
import PropTypes from 'prop-types'
import { TextControl, PanelBody, PanelRow } from '@wordpress/components'
import { useSelect } from '@wordpress/data'
import { useEntityProp } from '@wordpress/core-data'
import { useBlockProps, InspectorControls } from '@wordpress/block-editor'
import metadata from './block.json'
import json from '../../../data/review-definition'
import { Icon } from '../../components/Icon'
const { prefix, key, customFields } = json

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
	attributes: {
		width,
		height
	},
	setAttributes,
	isSelected,
	context: {
		postId,
		postType,
		queryId }
	} ) {

	const blockProps = useBlockProps()

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
		<p>{ `Error: This block must be used inside a ${label} post type query loop!` }</p>
	)

	// Setup field object.
	const [ meta ] = useEntityProp( 'postType', postType, 'meta', postId )
	let field = {}
	customFields.forEach( customField => {
		if ( customField[ 'block_name' ] === metadata.name ) {
			const metaKey = prefix + key + customField.suffix
			const value = meta[ metaKey ]
			field = customField
			field.metaKey = metaKey
			field.value = value
			field.media = useSelect( ( select ) => select( "core" ).getMedia( value ) )
		}
	} )

	const url = ( !! field.value && field.media?.source_url ) ? field.media.source_url : null

	const postEditUri = 'post.php?post=' + postId + '&action=edit'

	return (
		<>
			{ isSelected &&
				<InspectorControls>
					<PanelBody 
						title={ __( 'Settings' ) }
						initialOpen={true}
					>
						<PanelRow>
							<fieldset>
								<div { ...blockProps }>
									<TextControl
										label={ __( 'Width' ) }
										value={ width }
										onChange={ ( value ) => setAttributes( { width: value } ) }
										type="number"
									/>
									<TextControl
										label={ __( 'Height' ) }
										value={ height }
										onChange={ ( value ) => setAttributes( { height: value } ) }
										type="number"
									/>
								</div>
							</fieldset>
						</PanelRow>
						<PanelRow>
							<a
								href={ postEditUri }
							>
								{ __( 'Edit this service to set an icon' ) }
							</a>
						</PanelRow>
					</PanelBody>
				</InspectorControls>
			}

			{ url &&
				<div { ...blockProps }>
					<Icon
						url={ url }
						width={ width }
						height={ height }
					/>
				</div>
			}
		</>
	)
}

Edit.propTypes = {
	context: PropTypes.object,
	attributes: PropTypes.object,
	setAttributes: PropTypes.func,
	isSelected: PropTypes.bool,
}
