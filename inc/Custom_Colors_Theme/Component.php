<?php
/**
 * VRCHECKE\VRCHECKE\Custom_Logo\Component class
 *
 * @package vrchecke
 */

namespace VRCHECKE\VRCHECKE\Custom_Colors_Theme;

use VRCHECKE\VRCHECKE\Component_Interface;

/**
 * Class for adding custom logo support.
 *
 * @link https://codex.wordpress.org/Theme_Logo
 */
class Component implements Component_Interface {


	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'custom_color_theme';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'wp_enqueue_scripts', array( $this, 'action_add_custom_colors_to_theme' ) );
		// add_action( 'wp_head', [$this, 'debugging_theme'] );
	}

	/**
	 * Adds support for the Custom Logo feature.
	 */
	public function action_add_custom_colors_to_theme() {
		$font_color          = get_theme_mod( 'fontcolor_id' ) ? get_theme_mod( 'fontcolor_id' ) : '#0b2e4e'; // Assigning it to a variable to keep the markup clean.
		$heading_color       = get_theme_mod( 'headingcolor_id' ) ? get_theme_mod( 'headingcolor_id' ) : '#0b2e4e';
		$spanaccent_color    = get_theme_mod( 'spancolor_id' ) ? get_theme_mod( 'spancolor_id' ) : '#f60';
		$anchor_color        = get_theme_mod( 'anchorcolor_id' ) ? get_theme_mod( 'anchorcolor_id' ) : '#0073aa';
		$anchor_color_hover  = get_theme_mod( 'anchorcoloractive_id' ) ? get_theme_mod( 'anchorcoloractive_id' ) : '#f60';
		$anchor_color_active = get_theme_mod( 'anchorcolorvisited_id' ) ? get_theme_mod( 'anchorcolorvisited_id' ) : '#f60';
		$button_color        = get_theme_mod( 'buttoncolor_id' ) ? get_theme_mod( 'buttoncolor_id' ) : '#f60';
		$css                 = ( $font_color !== '' ) ? sprintf(
			'
	body{
		--global-font-color : %1s;
        --global-heading-color : %2s;
        --color-link: %3s;
        --color-link-visited : %4s;
        --color-link-active : %5s;
        --color-button : %6s;
	}
    .span--accent{
        --global-spanaccent-color: %7s;
    }
	',
			$font_color,
			$heading_color,
			$anchor_color,
			$anchor_color_hover,
			$anchor_color_active,
			$button_color,
			$spanaccent_color
		) : '';
		if ( $css ) {
			echo '<style id="theme_custom_colors">' . $css . '</style>';
		}
	}

	/**
	 * Class for debugging
	 */
	// public function debugging_theme()
	// {
	// echo '<pre>';
	// var_dump( get_theme_mod( 'fontcolor_id' ) );
	// echo '</pre>';
	// }
}
