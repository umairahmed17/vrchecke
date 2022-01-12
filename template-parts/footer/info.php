<?php
/**
 * Template part for displaying the footer info
 *
 * @package vrchecke
 */

namespace VRCHECKE\VRCHECKE;

?>

<div class="site-info">
    <a href="<?php echo esc_url( __( 'https://wordpress.org/', 'vrchecke' ) ); ?>">
        <?php
/* translators: %s: CMS name, i.e. WordPress. */
printf( esc_html__( 'Proudly powered by %s', 'vrchecke' ), 'WordPress' );
?>
    </a>
    <?php
/* translators: Theme name. */

if ( function_exists( 'the_privacy_policy_link' ) ) {
    the_privacy_policy_link( '<span class="sep"> | </span>' );
}
?>
</div><!-- .site-info -->