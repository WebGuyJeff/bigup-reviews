import { __ } from '@wordpress/i18n'
import PropTypes from 'prop-types'
import { compose } from '@wordpress/compose'
import { withSelect, withDispatch, useSelect } from '@wordpress/data'
import { PluginDocumentSettingPanel } from '@wordpress/edit-post'
import {
	AnyTextControl,
	EmailControl,
	UrlControl,
	NumberControl,
	DateControl,
	ImageControl
} from './Controls'
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
		fields[ customField.suffix ] = {
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
		}
	} )

	return(
		<PluginDocumentSettingPanel
			title={ __( 'Review Settings', 'bigup-reviews' ) } 
			initialOpen={ true }
		>

			<AnyTextControl data={ fields[ '_name' ] } />
			<EmailControl data={ fields[ '_email' ] } />
			<UrlControl data={ fields[ '_source_url' ] } />
			<AnyTextControl data={ fields[ '_ip' ] } />
			<DateControl data={ fields[ '_date' ] } />
			<NumberControl data={ fields[ '_rating' ] } />
			<ImageControl data={ fields[ '_avatar' ] } />

		</PluginDocumentSettingPanel>
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
