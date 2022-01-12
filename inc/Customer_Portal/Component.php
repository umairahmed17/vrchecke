<?php
/**
 * VRCHECKE\VRCHECKE\Customer_Portal\Component class
 *
 * @package vrchecke
 */

namespace VRCHECKE\VRCHECKE\Customer_Portal;

use function VRCHECKE\VRCHECKE\vrchecke;
use VRCHECKE\VRCHECKE\Component_Interface;

/**
 * Class for creating login page.
 *
 * @link https://codex.wordpress.org/Theme_Logo
 */
class Component implements Component_Interface
{

    /**
     * Variables
     */
    private $new_page_title;
    private $new_page_content;
    private $page_check;
    /**
     * Construct Object
     */
    public function __construct()
    {
        $this->new_page_title   = __( 'Customer Portal', 'vrchecke' );
        $this->new_page_content = '[customer-portal-page]';
        $this->page_check       = get_page_by_title( $this->new_page_title );
        $this->page_template    = 'page-customer-portal.php';
    }

    /**
     * Gets the unique identifier for the theme component.
     *
     * @return string Component slug.
     */
    public function get_slug(): string
    {
        return 'login-page';
    }

    /**
     * Adds the action and filter hooks to integrate with WordPress.
     */
    public function initialize()
    {
        $new_page = array(
            'post_type'    => 'page',
            'post_title'   => $this->new_page_title,
            'post_content' => $this->new_page_content,
            'post_status'  => 'publish',
            'post_author'  => 1,
            'post_name'    => 'customer-portal',
        );
        if ( !isset( $this->page_check->ID ) ) {
            $new_page_id = wp_insert_post( $new_page );
            if ( !empty( $new_page_template ) ) {
                update_post_meta( $new_page_id, '_wp_page_template', $this->page_template );
            }
        }
        add_action( 'wp_enqueue_scripts', array( $this, 'action_enqueue_form_scripts' ) );
        add_shortcode( 'customer-portal-page', array( $this, 'action_add_shortcode' ) );
        $this->require_function_files();
        // add_action( 'wp_head', [$this, 'debugging_theme'] );
    }

    /**
     * Registering Styles and Scripts
     */
    public function action_enqueue_form_scripts()
    {
        wp_register_style( 'customer_portal_style', get_stylesheet_directory_uri() . '/assets/css/customer-portal.min.css', vrchecke()->get_asset_version( get_theme_file_path( '/assets/css/customer-portal.min.css' ) ) );
        wp_register_script( 'customer_portal_script', get_stylesheet_directory_uri() . '/assets/js/customer-portal.min.js', vrchecke()->get_asset_version( get_theme_file_path( '/assets/js/customer-portal.min.js' ) ), array( 'jquery' ), false );
        wp_register_script( 'intlTelInput_script', get_stylesheet_directory_uri() . '/assets/js/intlTelInput-jquery.min.js', '1.0.0', array( 'jquery' ), false );
        wp_register_script( 'intlTelInput-utils_script', get_stylesheet_directory_uri() . '/assets/js/utils.min.js', '1.0.0', array( '' ), false );
        wp_localize_script(
            'customer_portal_script',
            'portal_script_options',
            array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
            )
        );
        wp_script_add_data( 'customer_portal_script', 'defer', true );
        wp_script_add_data( 'intlTelInput_script', 'defer', true );
        wp_script_add_data( 'intlTelInput-utils_script', 'defer', true );
        wp_enqueue_script( 'intlTelInput_script' );
        wp_enqueue_script( 'intlTelInput-utils_script' );
    }

    /**
     * Function to add template to page content
     */
    private function page_content()
    {

    }

    /**
     * Requiring function files for form
     */
    private function require_function_files()
    {
        require_once __DIR__ . '/inc/ajax-actions.php';
    }

    public function action_add_shortcode()
    {
        wp_enqueue_script( 'customer_portal_script' );
        ob_start();
        get_template_part( 'template-parts/customer/entry', 'portal' );
        return ob_get_clean();
    }

    /**
     * Class for debugging
     */
    // public function debugging_theme()
    // {
    // echo '<pre>';
    // var_dump( get_theme_mod( 'fontcolor_id' ) );
    // echo '</pre>';
    // }

}