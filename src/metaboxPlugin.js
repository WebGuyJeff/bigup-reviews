/**
 * Register a plugin to add items to the Gutenberg Toolbar.
 * 
 * This main index file is enqueued by the PHP Init class.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/slotfills/plugin-sidebar/
 */
import { registerPlugin } from '@wordpress/plugins'
import MetaBox from './components/MetaBox'

registerPlugin( 'bigup-cpt-review-metabox', {
	render: MetaBox
} )
