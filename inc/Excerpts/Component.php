<?php
/**
 * VRCHECKE\VRCHECKE\Excerpts\Component class
 *
 * @package vrchecke
 */

namespace VRCHECKE\VRCHECKE\Excerpts;

use function VRCHECKE\VRCHECKE\vrchecke;
use VRCHECKE\VRCHECKE\Component_Interface;

/**
 * Class for adjusting size and format for post excerpt.
 */
class Component implements Component_Interface {


	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'excerpts';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		add_filter( 'excerpt_more', array( $this, 'new_excerpt_more' ) );
	}

	/**
	 * Custom Excerpt ending.
	 */
	public function new_excerpt_more() {
		$link = sprintf(
			'<a href="%1$s" class="more-link">%2$s</a>',
			esc_url( get_permalink( get_the_ID() ) ),
			/* translators: %s: Post title. */
			sprintf( __( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'vrchecke' ), get_the_title( get_the_ID() ) )
		);
		return ' &hellip; ' . $link;
	}

}
