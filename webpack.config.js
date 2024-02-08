const path = require( 'path' )

// Import the @wordpress/scripts config.
const wordpressConfig = require( '@wordpress/scripts/config/webpack.config' )

// Import the utility to auto-generate the entry points in the src directory.
const { getWebpackEntryPoints } = require( '@wordpress/scripts/utils/config' )

module.exports = {
	// Spread the existing WordPress config.
	...wordpressConfig,

	entry: {
		// Spread the auto-generated entrypoints.
		...getWebpackEntryPoints(),

		// Extend with new entrypoints.
		'bigup-reviews-editor': path.resolve( process.cwd(), 'src', 'bigup-reviews-editor.js' ),
	},
}
