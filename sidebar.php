<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package vrchecke
 */

namespace VRCHECKE\VRCHECKE;

if ( ! vrchecke()->is_primary_sidebar_active() ) {
	return;
}

vrchecke()->print_styles( 'vrchecke-sidebar', 'vrchecke-widgets' );

?>
<aside id="secondary" class="primary-sidebar widget-area">
	<h2 class="screen-reader-text"><?php esc_attr_e( 'Asides', 'vrchecke' ); ?></h2>
	<?php vrchecke()->display_primary_sidebar(); ?>
</aside><!-- #secondary -->
