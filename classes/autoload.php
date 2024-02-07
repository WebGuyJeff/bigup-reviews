<?php
 /**
 * Bigup Forms - Class autoload by namesapce.
 *
 * - Sub-directories are searched recursively.
 * - Classes are denoted by the suffix .class.php.
 *
 * @package bigup-reviews
 * @author Jefferson Real <me@jeffersonreal.uk>
 * @copyright Copyright (c) 2024, Jefferson Real
 * @license GPL3+
 * @link https://jeffersonreal.uk
 *
 * @param string $full_classname A fully-qualified class name e.g. 'Brand\\Project\\Class'.
 * @param string $namespace The namespace e.g. 'Brand\\Project\\'.
 * @param string $root Directory to recursively search.
 */

spl_autoload_register(
	function ( $full_classname ) use ( $namespace, $root ) {

		if ( strpos( $full_classname, $namespace ) !== 0 ) {
			return;
		}

		$classname = substr( $full_classname, strlen( $namespace ) );
		$filename  = strtolower( str_replace( '_', '-', $classname ) ) . '.class.php';

		$tree = new RecursiveDirectoryIterator( $root );
		foreach ( new RecursiveIteratorIterator( $tree ) as $file ) {
			if ( $file->getFilename() === $filename ) {
				include_once $file->getPathname();
				break;
			}
		}
	}
);
