<?php
/**
 * The template for displaying all pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package vrchecke
 */

namespace VRCHECKE\VRCHECKE;

get_header();

vrchecke()->print_styles( 'vrchecke-content' );

?>
<main id="primary-ad" class="site-main">
	<?php

	while ( have_posts() ) {
		the_post();
		get_template_part( 'template-parts/customer/entry', 'portal' );
	}
	?>
</main><!-- #primary -->
<?php
get_sidebar();
get_footer();
