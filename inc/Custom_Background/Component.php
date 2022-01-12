<?php
/**
 * VRCHECKE\VRCHECKE\Custom_Background\Component class
 *
 * @package vrchecke
 */

namespace VRCHECKE\VRCHECKE\Custom_Background;

use VRCHECKE\VRCHECKE\Component_Interface;

/**
 * Class for adding custom background support.
 */
class Component implements Component_Interface {


	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'custom_background';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_action( 'after_setup_theme', array( $this, 'action_add_custom_background_support' ) );
	}

	/**
	 * Adds support for the Custom Background feature.
	 */
	public function action_add_custom_background_support() {
		add_theme_support(
			'custom-background',
			apply_filters(
				'vrchecke_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);
	}
}
