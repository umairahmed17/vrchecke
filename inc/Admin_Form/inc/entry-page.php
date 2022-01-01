<?php
/**
 * Form Entry Page
 */
use VRCHECKE_Form\Form_Entry;
use VRCHECKE\VRCHECKE\Admin_Form\Entries_Table;

if ( ! class_exists( 'VRCHECKE\VRCHECKE\Admin_Form\Entries_Table' ) ) {
	include_once __DIR__ . '/class-form-entry-list.php';
}
if ( ! class_exists( 'VRCHECKE_Form\Form_Entry' ) ) {
	include_once __DIR__ . '/class-form-entry.php';
}

/**
 * Render entry table
 *
 * @return void
 */
function vrchecke_entry_page() {
	if ( ! empty( $_GET['view'] ) && 'edit' == $_GET['view'] && ! empty( $_GET['entry_id'] ) ) {
		require_once __DIR__ . '/edit-entry.php';
		// } elseif ( !empty( $_GET['view'] ) && 'add' == $_GET['view'] ) {
		// require_once RCP_PLUGIN_DIR . 'includes/admin/customers/add-customer.php';
	} else {
		// List all customers.
		form_entry_list();
	}
	return;
}

/**
 * Display the list of customers.
 *
 * @since 3.0
 * @return void
 */
function form_entry_list() {
	include_once __DIR__ . '/class-form-entry-list.php';

	$table_class = new \VRCHECKE\VRCHECKE\Admin_Form\Entries_Table();
	$table_class->prepare_items();

	?>
<div class="wrap">
	<h1>
		<?php _e( 'Form Entries', 'rcp' ); ?>
	</h1>

	<form id="vrchecke-entry-filter" method="GET"
		action="<?php echo esc_url( Entries_Table::get_entries_admin_page() ); ?>">
		<input type="hidden" name="page" value="vrchecke-entry" />
		<?php
		$table_class->views();
		$table_class->search_box( __( 'Search entries', 'vrchecke' ), 'vrchecke-entry' );
		$table_class->display();
		?>
	</form>
</div>
	<?php

}

/**
 * Getting a single entry
 *
 * @param int $entry_id ID of the entry to recieve
 *
 * @return Form_Entry | false
 */

function vrchecke_get_single_entry( $entry_id ) {
	global $wpdb;
	$result     = false;
	$entry_id   = gettype( $entry_id ) === 'integer' ? absint( $entry_id ) : 0;
	$table_name = $wpdb->prefix . 'form_entries';
	$query      = sprintf( 'SELECT * FROM %s WHERE id = %d', $table_name, $entry_id );
	$result     = $wpdb->get_row( $wpdb->prepare( $query ) );
	if ( $result === null ) {
		$result = false;
	} elseif ( $result !== null ) {
		$result = Form_Entry::create_form_entry( $result );
	}
	return $result;
	// $result = Form_Entry::__construct( $result );
}

/**
 * Getting a single entry by user id
 *
 * @param int $user_id ID of the user
 *
 * @return Form_Entry | false
 */

function vrchecke_get_entry_by_user_id( $user_id ) {
	global $wpdb;
	$result     = false;
	$user_id    = gettype( (int) $user_id ) === 'integer' ? absint( $user_id ) : 0;
	$table_name = $wpdb->prefix . 'form_entries';
	$query      = sprintf( 'SELECT * FROM %s WHERE user_id = %d', $table_name, $user_id );
	$result     = $wpdb->get_row( $wpdb->prepare( $query ) );
	if ( $result === null ) {
		$result = false;
	} elseif ( $result !== null ) {
		$result = Form_Entry::create_form_entry( $result );
	}
	return $result;
	// $result = Form_Entry::__construct( $result );
}

/**
 * Populating Query result with our custom class
 */

function vrchecke_get_entries() {
	global $wpdb;
	$results    = false;
	$table_name = $wpdb->prefix . 'form_entries';
	$query      = sprintf( 'SELECT * FROM %s', $table_name );
	$results    = $wpdb->get_results( $query );
	if ( $results === null ) {
		$results = false;
	} elseif ( $results !== null && count( $results ) !== 0 ) {
		$results = array_map( array( Form_Entry::class, 'create_form_entry' ), $results );
	}
	return $results;
	// $result = Form_Entry::__construct( $result );
}

/**
 * Deleting entry
 */

function vrchecke_delete_entry( $entry_id ) {
	global $wpdb;
	$result     = false;
	$entry_id   = gettype( $entry_id ) === 'integer' ? (int) $entry_id : 0;
	$table_name = $wpdb->prefix . 'form_entries';
	$query      = sprintf( 'SELECT user_id FROM %s WHERE id = %d', $table_name, $entry_id );
	$result     = $wpdb->get_row( $query );
	if ( $result === null ) {
		return false;
	} elseif ( $result !== null && get_userdata( $result->user_id ) instanceof \WP_User ) {
		$user_id = gettype( $result->user_id ) === 'string' ? (int) $result->user_id : 0;
		delete_user_meta( $user_id, 'address' );
		delete_user_meta( $user_id, 'city' );
		delete_user_meta( $user_id, 'phone_number' );
		$wpdb->delete( $table_name, array( 'id' => $entry_id ), array( '%d' ) );
		return true;
	} else {
		return false;
	}
}
