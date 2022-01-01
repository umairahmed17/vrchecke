<?php
/**
 * Render your site front page, whether the front page displays the blog posts index or a static page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#front-page-display
 *
 * @package vrchecke
 */

namespace VRCHECKE\VRCHECKE;

get_header();

// Use grid layout if blog index is displayed.
if ( is_home() ) {
    vrchecke()->print_styles( 'vrchecke-content', 'vrchecke-front-page' );
} else {
    vrchecke()->print_styles( 'vrchecke-content' );
}

?>
<main id="primary" class="site-main">

    <?php
do_shortcode( '[vrchecke_form]' );
?>
    <div class="primary-container">
        <?php
while ( have_posts() ) {
    the_post();
    get_template_part( 'template-parts/content/entry', get_post_type() );

}

get_template_part( 'template-parts/content/pagination' );
?>
    </div>
</main><!-- #primary -->
<?php
get_footer();