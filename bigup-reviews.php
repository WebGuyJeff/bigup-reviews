<?php
namespace BigupWeb\Reviews;

/**
 * Plugin Name:       Bigup Reviews
 * Description:       A custom 'Reviews' post type with custom meta fields.
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Version:           0.1.2
 * Author:            Jefferson Real
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       bigup-cpt-review
 *
 * @package           bigup-cpt-review
 * @link              https://kinsta.com/blog/dynamic-blocks/
 * @link              https://kinsta.com/blog/wordpress-add-meta-box-to-post/
 * @link              https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/creating-dynamic-blocks/
 */

$enable_debug = false;

// Define constants.
define( 'CPTREV_DEBUG', defined( 'WP_DEBUG' ) && WP_DEBUG === true && $enable_debug );
define( 'CPTREV_DIR', trailingslashit( __DIR__ ) );
define( 'CPTREV_URL', trailingslashit( get_site_url( null, strstr( __DIR__, '/wp-content/' ) ) ) );

// Setup PHP namespace.
require_once CPTREV_DIR . 'classes/autoload.php';

// Setup this plugin.
$Init = new Init();
$Init->setup();
