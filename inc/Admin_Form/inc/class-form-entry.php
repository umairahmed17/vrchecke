<?php
/**
 * Form Entry Object
 */
namespace VRCHECKE_Form;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Form_Entry {

	/**
	 * Entry ID.
	 *
	 * @var int
	 */
	protected $id = 0;

	/**
	 * Corresponding user ID number.
	 *
	 * @var int
	 */
	protected $user_id = 0;

	/**
	 * Date of form entry.
	 *
	 * @var string
	 */
	protected $date_registered = '';

	/**
	 * User
	 *
	 * @var WP_User $user
	 */
	protected $user;

	/**
	 * First echeck
	 *
	 * @var boolean
	 */
	protected $first_echeck;

	/**
	 * Number of devices
	 *
	 * @var int
	 */
	protected $number_of_devices;

	/**
	 * Type of devices
	 *
	 * @var int
	 */
	protected $type_of_devices;

	/**
	 * Postal Code
	 *
	 * @var string
	 */
	protected $postal_code;

	/**
	 * First Suggesstion for Comapny
	 *
	 * @var int
	 */
	protected $company_first_option;

	/**
	 * Second Suggesstion for Comapny
	 *
	 * @var int
	 */
	protected $company_second_option;

	/**
	 * Third Suggesstion for Comapny
	 *
	 * @var int
	 */
	protected $company_third_option;

	/**
	 * Chosen Suggesstion for Comapny
	 *
	 * @var int
	 */
	protected $company_selected_option;

	/**
	 * Form_Entry constructor.
	 *
	 * @param object $entry_object Entry object row from the database.
	 * @return void
	 */
	public function __construct( $entry_object = null ) {
		if ( ! is_object( $entry_object ) ) {
			return;
		}

		$this->setup_entry( $entry_object );

	}

	/**
	 * Function to use to create class
	 */
	public static function create_form_entry( $args ) {
		 return new self( $args );
	}

	/**
	 * Setup properties.
	 *
	 * @param object $entry_object Row from the database.
	 *
	 * @access private
	 * @since  3.0
	 * @return bool
	 */
	private function setup_entry( $entry_object ) {
		if ( ! is_object( $entry_object ) ) {
			return false;
		}

		$vars = get_object_vars( $entry_object );

		foreach ( $vars as $key => $value ) {
			switch ( $key ) {
				case 'time':
					$this->date_registered = $value;
			}

			$this->{$key} = $value;
		}
		$this->set_user();
		if ( empty( $this->id ) ) {
			return false;
		}

		return true;

	}

	/**
	 * Setting user object
	 */

	private function set_user() {
		$this->user = new \WP_User( $this->get_user_id() );
	}

	/**
	 * Get the ID of the entry.
	 *
	 * @return int
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Get the ID of the corresponding user account.
	 *
	 * @access public
	 * @since  3.0
	 * @return int
	 */
	public function get_user_id() {
		 return $this->user_id;
	}

	/**
	 * Get the E-Check status.
	 *
	 * @access public
	 * @since  3.0
	 * @return int
	 */
	public function get_echeck_status() {
		return $this->first_echeck;
	}
	/**
	 * Get the Number of devices.
	 *
	 * @access public
	 * @since  3.0
	 * @return int
	 */
	public function get_num_devices() {
		 return $this->number_of_devices;
	}
	/**
	 * Get the Type of devices.
	 *
	 * @access public
	 * @since  3.0
	 * @return string
	 */
	public function get_type_of_devices() {
		 return $this->type_of_devices;
	}
	/**
	 * Get the Postal Code.
	 *
	 * @access public
	 * @since  3.0
	 * @return string
	 */
	public function get_postal_code() {
		 return $this->postal_code;
	}
	/**
	 * Get the First Suggesstion.
	 *
	 * @access public
	 * @since  3.0
	 * @return int
	 */
	public function get_first_option() {
		return $this->company_first_option;
	}
	/**
	 * Get the Second Suggesstion.
	 *
	 * @access public
	 * @since  3.0
	 * @return int
	 */
	public function get_second_option() {
		return $this->company_second_option;
	}
	/**
	 * Get the Third Suggesstion.
	 *
	 * @access public
	 * @since  3.0
	 * @return int
	 */
	public function get_third_option() {
		return $this->company_third_option;
	}
	/**
	 * Get the Selected Suggesstion.
	 *
	 * @access public
	 * @since  3.0
	 * @return int
	 */
	public function get_selected_option() {
		 return $this->company_selected_option;
	}

	/**
	 * Returns the user object.
	 *
	 * @return WP_User
	 */
	public function get_user() {
		if ( ! is_object( $this->user ) ) {
			$this->user = new \WP_User( $this->get_user_id() );
		}

		return $this->user;

	}

	/**
	 * Returns the date the entry submitted.
	 *
	 * @param bool $formatted Whether or not to format the returned date.
	 * @return string
	 */
	public function get_date_registered( $formatted = true ) {
		$date_registered = $this->date_registered;

		if ( $formatted && ! empty( $date_registered ) ) {
			$date_registered = date_i18n( get_option( 'date_format' ), strtotime( $date_registered, current_time( 'timestamp' ) ) );
		}

		return $date_registered;

	}

}
