<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       awais300@gmail.com
 * @since      1.0.0
 *
 * @package    Spam_Blocker
 * @subpackage Spam_Blocker/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Spam_Blocker
 * @subpackage Spam_Blocker/public
 * @author     Awais <awais300@gmail.com>
 */
class Spam_Blocker_Public {


	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Spam_Blocker_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Spam_Blocker_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/spam-blocker-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Spam_Blocker_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Spam_Blocker_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/spam-blocker-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add honeypot for UM
	 * @since    2.0.0
	 */
	public function plugins_loaded() {
		$options = get_option( HONEYPOT_WP_OPTIONS );
		if ( $options['honeypot-um-register'] == true && class_exists('UM_Functions') ) {
			add_action( 'um_after_form', [$this, 'honeypot_form_field'], 10 );
			add_action( 'um_before_new_user_register',[$this, 'honeypot_um_before_new_user_register'], 10, 1);
		}
	}

	/**
	 * Styles for comment section.
	 *
	 * @since    2.0.0
	 */
	public function honeypot_field_style() {
		if( ! SPAM_DEBUG ) {
			echo '<style>
       			.required-awp{
       				display:none !important;
       			}
       		</style>';

		}
	}

	/**
	 * Add shortcode to add honeypot on any form
	 * @since    2.0.0
	 */
	public function wp_init(){
		add_shortcode( 'simple-spam-blocker', [$this, 'spam_blocker_shortcode'] );
		$this->check_honeypot();
	}

	/**
	 * Shortcode callback to add honeypot field
	 * @since    2.0.0
	 * @param  Array $atts
	 * @param  String $content
	 * @return String
	 */
	public function spam_blocker_shortcode($atts, $content = null)
	{
  		ob_start();
  		echo $this->get_honeypot_form_field(true);
  		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}

	/**
	 * Check if honeypot field is set and thorws error
	 * @since    2.0.0
	 */
	public function check_honeypot()
	{
		$field = '';
		if ( isset( $_REQUEST[HONEYPOT_FIELD_NAME] ) && 
			isset( $_REQUEST['awp-form-shortcode'] ) &&
			$_REQUEST['awp-form-shortcode'] == 'shortcode'
		) {
			$field = sanitize_text_field( wp_unslash( $_REQUEST[HONEYPOT_FIELD_NAME] ) );
		}

		if ( strlen( $field ) > 0 ) {
			wp_die( esc_html__( 'ERROR: Spam detected', 'spam-blocker' ) );
		}
	}

	/**
	 * Add input field (Honeypot)
	 * @since    2.0.0
	 */
	public function honeypot_form_field() {
		echo $this->get_honeypot_form_field();
	}

	/**
	 * Check honeypot field at login page
	 * @since    2.0.0
	 * 
	 * @param  string $user
	 * @param  string $password
	 * @return $user | WP_Error
	 */
	public function honeypot_wp_authenticate_user( $user, $password ) {
		$field = '';
		if ( isset( $_POST[HONEYPOT_FIELD_NAME] ) ) {
			$field = sanitize_text_field( wp_unslash( $_POST[HONEYPOT_FIELD_NAME] ) );
		}

		if ( strlen( $field ) > 0 ) {
			wp_die( esc_html__( 'ERROR: Spam detected', 'spam-blocker' ) );
			//return new WP_Error( 'awp-honeypot', esc_html__( 'ERROR: Spammer detected', 'spam-blocker' ) );
		} else {
			return $user;
		}
	}


	/**
	 * Check spam for WP comments
	 * @since    2.0.0
	 * @param  Array $commentdata
	 * @return $commentdata| wp_die
	 */
	public function honeypot_preprocess_comment( $commentdata ) {
		$field = '';
		if ( isset( $_POST[HONEYPOT_FIELD_NAME] ) ) {
			$field = sanitize_text_field( wp_unslash( $_POST[HONEYPOT_FIELD_NAME] ) );
		}

		if ( strlen( $field ) > 0 ) {
			wp_die( esc_html__( 'ERROR: Spam detected', 'spam-blocker' ) );
		} else {
			return $commentdata;
		}

	}

	/**
	 * Check spam for WP registrations
	 * @since    2.0.0
	 * @param  Object $errors
	 * @param  String $sanitized_user_login
	 * @param  String $user_email
	 * @return $errors | wp_die
	 */
	public function honeypot_registration_errors( $errors, $sanitized_user_login, $user_email ) {
		$field = '';
		if ( isset( $_POST[HONEYPOT_FIELD_NAME] ) ) {
			$field = sanitize_text_field( wp_unslash( $_POST[HONEYPOT_FIELD_NAME] ) );
		}

		if ( strlen( $field ) > 0 ) {
			wp_die( esc_html__( 'ERROR: Spam detected', 'spam-blocker' ) );
		} else {
			return $errors;
		}
	}

	/**
	 * Check spam for WP registrations
	 * @since    2.0.0
	 * @param  Array $args
	 * @return $errors | wp_die
	 */
	public function honeypot_um_before_new_user_register( $args ) {
		$field = '';
		if ( isset( $_POST[HONEYPOT_FIELD_NAME] ) ) {
			$field = sanitize_text_field( wp_unslash( $_POST[HONEYPOT_FIELD_NAME] ) );
		}

		if ( strlen( $field ) > 0 ) {
			wp_die( esc_html__( 'ERROR: Spam detected', 'spam-blocker' ) );
		}
	}

	/**
	 * Get input field (Honeypot)
	 * @since    2.0.0
	 */
	public function get_honeypot_form_field($shortcode = false) {
		$field = '';
		if( $shortcode === false ) {
			$field = '<div class="required-awp">
				<input autocomplete="off" name="'. HONEYPOT_FIELD_NAME. '" type="text" value=""/>
			</div>';
		} else if($shortcode === true){
			$field = '<div class="required-awp">
				<input autocomplete="off" name="'. HONEYPOT_FIELD_NAME. '" type="text" value=""/>
				<input type="hidden" name="awp-form-shortcode" value="shortcode" />
			</div>';

		} else {
			$field = '<div class="required-awp">
				<input autocomplete="off" name="'. HONEYPOT_FIELD_NAME. '" type="text" value=""/>
			</div>';
		}
			
		return $field;
	}
}
