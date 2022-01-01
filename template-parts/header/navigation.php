<?php
/**
 * Template part for displaying the header navigation menu
 *
 * @package vrchecke
 */

namespace VRCHECKE\VRCHECKE;

if ( !vrchecke()->is_primary_nav_menu_active() ) {
    return;
}

?>

<nav id="<?php echo apply_filters( 'vrchecke_site_navigation_id', 'site-navigation' ); /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */ ?>"
    class="<?php echo apply_filters( 'vrchecke_site_navigation_classes', 'main-navigation nav--toggle-sub nav--toggle-small' ); /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */ ?>"
    aria-label="<?php esc_attr_e( 'Main menu', 'vrchecke' );?>" <?php
if ( vrchecke()->is_amp() ) {
    ?>
    [class]=" siteNavigationMenu.expanded ? 'main-navigation nav--toggle-sub nav--toggle-small nav--toggled-on' : 'main-navigation nav--toggle-sub nav--toggle-small' " <?php
}
?>>
    <?php
if ( vrchecke()->is_amp() ) {
    ?>
    <amp-state id="siteNavigationMenu">
        <script type="application/json">
        {
            "expanded": false
        }
        </script>
    </amp-state>
    <?php
}
?>

    <button class="menu-toggle" aria-label="<?php esc_html__( 'Open menu', 'vrchecke' );?>" aria-controls="primary-menu"
        aria-expanded="false">
        <?php esc_html__( 'Menu', 'vrchecke' );?>
        <span class="menu-toggle__lines"></span><span class="menu-toggle__lines"></span><span
            class="menu-toggle__lines"></span>
    </button>

    <?php echo $menu_toggle_button; /* phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped */ ?>
    <div class="primary-menu-container">
        <?php vrchecke()->display_primary_nav_menu( array( 'menu_id' => 'primary-menu' ) );?>
    </div>
</nav><!-- #site-navigation -->