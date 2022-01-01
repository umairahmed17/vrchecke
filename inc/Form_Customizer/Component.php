<?php
/**
 * VRCHECKE\VRCHECKE\Customizer\Component class
 *
 * @package vrchecke
 */

namespace VRCHECKE\VRCHECKE\Form_Customizer;

use function VRCHECKE\VRCHECKE\vrchecke;
use WP_Customize_Image_Control;
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
        return 'form-customizer';
    }

    /**
     * Adds the action and filter hooks to integrate with WordPress.
     */
    public function initialize()
    {
        add_action( 'customize_register', array( $this, 'action_customize_register' ) );
        add_action( 'customize_preview_init', array( $this, 'action_enqueue_customize_preview_js' ) );
        add_filter( 'wp_kses_allowed_html', array( $this, 'action_kses_allowed_html' ), 10, 2 );
    }

    /**
     * Action hook for adding panels, sections and settings.
     *
     * @param WP_Customize_Manager $wp_customize Customizer manager instance.
     */
    public function action_customize_register( WP_Customize_Manager $wp_customize )
    {
        $wp_customize->add_panel( 'form_customizer_panel', array(
            'title'    => 'Form Sections',
            'priority' => 10,
        ) );
        $wp_customize->add_panel( 'form_customizer_panel', array(
            'title' => 'Form Sections',
        ) );

        $wp_customize->add_section( 'form_customizer_section_one',
            array(
                'title'    => 'First Section',
                'priority' => 1,
                'panel'    => 'form_customizer_panel',
            )
        );
        $wp_customize->add_section( 'form_customizer_section_two',
            array(
                'title'    => 'Second Section',
                'priority' => 2,
                'panel'    => 'form_customizer_panel',
            )
        );
        $wp_customize->add_section( 'form_customizer_section_three',
            array(
                'title'    => 'Third Section',
                'priority' => 3,
                'panel'    => 'form_customizer_panel',
            )
        );
        $wp_customize->add_section( 'form_customizer_section_four',
            array(
                'title'    => 'Fourth Section',
                'priority' => 4,
                'panel'    => 'form_customizer_panel',
            )
        );
        $wp_customize->add_section( 'form_customizer_section_search',
            array(
                'title'    => 'Search Section',
                'priority' => 5,
                'panel'    => 'form_customizer_panel',
            )
        );
        $wp_customize->add_section( 'form_customizer_section_five',
            array(
                'title'    => 'Fifth Section',
                'priority' => 6,
                'panel'    => 'form_customizer_panel',
            )
        );
        $wp_customize->add_section( 'form_customizer_section_video',
            array(
                'title'    => 'Video Section',
                'priority' => 7,
                'panel'    => 'form_customizer_panel',
            )
        );

        /**
         * First Section form settings and controls
         */
        $wp_customize->add_setting( 'label_first_section_one', array(
            'type'              => 'theme_mod',
            'transport'         => 'refresh',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'default'           => 'Is this your first E-check?' )
        );
        $wp_customize->add_control( 'label_first_section_one', array(
            'label'   => 'Label',
            'section' => 'form_customizer_section_one',
            'type'    => 'text',
        ) );

        $wp_customize->add_setting( 'option_first_section_one', array(
            'type'              => 'theme_mod',
            'transport'         => 'refresh',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'default'           => 'Yes',
        ) );
        $wp_customize->add_control( 'option_first_section_one', array(
            'label'   => 'First Option Text',
            'section' => 'form_customizer_section_one',
            'type'    => 'text',
        ) );

        $wp_customize->add_setting( 'option_second_section_one', array(
            'type'              => 'theme_mod',
            'transport'         => 'refresh',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'default'           => 'No',
        ) );
        $wp_customize->add_control( 'option_second_section_one', array(
            'label'   => 'Second Option Text',
            'section' => 'form_customizer_section_one',
            'type'    => 'text',
        ) );

        $wp_customize->add_setting( 'hover_text_section_one', array(
            'type'              => 'theme_mod',
            'transport'         => 'refresh',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'default'           => 'Select',
        ) );
        $wp_customize->add_control( 'hover_text_section_one', array(
            'label'   => 'Hover Text',
            'section' => 'form_customizer_section_one',
            'type'    => 'text',
        ) );

        /**
         * Second Section form settings and controls
         */
        $wp_customize->add_setting( 'label_first_section_two', array(
            'type'              => 'theme_mod',
            'transport'         => 'refresh',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'default'           => 'Approximately how many electrical devices with plugs that are to be tested are there in the company(average 6 per employee)' )
        );
        $wp_customize->add_control( 'label_first_section_two', array(
            'label'   => 'Label',
            'section' => 'form_customizer_section_two',
            'type'    => 'textarea',
        ) );

        /**
         * Third Section form settings and controls
         */
        $wp_customize->add_setting( 'label_first_section_three', array(
            'type'              => 'theme_mod',
            'transport'         => 'refresh',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'default'           => 'What electrical equipement do you have?',
        ) );
        $wp_customize->add_control( 'label_first_section_three', array(
            'label'   => 'Label',
            'section' => 'form_customizer_section_three',
            'type'    => 'text',
        ) );

        $wp_customize->add_setting( 'option_first_section_three', array(
            'type'              => 'theme_mod',
            'transport'         => 'refresh',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'default'           => 'Portable equipment',
        ) ); // first option - Label
        $wp_customize->add_control( 'option_first_section_three', array(
            'label'   => 'First Option Text',
            'section' => 'form_customizer_section_three',
            'type'    => 'text',
        ) ); // first option - Label
        $wp_customize->add_setting( 'option_first_tooltip_section_three', array(
            'type'              => 'theme_mod',
            'transport'         => 'refresh',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'default'           => 'Portable electrical equipement includes all electrical devices that have a plug, are not permanently installed and weigh less than 23 kg.',
        ) ); // first option - Tooltip
        $wp_customize->add_control( 'option_first_tooltip_section_three', array(
            'label'   => 'First Option Tooltip Text',
            'section' => 'form_customizer_section_three',
            'type'    => 'textarea',
        ) ); // first option - Tooltip

        $wp_customize->add_setting( 'option_first_image_section_three', array(
            'type'              => 'theme_mod',
            'transport'         => 'refresh',
            'sanitize_callback' => array( $this, 'ic_sanitize_image' ),
        ) ); // first option - Image
        $wp_customize->add_control(
            new WP_Customize_Image_Control( $wp_customize, 'option_first_image_section_three',
                array(
                    'label'    => __( 'Option Image', 'vrchecke' ),
                    'section'  => 'form_customizer_section_three',
                    'settings' => 'option_first_image_section_three',
                )
            ) ); // first option - Image

        $wp_customize->add_setting( 'option_second_section_three', array(
            'type'              => 'theme_mod',
            'transport'         => 'refresh',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'default'           => 'Stationary equipment',
        ) ); // second option - Label
        $wp_customize->add_control( 'option_second_section_three', array(
            'label'   => 'Second Option Text',
            'section' => 'form_customizer_section_three',
            'type'    => 'text',
        ) ); // second option - Label
        $wp_customize->add_setting( 'option_second_tooltip_section_three', array(
            'type'              => 'theme_mod',
            'transport'         => 'refresh',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'default'           => 'Stationary electrical equipment is either firmly anchored or very massive equipment with an electrical element.',
        ) ); // second option - Tooltip
        $wp_customize->add_control( 'option_second_tooltip_section_three', array(
            'label'   => 'Second Option Tooltip Text',
            'section' => 'form_customizer_section_three',
            'type'    => 'textarea',
        ) ); // second option - Tooltip

        $wp_customize->add_setting( 'option_second_image_section_three', array(
            'type'              => 'theme_mod',
            'transport'         => 'refresh',
            'sanitize_callback' => array( $this, 'ic_sanitize_image' ),
        ) ); // second option - Image
        $wp_customize->add_control(
            new WP_Customize_Image_Control( $wp_customize, 'option_second_image_section_three',
                array(
                    'label'    => __( 'Option Image', 'vrchecke' ),
                    'section'  => 'form_customizer_section_three',
                    'settings' => 'option_second_image_section_three',
                )
            ) ); // second option - Image

        $wp_customize->add_setting( 'option_third_section_three', array(
            'type'              => 'theme_mod',
            'transport'         => 'refresh',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'default'           => 'Electrical systems',
        ) ); // third option - Label
        $wp_customize->add_control( 'option_third_section_three', array(
            'label'   => 'Third Option Text',
            'section' => 'form_customizer_section_three',
            'type'    => 'text',
        ) ); // third option - Label
        $wp_customize->add_setting( 'option_third_tooltip_section_three', array(
            'type'              => 'theme_mod',
            'transport'         => 'refresh',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'default'           => 'Electrical systems are permanently connected groups of electrical equipment.',
        ) ); // third option - Tooltip
        $wp_customize->add_control( 'option_third_tooltip_section_three', array(
            'label'   => 'Third Option Tooltip Text',
            'section' => 'form_customizer_section_three',
            'type'    => 'textarea',
        ) ); // third option - Tooltip

        $wp_customize->add_setting( 'option_third_image_section_three', array(
            'type'              => 'theme_mod',
            'transport'         => 'refresh',
            'sanitize_callback' => array( $this, 'ic_sanitize_image' ),
        ) ); // third option - Image
        $wp_customize->add_control(
            new WP_Customize_Image_Control( $wp_customize, 'option_third_image_section_three',
                array(
                    'label'    => __( 'Option Image', 'vrchecke' ),
                    'section'  => 'form_customizer_section_three',
                    'settings' => 'option_third_image_section_three',
                )
            ) ); // third option - Image

        $wp_customize->add_setting( 'option_fourth_section_three', array(
            'type'              => 'theme_mod',
            'transport'         => 'refresh',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'default'           => 'Electrical machine',
        ) ); // fourth option - Label
        $wp_customize->add_control( 'option_fourth_section_three', array(
            'label'   => 'Fourth Option Text',
            'section' => 'form_customizer_section_three',
            'type'    => 'text',
        ) ); // fourth option - Label
        $wp_customize->add_setting( 'option_fourth_tooltip_section_three', array(
            'type'              => 'theme_mod',
            'transport'         => 'refresh',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'default'           => 'An electrical machine is a machine used in electrical energy technology and represents a form of energy converter.',
        ) ); // fourth option - Tooltip
        $wp_customize->add_control( 'option_fourth_tooltip_section_three', array(
            'label'   => 'Fourth Option Tooltip Text',
            'section' => 'form_customizer_section_three',
            'type'    => 'textarea',
        ) ); // fourth option - Tooltip

        $wp_customize->add_setting( 'option_fourth_image_section_three', array(
            'type'              => 'theme_mod',
            'transport'         => 'refresh',
            'sanitize_callback' => array( $this, 'ic_sanitize_image' ),
        ) ); // fourth option - Image
        $wp_customize->add_control(
            new WP_Customize_Image_Control( $wp_customize, 'option_fourth_image_section_three',
                array(
                    'label'    => __( 'Option Image', 'vrchecke' ),
                    'section'  => 'form_customizer_section_three',
                    'settings' => 'option_fourth_image_section_three',
                )
            ) ); // fourth option - Image

        $wp_customize->add_setting( 'option_fifth_section_three', array(
            'type'              => 'theme_mod',
            'transport'         => 'refresh',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'default'           => 'Medical electrical devices',
        ) ); // fifth option - Label
        $wp_customize->add_control( 'option_fifth_section_three', array(
            'label'   => 'Fifth Option Text',
            'section' => 'form_customizer_section_three',
            'type'    => 'text',
        ) ); // fifth option - Label
        $wp_customize->add_setting( 'option_fifth_tooltip_section_three', array(
            'type'              => 'theme_mod',
            'transport'         => 'refresh',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'default'           => 'Medical electrical devices include all electronically operated devices that are used in medical practices or clinics.',
        ) ); // fifth option - Tooltip
        $wp_customize->add_control( 'option_fifth_tooltip_section_three', array(
            'label'   => 'Fifth Option Tooltip Text',
            'section' => 'form_customizer_section_three',
            'type'    => 'textarea',
        ) ); // fifth option - Tooltip

        $wp_customize->add_setting( 'option_fifth_image_section_three', array(
            'type'              => 'theme_mod',
            'transport'         => 'refresh',
            'sanitize_callback' => array( $this, 'ic_sanitize_image' ),
        ) ); // fifth option - Image
        $wp_customize->add_control(
            new WP_Customize_Image_Control( $wp_customize, 'option_fifth_image_section_three',
                array(
                    'label'    => __( 'Option Image', 'vrchecke' ),
                    'section'  => 'form_customizer_section_three',
                    'settings' => 'option_fifth_image_section_three',
                )
            ) ); // fifth option - Image

        /**
         * Fourth Section form settings and controls
         */
        $wp_customize->add_setting( 'label_first_section_four', array(
            'type'              => 'theme_mod',
            'transport'         => 'refresh',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'default'           => 'Postal code',
        ) );
        $wp_customize->add_control( 'label_first_section_four', array(
            'label'   => 'Label',
            'section' => 'form_customizer_section_four',
            'type'    => 'text',
        ) );
        $wp_customize->add_setting( 'map_image_section_four', array(
            'type'              => 'theme_mod',
            'transport'         => 'refresh',
            'sanitize_callback' => array( $this, 'ic_sanitize_image' ),
        ) );
        $wp_customize->add_control(
            new WP_Customize_Image_Control( $wp_customize, 'map_image_section_four',
                array(
                    'label'    => __( 'Option Image', 'vrchecke' ),
                    'section'  => 'form_customizer_section_four',
                    'settings' => 'map_image_section_four',
                )
            ) );

        /**
         * Search Section form settings and controls
         */
        $wp_customize->add_setting( 'search_text_section_four', array(
            'type'              => 'theme_mod',
            'transport'         => 'refresh',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'default'           => 'Found',
        ) );
        $wp_customize->add_control( 'search_text_section_four', array(
            'label'   => 'Search Text',
            'section' => 'form_customizer_section_search',
            'type'    => 'text',
        ) );

        /**
         * Fifth Section form settings and controls
         */
        $wp_customize->add_setting( 'label_first_section_five', array(
            'type'              => 'theme_mod',
            'transport'         => 'refresh',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'default'           => 'Saturation',
        ) );
        $wp_customize->add_control( 'label_first_section_five', array(
            'label'   => 'Label',
            'section' => 'form_customizer_section_five',
            'type'    => 'text',
        ) );
        $wp_customize->add_setting( 'submit_text_section_five', array(
            'type'              => 'theme_mod',
            'transport'         => 'refresh',
            'sanitize_callback' => 'wp_filter_nohtml_kses',
            'default'           => 'Agree and send application',
        ) ); // Submit Text
        $wp_customize->add_control( 'submit_text_section_five', array(
            'label'   => 'Label',
            'section' => 'form_customizer_section_five',
            'type'    => 'text',
        ) ); // Submit Text

        /**
         * Video Section
         */

        $wp_customize->add_setting( 'vrchecke_video_section', array(
            'type'              => 'theme_mod',
            'transport'         => 'refresh',
            'sanitize_callback' => 'wp_kses_post',
        ) ); // Url or Iframe
        $wp_customize->add_control( 'vrchecke_video_section', array(
            'label'       => 'Iframe or src url',
            'section'     => 'form_customizer_section_video',
            'type'        => 'textarea',
            'description' => 'Insert iframe or url as src:https://example.com',
        ) ); // Url or Iframe

    }

    /**
     * Validation: image
     * Control: text, WP_Customize_Image_Control
     *
     * @uses    wp_check_filetype()        https://developer.wordpress.org/reference/functions/wp_check_filetype/
     * @uses    in_array()                http://php.net/manual/en/function.in-array.php
     */
    public function ic_sanitize_image( $file, $setting )
    {

        $mimes = array(
            'jpg|jpeg|jpe' => 'image/jpeg',
            'gif'          => 'image/gif',
            'png'          => 'image/png',
            'bmp'          => 'image/bmp',
            'tif|tiff'     => 'image/tiff',
            'ico'          => 'image/x-icon',
        );

        //check file type from file name
        $file_ext = wp_check_filetype( $file, $mimes );

        //if file has a valid mime type return it, otherwise return default
        return ( $file_ext['ext'] ? $file : $setting->default );
    }

    public function action_kses_allowed_html( $allowedposttags )
    {

        // Only change for users who can publish posts
        if ( !current_user_can( 'publish_posts' ) ) {
            return $allowedposttags;
        }

        // Allow iframes and the following attributes
        $allowedposttags['iframe'] = array(
            'align'        => true,
            'width'        => true,
            'height'       => true,
            'frameborder'  => true,
            'name'         => true,
            'src'          => true,
            'id'           => true,
            'class'        => true,
            'style'        => true,
            'scrolling'    => true,
            'marginwidth'  => true,
            'marginheight' => true,
        );

        return $allowedposttags;
    }

}