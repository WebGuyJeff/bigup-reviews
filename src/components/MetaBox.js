import { __ } from '@wordpress/i18n'
import PropTypes from 'prop-types'
import { compose } from '@wordpress/compose'
import { withSelect, withDispatch, useSelect } from '@wordpress/data'
import { PluginDocumentSettingPanel } from '@wordpress/edit-post'
import { PanelRow, TextControl, Button, ResponsiveWrapper } from '@wordpress/components'
import { MediaUpload, MediaUploadCheck } from '@wordpress/block-editor'
import json from '../../data/review-definition'
const { prefix, key, customFields } = json

/**
 * Add a metabox for all post custom fields.
 * 
 * @see https://kinsta.com/blog/wordpress-add-meta-box-to-post/ 
 */
const MetaBox = ( { postType, metaFields, setMetaFields } ) => {

	if ( postType !== key ) return null

	// Dynamically build an object array of meta fields for output in the panel.
	let fields = []
	customFields.forEach( customField => {
		const metaKey = prefix + key + customField.suffix
		const value = metaFields[ metaKey ] || ''
		const inputType = customField.input_type
		fields.push( {
			'metaKey': metaKey,
			'value': value,
			'updateValue': ( newValue ) => setMetaFields( { [ metaKey ]: newValue } ),
			'label': customField.label,
			'description': customField.description,
			'type': inputType,
			'placeholder': customField?.placeholder || '',
			'required': customField?.required || '',
			'maxlength': customField?.length_limit || '',
			'max': customField?.max_value || '',
			'min': customField?.min_value || '',
			'step': customField?.value_step || '',
			'media': ( inputType === 'image-upload' ) ? useSelect( ( select ) => select( "core" ).getMedia( value ) ) : false,
		} )
	} )

	return(
		<>
			{ fields.map( ( field ) => (
				<PluginDocumentSettingPanel
					key={ field.metaKey }
					name={ field.metaKey + '-panel' }
					title={ field.label } 
					initialOpen={ true }
				>

					{ field.type === 'text' ||
					  field.type === 'email' ||
					  field.type === 'url' &&
						<PanelRow>
							<TextControl
								label={ field.description }
								value={ field.value }
								onChange={ field.updateValue }
								type={ field.type }
								placeholder={ field.placeholder }
								required={ field.required }
								maxLength={ field.maxlength }
							/>
						</PanelRow>
					}

					{ field.type === 'number' &&
						<PanelRow>
							<TextControl
								label={ field.description }
								value={ field.value }
								onChange={ field.updateValue }
								type={ field.type }
								placeholder={ field.placeholder }
								required={ field.required }
								max={ field.max_value }
								min={ field.min_value }
								step={ field.value_step }
							/>
						</PanelRow>
					}

					{ field.type === 'date' &&
						<PanelRow>
							<TextControl
								label={ field.description }
								value={ field.value }
								onChange={ field.updateValue }
								type={ field.type }
								placeholder={ field.placeholder }
								required={ field.required }
							/>
						</PanelRow>
					}

					{ field.type === 'number' &&
						<PanelRow>
							<TextControl
								label={ field.description }
								value={ field.value }
								onChange={ field.updateValue }
								type={ field.type }
								placeholder={ field.placeholder }
								required={ field.required }
								maxLength={ field.maxlength }
							/>
						</PanelRow>
					}

					{ field.type === 'image-upload' &&
						<>
							<PanelRow>
								<MediaUploadCheck>
									<MediaUpload
										onSelect={ ( newMedia ) => field.updateValue( newMedia.id ) }
										value={ field.value }
										allowedTypes={ [ 'image' ] }
										render={ ( { open } ) => (
											<Button 
												className={ ! field.value ? 'editor-post-featured-image__toggle' : 'editor-post-featured-image__preview' }
												onClick={ open }
											>
												{ ! field.value && __( 'Set an icon', 'bigup-cpt-service' ) }
												{ field.media !== undefined &&
												<ResponsiveWrapper
													naturalWidth={ field.media.media_details.width }
													naturalHeight={ field.media.media_details.height }
												>
													<img src={ field.media.source_url } />
												</ResponsiveWrapper>
											}
											</Button>
										) }
									/>
								</MediaUploadCheck>
							</PanelRow>

							{ field.value &&
								<PanelRow>
									<MediaUploadCheck>
										<MediaUpload
											title={__( 'Replace image', 'bigup-cpt-service' )}
											value={ field.value }
											onSelect={ ( newMedia ) => field.updateValue( newMedia.id ) }
											allowedTypes={ [ 'image' ] }
											render={ ( { open } ) => (
												<Button
													onClick={ open }
													variant="secondary" 
													isLarge
												>
													{ __( 'Replace icon', 'bigup-cpt-service' ) }
												</Button>
											) }
										/>
									</MediaUploadCheck>
									<MediaUploadCheck>
										<Button
											onClick={ () => field.updateValue( 0 ) }
											variant="secondary" 
											isLarge
										>
											{ __( 'Remove icon', 'bigup-cpt-service' ) }
										</Button>
									</MediaUploadCheck>
								</PanelRow>
							}
						</>
					}
				</PluginDocumentSettingPanel>
			) ) }
		</>
	)
}

const applyWithSelect = withSelect( ( select ) => {
	return {
		metaFields: select( 'core/editor' ).getEditedPostAttribute( 'meta' ),
		postType: select( 'core/editor' ).getCurrentPostType()
	}
} )

const applyWithDispatch = withDispatch( ( dispatch ) => {
	return {
		setMetaFields ( newValue ) {
			dispatch( 'core/editor' ).editPost( { meta: newValue } )
		}
	}
} )

export default compose( [
	applyWithSelect,
	applyWithDispatch
] )( MetaBox )

MetaBox.propTypes = {
	postType: PropTypes.string,
	metaFields: PropTypes.object,
	setMetaFields: PropTypes.func,
}
