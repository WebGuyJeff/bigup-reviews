/**
 * This config file is neccessary to control how SVGO (used by SVGR in WP scripts) manipulates SVG
 * when converted to a React component using "import { ReactComponent as Eg } from './svg/eg.svg'"
 */

module.exports = {
    plugins: [
		'removeDoctype',
		'removeXMLProcInst',
		'removeComments',
		'removeMetadata',
		'removeEditorsNSData',
		'cleanupAttrs',
		// 'inlineStyles',
		'minifyStyles',
		// 'cleanupIds',
		'removeUselessDefs',
		// 'cleanupNumericValues',
		'convertColors',
		// 'removeUnknownsAndDefaults',
		'removeNonInheritableGroupAttrs',
		'removeUselessStrokeAndFill',
		// 'removeViewBox',
		'cleanupEnableBackground',
		// 'removeHiddenElems',
		'removeEmptyText',
		'convertShapeToPath',
		'convertEllipseToCircle',
		'moveElemsAttrsToGroup',
		'moveGroupAttrsToElems',
		/*
		 * 'collapseGroups',
		 * 'convertPathData',
		 * 'convertTransform',
		 */
		'removeEmptyAttrs',
		'removeEmptyContainers',
		// 'mergePaths',
		'removeUnusedNS',
		'sortDefsChildren',
		'removeTitle',
		'removeDesc'
    ]
}
