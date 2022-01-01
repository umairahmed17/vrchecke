<?php
/**
 * VRCHECKE\VRCHECKE\Google_Sign_In\Component class
 *
 * @package vrchecke
 */

namespace VRCHECKE\VRCHECKE\Google_Sign_In;

use Google\Service\OAuth2;
use Google_Client;
use VRCHECKE\VRCHECKE\Component_Interface;

/**
 * Class for adding google log in support.
 *
 * @link https://codex.wordpress.org/Theme_Logo
 */
class Component implements Component_Interface
{

    /**
     * Setting Api Client and Private ID
     */
    private $ClientId;
    private $SecretId;
    public $ApplicationName;
    private $RedirectUrl;
    public $login_url;
    private $gClient;

    /**
     * Construct Object
     */
    public function __construct()
    {
        $this->ClientId        = get_theme_mod( 'gclient_id' );
        $this->SecretId        = get_theme_mod( 'gsecret_id' );
        $this->ApplicationName = get_theme_mod( 'gappname_id' );
        $this->RedirectUrl     = get_site_url( null, '/wp-admin/admin-ajax.php?action=vm_login_google', $scheme = 'https' );
    }

    /**
     * Gets the unique identifier for the theme component.
     *
     * @return string Component slug.
     */
    public function get_slug(): string
    {
        return 'google-signin';
    }

    /**
     * Adds the action and filter hooks to integrate with WordPress.
     */
    public function initialize()
    {
        $this->init();
        add_action( 'wp_ajax_nopriv_vm_login_google', array( $this, 'vm_login_google' ) );
        add_action( 'wp_ajax_vm_login_google', array( $this, 'vm_login_google' ) );
        // add_action( 'wp_head', array( $this, 'action_add_style' ) );
        add_shortcode( 'google-login', array( $this, 'vm_login_with_google' ) );
        // add_action( 'wp_head', [$this, 'debugging_theme'] );
        add_action( 'customize_register', array( $this, 'action_customize_add_control' ) );
    }

    private function init()
    {
        if ( $this->ClientId && $this->SecretId && $this->ApplicationName && $this->RedirectUrl ) {
            $this->gClient = new Google_Client();
        }
        if ( $this->gClient instanceof Google_Client ) {
            $this->gClient->setClientId( $this->ClientId );
            $this->gClient->setClientSecret( $this->SecretId );
            $this->gClient->setApplicationName( $this->ApplicationName );
            $this->gClient->setRedirectUri( $this->RedirectUrl );
            $this->gClient->addScope( 'https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email' );

            // Setting the login Url
            $this->login_url = $this->gClient->createAuthUrl();
            error_log( var_export( $this->login_url, true ) . ' ' . __FILE__ . ' on the line ' . __LINE__ . "\n" );
        }

    }

    /**
     * Shortcode for Google Signin Button
     */
    public function vm_login_with_google()
    {
        if ( !is_user_logged_in() ) {
            // checking to see if the registration is opend
            if ( !get_option( 'users_can_register' ) ) {
                return ( 'Registration is closed!' );
            } else {
                get_template_part( 'template-parts/signin/signin', null, array( 'login_url' => $this->login_url ) );
            }
        } else {
            $current_user = wp_get_current_user();
            return '<div class="googleBtn">Hi, ' . $current_user->first_name . '! - <a href="/wp-login.php?action=logout">Log Out</a></div>';
        }
    }

    /**
     * Ajax Request Function
     */
    public function vm_login_google()
    {
        if ( isset( $_GET['code'] ) ) {
            $token = $this->gClient->authenticate( $_GET['code'] );

            if ( !isset( $token['error'] ) ) {
                // get data from google
                $oAuth = new OAuth2( $this->gClient );
                var_dump( $oAuth );
                $userData = $oAuth->userinfo_v2_me->get();
                var_dump( $userData );
            }

            // check if user email already registered
            if ( !email_exists( $userData['email'] ) ) {
                // generate password
                $bytes    = openssl_random_pseudo_bytes( 2 );
                $password = md5( bin2hex( $bytes ) );

                $new_user_id = wp_insert_user(
                    array(
                        'user_login'      => $userData['email'],
                        'user_pass'       => $password,
                        'user_email'      => $userData['email'],
                        'first_name'      => $userData['givenName'],
                        'last_name'       => $userData['familyName'],
                        'user_registered' => date( 'Y-m-d H:i:s' ),
                        'role'            => 'subscriber',
                    )
                );
                if ( $new_user_id ) {
                    // send an email to the admin
                    wp_new_user_notification( $new_user_id );

                    // log the new user in
                    do_action( 'wp_login', $userData['email'], $userData['email'] );
                    wp_set_current_user( $new_user_id );
                    wp_set_auth_cookie( $new_user_id, true );

                    // send the newly created user to the home page after login
                    wp_redirect( home_url() );
                    exit;
                }
            } else {
                // if user already registered than we are just loggin in the user
                $user = get_user_by( 'email', $userData['email'] );
                do_action( 'wp_login', $user->user_login, $user->user_email );
                wp_set_current_user( $user->ID );
                wp_set_auth_cookie( $user->ID, true );
                wp_redirect( home_url() );
                exit;
            }

            var_dump( $userData );
        } else {
            wp_redirect( home_url() );
            exit();
        }
    }

    /**
     * Adding css styling
     */
    public function action_add_style()
    {?>
<style type="text/css" id="google-signin-btn">
#customBtn {
    display: inline-block;
    background: white;
    color: #444;
    width: 190px;
    border-radius: 5px;
    border: thin solid #888;
    box-shadow: 1px 1px 1px grey;
    white-space: nowrap;
}

#customBtn:hover {
    cursor: pointer;
}

span.label {
    font-family: serif;
    font-weight: normal;
}

span.icon {
    background: url('/wp-content/themes/wprig-vrchecke/assets/images/g-normal.png') transparent 5px 50% no-repeat;
    display: inline-block;
    vertical-align: middle;
    width: 42px;
    height: 42px;
}

span.buttonText {
    display: inline-block;
    vertical-align: middle;
    padding-left: 42px;
    padding-right: 42px;
    font-size: 14px;
    font-weight: bold;
    /* Use the Roboto font that is loaded in the <head> */
    font-family: 'Roboto', sans-serif;
}
</style>
<?php
}
    public function action_customize_add_control( $wp_customize )
    {
        $wp_customize->add_setting( 'callback_link', array(
            'default'   => $this->RedirectUrl,
            'transport' => 'postMessage',
        ) );
        $wp_customize->add_control( new \WP_Customize_Control( $wp_customize, 'action_hook_google', array(
            'label'       => __( 'Google Signin Action', 'vrchecke' ),
            'type'        => 'text',
            'priority'    => 10,
            'settings'    => 'callback_link',
            'section'     => 'vrchecke_theme_google_cred_section',
            'input_attrs' => array(
                'disabled' => 'disabled',
            ),
            'description' => 'use this link in the redirect uri section of google app',
        ) ) );
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