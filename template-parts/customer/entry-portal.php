<?php
/**
 * Template part for displaying a post
 *
 * @package vrchecke
 */

namespace VRCHECKE\VRCHECKE;

use function VRCHECKE\VRCHECKE\Customer_Portal\insert_attachment;
use function VRCHECKE\VRCHECKE\Customer_Portal\update_selected_company;
vrchecke()->print_styles( 'vrchecke-portal' );
vrchecke()->print_styles( 'intlTelInput' );

/**
 * Processing Form
 */
if ( isset( $_POST['selected_company'] ) && isset( $_POST['selected_company_nonce'] ) ) {
    $retval = update_selected_company( get_current_user_id(), $_POST['selected_company'], $_POST['selected_company_nonce'] );
}
/**
 * Uploading Invoice
 */
if ( isset( $_FILES['invoice'] ) && isset( $_POST['invoice_nonce'] ) ) {
    $invoice_msg = insert_attachment( 'invoice', get_current_user_id(), $_POST['invoice_nonce'] );
}

if ( !is_user_logged_in() ): ?>
<p>Please Log in.</p>
<?php
endif;
if ( is_user_logged_in() ):

    $user_id = get_current_user_id();
    $user    = get_userdata( $user_id );
    $entry   = vrchecke_get_entry_by_user_id( $user_id );
    if ( $entry && $entry instanceof \VRCHECKE_Form\Form_Entry ): // <!-- Entry Check -->
        $first_option    = ( $entry->get_first_option() || $entry->get_first_option() !== null ) ? (int) $entry->get_first_option() : false;
        $second_option   = ( $entry->get_second_option() && $entry->get_second_option() !== null ) ? (int) $entry->get_second_option() : false;
        $third_option    = ( $entry->get_third_option() && $entry->get_third_option() !== null ) ? (int) $entry->get_third_option() : false;
        $selected_option = ( $entry->get_selected_option() && $entry->get_selected_option() !== null ) ? (int) $entry->get_selected_option() : false;
        ?>


