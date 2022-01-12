<?php
/**
 * The `vrchecke()` function.
 *
 * @package vrchecke
 */

namespace VRCHECKE\VRCHECKE;

use VRCHECKE\VRCHECKE\Theme;
/**
 * Provides access to all available template tags of the theme.
 *
 * When called for the first time, the function will initialize the theme.
 *
 * @return Template_Tags Template tags instance exposing template tag methods.
 */
function vrchecke(): Template_Tags {
	static $theme = null;

	if ( null === $theme ) {
		$theme = new Theme();
		$theme->initialize();
	}

	return $theme->template_tags();
}
