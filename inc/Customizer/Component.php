<?php
/**
 * VRCHECKE\VRCHECKE\Customizer\Component class
 *
 * @package vrchecke
 */

namespace VRCHECKE\VRCHECKE\Customizer;

use function VRCHECKE\VRCHECKE\vrchecke;
use WP_Customize_Manager;
use VRCHECKE\VRCHECKE\Component_Interface;

/**
 * Class for managing Customizer integration.
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
        return 'customizer';
    }

    /**
     * Adds the action and filter hooks to integrate with WordPress.
     */
    public function initialize()
    {
        add_action( 'customize_register', array( $this, 'action_customize_register' ) );
        add_action( 'customize_preview_init', array( $this, 'action_enqueue_customize_preview_js' ) );
    }

    /**
     * Adds postMessage support for site title and description, plus a custom Theme Options section.
     *
     * @param WP_Customize_Manager $wp_customize Customizer manager instance.
     */
    public function action_customize_register( WP_Customize_Manager $wp_customize )
    {
        $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
        $wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
        $wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

        if ( isset( $wp_customize->selective_refresh ) ) {
            $wp_customize->selective_refresh->add_partial(
                'blogname',
                array(
                    'selector'        => '.site-title a',
                    'render_callback' => function () {
                        bloginfo( 'name' );
                    },
                )
            );
            $wp_customize->selective_refresh->add_partial(
                'blogdescription',
                array(
                    'selector'        => '.site-description',
                    'render_callback' => function () {
                        bloginfo( 'description' );
                    },
                )
            );
        }
    }

    /**
     * Enqueues JavaScript to make Customizer preview reload changes asynchronously.
     */
    public function action_enqueue_customize_preview_js()
    {
        wp_enqueue_script(
            'vrchecke-customizer',
            get_theme_file_uri( '/assets/js/customizer.min.js' ),
            array( 'customize-preview' ),
            vrchecke()->get_asset_version( get_theme_file_path( '/assets/js/customizer.min.js' ) ),
            true
        );
    }
}