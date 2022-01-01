<?php
/**
 * Error Tracking
 *
 * For managing, adding, and displaying error messages.
 */

/**
 * Stores error messages
 *
 * @since  1.0
 * @return WP_Error
 */
function vr_errors() {
	static $wp_error; // Will hold global variable safely
	return isset( $wp_error ) ? $wp_error : ( $wp_error = new WP_Error( null, null, null ) );
}

/**
 * Retrieves the HTML for error messages
 *
 * @param string $error_id
 *
 * @since  2.1
 * @return string
 */
function vr_get_error_messages_html( $error_id = '' ) {
	$html   = '';
	$errors = vr_errors()->get_error_codes();

	if ( $errors ) {

		$html .= '<div class="vr_message error-div" role="list">';
		// Loop error codes and display errors
		foreach ( $errors as $code ) {

			if ( vr_errors()->get_error_data( $code ) == $error_id ) {

				$message = vr_errors()->get_error_message( $code );

				$html .= '<p class="vr_error ' . esc_attr( $code ) . '" role="listitem"><span>' . $message . '</span></p>';

			}
		}

		$html .= '</div>';

	}

	return apply_filters( 'vr_error_messages_html', $html, $errors, $error_id );

}

/**
 * Displays the HTML for error messages
 *
 * @param string $error_id
 *
 * @since  1.0
 * @return void
 */
function vr_show_error_messages( $error_id = '' ) {
	if ( vr_errors()->get_error_codes() ) {
		do_action( 'vr_errors_before' );
		echo vr_get_error_messages_html( $error_id );
		do_action( 'vr_errors_after' );
	}
}
