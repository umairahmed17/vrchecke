<?php
/**
 * VRCHECKE\VRCHECKE\Admin_Form\Component class
 *
 * @package vrchecke
 */

namespace VRCHECKE\VRCHECKE\Admin_Form;

use function VRCHECKE\VRCHECKE\vrchecke;
use VRCHECKE\VRCHECKE\Component_Interface;

/**
 * Class for improving accessibility among various core features.
 */
class Component implements Component_Interface
{

    /**
     * Gets the unique identifier for the theme component.
     *
     * @return string Component slug.
     */
    public function get_slug(): string
    {
        return 'admin-form';
    }

    /**
     * Adds the action and filter hooks to integrate with WordPress.
     */
    public function initialize()
    {
        require_once __DIR__ . '/inc/entry-page.php';

        add_action( 'admin_enqueue_scripts', array( $this, 'action_admin_enqueue_scripts' ) );
        add_action( 'admin_menu', array( $this, 'action_admin_menu' ) );
        // add_action( 'adm in_post_{$action}')
        add_action( 'admin_init', array( $this, 'form_entry_process_actions' ) );
    }

    /**
     * Adding Form Menu on Admin Screen.
     */
    public function action_admin_menu()
    {
        add_menu_page( __( 'Form Entries', 'vrchecke' ), __( 'Form Entries', 'vrchecke' ), 'manage_options', 'vrcheck_entry', 'vrchecke_entry_page', 'dashicons-feedback', null );

    }

    /**
     * Process all Form Entry actions sent via POST and GET
     *
     * @return void
     */
    function form_entry_process_actions()
    {
        include_once __DIR__ . '/inc/entry-actions.php';
        if ( isset( $_POST['vrchecke-action'] ) ) {
            do_action( 'vrchecke_action_' . $_POST['vrchecke-action'], $_POST );
        }

        if ( isset( $_GET['vrchecke-action'] ) ) {
            do_action( 'vrchecke_action_' . $_GET['vrchecke-action'], $_GET );
        }
    }

    /**
     * Enqueuing Admin Css and JS
     */
    public function action_admin_enqueue_scripts()
    {
        wp_enqueue_script( 'edit_customer_upload_script', get_stylesheet_directory_uri() . '/assets/js/edit-customer-upload.min.js', vrchecke()->get_asset_version( get_theme_file_path( '/assets/js/edit-customer-upload.min.js' ) ), false );
        wp_enqueue_media();
    }

}