<?php
/**
 * VRCHECKE\VRCHECKE\Custom_Logo\Component class
 *
 * @package vrchecke
 */

namespace VRCHECKE\VRCHECKE\VRCHECKE_Form;

use function VRCHECKE\VRCHECKE\vrchecke;
use VRCHECKE\VRCHECKE\Component_Interface;

/**
 * Class for adding form.
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
		return 'vrchecke_form';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'wp_enqueue_scripts', array( $this, 'action_enqueue_form_scripts' ) );
		add_shortcode( 'vrchecke_form', array( $this, 'shortcode_form' ) );
		$this->require_function_files();
	}

	/**
	 * Registering Styles and Scripts
	 */
	public function action_enqueue_form_scripts() {
		 wp_register_style( 'vrchecke_form_style', get_stylesheet_directory_uri() . '/assets/css/vrchecke-form.min.css', vrchecke()->get_asset_version( get_theme_file_path( '/assets/css/vrchecke-form.min.css' ) ) );
		wp_register_script( 'vrchecke_form_script', get_stylesheet_directory_uri() . '/assets/js/vrchecke-form.min.js', vrchecke()->get_asset_version( get_theme_file_path( '/assets/js/vrchecke-form.min.js' ) ), array( 'jquery' ), false );
		wp_localize_script(
			'vrchecke_form_script',
			'vrchecke_script_options',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
			)
		);
		wp_script_add_data( 'vrchecke_form_script', 'defer', true );
	}

	/**
	 * Add Shortcode for form
	 */
	public function shortcode_form() {
		// wp_enqueue_style( 'vrchecke_form_style' );
		wp_enqueue_script( 'vrchecke_form_script' );
		get_template_part( 'template-parts/form/form' );

	}
	/**
	 * Requiring function files for form
	 */
	private function require_function_files() {
		 require_once __DIR__ . '/inc/ajax-actions.php';
		require_once __DIR__ . '/inc/error-tracking.php';
	}

	/**
	 * Class for debugging
	 */
	// public function adding_pages_to_ezjson()
	// {
	// $defaults = array(
	// 'depth'                 => 0,
	// 'child_of'              => 0,
	// 'selected'              => 0,
	// 'echo'                  => 1,
	// 'name'                  => 'page_id',
	// 'id'                    => '',
	// 'class'                 => '',
	// 'show_option_none'      => '',
	// 'show_option_no_change' => '',
	// 'option_none_value'     => '',
	// 'value_field'           => 'ID',
	// );

	// $pages = get_pages( $defaults );
	// $arr   = [];
	// foreach ( $pages as $page ) {
	// $arr[$page->ID] = $page->post_title;
	// }
	// $customizer_data                           = json_decode( file_get_contents( __DIR__ . '/../EZ_Customizer/themeCustomizeSettings.json' ), true );
	// $customizer_data['settings'][7]['choices'] = $arr;
	// file_put_contents( __DIR__ . '/../EZ_Customizer/themeCustomizeSettings.json', json_encode( $customizer_data ) );
	// }
}
