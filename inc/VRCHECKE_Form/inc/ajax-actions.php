<?php
/**
 * VRCHECKE\VRCHECKE\VRCHECKE_Form ajax functions
 *
 * @package vrchecke
 */

namespace VRCHECKE\VRCHECKE\VRCHECKE_Form;

use VRCHECKE_Emails;

/**
 *
 * Process Form Data
 *
 * @return void
 */

function process_form()
{
    // var_dump( $post_req );
    /**
     * Checking Nonce.
     */
    if ( !( isset( $_POST['vrchecke_nonce'] ) && wp_verify_nonce( sanitize_text_field( $_POST['vrchecke_nonce'] ), 'vrchecke_form_nonce' ) ) ) {
        vr_errors()->add( 'invalid_nonce', __( 'An authentication error occurred. Please try again.', 'vrchecke' ), 'vrchecke-form' );

        wp_send_json_error(
            array(
                'success' => false,
                'errors'  => vr_get_error_messages_html( 'vrchecke-form' ),
                'nonce'   => wp_create_nonce( 'vrchecke_form_nonce' ),
            ),
            500
        );
    }

    $post_req = $_POST;

    /**
     * Checking if data is not empty
     */
    $error_check = is_empty_form_data();
    if ( $error_check ) {
        // $errors = vr_errors()->get_error_messages();

        wp_send_json_error(
            array(
                'success' => false,
                'errors'  => vr_get_error_messages_html( 'vrchecke-form' ),
                'nonce'   => wp_create_nonce( 'vrchecke_form_nonce' ),
            ),
            500
        );
    }
    /**
     * Validating user data
     */
    $user_data = validate_userdata();
    $errors    = vr_errors()->get_error_messages();
    if ( !empty( $errors ) ) {
        wp_send_json_error(
            array(
                'success' => false,
                'errors'  => vr_get_error_messages_html( 'vrchecke-form' ),
                'nonce'   => wp_create_nonce( 'vrchecke_form_nonce' ),
            ),
            500
        );
    }

    /**
     * Adding user
     */
    $user_id = insert_user( $user_data );
    if ( is_wp_error( $user_id ) ) {

        vr_errors()->add( $user_id->get_error_code(), $user_id->get_error_message(), 'vrchecke-form' );
    }
    $errors = vr_errors()->get_error_messages();
    if ( !empty( $errors ) ) {
        wp_send_json_error(
            array(
                'success' => false,
                'errors'  => vr_get_error_messages_html( 'vrchecke-form' ),
                'nonce'   => wp_create_nonce( 'vrchecke_form_nonce' ),
            ),
            500
        );
    }
    /**
     * Updating user meta
     */
    $meta_keys = updating_user_meta( $user_id );
    $errors    = vr_errors()->get_error_messages();
    if ( !empty( $errors ) || !$meta_keys ) {
        wp_send_json_error(
            array(
                'success' => false,
                'errors'  => vr_get_error_messages_html( 'vrchecke-form' ),
                'nonce'   => wp_create_nonce( 'vrchecke_form_nonce' ),
            ),
            500
        );
    }

    /**
     * Sanitizing data
     */
    $args = array(
        'first_check'     => sanitize_text_field( $_POST['e-statement-check'] ) == 'true' ? 1 : 0,
        'num_devices'     => sanitize_text_field( $_POST['number_of_devices'] ),
        'postal_code'     => sanitize_text_field( $_POST['postal-code'] ),
        'type_of_devices' => sanitize_text_field( $_POST['electrical_eqp'] ),
        'default'         => false,
    );

    /**
     * Action for adding more values
     *
     * @version 1.0.0
     */
    // do_action( 'vrchecke_adding_field', $args );

    /**
     * Inserting data into database
     */
    $inserted = inserting_form_entry( $user_id, $args );
    if ( !$inserted ) {
        $errors = vr_errors()->get_error_messages();
        wp_send_json_error(
            array(
                'success' => false,
                'errors'  => vr_get_error_messages_html( 'vrchecke-form' ),
                'nonce'   => wp_create_nonce( 'vrchecke_form_nonce' ),
            ),
            500
        );

    } else {
        wp_send_json_success( 'Form entry successfull', 200 );
    }

}
add_action( 'wp_ajax_vrchecke_process_form', __NAMESPACE__ . '\\process_form', 100 );
add_action( 'wp_ajax_nopriv_vrchecke_process_form', __NAMESPACE__ . '\\process_form', 100 );

/**
 *
 * Check if Form Data is empty.
 *
 * @return bool || error
 */
