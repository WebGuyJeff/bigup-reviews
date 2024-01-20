<?php
namespace BigupWeb\Reviews;

/**
 * Utilities.
 *
 * @package bigup-cpt-review
 */
class Util {


	/**
	 * Retrieve file contents the 'WordPress way'.
	 * 
	 * @param string $path File system path.
	 */
	public static function get_contents( $path ) {
		include_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
		include_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';
		if ( ! class_exists( 'WP_Filesystem_Direct' ) ) {
			return false;
		}
		$wp_filesystem = new \WP_Filesystem_Direct( null );
		$string        = $wp_filesystem->get_contents( $path );
		return $string;
	}
}
