<?php
/**
 * Edit Entry
 */
use VRCHECKE\VRCHECKE\Admin_Form\Entries_Table;
require_once __DIR__ . '/class-form-entry-list.php';

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! isset( $_GET['entry_id'] ) || ! is_numeric( $_GET['entry_id'] ) ) {
	wp_die( __( 'Something went wrong.', 'vrchecke' ), __( 'Error', 'vrchecke' ), array( 'response' => 400 ) );
}

$entry_id = $_GET['entry_id'];
$entry    = vrchecke_get_single_entry( (int) $entry_id );

if ( empty( $entry ) ) {
	wp_die( __( 'Something went wrong.', 'vrchecke' ), __( 'Error', 'vrchecke' ), array( 'response' => 400 ) );
}

$user            = get_userdata( $entry->get_user_id() );
$address         = get_user_meta( $user->ID, 'address', true );
$city            = get_user_meta( $user->ID, 'city', true );
$phone           = get_user_meta( $user->ID, 'phone_number', true );
$first_option    = $entry->get_first_option();
$second_option   = $entry->get_second_option();
$third_option    = $entry->get_third_option();
$selected_option = $entry->get_selected_option();

$delete_entry_url = wp_nonce_url(
	Entries_Table::get_entries_admin_page(
		array(
			'vrchecke-action' => 'delete_entry',
			'entry_id'        => $entry->get_id(),
		)
	),
	'vrchecke_delete_entry_nonce'
);
?>
<div class="wrap">
	<h1><?php _e( 'Entry Details', 'vrchecke' ); ?></h1>

	<div id="vrchecke-item-card-wrapper">
		<div class="vrchecke-info-wrapper vrchecke-item-section vrchecke-entry-card-wrapper">
			<form id="vrchecke-edit-entry-info" method="POST">
				<div class="vrchecke-item-info">
					<div id="vrchecke-entry-account">
						<p class="vrchecke-entry-avatar">
							<?php echo get_avatar( $user->user_email, 150 ); ?>
						</p>
					</div>

					<div id="vrchecke-entry-details">
						<table class="widefat striped">
							<tbody>
								<?php do_action( 'vrchecke_edit_entry_before', $entry->get_user_id() ); ?>
								<tr>
									<th scope="row" class="row-title">
										<label for="tablecell"><?php _e( 'ID:', 'vrchecke' ); ?></label>
									</th>
									<td>
										<?php echo $entry->get_id(); ?>
									</td>
								</tr>
								<tr>
									<th scope="row" class="row-title">
										<label for="tablecell"><?php _e( 'Name:', 'vrchecke' ); ?></label>
									</th>
									<td>
										<label for="vrchecke-customer-first-name"
											class="screen-reader-text"><?php _e( 'First Name', 'vrchecke' ); ?></label>
										<input type="text" id="vrchecke-customer-first-name" name="first_name"
											value="<?php echo esc_attr( $user->first_name ); ?>"
											placeholder="<?php esc_attr_e( 'First name', 'vrchecke' ); ?>" />
										<label for="vrchecke-customer-last-name"
											class="screen-reader-text"><?php _e( 'Last Name', 'vrchecke' ); ?></label>
										<input type="text" id="vrchecke-customer-last-name" name="last_name"
											value="<?php echo esc_attr( $user->last_name ); ?>"
											placeholder="<?php esc_attr_e( 'Last name', 'vrchecke' ); ?>" />
									</td>
								</tr>
								<tr>
									<th scope="row" class="row-title">
										<label for="vrchecke-entry-email"><?php _e( 'Email:', 'vrchecke' ); ?></label>
									</th>
									<td>
										<input type="text" id="vrchecke-entry-email" name="user_email"
											value="<?php echo esc_attr( $user->user_email ); ?>" />
									</td>
								</tr>
								<tr>
									<th scope="row" class="row-title">
										<label for="tablecell"><?php _e( 'Entry Submitted:', 'vrchecke' ); ?></label>
									</th>
									<td>
										<?php echo $entry->get_date_registered(); ?>
									</td>
								</tr>
								<tr>
									<th scope="row" class="row-title">
										<label for="tablecell"><?php _e( 'First E-Check:', 'vrchecke' ); ?></label>
									</th>
									<td>
										<?php echo $entry->get_echeck_status() ? __( 'Yes', 'vrchecke' ) : __( 'No', 'vrchecke' ); ?>
									</td>
								</tr>
								<tr>
									<th scope="row" class="row-title">
										<label for="tablecell"><?php _e( 'Number of devices:', 'vrchecke' ); ?></label>
									</th>
									<td>
										<?php
										$num_devices = $entry->get_num_devices();
										echo ( gettype( $num_devices ) == 'integer' ) ? $num_devices : (int) $num_devices;
										?>
									</td>
								</tr>
								<tr>
									<th scope="row" class="row-title">
										<label for="tablecell"><?php _e( 'Type of devices:', 'vrchecke' ); ?></label>
									</th>
									<td>
										<?php
										$type_of_devices = $entry->get_type_of_devices();
										echo $type_of_devices;
										?>
									</td>
								</tr>
								<tr>
									<th scope="row" class="row-title">
										<label for="tablecell"><?php _e( 'Postal Code:', 'vrchecke' ); ?></label>
									</th>
									<td>
										<?php
										$postal_code = $entry->get_postal_code();
										echo ( gettype( $postal_code ) == 'string' ) ? $postal_code : (string) $postal_code;
										?>
									</td>
								</tr>
								<tr>
									<th scope="row" class="row-title">
										<label for="tablecell"><?php _e( 'Address:', 'vrchecke' ); ?></label>
									</th>
									<td>
										<?php
										echo $address;
										?>
									</td>
								</tr>
								<tr>
									<th scope="row" class="row-title">
										<label for="tablecell"><?php _e( 'City:', 'vrchecke' ); ?></label>
									</th>
									<td>
										<?php
										echo $city;
										?>
									</td>
								</tr>
								<tr>
									<th scope="row" class="row-title">
										<label for="tablecell"><?php _e( 'Phone Number:', 'vrchecke' ); ?></label>
									</th>
									<td>
										<?php
										echo $phone;
										?>
									</td>
								</tr>
								<tr>
									<th scope="row" class="row-title">
										<label for="tablecell"><?php _e( 'Select first choice:', 'vrchecke' ); ?></label>
									</th>
									<td>
										<a href="<?php echo ( $first_option ) ? wp_get_attachment_url( (int) $first_option ) : ''; ?>"
											class="selectedpdf"
											style="<?php echo ( $first_option ) ? '' : 'display:none;'; ?>">View Pdf</a>
										<a id="uploadpdf" href="#" class="misha-upl_1"
											style="<?php echo ( $first_option ) ? 'display:none;' : ''; ?>">Upload
											Pdf</a>
										<a href="#" class="misha-rmv"
											style="<?php echo ( $first_option ) ? '' : 'display:none;'; ?> color:red;">Remove
											Pdf</a>
										<input type="hidden" name="first_option" value="">
									</td>
								</tr>
								<tr>
									<th scope="row" class="row-title">
										<label for="tablecell"><?php _e( 'Select second choice:', 'vrchecke' ); ?></label>
									</th>
									<td>
										<a href="<?php echo ( $second_option ) ? wp_get_attachment_url( (int) $second_option ) : ''; ?>"
											class="selectedpdf"
											style="<?php echo ( $second_option ) ? '' : 'display:none;'; ?>">View Pdf</a>
										<a id="uploadpdf" href="#" class="misha-upl_2"
											style="<?php echo ( $second_option ) ? 'display:none;' : ''; ?>">Upload
											Pdf</a>
										<a href="#" class="misha-rmv"
											style="<?php echo ( $second_option ) ? '' : 'display:none;'; ?> color:red;">Remove
											Pdf</a>
										<input type="hidden" name="second_option" value="">

									</td>
								</tr>
								<tr>
									<th scope="row" class="row-title">
										<label for="tablecell"><?php _e( 'Select third choice:', 'vrchecke' ); ?></label>
									</th>
									<td>
										<a href="<?php echo ( $third_option ) ? wp_get_attachment_url( (int) $third_option ) : ''; ?>"
											class="selectedpdf"
											style="<?php echo ( $third_option ) ? '' : 'display:none;'; ?>">View Pdf</a>
										<a id="uploadpdf" href="#" class="misha-upl_3"
											style="<?php echo ( $third_option ) ? 'display:none;' : ''; ?>">Upload
											Pdf</a>
										<a href="#" class="misha-rmv"
											style="<?php echo ( $third_option ) ? '' : 'display:none;'; ?> color:red;">Remove
											Pdf</a>
										<input type="hidden" name="third_option" value="">
									</td>
								</tr>
								<tr>
									<th scope="row" class="row-title">
										<label for="tablecell"><?php _e( 'Selected choice:', 'vrchecke' ); ?></label>
									</th>
									<td> <?php if ( $selected_option ) { ?>
										<a href="<?php echo wp_get_attachment_url( (int) $selected_option ); ?>"
											id="selected_company_pdf">View Pdf</a>
										<?php } else { ?>
										None Selected
										<?php } ?>
									</td>
								</tr>

								<?php do_action( 'rcp_edit_entry_user_after', $entry->get_user_id() ); ?>
							</tbody>
						</table>
					</div>
				</div>

				<div id="vrchecke-item-edit-actions" class="edit-item">
					<input type="hidden" name="vrchecke-action" value="edit_entry" />
					<input type="hidden" name="entry_id" value="<?php echo esc_attr( $entry->get_id() ); ?>" />
					<?php wp_nonce_field( 'vrchecke_edit_entry_nonce', 'vrchecke_edit_entry_nonce' ); ?>
					<input type="submit" name="vrchecke_update_entry" id="vrchecke_update_entry"
						class="button button-primary" value="<?php _e( 'Update Entry', 'vrchecke' ); ?>" />
					&nbsp;<a href="<?php echo esc_url( $delete_entry_url ); ?>"
						class="vrchecke-delete-entry button"><?php _e( 'Delete Entry', 'vrchecke' ); ?></a>
				</div>
			</form>
		</div>
	</div>
</div>
</div>
