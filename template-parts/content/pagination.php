<?php
/**
 * Template part for displaying a pagination
 *
 * @package vrchecke
 */

namespace VRCHECKE\VRCHECKE;

the_posts_pagination(
	array(
		'mid_size'           => 2,
		'prev_text'          => _x( 'Previous', 'previous set of search results', 'vrchecke' ),
		'next_text'          => _x( 'Next', 'next set of search results', 'vrchecke' ),
		'screen_reader_text' => __( 'Page navigation', 'vrchecke' ),
	)
);
