import { __ } from '@wordpress/i18n'
import { registerBlockCollection, registerBlockType } from '@wordpress/blocks'
import { InnerBlocks } from '@wordpress/block-editor'
import {
	Logo,
	Icon
} from './svg'
import Edit from './edit'
import metadata from './block.json'

/*
 * Debug.
 * console.log( metadata.name + ' BLOCK LOADED' )
 * RUN IN CONSOLE TO SEE REGISTERED BLOCKS: wp.blocks.getBlockTypes() 
 */

/**
 * Register the collection.
 * 
 * COLLECTIONS ARE NOT CATEGORIES!
 * @link https://make.wordpress.org/core/2020/02/27/block-collections/
 */
registerBlockCollection(
	'bigupweb',
	{
		title: __( 'Bigup Web' ),
		icon: Logo
	}
)

registerBlockType( metadata.name, {
	...metadata,
	icon: Icon,

	/**
	 * @see ./edit.js
	 */
	edit: Edit,

	/*
	 * This is a dynamic content block meaning the data is rendered server-side at runtime. This
	 * block forms a 'template' for the dynamic post data retrieved by a query-loop. The output of the
	 * block in the editor is defined by the Edit function above. The output of the block on the
	 * frontend is defined by the render_callback function. See PHP function register_block_type().
	 */
	save: props => <InnerBlocks.Content />
} )
