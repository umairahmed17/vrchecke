<?php
/**
 * VRCHECKE\VRCHECKE\Email_Template\Component class
 *
 * @package vrchecke
 */

namespace VRCHECKE\VRCHECKE\Email_Template;

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
        return 'email-template';
    }

    /**
     * Adds the action and filter hooks to integrate with WordPress.
     */
    public function initialize()
    {
        require_once __DIR__ . '/inc/email-page.php';

        add_action( 'admin_menu', array( $this, 'action_admin_menu' ) );

        //call register settings function
        add_action( 'admin_init', array( $this, 'action_register_settings' ) );

    }

    /**
     * Adding Form Menu on Admin Screen.
     */
    public function action_admin_menu()
    {
        add_submenu_page( 'vrcheck_entry', __( 'Email Templates', 'vrchecke' ), __( 'Email Templates', 'vrchecke' ), 'manage_options', 'vrcheck_email_template', 'vrchecke_email_page', 'dashicons-email-alt', null );
    }

    /**
     * Adding options.
     */
    public function action_register_settings()
    {
        //register our settings
        register_setting( 'vrchecke_settings_group', 'email_template' );
        register_setting( 'vrchecke_settings_group', 'email_from_name' );
        register_setting( 'vrchecke_settings_group', 'email_from_address' );

    }

}