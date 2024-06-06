const path = require( 'path' )
const BrowserSyncPlugin = require( 'browser-sync-webpack-plugin' )
// Import the @wordpress/scripts config.
const wordpressConfig = require( '@wordpress/scripts/config/webpack.config' )

// See svgo.config.js to configure SVG manipulation.

module.exports = {
	// Spread the WordPress config.
	...wordpressConfig,

	entry: {
		// @wordpress/scripts helper which generates entry points from any '**/block.json' in 'src'.
		...wordpressConfig.entry(),
		// Extend with new entrypoints.
		'js/bigup-reviews-editor': path.join( __dirname, '/src/bigup-reviews-editor.js' ),
	},
	plugins: [
		// Spread the WordPress plugins.
		...wordpressConfig.plugins,

		new BrowserSyncPlugin( {
			proxy: 'localhost:6969', // Live WordPress site. Using IP breaks it.
			ui: { port: 3001 }, // BrowserSync UI.
			port: 3000, // Dev port on localhost.
			logLevel: 'debug',
			reload: false, // false = webpack handles reloads (not sure if this works with files option).
			browser: "google-chrome-stable",
			files: [
				'src/**',
				'classes/**',
				'patterns/**',
				'parts/**',
				'templates/**',
				'**/**.json'
			]
		} )
	]
}
