<?php
/**
 * VRCHECKE\VRCHECKE\portal_Form ajax functions
 *
 * @package vrchecke
 */

namespace VRCHECKE\VRCHECKE\Customer_Portal;

/**
 *
 * Process Form Data
 *
 * @param $data array  form data
 *
 * @return bool || error
 */
function process_portal_form()
{
    $post_req = $_POST;
    // var_dump( $post_req );
    /**
     * Checking Nonce
     */
    if ( !( isset( $_POST['edit_customer_nonce'] ) && wp_verify_nonce( $_POST['edit_customer_nonce'], 'edit_customer_nonce' ) ) ) {
        vr_errors()->add( 'invalid_nonce', __( 'An authentication error occurred. Please try again.', 'vrchecke' ), 'portal-form' );

        wp_send_json_error(
            array(
                'success' => false,
                'errors'  => vr_get_error_messages_html( 'portal-form' ),
                'nonce'   => wp_create_nonce( 'edit_customer_nonce' ),
            ),
            500
        );
    }

    $user_id = get_current_user_id();
    $user    = get_userdata( $user_id );

    if ( !$user ) {
        vr_errors()->add( 'user_invalid', __( 'User could not be found', 'vrchecke' ), 'portal-form' );
        wp_send_json_error(
            array(
                'success' => false,
                'errors'  => vr_get_error_messages_html( 'portal-form' ),
                'nonce'   => wp_create_nonce( 'edit_customer_nonce' ),
            ),
            500
        );
    } else {
        $args = array(
            'first_name'   => sanitize_text_field( $_POST['first-name'] ),
            'last_name'    => sanitize_text_field( $_POST['last-name'] ),
            'address'      => sanitize_text_field( $_POST['address'] ),
            'city'         => sanitize_text_field( $_POST['city'] ),
            'phone_number' => sanitize_text_field( $_POST['phone'] ),
            'postal_code ' => sanitize_text_field( $_POST['postal-code'] ),
        );

        foreach ( $args as $key => $val ) {
            if ( $key === 'postal_code' && get_single_entrydata_by_userid( $user_id, 'postal_code' ) && get_single_entrydata_by_userid( $user_id, 'postal_code' ) === $val ) {
                updating_customer_single_entrydata( $user_id, $key, $val );
            } else {
                if ( $val !== $user->{$key} ) {
                    updating_customer_single_usermeta( $user_id, $key, $val );
                }
            }
        }

        $errors = vr_errors()->get_error_messages();
        if ( !empty( $errors ) ) {
            wp_send_json_error(
                array(
                    'success' => false,
                    'errors'  => vr_get_error_messages_html( 'portal-form' ),
                    'nonce'   => wp_create_nonce( 'edit_customer_nonce' ),
                ),
                500
            );
        } else {
            $data = array(
                'success'      => true,
                'first-name'   => get_user_meta( $user_id, 'first_name', true ),
                'last-name'    => get_user_meta( $user_id, 'last_name', true ),
                'address'      => get_user_meta( $user_id, 'address', true ),
                'city'         => get_user_meta( $user_id, 'city', true ),
                'phone'        => get_user_meta( $user_id, 'phone_number', true ),
                'postal_code ' => ( get_single_entrydata_by_userid( $user_id, 'postal_code' ) ),
            );
            wp_send_json_success( $data, 200 );
        }
    }

}
add_action( 'wp_ajax_vrchecke_portal_form', __NAMESPACE__ . '\\process_portal_form' );

/**
 * Updating user meta for customer
 * Expect a valid user id and sanitized meta value
 * Same as update user meta but with our error handling added
 *
 * @param int    $user_id
 *
 * @param string $meta_key
 *
 * @param mixed  $meta_value
 *
 * @return boolean || array
 */
