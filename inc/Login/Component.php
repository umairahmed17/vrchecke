<?php
/**
 * VRCHECKE\VRCHECKE\Login\Component class
 *
 * @package vrchecke
 */

namespace VRCHECKE\VRCHECKE\Login;

use VRCHECKE\VRCHECKE\Component_Interface;

/**
 * Class for creating login page.
 *
 * @link https://codex.wordpress.org/Theme_Logo
 */
class Component implements Component_Interface {


	/**
	 * Variables
	 */
	private $new_page_title;
	private $new_page_content;
	private $page_check;
	/**
	 * Construct Object
	 */
	public function __construct() {
		 $this->new_page_title   = __( 'Login', 'vrchecke' );
		$this->new_page_content = '';
		$this->page_check       = get_page_by_title( $this->new_page_title );
		$this->page_template    = 'page-login.php';
	}

	/**
	 * Gets the unique identifier for the theme component.
	 *
	 * @return string Component slug.
	 */
	public function get_slug(): string {
		return 'login-page';
	}

	/**
	 * Adds the action and filter hooks to integrate with WordPress.
	 */
	public function initialize() {
		$new_page = array(
			'post_type'    => 'page',
			'post_title'   => $this->new_page_title,
			'post_content' => $this->new_page_content,
			'post_status'  => 'publish',
			'post_author'  => 1,
			'post_name'    => 'login',
		);
		if ( ! isset( $this->page_check->ID ) ) {
			$new_page_id = wp_insert_post( $new_page );
			if ( ! empty( $new_page_template ) ) {
				update_post_meta( $new_page_id, '_wp_page_template', $this->page_template );
			}
		}
		add_shortcode( 'login-page', array( $this, 'page_content' ) );
		add_action( 'init', array( $this, 'redirect_login_page' ) );
		add_action( 'wp_login_failed', array( $this, 'login_failed' ) );
		add_filter( 'authenticate', array( $this, 'verify_username_password' ), 1, 3 );
		add_action( 'wp_logout', array( $this, 'logout_page' ) );
		// add_action( 'wp_head', [$this, 'debugging_theme'] );
	}

	/**
	 * Function to add template to page content
	 */
	private function page_content() {
		get_template_part( 'template-parts/signin/entry', 'signin' );
	}

	/**
	 * Redirecting if wp-login.php is accessed.
	 */
	public function redirect_login_page() {
		 $login_page  = home_url( '/login/' );
		$page_viewed = basename( $_SERVER['REQUEST_URI'] );

		if ( $page_viewed == 'wp-login.php' && $_SERVER['REQUEST_METHOD'] == 'GET' ) {
			wp_redirect( $login_page );
			exit;
		}
	}

	/**
	 * Redirecting after Failed Login
	 */
	public function login_failed() {
		$login_page = home_url( '/login/' );
		wp_redirect( $login_page . '?login=failed' );
		exit;
	}

	/**
	 * Authenticating Username and Password
	 */
	public function verify_username_password( $user, $username, $password ) {
		$login_page = home_url( '/login/' );
		if ( $username == '' ) {
			wp_redirect( $login_page . '?login=empty-username' );
			exit;
		} elseif ( $password == '' ) {
			wp_redirect( $login_page . '?login=empty-password' );
			exit;
		}
		// if ( $user == null ) {
		// TODO what should the error message be? (Or would these even happen?)
		// Only needed if all authentication handlers fail to return anything.
		// wp_redirect( $login_page . '?login=invalid' );
		// exit;
		// }
	}

	/**
	 * Redirect after failed login
	 */
	public function logout_page() {
		 $login_page = home_url( '/login/' );
		wp_redirect( $login_page . '?login=false' );
		exit;
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
