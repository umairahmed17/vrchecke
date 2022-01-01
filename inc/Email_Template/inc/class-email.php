<?php
/**
 * Emails
 *
 * This class handles all emails sent.
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * VRCHECKE_Emails class
 */
class VRCHECKE_Emails
{

    /**
     * Holds the from address
     */
    private $from_address;

    /**
     * Holds the from name
     */
    private $from_name;

    /**
     * Holds the email content type
     */
    private $content_type;

    /**
     * Holds the email headers
     */
    private $headers;

    /**
     * Whether to send email in HTML
     */
    private $html = true;

    /**
     * User ID
     */
    private $user_id;

    /**
     * Container for storing all tags
     */
    private $tags;

    /**
     * Set a property
     *

     * @return void
     */
    public function __set( $key, $value )
    {
        $this->$key = $value;
    }

    /**
     * Get the email from name
     *

     * @return string The email from name
     */
    public function get_from_name()
    {

        if ( !$this->from_name ) {
            $this->from_name = !empty( get_option( 'email_from_name' ) ) ? sanitize_text_field( get_option( 'email_from_name' ) ) : get_option( 'blogname' );
        }

        return wp_specialchars_decode( $this->from_name );
    }

    /**
     * Get the email from address
     *

     * @return string The email from address
     */
    public function get_from_address()
    {
        if ( !$this->from_address ) {

            $this->from_address = !empty( get_option( 'email_from_address' ) ) ? sanitize_text_field( get_option( 'email_from_address' ) ) : get_option( 'admin_email' );
        }

        return $this->from_address;
    }

    /**
     * Get the corresponding user ID.
     *
     * @since 3.0.8
     * @return int
     */
    public function get_user_id()
    {
        return absint( $this->user_id );
    }

    /**
     * Get the email headers
     *

     * @return string The email headers
     */
    public function get_headers()
    {
        if ( !$this->headers ) {
            $this->headers = "From: {$this->get_from_name()} <{$this->get_from_address()}>\r\n";
            $this->headers .= "Reply-To: {$this->get_from_address()}\r\n";
            $this->headers .= "Content-Type: html; charset=utf-8\r\n";
        }

        return $this->headers;
    }

    /**
     * Build the email
     *

     * @param string $message The email message
     * @return string
     */
    public function build_email( $message )
    {

        $message = $this->parse_tags( $message );

        if ( false === $this->html ) {
            return wp_strip_all_tags( $message );
        }

        $message = $this->text_to_html( $message );

        return $message;
    }

    /**
     * Send the email
     *

     * @param string|array $to The To address
     * @param string $subject The subject line of the email
     * @param string $message The body of the email
     * @param string|array $attachments Attachments to the email
     *
     * @return bool Whether the email contents were sent successfully.
     */
    public function send( $to, $subject, $message )
    {

        if ( !did_action( 'init' ) && !did_action( 'admin_init' ) ) {
            _doing_it_wrong( __FUNCTION__, __( 'You cannot send emails with vrchecke theme until init/admin_init has been reached', 'vrchecke' ), null );
            return false;
        }

        $this->setup_email_tags();

        $subject = $this->parse_tags( $subject );

        $message = $this->build_email( $message );

        $sent = wp_mail( $to, $subject, $message, $this->get_headers() );

        return $sent;
    }

    /**
     * Converts text formatted HTML. This is primarily for turning line breaks into <p> and <br/> tags.
     *

     * @return string
     */
    public function text_to_html( $message )
    {
        if ( 'text/html' === $this->content_type || true === $this->html ) {
            $message = wpautop( make_clickable( $message ) );
            $message = str_replace( '&#038;', '&amp;', $message );
        }

        return $message;
    }

    /**
     * Search content for email tags and filter email tags through their hooks
     *

     * @param string $content Content to search for email tags
     * @return string $content Filtered content
     */
    private function parse_tags( $content )
    {

        // Make sure there's at least one tag
        if ( empty( $this->tags ) || !is_array( $this->tags ) ) {
            return $content;
        }

        $new_content = preg_replace_callback( "/%([A-z0-9\-\_]+)%/s", array( $this, 'do_tag' ), $content );

        return $new_content;
    }

    /**
     * Setup all registered email tags
     *

     * @return void
     */
    public function setup_email_tags()
    {

        $tags = $this->get_tags();

        foreach ( $tags as $tag ) {
            if ( isset( $tag['function'] ) && is_callable( $tag['function'] ) ) {
                $this->tags[$tag['tag']] = $tag;
            }
        }

    }

    /**
     * Retrieve all registered email tags
     *

     * @return array
     */
    public function get_tags()
    {
        // Setup default tags array
        $email_tags = array(
            array(
                'tag'         => 'name',
                'description' => __( 'The full name', 'vrchecke' ),
                'function'    => 'vrchecke_name_do_tag',
            ),
            array(
                'tag'         => 'useremail',
                'description' => __( 'The email address', 'vrchecke' ),
                'function'    => 'vrchecke_email_do_tag',
            ),
            array(
                'tag'         => 'firstname',
                'description' => __( 'The first name', 'vrchecke' ),
                'function'    => 'vrchecke_firstname_do_tag',
            ),
            array(
                'tag'         => 'lastname',
                'description' => __( 'The last name', 'vrchecke' ),
                'function'    => 'vrchecke_lastname_do_tag',
            ),
            array(
                'tag'         => 'password',
                'description' => __( 'The password', 'vrchecke' ),
                'function'    => 'vrchecke_userpassword_do_tag',

            ),
        );

        return apply_filters( 'vrchecke_email_template_tags', $email_tags );

    }
    /**
     * Parse a specific tag.
     *

     * @param $m Message
     */
    private function do_tag( $m )
    {

        // Get tag
        $tag = $m[1];

        // Return tag if not set
        if ( !$this->email_tag_exists( $tag ) ) {
            return $m[0];
        }

        return call_user_func( $this->tags[$tag]['function'], $this->user_id, $tag );
    }

    /**
     * Check if $tag is a registered email tag
     *

     * @param string $tag Email tag that will be searched
     * @return bool True if exists, false otherwise
     */
    public function email_tag_exists( $tag )
    {
        return array_key_exists( $tag, $this->tags );
    }

}