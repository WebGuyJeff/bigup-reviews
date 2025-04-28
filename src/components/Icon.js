import PropTypes from 'prop-types'
// import 'external-svg-loader'


const Icon = ( { url, width, height } ) => {

	const isSVG = ( url ) => {
		return /\.svg$/.test( url )
	}

	// SVG image.
	if ( isSVG( url ) ) {
		// Get the SVG using external-svg-loader.
		return (
			<svg
				data-src={ url }
				width={ width }
				height={ height }
				data-loading="lazy"
			/>
		)

	// Non-SVG image.
	} else {
		return(
			<img
				src={ url }
				width={ width }
				height={ height }
			/>
		)
	}
}

export { Icon }

Icon.propTypes = {
	url: PropTypes.string,
	width: PropTypes.number,
	height: PropTypes.number,
}
