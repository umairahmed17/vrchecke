<?php
/**
 * Form Entry List Table
 */

namespace VRCHECKE\VRCHECKE\Admin_Form;

include_once __DIR__ . '/class-form-entry.php';

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

// Load WP_List_Table if not loaded
if ( !class_exists( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}
/**
 * Class Entries_Table
 */
class Entries_Table extends \WP_List_Table
{

    /**
     * Number of results to show per page.
     *
     * @var   int
     */
    public $per_page = 30;

    /**
     * Counts.
     *
     * @var   array
     */
    public $counts = array(
        'total' => 0,
    );

    /**
     * Constructor.
     *
     * @see   WP_List_Table::__construct()
     */

    public function __construct()
    {
        parent::__construct(
            array(
                'singular' => 'Entry',
                'plural'   => 'Entries',
                'ajax'     => false,
            )
        );

        $this->process_bulk_action();
        $this->get_counts();
    }

    /**
     * Get the base URL for the customers list table.
     *
     * @return string Base URL.
     */
    public function get_base_url()
    {
        return $this->get_entries_admin_page();
    }

    /**
     * Retrieve the table columns.
     *
     * @return array
     */
    public function get_columns()
    {
        $columns = array(
            'cb'              => '<input type="checkbox" />',
            'name'            => __( 'Name', 'vrchecke' ),
            'email'           => __( 'Email', 'vrchecke' ),
            'e_check'         => __( 'E-Check', 'vrchecke' ),
            'num_devices'     => __( 'Number of Devices', 'vrchecke' ),
            'postal_code'     => __( 'Postal Code', 'vrchecke' ),
            'date_registered' => __( 'Date Registered', 'vrchecke' ),
        );

        $columns = apply_filters( 'vrchecke_list_table_columns', $columns );

        return $columns;
    }

    /**
     * Retrieve the sortable columns.
     *
     * @return array
     */
    public function get_sortable_columns()
    {
        return array(
            'date_registered' => array( 'date_registered', false ),
        );
    }

    /**
     * Gets the name of the primary column.
     *
     * @return string
     */
    protected function get_primary_column_name()
    {
        return 'name';
    }

    /**
     * Render the checkbox column.
     *
     * @return string
     */
    public function column_cb( $entry )
    {
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            'entry_id',
            $entry->get_id()
        );
    }

    /**
     * Render the "Name" column.
     *
     * @param \Form_Entry $entry
     *
     * @return string
     */
    public function column_name( $entry )
    {
        $entry_id       = $entry->get_id();
        $user_id        = $entry->get_user_id();
        $user           = get_userdata( $user_id );
        $display_name   = !empty( $user->display_name ) ? $user->display_name : $user->user_login;
        $edit_entry_url = $this->get_entries_admin_page(
            array(
                'view'     => 'edit',
                'entry_id' => $entry_id,
            )
        );
        $delete_entry_url = wp_nonce_url(
            $this->get_entries_admin_page(
                array(
                    'vrchecke-action' => 'delete_entry',
                    'entry_id'        => $entry_id,
                )
            ),
            'vrchecke_delete_entry_nonce'
        );

        $actions = array(
            'edit_entry'   => '<a href="' . esc_url( $edit_entry_url ) . '">' . __( 'Edit entry', 'vrchecke' ) . '</a>',
            'delete_entry' => '<span class="trash"><a href="' . esc_url( $delete_entry_url ) . '" class="vrchecke-delete-entry">' . __( 'Delete', 'vrchecke' ) . '</a></span>',
            'entry_id'     => '<span class="vrchecke-id-col">' . sprintf( __( 'ID: %d', 'vrchecke' ), $entry_id ) . '</span>',
        );

        ob_start();

        /**
         * Filters the row actions.
         *
         * @param array         $actions    Default actions.
         * @param \Form_Entry $entry.
         */
        $actions = apply_filters( 'rcp_entrys_list_table_row_actions', $actions, $entry );

        return '<strong><a class="row-title" href="' . esc_url( $edit_entry_url ) . '">' . esc_html( $display_name ) . '</a></strong>' . $this->row_actions( $actions );

    }

    /**
     * Render the "Email" column.
     *
     * @param \Form_Entry $entry
     *
     * @return string
     */
    public function column_email( $entry )
    {
        $user = get_userdata( $entry->get_user_id() );

        return esc_html( $user->user_email );

    }

    /**
     * Render the "Date Registered" column.
     *
     * @param \Form_Entry $entry
     *
     * @return string
     */
    public function column_date_registered( $entry )
    {
        return $entry->get_date_registered();
    }

    /**
     * Render the "E-check" column.
     *
     * @param \Form_Entry $entry
     *
     * @return string
     */
    public function column_e_check( $entry )
    {
        $check = $entry->get_echeck_status() ? 'true' : 'false';

        return (string) $check;
    }

    /**
     * Render the "Number of Devices" column.
     *
     * @param \Form_Entry $entry
     *
     * @return string
     */
    public function column_num_devices( $entry )
    {
        $num_devices = $entry->get_num_devices();

        return $num_devices;
    }
    /**
     * Render the "Postal Code" column.
     *
     * @param \Form_Entry $entry
     *
     * @return string
     */
    public function column_postal_code( $entry )
    {
        $postal_code = $entry->get_postal_code();

        return $postal_code;
    }

    /**
     * Message to be displayed when there are no entries.
     *
     * @return void
     */
    public function no_items()
    {
        esc_html_e( 'No entries found.', 'rcp' );
    }

    /**
     * Retrieve the bulk actions.
     *
     * @return array
     */
    public function get_bulk_actions()
    {
        return array(
            'delete' => __( 'Permanently Delete', 'vrchecke' ),
        );
    }

    /**
     * Process bulk actions.
     *
     * @return void
     */
    public function process_bulk_action()
    {
        // Bail if a nonce was not supplied.
        if ( !isset( $_REQUEST['_wpnonce'] ) ) {
            return;
        }

        if ( !wp_verify_nonce( $_REQUEST['_wpnonce'], 'bulk-entries' ) ) {
            return;
        }

        $ids = wp_parse_id_list( (array) $this->get_request_var( 'entry_id', false ) );

        // Bail if no IDs
        if ( empty( $ids ) ) {
            return;
        }

        $current_user = wp_get_current_user();

        foreach ( $ids as $id ) {
            $entry = vrchecke_get_single_entry( absint( $id ) );

            if ( empty( $entry ) ) {
                continue;
            }

            switch ( $this->current_action() ) {
                case 'delete':
                    vrchecke_delete_entry( $entry->get_id() );
                    break;
            }
        }

        $this->show_admin_notice( $this->current_action() );

    }

    /**
     * Show admin notice for bulk actions.
     *
     * @param string $action The action to show the notice for.
     *
     * @access private
     * @return void
     */
    private function show_admin_notice( $action )
    {
        $message = '';

        switch ( $action ) {
            case 'delete':
                $message = __( 'Customer(s) deleted.', 'rcp' );
                break;
        }

        if ( empty( $message ) ) {
            return;
        }

        echo '<div class="updated"><p>' . $message . '</p></div>';

    }

    /**
     * Retrieve the customer counts.
     *
     * @return void
     */
    public function get_counts()
    {
        // $this->counts = array(
        // 'total'   => rcp_count_customers(),
        // 'pending' => rcp_count_customers( array(
        // 'email_verification' => 'pending'
        // ) )
        // );
    }

    /**
     * Retrieve customers data.
     *
     * @param bool $count Whether or not to get customer objects (false) or just count the total number (true).
     *
     * @return array|int
     */
    public function entries_data( $count = false )
    {
        $args = array(
            'number'  => $this->per_page,
            'offset'  => $this->get_offset(),
            'orderby' => sanitize_text_field( $this->get_request_var( 'orderby', 'id' ) ),
            'order'   => sanitize_text_field( $this->get_request_var( 'order', 'DESC' ) ),
        );

        $search = $this->get_search();

        if ( !empty( $search ) ) {
            /*
             * Search by user account
             * This process sucks because our query class doesn't do joins.
             * @todo first name and last name
             */

            // First we have to search for user accounts.
            $user_ids = get_users(
                array(
                    'number' => -1,
                    'search' => '*' . $this->get_search() . '*',
                    'fields' => 'ids',
                )
            );

            // No user results - bail.
            if ( empty( $user_ids ) ) {
                return $count ? 0 : array();
            }

            // Finally, include these user IDs in the entries query.
            $args['user_id__in'] = $user_ids;

        }

        if ( $count ) {
            return vrchecke_get_entries();
        }

        return vrchecke_get_entries();
    }

    /**
     * Setup the final data for the table.
     *
     * @return void
     */
    public function prepare_items()
    {
        $columns  = $this->get_columns();
        $hidden   = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array( $columns, $hidden, $sortable );
        $this->items           = $this->entries_data();

        $total = count( $this->entries_data( true ) );

        // Setup pagination
        $this->set_pagination_args(
            array(
                'total_items' => $total,
                'per_page'    => $this->per_page,
                'total_pages' => ceil( $total / $this->per_page ),
            )
        );
    }

    /**
     * Returns the URL to the form entry page.
     *
     * @param array $args Query args to add.
     */
    public static function get_entries_admin_page( $args = array() )
    {
        $args = wp_parse_args(
            $args,
            array(
                'page' => 'vrcheck_entry',
            )
        );

        $entry_page = add_query_arg( $args, admin_url( 'admin.php' ) );

        return $entry_page;

    }

    /**
     * Retrieve the current page number.
     *
     * @return int Current page number.
     */
    protected function get_search()
    {
        return rawurldecode( trim( $this->get_request_var( 's', '' ) ) );
    }

    /**
     * Get a request var, or return the default if not set.
     *
     * @param string $var
     * @param mixed  $default
     *
     * @return mixed Un-sanitized request var
     */
    public function get_request_var( $var = '', $default = false )
    {
        return isset( $_REQUEST[$var] )
            ? $_REQUEST[$var]
            : $default;
    }

    /**
     * Set number of results per page
     *
     * This uses the screen options setting if available. Otherwise it defaults to 30.
     *
     * @since  3.0.3
     * @access protected
     */
    protected function set_per_page()
    {
        $per_page      = 30;
        $user_id       = get_current_user_id();
        $screen        = get_current_screen();
        $screen_option = $screen->get_option( 'per_page', 'option' );

        if ( !empty( $screen_option ) ) {
            $per_page = get_user_meta( $user_id, $screen_option, true );

            if ( empty( $per_page ) || $per_page < 1 ) {
                $per_page = $screen->get_option( 'per_page', 'default' );
            }
        }

        $this->per_page = $per_page;

    }

    /**
     * Show the search field.
     *
     * @param string $text     Label for the search box
     * @param string $input_id ID of the search box
     *
     * @since 3.0
     */
    public function search_box( $text, $input_id )
    {
        // Bail if no items and no search
        if ( !$this->get_search() && !$this->has_items() ) {
            return;
        }

        $orderby  = $this->get_request_var( 'orderby' );
        $order    = $this->get_request_var( 'order' );
        $input_id = $input_id . '-search-input';

        if ( !empty( $orderby ) ) {
            echo '<input type="hidden" name="orderby" value="' . esc_attr( $orderby ) . '" />';
        }

        if ( !empty( $order ) ) {
            echo '<input type="hidden" name="order" value="' . esc_attr( $order ) . '" />';
        }

        ?>

<p class="search-box">
    <label class="screen-reader-text"
        for="<?php echo esc_attr( $input_id ); ?>"><?php echo esc_html( $text ); ?>:</label>
    <input type="search" id="<?php echo esc_attr( $input_id ); ?>" name="s" value="<?php _admin_search_query();?>" />
    <?php submit_button( esc_html( $text ), 'button', false, false, array( 'ID' => 'search-submit' ) );?>
</p>

<?php
}

}