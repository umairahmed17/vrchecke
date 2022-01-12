<?php

/**
 * Render email template page
 *
 * @return void
 */
function vrchecke_email_page()
{
    ?>
<div class="wrap">
    <h2>Email Template</h2>

    <form method="post" action="options.php">
        <?php settings_fields( 'vrchecke_settings_group' );?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php _e( 'Email From:', 'vrchecke' );?></th>
                <td>
                    <?php
$email_from = get_option( 'email_from_name' ) ? sanitize_text_field( get_option( 'email_from_name' ) ) : get_option( 'blogname' );
    ?>
                    <input type="text" name="email_from_name" value="<?php echo $email_from; ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e( 'Email Address From:', 'vrchecke' );?></th>
                <td>
                    <?php
$email_from_address = get_option( 'email_from_address' ) ? sanitize_text_field( get_option( 'email_from_address' ) ) : get_option( 'blogname' );
    ?>
                    <input type="text" name="email_from_address" value="<?php echo $email_from_address; ?>" />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e( 'Email Tags:', 'vrchecke' );?></th>
                <td>
                    <?php
echo vrchecke_list_email_tags();

    ?>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php _e( 'Email Message:', 'vrchecke' );?></th>
                <td>
                    <?php
$email_body = get_option( 'email_template' ) ? wptexturize( get_option( 'email_template' ) ) : sprintf( __( 'Your email is %1s and your password assosiated is %2s', 'vrchecke' ), '%username%', '%password%' );
    wp_editor( $email_body, 'vrchecke-settings', array( 'textarea_name' => 'email_template', 'teeny' => true ) );
    ?>
                </td>
            </tr>

        </table>

        <p class="submit">
            <input type="submit" class="button-primary" value="<?php _e( 'Save Changes' )?>" />
        </p>

    </form>
</div>
<?php
}

/**
 * Rendering List of tags.
 */

function vrchecke_list_email_tags()
{

    $list = '<ul>';
    if ( !class_exists( 'VRCHECKE_Emails' ) ) {
        require_once __DIR__ . '/class-email.php';
    }

    $emails = new VRCHECKE_Emails();
    $tags   = $emails->get_tags();

    foreach ( $tags as $tag ) {
        $list .= '<li><em>%' . $tag['tag'] . '%</em> - ' . $tag['description'] . '</li>';
    }
    return $list . '</ul>';
}

function vrchecke_name_do_tag( $user_id = 0 )
{

    $user = get_userdata( $user_id );
    return ( $user->display_name ) ? $user->display_name : false;
}

function vrchecke_useremail_do_tag( $user_id = 0 )
{
    $user = get_userdata( $user_id );
    return ( $user->user_email ) ? $user->user_email : false;
}

function vrchecke_firstname_do_tag( $user_id = 0 )
{
    $user = get_userdata( $user_id );
    return ( $user->first_name ) ? $user->first_name : false;
}

function vrchecke_lastname_do_tag( $user_id = 0 )
{
    $user = get_userdata( $user_id );
    return ( $user->last_name ) ? $user->last_name : false;
}

function vrchecke_userpassword_do_tag( $user_id = 0 )
{
    $user = get_userdata( $user_id );
    return ( $user->user_pass ) ? $user->user_pass : false;
}