function is_empty_form_data()
{
    if ( !isset( $_POST['e-statement-check'] ) ) {
        vr_errors()->add( 'empty_statement_check', __( 'Select if this is your first E-scheck or not', 'vrchecke' ), 'vrchecke-form' );
    }
    if ( !isset( $_POST['number_of_devices'] ) ) {
        vr_errors()->add( 'empty_number_of_devices', __( 'Select Number of devices', 'vrchecke' ), 'vrchecke-form' );
    }
    if ( !isset( $_POST['electrical_eqp'] ) ) {
        vr_errors()->add( 'empty_electrical_eqp', __( 'Select Type of electrical equipments', 'vrchecke' ), 'vrchecke-form' );
    }
    if ( !isset( $_POST['postal-code'] ) ) {
        vr_errors()->add( 'postal-code', __( 'Enter postal code', 'vrchecke' ), 'vrchecke-form' );
    }
    if ( !isset( $_POST['postal-code-duplicate'] ) || $_POST['postal-code-duplicate'] !== $_POST['postal-code'] ) {
        vr_errors()->add( 'postal-code-mismatch', __( 'Please add same postal code', 'vrchecke' ), 'vrchecke-form' );
    }
    if ( !isset( $_POST['first-name'] ) ) {
        vr_errors()->add( 'empty_first-name', __( 'Enter your first name', 'vrchecke' ), 'vrchecke-form' );
    }
    if ( !isset( $_POST['last-name'] ) ) {
        vr_errors()->add( 'empty_last-name', __( 'Enter your last name', 'vrchecke' ), 'vrchecke-form' );
    }
    if ( !isset( $_POST['street-address'] ) ) {
        vr_errors()->add( 'empty_street-address', __( 'Enter your street address', 'vrchecke' ), 'vrchecke-form' );
    }
    if ( !isset( $_POST['email'] ) ) {
        vr_errors()->add( 'empty_email', __( 'Enter your email address', 'vrchecke' ), 'vrchecke-form' );
    }
    if ( !isset( $_POST['phone-number'] ) ) {
        vr_errors()->add( 'empty_phone-number', __( 'Enter your phone number address', 'vrchecke' ), 'vrchecke-form' );
    }
    if ( !empty( vr_errors()->get_error_messages() ) ) {
        return true;
    } else {
        return false;
    }

}

/**
 * Creating user
 *
 * @return array $user
 */
function validate_userdata()
{
    $user               = array();
    $user['id']         = 0;
    $user['login']      = sanitize_text_field( $_POST['email'] );
    $user['email']      = sanitize_text_field( $_POST['email'] );
    $user['first_name'] = sanitize_text_field( $_POST['first-name'] );
    $user['last_name']  = sanitize_text_field( $_POST['last-name'] );
    $user['password']   = wp_generate_password();
    $user['need_new']   = true;
    if ( username_exists( $user['login'] ) ) {
        // Username already registered
        vr_errors()->add(
            'username_unavailable',
            sprintf(
                __( 'This username is already in use. If this is your username, please <a href="%s">log in</a> and try again.', 'rcp' ),
                esc_url( wp_login_url() )
            ),
            'vrchecke-form'
        );
    }

    if ( !is_email( $user['email'] ) ) {
        // invalid email
        vr_errors()->add( 'email_invalid', __( 'Invalid email', 'rcp' ), 'vrchecke-form' );
    }
    if ( email_exists( $user['email'] ) ) {
        // Email address already registered
        vr_errors()->add(
            'email_used',
            sprintf(
                __( 'This email address is already in use. If this is your email address, please <a href="%s">log in</a> and try again.', 'rcp' ),
                esc_url( wp_login_url() )
            ),
            'vrchecke-form'
        );
    }
    return $user;
}

/**
 * Creating new user
 *
 * @param array $user data
 *
 * @return int || object
 */
function insert_user( $user )
{
    $display_name = trim( $user['first_name'] . ' ' . $user['last_name'] );
    $user['id']   = wp_insert_user(
        array(
            'user_login'      => $user['login'],
            'user_pass'       => $user['password'],
            'user_email'      => $user['email'],
            'first_name'      => $user['first_name'],
            'last_name'       => $user['last_name'],
            'display_name'    => !empty( $display_name ) ? $display_name : $user['login'],
            'user_registered' => date( 'Y-m-d H:i:s' ),
        )
    );
    if ( !is_wp_error( $user['id'] ) ) {
        /**
         * Not required to log in
         */
        // wp_set_auth_cookie( $user['id'] );
        // wp_set_current_user( $user['id'] );

        // Send an email to the admin alerting them of the registration.
        wp_new_user_notification( absint( $user['id'] ) );

        // Send email to user
        user_email_send( $user );
    }
    return $user['id'];
}

/**
 * Sending email to user
 */

function user_email_send( array $user )
{
    //setting up email
    $email          = new VRCHECKE_Emails();
    $email->user_id = $user['id'];

    $subject = 'New Account Created';
    $message = get_option( 'email_template' );

    $email->send( $user['email'], $subject, $message );

}

/**
 * Updating user meta
 *
 * @param int $user_id
 *
 * @return boolean || array
 */
