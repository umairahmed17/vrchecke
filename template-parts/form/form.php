<?php
/**
 * Template part for displaying the form
 *
 * @package vrchecke
 */

namespace VRCHECKE\VRCHECKE;

vrchecke()->print_styles( 'vrchecke-vrchecke-form' );
?>

<article class="vrchecke-form">
    <div id="svg_wrap"></div>
    <form action="">
        <section class="form-sections" data-section="1">
            <p><?php _e( get_theme_mod( 'label_first_section_one', 'Is this your first E-check?' ), 'vrchecke' );?></p>
            <div class="e-statement-container vrchecke-input-container">
                <div class="vrchecke-radio-container">
                    <div class="vrchecke-radio-wrapper">
                        <span class="vrchecke-radio vrchecke-radio--hidden"><?php _e( 'Select', 'vrchecke' );?></span>
                        <span class="vrchecke-radio">Y</span>
                    </div>
                </div>
                <label for="check-yes">
                    <input type="radio" id="check-yes" name="e-statement-check" value="true" />
                    <?php _e( get_theme_mod( 'option_first_section_one', 'Yes' ), 'vrchecke' );?>
                </label>
                <div class="icon-container"><span class="material-icons">
                        done
                    </span></div>
            </div>
            <div class="e-statement-container vrchecke-input-container">
                <div class="vrchecke-radio-container">
                    <div class="vrchecke-radio-wrapper">
                        <span class="vrchecke-radio vrchecke-radio--hidden"><?php _e( 'Select', 'vrchecke' );?></span>
                        <span class="vrchecke-radio">N</span>
                    </div>
                </div>
                <label for="check-no">
                    <input type="radio" id="check-no" name="e-statement-check" value="false" />
                    <?php _e( get_theme_mod( 'option_second_section_one', 'No' ), 'vrchecke' );?>
                </label>
                <div class="icon-container"><span class="material-icons">
                        done
                    </span></div>
            </div>
        </section>
        <section class="form-sections" data-section="2">
            <p><?php _e( get_theme_mod( 'label_first_section_two', 'Approximately how many electrical devices with plugs that are to be tested are there in the company(average 6 per employee)' ), 'vrchecke' );?>
            </p>
            <input id="num_devices" name="number_of_devices" type="number" inputmode="numeric"
                placeholder="<?php _e( 'Number of Electrical Devices', 'vrchecke' );?>">

        </section>
        <section class="form-sections  form-sections--image" data-section="3">
            <p><?php _e( get_theme_mod( 'label_first_section_three', 'What electrical equipement do you have?' ), 'vrchecke' );?>
            </p>
            <div class="electrical-eqp-container vrchecke-input-container vrchecke-input-container--image"
                data-container-id=1>
                <div class="icon-container icon-container--lg">
                    <?php if ( get_theme_mod( 'option_first_image_section_three' ) ) {?>
                    <img src="<?php echo esc_url( get_theme_mod( 'option_first_image_section_three' ) ) ?>" />
                    <?php } else {?>
                    <span class="material-icons">electrical_services</span>
                    <?php }?>
                </div>
                <div class="vrchecke-radio-container">
                    <div class="vrchecke-radio-wrapper">
                        <span class="vrchecke-radio vrchecke-radio--hidden"><?php _e( 'Select', 'vrchecke' );?></span>
                        <span class="vrchecke-radio">P</span>
                    </div>
                </div>
                <label for="check-p">
                    <input type="radio" id="check-p" name="electrical_eqp"
                        value="<?php _e( get_theme_mod( 'option_first_section_three', 'Portable equipment' ), 'vrchecke' );?>" />
                    <?php _e( get_theme_mod( 'option_first_section_three', 'Portable equipment' ), 'vrchecke' );?>
                </label>
                <div class="icon-container"><span class="material-icons">done</span></div>
                <div class="tooltip-container"><span class="material-icons">help_outline</span><span
                        class="tooltiptext"><?php _e( get_theme_mod( 'option_first_tooltip_section_three', 'Portable electrical equipement includes all electrical devices that have a plug, are not permanently installed and weigh less than 23 kg.' ), 'vrchecke' );?></span>
                </div>
            </div>
            <div class="electrical-eqp-container vrchecke-input-container vrchecke-input-container--image"
                data-container-id=2>
                <div class="icon-container icon-container--lg">
                    <?php if ( get_theme_mod( 'option_second_image_section_three' ) ) {?>
                    <img src="<?php echo esc_url( get_theme_mod( 'option_second_image_section_three' ) ) ?>" />
                    <?php } else {?>
                    <span class="material-icons">schedule</span>
                    <?php }?>
                </div>
                <div class="vrchecke-radio-container">
                    <div class="vrchecke-radio-wrapper">
                        <span class="vrchecke-radio vrchecke-radio--hidden"><?php _e( 'Select', 'vrchecke' );?></span>
                        <span class="vrchecke-radio">S</span>
                    </div>
                </div>
                <label for="check-s">
                    <input type="radio" id="check-s" name="electrical_eqp"
                        value="<?php _e( get_theme_mod( 'option_second_section_three', 'Stationary equipment' ), 'vrchecke' );?>" />
                    <?php _e( get_theme_mod( 'option_second_section_three', 'Stationary equipment' ), 'vrchecke' );?>
                </label>
                <div class="icon-container"><span class="material-icons">done</span></div>
                <div class="tooltip-container"><span class="material-icons">help_outline</span><span
                        class="tooltiptext"><?php _e( get_theme_mod( 'option_second_tooltip_section_three', 'Stationary electrical equipment is either firmly anchored or very massive equipment with an electrical element.' ), 'vrchecke' );?></span>
                </div>
            </div>
            <div class="electrical-eqp-container vrchecke-input-container vrchecke-input-container--image"
                data-container-id=3>
                <div class="icon-container icon-container--lg">
                    <?php if ( get_theme_mod( 'option_third_image_section_three' ) ) {?>
                    <img src="<?php echo esc_url( get_theme_mod( 'option_third_image_section_three' ) ) ?>" />
                    <?php } else {?>
                    <span class="material-icons">precision_manufacturing</span>
                    <?php }?>
                </div>
                <div class="vrchecke-radio-container">
                    <div class="vrchecke-radio-wrapper">
                        <span class="vrchecke-radio vrchecke-radio--hidden"><?php _e( 'Select', 'vrchecke' );?></span>
                        <span class="vrchecke-radio">ES</span>
                    </div>
                </div>
                <label for="check-es">
                    <input type="radio" id="check-es" name="electrical_eqp"
                        value="<?php _e( get_theme_mod( 'option_third_section_three', 'Electrical systems ' ), 'vrchecke' );?>" />
                    <?php _e( get_theme_mod( 'option_third_section_three', 'Electrical systems ' ), 'vrchecke' );?>
                </label>
                <div class="icon-container"><span class="material-icons">done</span></div>
                <div class="tooltip-container"><span class="material-icons">help_outline</span><span
                        class="tooltiptext"><?php _e( get_theme_mod( 'option_third_tooltip_section_three', 'Electrical systems are permanently connected groups of electrical equipment.' ), 'vrchecke' );?></span>
                </div>
            </div>
            <div class="electrical-eqp-container vrchecke-input-container vrchecke-input-container--image"
                data-container-id=4>
                <div class="icon-container icon-container--lg">
                    <?php if ( get_theme_mod( 'option_fourth_image_section_three' ) ) {?>
                    <img src="<?php echo esc_url( get_theme_mod( 'option_fourth_image_section_three' ) ) ?>" />
                    <?php } else {?>
                    <span class="material-icons">factory</span>
                    <?php }?>
                </div>
                <div class="vrchecke-radio-container">
                    <div class="vrchecke-radio-wrapper">
                        <span class="vrchecke-radio vrchecke-radio--hidden"><?php _e( 'Select', 'vrchecke' );?></span>
                        <span class="vrchecke-radio">EM</span>
                    </div>
                </div>
                <label for="check-em">
                    <input type="radio" id="check-em" name="electrical_eqp"
                        value="<?php _e( get_theme_mod( 'option_fourth_section_three', 'Electrical machine' ), 'vrchecke' );?>" />
                    <?php _e( get_theme_mod( 'option_fourth_section_three', 'Electrical machine' ), 'vrchecke' );?>
                </label>
                <div class="icon-container"><span class="material-icons">done</span></div>
                <div class="tooltip-container"><span class="material-icons">help_outline</span><span
                        class="tooltiptext"><?php _e( get_theme_mod( 'option_fourth_tooltip_section_three', 'An electrical machine is a machine used in electrical energy technology and represents a form of energy converter.' ), 'vrchecke' );?></span>
                </div>
            </div>
            <div class="electrical-eqp-container vrchecke-input-container vrchecke-input-container--image"
                data-container-id=5>
                <div class="icon-container icon-container--lg">
                    <?php if ( get_theme_mod( 'option_fifth_image_section_three' ) ) {?>
                    <img src="<?php echo esc_url( get_theme_mod( 'option_fifth_image_section_three' ) ) ?>" />
                    <?php } else {?>
                    <span class="material-icons">medical_services</span>
                    <?php }?>
                </div>
                <div class="vrchecke-radio-container">
                    <div class="vrchecke-radio-wrapper">
                        <span class="vrchecke-radio vrchecke-radio--hidden"><?php _e( 'Select', 'vrchecke' );?></span>
                        <span class="vrchecke-radio">M</span>
                    </div>
                </div>
                <label for="check-m">
                    <input type="radio" id="check-m" name="electrical_eqp"
                        value="<?php _e( get_theme_mod( 'option_fifth_section_three', 'Medical electrical devices' ), 'vrchecke' );?>" />
                    <?php _e( get_theme_mod( 'option_fifth_section_three', 'Medical electrical devices' ), 'vrchecke' );?>
                </label>
                <div class="icon-container"><span class="material-icons">done</span></div>
                <div class="tooltip-container"><span class="material-icons">help_outline</span><span
                        class="tooltiptext"><?php _e( get_theme_mod( 'option_fifth_tooltip_section_three', 'Medical electrical devices include all electronically operated devices that are used in medical practices or clinics.' ), 'vrchecke' );?></span>
                </div>
            </div>
        </section>
        <section class="form-sections" data-section="4">
            <div class="map-container">
                <img src="<?php echo ( get_theme_mod( 'map_image_section_four' ) ) ? esc_url( get_theme_mod( 'map_image_section_four' ) ) : get_stylesheet_directory_uri() . '/assets/images/de.svg'; ?>"
                    alt="Germany Map" srcset="">
            </div>
            <div class="input-container">
                <p><?php _e( get_theme_mod( 'label_first_section_four', 'Postal code' ), 'vrchecke' );?> </p>
                <!-- <input type="text" placeholder="Email address" /> -->
                <input id="zip" name="postal-code" type="text" inputmode="numeric"
                    pattern="^(?(^00000(|-0000))|(\d{5}(|-\d{4})))$" placeholder="Zip Code">
            </div>
        </section>
        <section class="form-sections" data-section="5">
            <div class="loading-icon_container">
                <div class="company-images__container">
                    <div class="images__wrap">
                        <img src="//d2gui02c8ysary.cloudfront.net/uploads/attachment/image/64406/solar_lupe_logos_animation_02_green.gif"
                            alt="Loading gif" />
                        <!-- <div class="image-container">
                            <img src="" />
                        </div>
                        <div class="image-container"><img src="" />
                        </div>
                        <div class="image-container"><img src="" />
                        </div>
                        <div class="image-container"><img src="" />
                        </div> -->
                    </div>
                </div>
                <!-- <div class="icon__search"><span class="material-icons">search</span></div> -->
            </div>
            <div class="loading-done">
                <div class="icon__done"><span class="material-icons">done</span></div>
                <p class="loading_text--done">
                    <?php _e( get_theme_mod( 'search_text_section_four' ) ? get_theme_mod( 'search_text_section_four' ) : 'Found', 'vrchecke' );?>
                </p>
            </div>
        </section>
        <section class="form-sections" data-section="6">
            <p><?php _e( get_theme_mod( 'label_first_section_five', 'Salutations' ), 'vrchecke' );?></p>
            <p class="name-container input-container__ls">
                <input type="text" placeholder="First Name" name="first-name" />
                <input type="text" placeholder="Last Name" name="last-name" />
            </p>
            <p class="street-container input-container__ls">
                <input type="text" placeholder="Street , House Number" name="street-address" />
            </p>
            <p class="city-container input-container__ls">
                <input id="zip" name="postal-code-duplicate" type="text" inputmode="numeric"
                    pattern="^(?(^00000(|-0000))|(\d{5}(|-\d{4})))$" placeholder="Zip Code">
                <input type="text" placeholder="City" name="city" />
            </p>
            <p class="email-container input-container__ls">
                <input type="text" placeholder="Email Address" name="email" />
                <input type="number" placeholder="Phone Number" name="phone-number" />
            </p>
        </section>
        <div class="progress-bar">
            <div id="progress-bar__inner">
            </div>
        </div>
        <div class="button arrow-controls" id="prev"><span class="material-icons">chevron_left</span></div>
        <div class="button arrow-controls" id="next"><span class="material-icons">chevron_right</span></div>
        <div class="button-group">
            <input type="hidden" name="vrchecke_nonce"
                value="<?php echo wp_create_nonce( 'vrchecke_form_nonce' ); ?>" />
            <div class="button" id="submit">
                <?php _e( get_theme_mod( 'submit_text_section_five' ) ? get_theme_mod( 'submit_text_section_five' ) : 'Agree and send application', 'vrchecke' );?>
            </div>
        </div>
    </form>
</article>

<article class="video-container">
    <?php
$video  = get_theme_mod( 'vrchecke_video_section' ) ? get_theme_mod( 'vrchecke_video_section' ) : false;
$is_src = preg_match( '/(src:)/', $video );
if ( $is_src ) {
    $video = preg_replace( '/(src:)/', '', $video );
    ?>
    <video controls>
        <source src="<?php esc_url( $video );?>">
        Your browser does not support the video tag.
    </video>
    <?php } else {
    echo $video;
}

?>
</article>