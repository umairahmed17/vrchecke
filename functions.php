<?php
/**
 * VRCHECKE functions and definitions
 *
 * This file must be parseable by PHP 5.2.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package vrchecke
 */

define( 'VRCHECKE_MINIMUM_WP_VERSION', '4.7' );
define( 'VRCHECKE_MINIMUM_PHP_VERSION', '7.0' );

// Bail if requirements are not met.
if ( version_compare( $GLOBALS['wp_version'], VRCHECKE_MINIMUM_WP_VERSION, '<' ) || version_compare( phpversion(), VRCHECKE_MINIMUM_PHP_VERSION, '<' ) ) {
    require get_template_directory() . '/inc/back-compat.php';
    return;
}

// Include WordPress shims.
require get_template_directory() . '/inc/wordpress-shims.php';

// Setup autoloader (via Composer or custom).
/**
 * Custom autoloader function for theme classes.
 *
 * @access private
 *
 * @param string $class_name Class name to load.
 * @return bool True if the class was loaded, false otherwise.
 */
function _vrchecke_autoload( $class_name )
{
    $namespace = 'VRCHECKE\VRCHECKE';

    if ( strpos( $class_name, $namespace . '\\' ) !== 0 ) {
        return false;
    }

    $parts = explode( '\\', substr( $class_name, strlen( $namespace . '\\' ) ) );

    $path = get_template_directory() . '/inc';
    foreach ( $parts as $part ) {
        $path .= '/' . $part;
    }
    $path .= '.php';

    if ( !file_exists( $path ) ) {
        return false;
    }

    require_once $path;

    return true;
}
require_once get_template_directory() . '/vendor/autoload.php';
spl_autoload_register( '_vrchecke_autoload' );

// Load the `vrchecke()` entry point function.
require get_template_directory() . '/inc/functions.php';

// Initialize the theme.
call_user_func( 'VRCHECKE\VRCHECKE\vrchecke' );

// add_action( 'wp_head', function () {
//     echo '<pre>';
//     var_dump( get_theme_mod( 'vrchecke_video_section' ) );
//     echo '</pre>';
// } );