function updating_user_meta( $user_id )
{
    $user         = get_user_by( 'ID', (int) $user_id );
    $user_phone   = sanitize_text_field( $_POST['phone-number'] );
    $user_address = sanitize_text_field( $_POST['street-address'] );
    $user_city    = sanitize_text_field( $_POST['city'] );

    if ( !$user ) {
        vr_errors()->add( 'user_meta_failed', sprintf( __( 'Failed to add user meta for user with user id %d', 'vrchecke' ), $user_id ), 'vrchecke-form' );
    } else {
        $meta_address = update_user_meta( $user_id, 'address', $user_address );
        $meta_city    = update_user_meta( $user_id, 'city', $user_city );
        $meta_phone   = update_user_meta( $user_id, 'phone_number', $user_phone );
    }

    /**
     * Triggers after default user data is added.
     *
     * @param int                  $user_id             ID of the user.
     */
    do_action( 'vrchecke_userdata_update', $user_id );

    if ( !$meta_address ) {
        vr_errors()->add( 'user_meta_failed', sprintf( __( 'Failed to add user meta for user with user id %1$d and meta key %2$s', 'vrchecke' ), $user_id, 'address' ), 'vrchecke-form' );
    }
    if ( !$meta_city ) {
        vr_errors()->add( 'user_meta_failed', sprintf( __( 'Failed to add user meta for user with user id %1$d and meta key %2$s', 'vrchecke' ), $user_id, 'city' ), 'vrchecke-form' );
    }
    if ( !$meta_phone ) {
        vr_errors()->add( 'user_meta_failed', sprintf( __( 'Failed to add user meta for user with user id %1$d and meta key %2$s', 'vrchecke' ), $user_id, 'phone' ), 'vrchecke-form' );
    }
    if ( !empty( vr_errors()->get_error_messages() ) ) {
        return false;
    } else {
        $meta_keys = array( $meta_address, $meta_city, $meta_phone );
        return apply_filters( 'meta_keys_updated', $meta_keys );
    }

}

/**
 * Creating db entry of form
 *
 * @param  $user_id int The id of the user created
 * @param  $args array An array of given data
 *
 * @return int | boolean true/false or id of row inserted
 */
function inserting_form_entry( $user_id, $args )
{
    global $wpdb;

    $default = array(
        'first_check'     => false,
        'num_devices'     => 0,
        'postal_code'     => '',
        'type_of_devices' => '',
        'default'         => true,
    );
    $args = wp_parse_args( $args, $default );

    /**
     * No functionality for added database columns
     *
     * @version 1.0.0
     *
     * Will provide it soon
     */
    if ( !$args['default'] && count( $args ) === count( $default ) ) {
        $table = $wpdb->prefix . 'form_entries';
        $now   = current_time( 'mysql' );
        $data  = array(
            'time'              => $now,
            'user_id'           => $user_id,
            'first_echeck'      => $args['first_check'],
            'number_of_devices' => $args['num_devices'],
            'type_of_devices'   => $args['type_of_devices'],
            'postal_code'       => $args['postal_code'],
        );
        $format = array( '%s', '%d', '%d', '%d', '%s' );
        $rows   = $wpdb->insert( $table, $data, $format );
        $my_id  = $wpdb->insert_id;
    }
    if ( !$rows ) {
        vr_errors()->add( 'entry_failed', __( 'Failed to add form entry', 'vrchecke' ), 'vrchecke-form' );
        vr_errors()->add( 'entry_failed_debug', print_r( $args ) . '\n ' . $rows, 'vrchecke-form' );
        deleting_user_meta( $user_id );
        return false;
    } else {
        return $my_id;
    }
}

/**
 * Deleting user meta on failed entry
 *
 * @param int $user_id
 */
function deleting_user_meta( $user_id )
{
    $meta_address = delete_user_meta( $user_id, 'address' );
    $meta_city    = delete_user_meta( $user_id, 'city' );
    $meta_phone   = delete_user_meta( $user_id, 'phone_number' );
    $del_user     = wp_delete_user( $user_id );
    if ( !$meta_address ) {
        vr_errors()->add( 'delete_user_meta_failed', sprintf( __( 'Failed to delete user meta for user with user id %1$d and meta key %2$s', 'vrchecke' ), $user_id, 'address' ), 'vrchecke-form' );
    }
    if ( !$meta_city ) {
        vr_errors()->add( 'delete_user_meta_failed', sprintf( __( 'Failed to delete user meta for user with user id %1$d and meta key %2$s', 'vrchecke' ), $user_id, 'city' ), 'vrchecke-form' );
    }
    if ( !$meta_phone ) {
        vr_errors()->add( 'delete_user_meta_failed', sprintf( __( 'Failed to delete user meta for user with user id %1$d and meta key %2$s', 'vrchecke' ), $user_id, 'phone' ), 'vrchecke-form' );
    }
    if ( !empty( vr_errors()->get_error_messages() ) ) {
        return false;
    } else {
        return true;
    }
}