<article id="post-<?php the_ID();?>" <?php post_class( 'entry-portal' );?>>

    <?php
        /**
         * Displaying form process result
         */
        echo '<div class="return-msg">' . __( $retval, 'vrchecke' ) . '</div>';
        echo '<div class="return-msg">' . __( $invoice_msg, 'vrchecke' ) . '</div>';
        ?>

    <div class="portal-container">
        <div class="portal__form">
            <div class="entry-title">
                <h1>Personal Information</h1>
                <a href="#" class="edit_icon">
                    <span class="material-icons">
                        edit
                    </span></a>
            </div>
            <form action="" method="post" id="edit_customer_form">
                <div class="portal__name-wrap  portal__input-wrap">
                    <div class="name__first input__wrapper ">
                        <label for="first-name" class="first-name-label">First Name</label>
                        <input value="<?php echo esc_attr( $user->first_name ); ?>" type="text"
                            class="portal__input first-name" name="first-name" disabled />
                        <!-- <div class="portal__icon-wrap">
																																																																																																																																																																																																																																																																																													<a href="#" class="edit_icon">
																																																																																																																																																																																																																																																																																														<span class="material-icons">
																																																																																																																																																																																																																																																																																															edit
																																																																																																																																																																																																																																																																																														</span></a>
																																																																																																																																																																																																																																																																																													<a href="#" id="edit_name" class="process_edit_icon hidden" data-action="first_name">
																																																																																																																																																																																																																																																																																														<span class="material-icons">
																																																																																																																																																																																																																																																																																															done
																																																																																																																																																																																																																																																																																														</span></a>
																																																																																																																																																																																																																																																																																													<div class="loader"></div>
																																																																																																																																																																																																																																																																																												</div> -->
                    </div>

                    <div class="name__last input__wrapper ">
                        <label for="last-name" class="last-name-label">Last Name</label>
                        <input value="<?php echo esc_attr( $user->last_name ); ?>" type="text"
                            class="portal__input last-name" name="last-name" disabled />
                    </div>
                </div>
                <div class="portal__postal-wrap portal__input-wrap">
                    <div class="input__wrapper">
                        <label for="postal-code" class="postal-code-label">Postal Code</label>
                        <input value="<?php echo esc_attr( $entry->get_postal_code() ); ?>" type="text"
                            class="portal__input postal-code" name="postal-code" disabled />
                    </div>
                </div>
                <div class="portal__address-wrap portal__input-wrap">
                    <div class="input__wrapper">
                        <label for="address" class="address-label">Address</label>
                        <input value="<?php echo esc_attr( $user->address ); ?>" type="text"
                            class="portal__input address" name="address" disabled />
                    </div>
                </div>
                <div class="portal__city-wrap  portal__input-wrap">
                    <div class="input__wrapper">
                        <label for="city" class="city-label">City</label>
                        <input value="<?php echo esc_attr( $user->city ); ?>" type="text" class="portal__input city"
                            name="city" disabled />
                    </div>
                </div>
                <div class="portal__phone-wrap portal__input-wrap">
                    <div class="input__wrapper">
                        <label for="phone" class="phone-label">Phone Number</label>
                        <input value="<?php echo esc_attr( $user->phone_number ); ?>" name="phone" type="tel" id="phone"
                            class="phone" disabled />
                    </div>
                    <span class="error-msg hidden"></span>
                </div>
                <div class="portal__submit-wrap">
                    <input type="hidden" value="<?php echo wp_create_nonce( 'edit_customer_nonce' ); ?>"
                        name="edit_customer_nonce">
                    <input type="submit" value="Submit" id="submit" />
                    <div class="loader"></div>
                </div>
            </form>
        </div>

        <div class="invoice__upload-form">
            <h3><?php _e( 'Upload your invoice', 'vrchecke' );?></h3>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="portal__invoice-wrap portal__input-wrap">
                    <input type="file" class="invoice portal__input" name="invoice" />
                    <input type="submit" value="Submit">
                    <input type="hidden" name="invoice_nonce"
                        value="<?php echo wp_create_nonce( 'invoice_nonce_action' ); ?>">
                </div>
            </form>
        </div>
        <?php if ( !$selected_option ): ?>
        <div class="company-select__form">
            <?php if ( !$first_option && !$second_option && !$third_option ) {
            echo '<h3>' . __( 'No options available at the moment', 'vrchecke' ) . '</h3>';
        } else {?>
            <h3>Select your preferred company</h3>
            <form action="" method="post" class="company-select">
                <div class="options__container">
                    <div class="option-container <?php echo ( $first_option ) ? 'option-available' : ''; ?>">
                        <div class="pdf-container">
                            <div class="pdf-img__wrap">
                                <img src="/wp-content/themes/wprig-vrchecke/assets/images/description_black_24dp.svg"
                                    alt="" class="pdf-img" />
                            </div>
                            <p class="pdf-title">
                                <?php echo ( $first_option ) ? get_the_title( $first_option ) : __( 'Not available', 'vrchecke' ); ?>
                            </p>
                        </div>
                        <?php if ( $first_option ): ?>
                        <div class="icon-container"><span class="material-icons">done</span></div>
                        <div class="tool-set"><a href="<?php echo esc_url( wp_get_attachment_url( $first_option ) ); ?>"
                                target="_blank"><span class="material-icons">visibility</span></a><a href="#"
                                class="company-select-btn"><span class="material-icons">done</span></a></div>
                        <input type="radio" name="selected_company" value="<?php echo $first_option; ?>"
                            style="visibility:hidden;">
                        <?php endif;?>
                    </div>
                    <div class="option-container <?php echo ( $second_option ) ? 'option-available' : ''; ?>">
                        <div class="pdf-container">
                            <div class="pdf-img__wrap">
                                <img src="/wp-content/themes/wprig-vrchecke/assets/images/description_black_24dp.svg"
                                    alt="" class="pdf-img" />
                            </div>
                            <p class="pdf-title">
                                <?php echo ( $second_option ) ? get_the_title( $second_option ) : __( 'Not available', 'vrchecke' ); ?>
                            </p>
                        </div>
                        <?php if ( $second_option ): ?>
                        <div class="icon-container"><span class="material-icons">done</span></div>
                        <div class="tool-set"><a
                                href="<?php echo esc_url( wp_get_attachment_url( $second_option ) ); ?>"
                                target="_blank"><span class="material-icons">visibility</span></a><a href="#"
                                class="company-select-btn"><span class="material-icons">done</span></a></div>
                        <input type="radio" name="selected_company" value="<?php echo $second_option; ?>"
                            style="visibility:hidden;">
                        <?php endif;?>
                    </div>
                    <div class="option-container <?php echo ( $third_option ) ? 'option-available' : ''; ?>">
                        <div class="pdf-container">

                            <div class="pdf-img__wrap">
                                <img src="/wp-content/themes/wprig-vrchecke/assets/images/description_black_24dp.svg"
                                    alt="" class="pdf-img" />
                            </div>
                            <p class="pdf-title">
                                <?php echo ( $third_option ) ? get_the_title( $third_option ) : __( 'Not available', 'vrchecke' ); ?>
                            </p>
                        </div>
                        <?php if ( $third_option ): ?>
                        <div class="icon-container"><span class="material-icons">done</span></div>
                        <div class="tool-set"><a href="<?php echo esc_url( wp_get_attachment_url( $third_option ) ); ?>"
                                target="_blank"><span class="material-icons">visibility</span></a><a href="#"
                                class="company-select-btn"><span class="material-icons">done</span></a></div>
                        <input type="radio" name="selected_company" value="<?php echo $third_option; ?>"
                            style="visibility:hidden;">
                        <?php endif;?>
                    </div>

                </div>
                <div class="button-wrap">
                    <input type="submit" value="Submit" />
                    <input type="hidden" name="selected_company_nonce"
                        value="<?php echo wp_create_nonce( 'selected_company_nonce_action' ); ?>">
                </div>

            </form>
            <?php }?>
        </div>
        <?php endif;
if ( $selected_option ):
?>
        <h3>Your selected company</h3>
        <div class="selected__option">
            <div class="selected__option-container">
                <div class="pdf-container">
                    <div class="pdf-img__wrap">
                        <img src="/wp-content/themes/wprig-vrchecke/assets/images/description_black_24dp.svg" alt=""
                            class="pdf-img" />
                    </div>
                    <p class="pdf-title">
                        <?php echo ( $selected_option ) ? get_the_title( $selected_option ) : __( 'Not available', 'vrchecke' ); ?>
                    </p>
                </div>
                <div class="icon-container"><span class="material-icons">done</span></div>
                <div class="tool-set"><a href="<?php echo esc_url( wp_get_attachment_url( $selected_option ) ); ?>"
                        target="_blank"><span class="material-icons">visibility</span></a></div>
            </div>
        </div>
    </div>
    <?php
endif; // <!-- Selected Check -->
endif; // <!-- Entry Check -->
if ( is_search() ) {
    get_template_part( 'template-parts/content/entry_summary', get_post_type() );
} else {
    get_template_part( 'template-parts/content/entry_content', get_post_type() );
}
get_template_part( 'template-parts/content/entry_footer', get_post_type() );
?>
</article>

<?php endif;?>