function updating_customer_single_usermeta( $user_id, $meta_key, $meta_value )
{
    $user = get_user_by( 'ID', (int) $user_id );

    if ( !$user ) {
        vr_errors()->add( 'user_invalid', __( 'User could not be found', 'vrchecke' ), 'portal-form' );
    } else {
        $meta_added = update_user_meta( $user_id, $meta_key, $meta_value );
    }

    if ( !$meta_added ) {
        vr_errors()->add( 'user_meta_failed', sprintf( __( 'Failed to add user meta for user with user id %1$d and meta key %2$s', 'vrchecke' ), $user_id, $meta_key ), 'portal-form' );
    } else {
        return $meta_added;
    }

}

/**
 * Updating entry data for customer
 *
 * @param int    $user_id
 *
 * @param string $col_name Expected to be a valid column name from db.
 *
 * @param mixed  $entry_data Expected to be escaped and sanitized
 *
 * @return boolean
 */
function updating_customer_single_entrydata( $user_id, $col_name, $entry_data )
{
    global $wpdb;

    $table        = $wpdb->prefix . 'form_entries';
    $user_id      = (int) $user_id;
    $col_name     = sanitize_text_field( $col_name );
    $data         = array( $col_name => $entry_data );
    $format       = array( '%s' );
    $where        = array( 'user_id' => $user_id );
    $where_format = array( '%d' );
    $updated_row  = $wpdb->update( $table, $data, $where, $format, $where_format );
    if ( is_wp_error( $updated_row ) ) {
        vr_errors()->add( 'entry_failed', __( 'Failed to add form entry', 'vrchecke' ), 'portal-form' );
        return false;
    } else {
        return true;
    }

}

/**
 * Getting entry data for customer
 *
 * @param int    $user_id
 *
 * @param string $key
 *
 * @return string | false
 */
function get_single_entrydata_by_userid( $user_id, $key )
{
    global $wpdb;

    $table   = $wpdb->prefix . 'form_entries';
    $user_id = (int) $user_id;
    $key     = sanitize_text_field( $key );
    $query   = $wpdb->prepare( "SELECT $key FROM $table WHERE user_id = %d", $user_id );
    $value   = $wpdb->get_var( $query );
    if ( $value == null ) {
        vr_errors()->add( 'entry_failed', __( 'Failed to getform entry', 'vrchecke' ), 'portal-form' );
        return false;
    } else {
        return $value;
    }

}

/**
 * Invoice Handler
 */
function insert_attachment( $file_handler, $user_id, $nonce )
{

    if ( empty( $nonce ) || $nonce == null || wp_verify_nonce( $nonce, 'selected_company_nonce_action' ) ) {
        return 'Nonce not verified';
    }
    if ( empty( $user_id ) ) {
        return 'User not found';
    }
    // check to make sure its a successful upload
    if ( $_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK ) {
        return 'Your invoice could not be uploaded.';
    }

    require_once ABSPATH . 'wp-admin' . '/includes/image.php';
    require_once ABSPATH . 'wp-admin' . '/includes/file.php';
    require_once ABSPATH . 'wp-admin' . '/includes/media.php';

    $attach_id = media_handle_upload( $file_handler, $user_id );

    if ( is_wp_error( $attach_id ) ) {
        return "Invoice could not be saved.uploaded";
    } else {
        update_user_meta( $user_id, '_invoice_id', $attach_id );

        return 'Invoice is uploaded successfully';
    }

}

/**
 * Selected Company Updater
 */
function update_selected_company( $user_id, $selected_company, $nonce )
{
    if ( empty( $nonce ) || $nonce == null || wp_verify_nonce( $nonce, 'selected_company_nonce_action' ) ) {
        return 'Nonce not verified';
    }

    if ( empty( $user_id ) ) {
        return 'User not found';
    }

    $retval = updating_customer_single_entrydata( $user_id, 'company_selected_option', $selected_company );
    if ( !$retval ) {
        return 'Your selection could not be saved.';
    } else {
        return 'Your selection is saved.';
    }
}