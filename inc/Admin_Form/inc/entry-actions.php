<?php
/**
 * Entry Actions
 */
namespace VRCHECKE\VRCHECKE\Admin_Form\Entry_Actions;

use function VRCHECKE\VRCHECKE\Customer_Portal\updating_customer_single_entrydata;
use VRCHECKE\VRCHECKE\Admin_Form\Entries_Table;
if ( ! class_exists( 'VRCHECKE\VRCHECKE\Admin_Form\Entries_Table' ) ) {
	include_once __DIR__ . '/class-form-entry-list.php';
}
/**
 * Process editing an entry.
 *
 * @return void
 */
function vrchecke_process_edit_entry( $post ) {
	if ( ! wp_verify_nonce( $post['vrchecke_edit_entry_nonce'], 'vrchecke_edit_entry_nonce' ) ) {
		wp_die( __( 'Nonce verification failed.', 'vrchecke' ), __( 'Error', 'vrchecke' ), array( 'response' => 403 ) );
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( __( 'You do not have permission to perform this action.', 'vrchecke' ), __( 'Error', 'vrchecke' ), array( 'response' => 403 ) );
	}

	if ( empty( $post['entry_id'] ) ) {
		wp_die( __( 'Invalid entry ID.', 'vrchecke' ), __( 'Error', 'vrchecke' ), array( 'response' => 400 ) );
	}

	$entry_id = absint( $post['entry_id'] );
	$entry    = vrchecke_get_single_entry( $entry_id );

	if ( empty( $entry ) ) {
		wp_die( __( 'Invalid entry.', 'vrchecke' ), __( 'Error', 'vrchecke' ), array( 'response' => 400 ) );
	}

	$user         = get_userdata( $entry->get_user_id() );
	$current_user = wp_get_current_user();

	/**
	 * Maybe update user account record.
	 */
	$user_args  = array();
	$first_name = ! empty( $post['first_name'] ) ? $post['first_name'] : '';
	$last_name  = ! empty( $post['last_name'] ) ? $post['last_name'] : '';
	$email      = ! empty( $post['user_email'] ) ? $post['user_email'] : '';

	if ( $user->first_name != $first_name ) {
		$user_args['first_name'] = sanitize_text_field( $first_name );
	}

	if ( $user->last_name != $last_name ) {
		$user_args['last_name'] = sanitize_text_field( $last_name );
	}

	if ( $user->user_email != $email && is_email( $email ) ) {
		$user_args['user_email'] = sanitize_text_field( $email );
	}

	$display_name = trim( $first_name . ' ' . $last_name );
	if ( empty( $display_name ) ) {
		$display_name = $user->user_login;
	}
	$user_args['display_name'] = sanitize_text_field( trim( $display_name ) );

	if ( ! empty( $user_args ) ) {
		$user_args['ID'] = $user->ID;

		wp_update_user( $user_args );
	}

	/**
	 * Adding choices
	 */
	$first_option  = sanitize_text_field( $post['first_option'] );
	$second_option = sanitize_text_field( $post['second_option'] );
	$third_option  = sanitize_text_field( $post['third_option'] );

	if ( $entry->get_first_option() != (int) $first_option && $first_option != 0 ) {
		$added = updating_customer_single_entrydata( (int) $entry->get_user_id(), 'company_first_option', (int) $first_option );
	}

	if ( $entry->get_second_option() != (int) $second_option && $second_option != 0 ) {
		$added = updating_customer_single_entrydata( (int) $entry->get_user_id(), 'company_second_option', (int) $second_option );
	}

	if ( $entry->get_third_option() != (int) $third_option && $third_option != 0 ) {
		$added = updating_customer_single_entrydata( (int) $entry->get_user_id(), 'company_third_option', (int) $third_option );
	}

	/**
	 * Triggers when an entry is edited.
	 *
	 * @param Form_Entry $entry entry object.
	 *
	 * @since 3.0
	 */
	do_action( 'vrchecke_edit_entry', $entry );

}

add_action( 'vrchecke_action_edit_entry', __NAMESPACE__ . '\\vrchecke_process_edit_entry' );

/**
 * Process deleting an entry.
 *
 * @since 3.0
 * @return void
 */
function vrchecke_process_delete_entry() {
	if ( ! wp_verify_nonce( $_GET['_wpnonce'], 'vrchecke_delete_entry_nonce' ) ) {
		wp_die( __( 'Nonce verification failed.', 'vrchecke' ), __( 'Error', 'vrchecke' ), array( 'response' => 403 ) );
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( __( 'You do not have permission to perform this action.', 'vrchecke' ), __( 'Error', 'vrchecke' ), array( 'response' => 403 ) );
	}

	if ( empty( $_GET['entry_id'] ) ) {
		wp_die( __( 'Invalid entry ID.', 'vrchecke' ), __( 'Error', 'vrchecke' ), array( 'response' => 400 ) );
	}

	$current_user = wp_get_current_user();

	$deleted = vrchecke_delete_entry( absint( $_GET['entry_id'] ) );

	if ( ! $deleted ) {
		wp_die( __( 'Failed to delete entry.', 'vrchecke' ), __( 'Error', 'vrchecke' ), array( 'response' => 400 ) );
	}

}

add_action( 'vrchecke_action_delete_entry', __NAMESPACE__ . '\\vrchecke_process_delete_entry' );
