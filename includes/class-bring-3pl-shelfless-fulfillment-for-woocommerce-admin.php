<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.bring.no/
 * @since      1.0.0
 *
 * @package    Bring_3pl_Shelfless_Fulfillment_For_Woocommerce
 * @subpackage Bring_3pl_Shelfless_Fulfillment_For_Woocommerce/includes
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the admin-specific methods and variables.
 *
 * @package    Bring_3pl_Shelfless_Fulfillment_For_Woocommerce
 * @subpackage Bring_3pl_Shelfless_Fulfillment_For_Woocommerce/includes
 * @author     Tiqqe <bring_team@tiqqe.com>
 */
class Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		$screen = get_current_screen();

		$plugin_pages = array( 
			'toplevel_page_bring_3pl_shelfless_fulfillment_for_woocommerce', 
			'toplevel_page_bring_3pl_shelfless_fulfillment_for_woocommerce_setup',
			'shelfless-by-bring_page_bring_3pl_shelfless_fulfillment_for_woocommerce_inventory_settings', 
			'shelfless-by-bring_page_bring_3pl_shelfless_fulfillment_for_woocommerce_order_settings', 
			'shelfless-by-bring_page_bring_3pl_shelfless_fulfillment_for_woocommerce_shipping_settings', 
			'shelfless-by-bring_page_bring_3pl_shelfless_fulfillment_for_woocommerce_general_settings', 
			'shelfless-by-bring_page_bring_3pl_shelfless_fulfillment_for_woocommerce_help', 
		);

		if ( ! is_admin() || (is_admin() && ! current_user_can( 'manage_options' ) ) )
			return false;
		
		if ( in_array( $screen->id, $plugin_pages ) ) {
			wp_enqueue_style( $this->plugin_name . '-bootstrap-4-6-css', plugin_dir_url( dirname( __FILE__ ) ) . 'admin/css/bootstrap/bootstrap.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( dirname( __FILE__ ) ) . 'admin/css/bring-3pl-shelfless-fulfillment-for-woocommerce-admin.css', array( $this->plugin_name . '-bootstrap-4-6-css' ), $this->version, 'all' );
		} else {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( dirname( __FILE__ ) ) . 'admin/css/bring-3pl-shelfless-fulfillment-for-woocommerce-admin.css', array(), $this->version, 'all' );
		}
		
		wp_enqueue_style( $this->plugin_name . '-jquery-datatables-1.11.2-css', plugin_dir_url( dirname( __FILE__ ) ) . 'admin/assets/vendor/datatables/datatables-1.11.2/css/jquery.dataTables.min.css', array(), $this->version, 'all' );

		wp_enqueue_style( $this->plugin_name . '-select2-css', plugin_dir_url( dirname( __FILE__ ) ) . 'admin/assets/vendor/select2/css/select2.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		if ( ! is_admin() || (is_admin() && ! current_user_can( 'manage_options' ) ) )
			return false;

		wp_enqueue_script( $this->plugin_name . '-bootstrap-4-6-js', plugin_dir_url( dirname( __FILE__ ) ) . 'admin/js/bootstrap/bootstrap.bundle.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( dirname( __FILE__ ) ) . 'admin/js/bring-3pl-shelfless-fulfillment-for-woocommerce-admin.js', array( 'jquery', 'jquery-ui-core' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name . '-datatables-js', plugin_dir_url( dirname( __FILE__ ) ) . 'admin/assets/vendor/datatables/datatables.min.js', array( 'jquery', 'jquery-ui-core' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name . '-jquery-datatables-js', plugin_dir_url( dirname( __FILE__ ) ) . 'admin/assets/vendor/datatables/datatables-1.11.2/js/dataTables.dataTables.min.js', array( 'jquery', 'jquery-ui-core' ), $this->version, false );

		wp_enqueue_script( $this->plugin_name . '-select2-js', plugin_dir_url( dirname( __FILE__ ) ) . 'admin/assets/vendor/select2/js/select2.min.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register Shelfless menus to the admin area.
	 *
	 * @since    1.0.0
	 */
	public function shelfless_admin_settings_page() {

		$admin_page = add_menu_page(
			esc_html__( 'Shelfless by Bring', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
			esc_html__( 'Shelfless by Bring', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
			'manage_options',
			$this->plugin_name,
			null,
			'dashicons-cart',
			58
		);

		$inventory_page = add_submenu_page(
			$this->plugin_name,
			esc_html__( 'Products & Stocks', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
			esc_html__( 'Products & Stocks', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
			'manage_options',
			$this->plugin_name, 
			array( $this, 'shelfless_inventory_settings_page' ),
		);

		$order_page = add_submenu_page(
			$this->plugin_name,
			esc_html__( 'Orders', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
			esc_html__( 'Orders', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
			'manage_options',
			$this->plugin_name. '_order_settings', 
			array( $this, 'shelfless_order_settings_page' ),
		);

		$shiping_page = add_submenu_page(
			$this->plugin_name,
			esc_html__( 'Shipping', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
			esc_html__( 'Shipping', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
			'manage_options',
			$this->plugin_name. '_shipping_settings', 
			array( $this, 'shelfless_shipping_settings_page' ),
		);

		$general_page = add_submenu_page(
			$this->plugin_name,
			esc_html__( 'General Settings', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
			esc_html__( 'General', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
			'manage_options',
			$this->plugin_name. '_general_settings', 
			array( $this, 'shelfless_api_settings_page' ),
		);

		$help_page = add_submenu_page(
			$this->plugin_name,
			esc_html__( 'Help', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
			esc_html__( 'Help', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
			'manage_options',
			$this->plugin_name. '_help', 
			array( $this, 'shelfless_help_page' ),
		);

		add_action( 'load-' . $admin_page, array( $this, 'shelfless_admin_settings_page_filter_body_classes' ) );
		add_action( 'load-' . $general_page, array( $this, 'shelfless_admin_settings_page_filter_body_classes' ) );
		add_action( 'load-' . $inventory_page, array( $this, 'shelfless_admin_settings_page_filter_body_classes' ) );
		add_action( 'load-' . $order_page, array( $this, 'shelfless_admin_settings_page_filter_body_classes' ) );
		add_action( 'load-' . $shiping_page, array( $this, 'shelfless_admin_settings_page_filter_body_classes' ) );
		add_action( 'load-' . $help_page, array( $this, 'shelfless_admin_settings_page_filter_body_classes' ) );

		// Check if there are stocks marked as to be fulfilled by Bring.
		$this->shelfless_check_marked_products();

		// Check if there are products that are out of stocks.
		$this->shelfless_outofstock_products();

		// Check for partially fulfilled orders by Bring.
		// $this->shelfless_check_for_partially_fullfilled_orders();

		// Check for partially fulfilled orders by Bring.
		$this->shelfless_check_for_cancelled_orders();

	}

	/**
	 * Calls Shelfless API settings page.
	 *
	 * @since    1.0.0
	 */
	public function shelfless_api_settings_page() {

		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/views/bring-3pl-admin-shelfless-api-settings.php';
		
	}

	/**
	 * Calls Shelfless inventory settings page.
	 *
	 * @since    1.0.0
	 */
	public function shelfless_inventory_settings_page() {

		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/views/bring-3pl-admin-inventory-settings.php';

	}

	/**
	 * Calls Shelfless order settings page.
	 *
	 * @since    1.0.0
	 */
	public function shelfless_order_settings_page() {

		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/views/bring-3pl-admin-order-settings.php';

	}

	/**
	 * Calls Shelfless reports page.
	 *
	 * @since    1.0.0
	 */
	public function shelfless_reports_page() {

		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/views/bring-3pl-admin-reports.php';

	}

	/**
	 * Calls Shelfless help page.
	 *
	 * @since    1.0.0
	 */
	public function shelfless_help_page() {

		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/views/bring-3pl-admin-help.php';

	}

	/**
	 * Generates Shelfless API settings sections.
	 *
	 * @since    1.0.0
	 */
	public function shelfless_api_settings_sections() {

		add_settings_section( $this->plugin_name . '_api_settings_mybring', '', array( $this, 'shelfless_api_settings_section_content' ), $this->plugin_name );

	}

	/**
	 * Generates Shelfless API settings section content.
	 *
	 * @since    1.0.0
	 */
	public function shelfless_api_settings_section_content() {

		generate_bring_shelfless_api_section_content();

	}

	/**
	 * Generates Shelfless API field registrations.
	 *
	 * @since    1.0.0
	 */
	public function shelfless_api_settings_section_fields() {

		$fields = array(
			// At this time, this setting is not used because the API key will determine the correct customer ID. This remains here until fully decided to be removed.
			array(
				'fid' 			=> $this->plugin_name . '_mybring_customer_id',
				'label' 		=> esc_html__( 'Mybring Customer ID or Dream Logistics Partner ID', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'section' 		=> $this->plugin_name . '_api_settings_mybring',
				'type' 			=> 'text',
				'options' 		=> false,
				'placeholder' 	=> esc_html__( 'Customer ID', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'tooltip' 		=> esc_html__( 'Enter your Mybring Customer ID or Dream Logistics Partner ID. If you don\'t know your ID - please contact your contact person at Shelfless or Dream.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'supplemental' 	=> esc_html__( 'You may get your Mybring Customer ID or Dream Logistics Partner ID from Bring.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'default' 		=> '',
				'label_for'		=> $this->plugin_name . '_mybring_customer_id',
			),
			array(
				'fid' 			=> $this->plugin_name . '_shelfless_api_key',
				'label' 		=> esc_html__( 'Shelfless API Key', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'section' 		=> $this->plugin_name . '_api_settings_mybring',
				'type' 			=> 'text',
				'options' 		=> false,
				'placeholder' 	=> esc_html__( 'API Key', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'tooltip' 		=> esc_html__( 'Enter your Shelfless API Key. If you don\'t know your Shelfless API Key - please contact your Shelfless operational KAM.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'supplemental' 	=> esc_html__( 'You may get your Shelfless API Key from Bring.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'default' 		=> '',
				'label_for'		=> $this->plugin_name . '_shelfless_api_key',
			),
			array(
				'fid' 			=> $this->plugin_name . '_shelfless_api_secret_key',
				'label' 		=> esc_html__( 'Shelfless API Secret Key', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'section' 		=> $this->plugin_name . '_api_settings_mybring',
				'type' 			=> 'password',
				'options' 		=> false,
				'placeholder' 	=> esc_html__( 'Secret Key', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'tooltip' 		=> esc_html__( 'Enter your Shelfless API Secret Key. If you don\'t know your Shelfless API Secret Key - please contact your Shelfless operational KAM.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'supplemental' 	=> esc_html__( 'You may get your Shelfless API Secret Key from Bring.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'default' 		=> '',
				'label_for'		=> $this->plugin_name . '_shelfless_api_secret_key',
			),
			array(
				'fid' 			=> $this->plugin_name . '_shelfless_api_mode',
				'label' 		=> esc_html__( 'Mode', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'section' 		=> $this->plugin_name . '_api_settings_mybring',
				'type' 			=> 'select',
				'options' 		=> array(
					'live' 		=> esc_html__( 'Live', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
					'sandbox'	=> esc_html__( 'Staging', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
					'dev'		=> esc_html__( 'Development', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				),
				'tooltip' 		=> esc_html__( 'Choose which environment to use. Live is for Live Store. Staging is for testing. Development if you are a developer.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'supplemental' 	=> esc_html__( 'Choose which environment to use. Live is for Live Store. Staging is for testing. Development if you are a developer.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'default' 		=> 'sandbox',
				'label_for'		=> $this->plugin_name . '_shelfless_api_mode',
			),
			array(
				'fid' 			=> $this->plugin_name . '_shelfless_debug_mode',
				'label' 		=> esc_html__( 'Enable logging', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'section' 		=> $this->plugin_name . '_api_settings_mybring',
				'type' 			=> 'select',
				'options' 		=> array(
					'0' 	=> esc_html__( 'No', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
					'1' 	=> esc_html__( 'Yes', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				),
				'tooltip' 		=> esc_html__( 'Enabling logging allows Shelfless to write log files for debugging and tracing purposes. Logs will be shown on the WooCommerce <b>Status</b> page under the <b>Log</b> tab. This is recommended to be set to Yes.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'ttip_w_html'	=> true,
				'supplemental' 	=> esc_html__( 'Enabling logging allows Shelfless to write log files for debugging and tracing purposes. Logs will be shown on the WooCommerce <b>Status</b> page under the <b>Log</b> tab. This is recommended to be set to Yes.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'default' 		=> '0',
				'label_for'		=> $this->plugin_name . '_shelfless_api_version',
				'field_text'	=> __( 'Check the <a href="/wp-admin/admin.php?page=wc-status&tab=logs" target="_blank">WooCommerce Logs</a> to see the recent logs written by Shelfless.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
			),
		);

		$this->shelfless_register_fields( $fields, $this->plugin_name );

	}

	/**
	 * Generates Shelfless API settings diagnostic sections.
	 *
	 * @since    1.0.0
	 */
	public function shelfless_api_settings_diagnostic_sections() {

		add_settings_section( $this->plugin_name . '_api_settings_diagnostics_mybring', '', array( $this, 'shelfless_api_settings_diagnostic_section_content' ), $this->plugin_name . '_api_settings_diagnostics_mybring' );
		
	}

	/**
	 * Generates Shelfless API settings diagnostic section content.
	 *
	 * @since    1.0.0
	 */
	public function shelfless_api_settings_diagnostic_section_content() {

		generate_bring_shelfless_api_diagnostic_section_content();

	}

	/**
	 * AJAX handler for wp_ajax_bring_shelfless_api_settings_diagnostics hook.
	 *
	 * @since    1.0.0
	 */
	public function shelfless_api_settings_diagnostics() {

		header( 'Content-Type: application/json' );

		if ( ! wp_verify_nonce( $_REQUEST['mybring_shelfless_nonce'], 'nonce_' . $this->plugin_name . '_api_settings_diagnostics_mybring' ) 
			|| ( wp_verify_nonce( $_REQUEST['mybring_shelfless_nonce'], 'nonce_' . $this->plugin_name . '_api_settings_diagnostics_mybring' )
			&& $_REQUEST['action'] !== 'bring_shelfless_api_settings_diagnostics' ) ) {
				wp_die( json_encode( array( 'error' => true ) ) );
		}

		$conn = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Http();
		$response = $conn->verification_test();

		wp_die( $response );

	}

	/**
	 * AJAX handler for wp_ajax_bring_shelfless_api_settings_diagnostics_delete_transients hook.
	 *
	 * @since    1.0.0
	 */
	public function shelfless_api_settings_diagnostics_delete_transients() { 

		header( 'Content-Type: application/json' );

		if ( ! wp_verify_nonce( $_REQUEST['mybring_shelfless_nonce'], 'nonce_' . $this->plugin_name . '_api_settings_diagnostics_delete_transient_mybring' ) 
			|| ( wp_verify_nonce( $_REQUEST['mybring_shelfless_nonce'], 'nonce_' . $this->plugin_name . '_api_settings_diagnostics_delete_transient_mybring' )
			&& $_REQUEST['action'] !== 'bring_shelfless_api_settings_diagnostics_delete_transients' ) ) {
				wp_die( json_encode( array( 'error' => true ) ) );
		}

		global $wpdb;

		$transient_wildcard = '_transient_bring_3pl_shelfless_fulfillment_for_woocommerce';

		$sql = "SELECT `option_name` AS `name`, `option_value` AS `value` FROM {$wpdb->options} WHERE `option_name` LIKE '{$transient_wildcard}%' ORDER BY `option_name`";

		$results = $wpdb->get_results( $sql );
		$transients = array();

		if ( !empty( $results ) ) {
			$transients['error'] = false;
			foreach( $results as $res_key => $res ) { 
				$transient = str_replace($transient_wildcard, '', $res->name);
				$transients['data'][$res_key]['name'] = $transient;
				delete_transient( $this->plugin_name . $transient );
				// delete_transient( $res->name );
			}
		} else {
			$transients['error'] = true;
		}
		
		wp_die( json_encode( $transients ) );

	}

	/**
	 * AJAX handler for wp_ajax_bring_shelfless_wizard_save_api_creds hook.
	 *
	 * @since    1.0.0
	 */
	public function shelfless_wizard_save_api_creds() {

		header( 'Content-Type: application/json' );

		if ( ! wp_verify_nonce( $_REQUEST['mybring_shelfless_nonce'], 'nonce_' . $this->plugin_name . '_api_creds_wizard_mybring' ) 
			|| ( wp_verify_nonce( $_REQUEST['mybring_shelfless_nonce'], 'nonce_' . $this->plugin_name . '_api_creds_wizard_mybring' )
			&& $_REQUEST['action'] !== 'bring_shelfless_wizard_save_api_creds' ) ) {
				wp_die( json_encode( array( 'error' => true ) ) );
		}

		$post = wc_clean( $_POST );

		foreach ( $post as $key => $value ) {
			if ( preg_match( '/^shelfless_/', $key ) ) {
				$temp_key = $key;
				if ( $temp_key === 'shelfless_mybring_customer_id' ) {
					$temp_key = preg_replace( '/^shelfless_/', '', $temp_key );
				}
				$post[$this->plugin_name . '_' . $temp_key] = validate_bring_shelfless_text_field( sanitize_text_field( $value ) );
				unset( $post[$key] );
			}
			else {
				unset( $post[$key] );
			}
		}

		foreach ( $post as $key => $value ) {
			update_option( $key, $value, 'yes' );
		}

		wp_die( json_encode( array( 'error' => false, 'data' => $post ) ) );

	}

	/**
	 * Generates Shelfless Product Inventory settings section content.
	 *
	 * @since    1.0.0
	 */
	public function shelfless_inventory_settings_section_content() {

		generate_bring_shelfless_inventory_settings_section_content();

	}
	
	/**
	 * Generates Shelfless Product Inventory settings section.
	 *
	 * @since    1.0.0
	 */
	public function shelfless_inventory_settings_sections() {

		add_settings_section( $this->plugin_name . '_inventory_settings', '', array( $this, 'shelfless_inventory_settings_section_content' ), $this->plugin_name . '_inventory_settings' );

	}

	/**
	 * Generates Shelfless Product Inventory Settings field registrations.
	 *
	 * @since    1.0.0
	 */
	public function shelfless_inventory_settings_section_fields() { 

		// This part identifies to hide some fields during setup wizard
		// then add class attribute - "hidden" to the field setting
		$wizard = false;
		if ( get_option( $this->plugin_name . '_shelfless_api_setup_is_complete' ) == 0 ) {
			$wizard = true;
		}
	
		$fields = array(
			array(
				'fid' 			=> $this->plugin_name . '_inventory_is_manage_stock',
				'label' 		=> esc_html__( 'Manage stock', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'section' 		=> $this->plugin_name . '_inventory_settings',
				'type' 			=> 'checkbox',
				'options' 		=> array(
					array(
						'text'		=> esc_html__( 'Synchronize available stock from Shelfless warehouse to WooCommerce and have Shelfless fulfill orders for all products', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
						'value'		=> '1',
						'default' 	=> '0',
					),
				),				
				'placeholder'	=> '',
				'tooltip' 		=> esc_html__( 'Checking this box will let Shelfless synchronize available stock from the Shelfless warehouse to WooCommerce, and have Shelfless fulfill orders for all products with WooCommerce global setting. You can later change this in both global setting and per product. Default: "yes".', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'ttip_w_html'	=> true,
				'default'		=> '0',
				'label_for'		=> $this->plugin_name . '_inventory_is_manage_stock',
			),
			array(
				'fid' 			=> $this->plugin_name . '_inventory_is_sync_products',
				'label' 		=> esc_html__( 'Synchronize products', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'section' 		=> $this->plugin_name . '_inventory_settings',
				'type' 			=> 'checkbox',
				'options' 		=> array(
					array(
						'text'		=> esc_html__( 'Allow WooCommerce to synchronize product creations and updates to Shelfless', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
						'value'		=> '1',
						'default' 	=> '1',
					),
				),				
				'placeholder'	=> '',
				'tooltip' 		=> esc_html__( 'If <b>Synchronize Products</b> is enabled, it allows product creations and product updates to be sent to Shelfless, for all products. If you want to exclude specific products from being synced, you can disable product synchronization at product level later. Default: "Yes".', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'ttip_w_html'	=> true,
				'default'		=> '1',
				'label_for'		=> $this->plugin_name . '_inventory_is_sync_products',
				'class'			=> ( $wizard ? '' : 'hidden' ),
			),
			array(
				'fid' 			=> $this->plugin_name . '_inventory_is_show_notif_oos',
				'label' 		=> esc_html__( 'Admin notification when a product is out of stock', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'section' 		=> $this->plugin_name . '_inventory_settings',
				'type' 			=> 'checkbox',
				'options' 		=> array(
					array(
						'text'		=> esc_html__( 'Allow Shelfless to show admin notifications when a product is out of stock', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
						'value'		=> '1',
						'default' 		=> '0',
					),
				),				
				'placeholder'	=> '',
				'tooltip' 		=> esc_html__( 'When stock is below 1, Shelfless invokes a notification on in this page and in other relevant pages. Default: "yes".', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'ttip_w_html'	=> true,
				'default'		=> '0',
				'label_for'		=> $this->plugin_name . '_inventory_is_show_notif_oos',
				'class'			=> ( $wizard ? 'hidden' : '' ),
			),
			array(
				'fid' 			=> $this->plugin_name . '_inventory_is_show_notif_low_stock',
				'label' 		=> esc_html__( 'Show admin notification when a product stock is low', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'section' 		=> $this->plugin_name . '_inventory_settings',
				'type' 			=> 'checkbox',
				'options' 		=> array(
					array(
						'text'		=> esc_html__( 'Allow Shelfless to show admin notification when stock has met low threshold', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
						'value'		=> '1',
						'default' 		=> '0',
					),
				),				
				'placeholder'	=> '',
				'tooltip' 		=> esc_html__( 'When stock is below low threshold, Shelfless invokes a notification on this page and in other relevant pages. Default: "yes".', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'ttip_w_html'	=> true,
				'default'		=> '',
				'label_for'		=> $this->plugin_name . '_inventory_is_show_notif_low_stock',
				'class'			=> ( $wizard ? 'hidden' : '' ),
			),
			array(
				'fid' 			=> $this->plugin_name . '_inventory_low_threshold_value',
				'label' 		=> esc_html__( 'Set low stock threshold value', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'section' 		=> $this->plugin_name . '_inventory_settings',
				'type' 			=> 'text',
				'placeholder'	=> '',
				'tooltip' 		=> esc_html__( 'Override WooCommerce low stock threshold value with customized low stock threshold. Default: "2".', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'ttip_w_html'	=> true,
				'default'		=> '5',
				'label_for'		=> $this->plugin_name . '_inventory_low_threshold_value',
				'class'			=> ( $wizard ? 'hidden' : '' ),
			),
		);

		$this->shelfless_register_fields( $fields, $this->plugin_name . '_inventory_settings' );

	}

	/**
	 * Generates Shelfless Product Inventory grid section.
	 *
	 * @since    1.0.0
	 */
	public function shelfless_inventory_settings_product_grid() {

		add_settings_section( $this->plugin_name . '_inventory_settings_product_grid', '', array( $this, 'shelfless_inventory_settings_product_grid_content' ), $this->plugin_name . '_inventory_settings_product_grid' );
		
	}

	/**
	 * Generates Shelfless Product Inventory grid section content.
	 *
	 * @since    1.0.0
	 */
	public function shelfless_inventory_settings_product_grid_content() {

		generate_bring_shelfless_inventory_settings_product_grid_content();

	}

	/**
	 * Generates Shelfless setup wizard page.
	 *
	 * @since    1.0.0
	 */
	public function shelfless_setup_wizard_page_load() {

		$setup_wizard = add_menu_page(
			esc_html__( 'Shelfless Setup Wizard', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
			esc_html__( 'Shelfless by Bring', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
			'manage_options',
			$this->plugin_name . '_setup',
			array( $this, 'shelfless_setup_wizard_page_content' ),
			'dashicons-cart',
			55.5
		);

		add_action( 'load-' . $setup_wizard, array( $this, 'shelfless_setup_wizard_page_filter_body_classes' ) );

	}

	/**
	 * Generates Shelfless setup wizard page content.
	 *
	 * @since    1.0.0
	 */
	public function shelfless_setup_wizard_page_content() {

		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/views/bring-3pl-admin-setup-wizard.php';
		
	}
	
	/**
	 * Filters body classes for Shelfless admin pages.
	 *
	 * @since    1.0.0
	 */
	public function shelfless_admin_settings_page_filter_body_classes() {

		add_filter( 'admin_body_class', array( $this, 'shelfless_admin_settings_page_body_classes'), 100, 1);

	}

	/**
	 * Filters body classes for Shelfless setup wizard pages.
	 *
	 * @since    1.0.0
	 */
	public function shelfless_setup_wizard_page_filter_body_classes() {

		wp_enqueue_script( $this->plugin_name . '_setup_wizard', plugin_dir_url( dirname( __FILE__ ) ) . 'admin/js/bring-3pl-shelfless-fulfillment-for-woocommerce-setup-wizard.js', array( $this->plugin_name ), $this->version, false );
		add_filter( 'admin_body_class', array( $this, 'shelfless_setup_wizard_page_body_classes'), 100, 1);
		add_filter( 'show_admin_bar', array( $this, 'shelfless_setup_wizard_page_html_class_show_admin_bar' ), 100 );

	}

	/**
	 * Callback filter actions for body classes for Shelfless admin pages.
	 *
	 * @since    1.0.0
	 */
	public function shelfless_admin_settings_page_body_classes( $classes ) {
		
		// Take note: unlike public-facing pages, admin body classes are presented as a string,
		// so we need to dissect, reconstruct and re-assemble the classes before returning. -> Harvey
		$classes = explode( ' ', $classes );
		$classes[] = $this->plugin_name;
		return implode( ' ', $classes );

	}	

	/**
	 * Callback filter actions for body classes for Shelfless setup wizard pages.
	 *
	 * @since    1.0.0
	 */
	public function shelfless_setup_wizard_page_body_classes( $classes ) {
		
		// Take note: unlike public-facing pages, admin body classes are presented as a string,
		// so we need to dissect, reconstruct and re-assemble the classes before returning. -> Harvey
		$classes = explode( ' ', $classes );
		$classes[] = $this->plugin_name;
		$classes[] = $this->plugin_name . '_full_screen_setup_wizard';
		$classes[] = 'is-wp-toolbar-disabled';
		return implode( ' ', $classes );

	}

	/**
	 * Hides of admin bar in Shelfless setup wizard pages.
	 *
	 * @since    1.0.0
	 */
	public function shelfless_setup_wizard_page_html_class_show_admin_bar() {

		return false;

	}

	/**
	 * Hides of nagging in Shelfless setup wizard pages.
	 *
	 * @since    1.0.0
	 */
	public function shelfless_setup_wizard_page_hide_update_nag() {

		remove_action( 'admin_notices', 'update_nag', 3 );

	}

	/**
	 * Generates Shelfless Settings link from the plugin list.
	 *
	 * @since    1.0.0
	 */
	public function shelfless_admin_plugin_settings_link( $links ) {

		if ( ! get_option( $this->plugin_name . '_shelfless_api_setup_is_complete') ) {
			$page = $this->plugin_name . '_setup';
		}
		else {
			$page = $this->plugin_name;
		}

		$admin_dash = self_admin_url( '', 'admin' );

		$url = esc_url(
			add_query_arg(
				'page',
				$page,
				$admin_dash . 'admin.php'
			)
		);

		$settings_link = '<a href="' . esc_url( $url ) . '">' . __( 'Settings', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) . '</a>';

		array_unshift( $links, $settings_link) ;
		return $links;

	}
	
	/**
	 * Updates and completes the shelfless api setup wizard
	 *
	 * @since    1.0.0
	 */
	public function shelfless_setup_wizard_page_finished() {

		if ( ! wp_verify_nonce( $_REQUEST['nonce_finished'], 'nonce_' . $this->plugin_name . '_api_creds_wizard_mybring_finished' ) 
			|| ( wp_verify_nonce( $_REQUEST['nonce_finished'], 'nonce_' . $this->plugin_name . '_api_creds_wizard_mybring_finished' )
			&& $_REQUEST['action'] !== 'bring_shelfless_setup_wizard_page_finished' ) ) {

				add_bring_shelfless_notice(
					esc_html__( 'Shelfless setup wizard encountered an error during configuration, or your session has expired. Please try running the setup wizard again.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
					'error',
					true,
					'setup-wizard'
				);

				$admin_dash = self_admin_url( '', 'admin' );

				$url = esc_url( $admin_dash . 'plugins.php' );

				exit ( wp_safe_redirect( urldecode( $url ) ) );

		}

		update_option( $this->plugin_name .  '_shelfless_api_setup_is_complete', 1, 'yes');

		redirect_bring_shelfless_page( $this->plugin_name );

	}

	/**
	 * Reconstruct the structure of each article
	 *
	 * @since    	1.0.0
	 * @access		private
	 * @param		array		$articles		The article structure to recontruct
	 */
	private function shelfless_reconstruct_article_structure( $articles ) {

		$processed_articles = array();

		if ( empty( $articles ) ) {
			return $processed_articles;
		}
		
		// /article endpoint
		if ( isset( $articles ) ) {
			foreach( $articles as $key => $arr ) { 
				foreach( $arr->data as $k => $val ) {
					$processed_articles[$val->sku] = $val->available_quantity;
				}
			}
		}

		return $processed_articles;

	}
	
	/**
	 * Display and pull the products in datatable format
	 *
	 * @since    	1.0.0
	 */
	public function shelfless_pull_inventory_datatables() {

		if ( empty ( get_option( $this->plugin_name . '_shelfless_api_key' ) ) 
			|| empty ( get_option( $this->plugin_name . '_shelfless_api_secret_key' ) )
			) {
			return false;
		}

		header( 'Content-Type: application/json' );

		if ( ! wp_verify_nonce( $_REQUEST['mybring_shelfless_nonce'], 'nonce_' . $this->plugin_name . '_inventory_settings_pull_inv_grid_mybring' ) 
		|| ( wp_verify_nonce( $_REQUEST['mybring_shelfless_nonce'], 'nonce_' . $this->plugin_name . '_inventory_settings_pull_inv_grid_mybring' )
		&& $_REQUEST['action'] !== 'bring_shelfless_inventory_datatables_endpoint' ) ) {
			wp_die( json_encode( array( 'error' => true ) ) );
		}
		
		$inventory_instance = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Inventory( $this->plugin_name );

		$payload = array(
			'error'	=> true,
		);

		$init_products = $inventory_instance->get_products();

		$totalRecords = count( $init_products );

		$search = esc_attr( trim( $request['search']['value'] ) );
		$search_value = sanitize_text_field( $_POST['search']['value'] );

		// Read values
		$draw = validate_bring_shelfless_text_field( sanitize_text_field( $_POST['draw'] ) );
		$row = validate_bring_shelfless_text_field( sanitize_text_field( $_POST['start'] ) );
		$rowperpage = validate_bring_shelfless_text_field( sanitize_text_field( $_POST['length'] ) ); // Rows display per page
		$columnIndex = validate_bring_shelfless_text_field( sanitize_text_field( $_POST['order'][0]['column'] ) ); // Column index to order
		$columnName = validate_bring_shelfless_text_field( sanitize_text_field( $_POST['columns'][$columnIndex]['data'] ) ); // Column name to order
		$columnSortOrder = validate_bring_shelfless_text_field( sanitize_text_field( $_POST['order'][0]['dir'] ) ); // asc or desc
		$searchValue = esc_attr( trim( validate_bring_shelfless_text_field( $search_value ) ) ); // Search value

		// Override the colunmName to the actual WP data
		$columnName = ( $columnName == 'product_edit_link' ? 'name' : ( $columnName == 'type' ? 'product_type' : $columnName ) );

		$args = array();

		$limit_args = array( 'limit' => $rowperpage, 'offset' => $row );
		$order_args = array();
		$search_args = array();

		$order_args = array( 'orderby' => $columnName, 'order' => $columnSortOrder );

		$searchFilter = false;
		if ( ! empty( $searchValue ) ) {

			$searchFilter = true;

			// by type
			$search_args = array('type' => $searchValue);
			$by_type_args = array('type' => $searchValue, 'limit' => -1);
			$args = array_merge( $order_args, $by_type_args );

			$products = $inventory_instance->get_products( $args );
			$totalRecords = count( $products );

			// by SKU
			if ( empty($products) ) {
				unset( $search_args['type'] );
				
				$search_args = array('sku' => $searchValue);
				$by_type_args = array('sku' => $searchValue, 'limit' => -1);
				$args = array_merge( $order_args, $by_type_args );

				$products = $inventory_instance->get_products( $args );
				$totalRecords = count( $products );
			}
			
			// by product name
			if ( empty($products) ) { 
				unset( $search_args['type'] );
				unset( $search_args['sku'] );
				
				$search_args = array('s' => $searchValue);
				$by_type_args = array('s' => $searchValue, 'limit' => -1);
				$args = array_merge( $order_args, $by_type_args );

				$products = $inventory_instance->get_products( $args );
				
				$totalRecords = count( $products );
			}

			unset( $products );

		}

		$args = array_merge( $order_args, $search_args, $limit_args );
		
		$products = $inventory_instance->get_products( $args );
		
		$articles_instance = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Http();
		$articles = $articles_instance->get_article( false, true );
		$processed_articles = $this->shelfless_reconstruct_article_structure( $articles );

		$payload['data'] = array();
		$payload['recordsTotal'] = 0;
		$payload['recordsFiltered'] = 0;

		if ( $products ) {
			$payload['error'] = false;
			foreach ( $products as $key => $product ) {
				
				$thumbnail = get_the_post_thumbnail_url( $product['id'] );
				$product_edit_url = get_edit_post_link( $product['id'] );

				// get post_meta for is_fulfill product
				$is_fulfill = get_post_meta( $product['id'], '_' . $this->plugin_name . '_is_fulfill', true );
				$is_fulfill = ( $is_fulfill === 'yes' ? true : false );
				// get post meta for is_sync product
				$is_sync = get_post_meta(  $product['id'], '_' . $this->plugin_name . '_is_article_sync', true );
				$is_sync = ( $is_sync === 'yes' ? true : false );

				$matched = false;
				if ( array_key_exists( $product['sku'], $processed_articles ) ) {
					$matched = true;
				}

				$payload['data'][] = array(
					'chkbx'				=> sprintf( '<input type="checkbox" id="is_fulfill_%1$s" class="inv-check-is-fulfill" name="is_fulfill[]" data-productid="%2$s" '. ( $is_fulfill ? 'checked' : '' ) .'/>', $key, esc_html( $product['id'] ) ),
					'product_id'		=> $product['id'],
					'name'				=> esc_html( $product['name'] ),
					'sku'				=> $product['sku'],
					'matched'			=> ( $matched ? esc_html( 'Found', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) : esc_html( 'Not Found', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) ),
					'type'				=> ucfirst( $product['product_type'] ),
					'image'				=> sprintf( ( $thumbnail ? '<img src="%1$s" width="50" height="50" alt="%2$s" />' : '%2$s' ), esc_url( $thumbnail ), esc_html( $product['name'] ) ),
					'chkbx_sync'		=> sprintf( '<input type="checkbox" id="is_article_sync_%1$s" class="inv-check-is-sync" name="is_article_sync[]" data-productid="%2$s" '. ( $is_sync ? 'checked' : '' ) .'/>', $key, esc_html( $product['id'] ) ),
					'is_fulfill'		=> $is_fulfill,
					'is_sync'			=> $is_sync,
					'product_edit_link'	=> sprintf( '<a href="%1$s" target="_blank" title="%2$s">%2$s</a>', esc_url( $product_edit_url ), esc_html( $product['name'] ) ),
				);
				unset( $quantities );
				unset( $total_quantities );
				unset( $quantities_count );
			}

			$payload['draw'] = intval( $draw );
			$payload['recordsTotal'] = ( ! empty( $products ) ? $totalRecords : 0 );
			$payload['recordsFiltered'] = ( ! empty( $products ) ? $totalRecords : 0 );
			
		}
		
		unset( $inventory_instance );
		unset( $articles );
		unset( $articles_instance );
		
		wp_send_json( $payload );

	}

	/**
	 * Display and pull the stock adjustment report
	 *
	 * @since    	1.0.0
	 */
	public function shelfless_pull_stock_adjustment_report_datatables() { 

		if ( empty ( get_option( $this->plugin_name . '_shelfless_api_key' ) ) 
			|| empty ( get_option( $this->plugin_name . '_shelfless_api_secret_key' ) )
			) {
			return false;
		}

		header( 'Content-Type: application/json' );

		if ( ! wp_verify_nonce( $_REQUEST['mybring_shelfless_nonce'], 'nonce_' . $this->plugin_name . '_inventory_settings_pull_stock_adjustment_report_mybring' ) 
		|| ( wp_verify_nonce( $_REQUEST['mybring_shelfless_nonce'], 'nonce_' . $this->plugin_name . '_inventory_settings_pull_stock_adjustment_report_mybring' )
		&& $_REQUEST['action'] !== 'bring_shelfless_stock_adjustment_report_datatables_endpoint' ) ) {
			wp_die( json_encode( array( 'error' => true ) ) );
		}

		$payload = array(
			'error'	=> true,
		);
		
		$http_instance = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Http();
		$stocks_adjustment = $http_instance->get_stock_adjustments( false, true );
		
		$totalRecords = count( $stocks_adjustment->data );
		
		$search = esc_attr( trim( $request['search']['value'] ) );
		$search_value = sanitize_text_field( $_POST['search']['value'] );

		// Read values
		$draw = validate_bring_shelfless_text_field( sanitize_text_field( $_POST['draw'] ) );
		$row = validate_bring_shelfless_text_field( sanitize_text_field( $_POST['start'] ) );
		$rowperpage = validate_bring_shelfless_text_field( sanitize_text_field( $_POST['length'] ) ); // Rows display per page
		$columnIndex = validate_bring_shelfless_text_field( sanitize_text_field( $_POST['order'][0]['column'] ) ); // Column index to order
		$columnName = validate_bring_shelfless_text_field( sanitize_text_field( $_POST['columns'][$columnIndex]['data'] ) ); // Column name to order
		$columnSortOrder = validate_bring_shelfless_text_field( sanitize_text_field( $_POST['order'][0]['dir'] ) ); // asc or desc
		$searchValue = esc_attr( trim( validate_bring_shelfless_text_field( $search_value ) ) ); // Search value

		$payload['data'] = array();
		$payload['recordsTotal'] = 0;
		$payload['recordsFiltered'] = 0;

		if ( $stocks_adjustment->data ) { 

			$payload['error'] = false;

			foreach ( $stocks_adjustment->data as $key => $stock ) {
				$payload['data'][] = array(
					'customerNumber' => $stock->customerNumber, 
					'sku' => $stock->sku, 
					'warehouseId' => $stock->warehouseId, 
					'adjustment' => $stock->adjustment, 
					'unit' => $stock->unit, 
					'balanceType' => $stock->balanceType, 
					'reason' => $stock->reason, 
					'sourceCreatedEpoch' => date('Y-m-d H:i:s', ( $stock->sourceCreatedEpoch/1000000000 ) ), 
					'eventCreated' => date('Y-m-d H:i:s', ( $stock->eventCreated/1000000000 ) ), 
					'batchNumber' => $stock->batchNumber,
				);
			}

			$payload['draw'] = intval( $draw );
			$payload['recordsTotal'] = ( ! empty( $stocks_adjustment->data ) ? $totalRecords : 0 );
			$payload['recordsFiltered'] = ( ! empty( $stocks_adjustment->data ) ? $totalRecords : 0 );
			
		}
		
		unset( $http_instance );
		unset( $stocks_adjustment );
		
		wp_send_json( $payload );

	}

	/**
	 * Displays notice messages
	 *
	 * @since    	1.0.0
	 */
	public function shelfless_show_notices() {

		$notices = get_transient( 'shelfless_notices_'  . get_current_blog_id() . '_' .  get_current_user_id() );
		if ( $notices === false ) return;

		// removes the duplicate admin messages/notices
		$final_notices = array();
		foreach ( $notices as $key => $notice ) {
			if ( ! in_array( $notice, $final_notices ) )
				$final_notices[$key] = $notice;
		}
		
		if ( $final_notices ) {
			foreach ( $final_notices as $notice ) {
				printf(
					'<div class="notice notice-%1$s %2$s clearfix">' . 
					'<div class="float-left "><img class="bring-error-icon" src="' . urldecode( esc_url( plugin_dir_url( dirname( __FILE__ ) ) . 'admin/assets/bring_icon.png' ) ) . '" /></div>' . 
					'<div><p>%3$s</p></div>' . 
					'</div>',
					$notice['type'],
					$notice['dismissible'],
					$notice['notice'],
				);
			}
		}
	
		delete_transient( 'shelfless_notices_' . get_current_blog_id() . '_' .  get_current_user_id() ); 
		
	}

	/**
	 * Displays wizard to save the all data setup for inventory settings
	 *
	 * @since    	1.0.0
	 */
	public function shelfless_wizard_save_inventory_settings() {

		header( 'Content-Type: application/json' );

		if ( ! wp_verify_nonce( $_REQUEST['mybring_shelfless_nonce'], 'nonce_' . $this->plugin_name . '_inventory_settings_mybring' ) 
		|| ( wp_verify_nonce( $_REQUEST['mybring_shelfless_nonce'], 'nonce_' . $this->plugin_name . '_inventory_settings_mybring' )
		&& $_REQUEST['action'] !== 'bring_shelfless_wizard_save_inventory_settings' ) ) {
			wp_die( json_encode( array( 'error' => true ) ) );
		}

		$post = wc_clean( $_POST );

		foreach ( $post as $key => $value ) {
			if ( preg_match( '/^shelfless_/', $key ) ) {
				if ( in_array( $value, array( 'true', 'false' ) ) ) {
					$value = ( $value === 'true' ? 1 : 0 );
				}

				$temp_key = preg_replace( '/^shelfless_/', '', $key );
				$post[$this->plugin_name . '_' . $temp_key] = validate_bring_shelfless_text_field( sanitize_text_field( $value ) );
				unset( $post[$key] );
			}
			else {
				unset( $post[$key] );
			}
		}

		foreach ( $post as $key => $value ) {
		 	update_option( $key, $value, 'yes' );
		}

		wp_die( json_encode( array( 'error' => false, 'data' => $post ) ) );

	}

	/**
	 * Displays wizard to save the all data setup for order settings
	 *
	 * @since    	1.0.0
	 */
	public function shelfless_wizard_save_order_settings() {

		header( 'Content-Type: application/json' );

		if ( ! wp_verify_nonce( $_REQUEST['mybring_shelfless_nonce'], 'nonce_' . $this->plugin_name . '_order_settings_mybring' ) 
		|| ( wp_verify_nonce( $_REQUEST['mybring_shelfless_nonce'], 'nonce_' . $this->plugin_name . '_order_settings_mybring' )
		&& $_REQUEST['action'] !== 'bring_shelfless_wizard_save_order_settings' ) ) {
			wp_die( json_encode( array( 'error' => true ) ) );
		}

		$post = wc_clean( $_POST );

		foreach ( $post as $key => $value ) { 

			if ( $key == 'shelfless_order_value_added_services_codes' ) continue;

			if ( preg_match( '/^shelfless_/', $key ) ) {
				if ( in_array( $value, array( 'true', 'false' ) ) ) {
					$value = ( $value === 'true' ? 1 : 0 );
				}

				$temp_key = preg_replace( '/^shelfless_/', '', $key );

				// when saving 1 or more data from a multiple select field
				if ( is_array($value) ) {
					foreach( $value as $k => $v ) {
						$temp_value[$k] = validate_bring_shelfless_text_field( sanitize_text_field( $v ) );
					}
					$post[$this->plugin_name . '_' . $temp_key] = $temp_value;
				} else {
					$post[$this->plugin_name . '_' . $temp_key] = validate_bring_shelfless_text_field( sanitize_text_field( $value ) );
				}
				
				unset( $post[$key] );
			}
			else {
				unset( $post[$key] );
			}
		}
		
		foreach ( $post as $k => $val ) { 
			if ( $k == 'shelfless_order_value_added_services_codes' ) {
				$temp_key = preg_replace( '/^shelfless_/', '', $k );
				unset( $post[$k] );
				$k = $this->plugin_name . '_' . $temp_key;
				$post[$k] = $val;
			}
			
			update_option( $k, $val, 'yes' );
		}

		wp_die( json_encode( array( 'error' => false, 'data' => $post ) ) );

	}

	/**
	 * Displays wizard to save the all data setup for shipping mappings
	 *
	 * @since    	1.2.1
	 */
	public function shelfless_wizard_save_shipping_mappings() {

		header( 'Content-Type: application/json' );

		if ( ! wp_verify_nonce( $_REQUEST['mybring_shelfless_nonce'], 'nonce_' . $this->plugin_name . '_order_shipping_maps_settings_mybring' ) 
		|| ( wp_verify_nonce( $_REQUEST['mybring_shelfless_nonce'], 'nonce_' . $this->plugin_name . '_order_shipping_maps_settings_mybring' )
		&& $_REQUEST['action'] !== 'bring_shelfless_wizard_save_shipping_mappings' ) ) {
			wp_die( json_encode( array( 'error' => true ) ) );
		}

		$post = wc_clean( $_POST );

		foreach ( $post as $key => $value ) {
			if ( preg_match( '/^shelfless_/', $key ) ) {
				if ( in_array( $value, array( 'true', 'false' ) ) ) {
					$value = ( $value === 'true' ? 1 : 0 );
				}

				$temp_key = preg_replace( '/^shelfless_/', '', $key );

				// when saving 1 or more data from a multiple select field
				if ( is_array($value) ) {
					foreach( $value as $k => $v ) {
						$temp_value[$k] = validate_bring_shelfless_text_field( sanitize_text_field( $v ) );
					}
					$post[$this->plugin_name . '_' . $temp_key] = $temp_value;
				} else {
					$post[$this->plugin_name . '_' . $temp_key] = validate_bring_shelfless_text_field( sanitize_text_field( $value ) );
				}
				
				unset( $post[$key] );
			}
			else {
				unset( $post[$key] );
			}
		}

		foreach ( $post as $key => $value ) {
		 	update_option( $key, $value, 'yes' );
		}

		wp_die( json_encode( array( 'error' => false, 'data' => $post ) ) );

	}

	/**
	 * Setting to make changes for Enable Fulfillment with Shelfless in variations level
	 *
	 * @since    	1.0.0
	 * @param		integer		$loop				Variation key
	 * @param		array		$variation_data		Variation data that is in array
	 * @param		object		$variation			Variation data that is in object
	 */
	public function shelfless_variation_options( $loop, $variation_data, $variation ) {

		$variation_name = '_'. $this->plugin_name . '_is_fulfill';
		
		// Setting value and cbvalue here ensure that this meta is checked by default. -> Harvey
		$args = array(
			'id'			=> $variation_name .'['. $variation->ID .']', 
			'label'			=> esc_html__( 'Enable Fulfillment with Shelfless', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
			'value'			=> get_post_meta( $variation->ID, $variation_name, true ), 
			'class'			=> 'shelfless_is_fulfill checkbox variable_checkbox',
			'desc_tip'		=> true,
			'value'			=> true,
			'cbvalue'		=> true,
			'description'	=> esc_html__( 'Fulfill this item with Shelfless at variation level', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
		);

		woocommerce_wp_checkbox( $args );

	}

	/**
	 * Setting to save the changes for Enable Fulfillment with Shelfless in variations level
	 *
	 * @since    	1.0.0
	 * @param		integer			$variation_id		Variation ID
	 */
	public function shelfless_save_product_variation( $variation_id ) { 

		$variation_name = '_'. $this->plugin_name .'_is_fulfill';

		$sanitized_chkbox = validate_bring_shelfless_text_field( sanitize_text_field( $_POST[$variation_name][$variation_id] ) );
		$checkbox = ( ! empty( $sanitized_chkbox ) ? 'yes' : 'no' );

  		update_post_meta( $variation_id, $variation_name, $checkbox );

	}

	/**
	 * Store custom field value into WooCommerce variation data
	 * @since    	1.0.0
	 * @param		array		$variations		Variation data in array format
	 *
	*/
	public function shelfless_load_variation_settings_fields( $variations ) {
		
		$variation_name = '_'. $this->plugin_name .'_is_fulfill';
		
		$variations[$variation_name] = get_post_meta( $variations[ 'variation_id' ], $variation_name, true );
		
		return $variations;
	}

	/**
	 * Setting to override the WooCommerce manage stock settings to Shelfless manage stock level
	 *
	 * @since    	1.0.0
	 */
	public function shelfless_manage_stock() {
		global $post;

		echo '<div id="notify" class="col-sm-12"></div>';
		$nonce = wp_create_nonce( 'nonce_' . get_bring_shelfless_plugin_name() . '_inventory_settings_pull_inv_grid_mybring' );
		echo '<input type="hidden"
			id="nonce_pull_inv_grid" 
			name="nonce_pull_inv_grid" 
			value="' . esc_attr( $nonce ) . '" />';
			
		$nonce_match = wp_create_nonce( 'nonce_' . get_bring_shelfless_plugin_name() . '_inventory_settings_match_inv_grid_mybring' );
		echo '<input type="hidden"
			id="nonce_match_inv_grid" 
			name="nonce_match_inv_grid" 
			value="' . esc_attr( $nonce_match ) . '" />';
		
		// Setting value and cbvalue here ensure that this meta is checked by default. -> Harvey
		$is_fulfilled = get_post_meta( $post->ID, '_' . $this->plugin_name . '_is_fulfill', true );
		$args = array(
			'id'			=> '_' . $this->plugin_name . '_is_fulfill',
			'label'			=> esc_html__( 'Enable Fulfillment with Shelfless', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
			'class'			=> 'shelfless_is_fulfill checkbox'. ' '. $post->ID,
			'desc_tip'		=> true,
			'cbvalue'		=> true,
			'value'			=> ( empty( $is_fulfilled ) ? true : ( $is_fulfilled == 'yes' ? true : false ) ), 
			'description'	=> esc_html__( 'Fulfill this item with Shelfless', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
		);
		woocommerce_wp_checkbox( $args );

		if ( get_option( $this->plugin_name . '_inventory_is_sync_products' ) === '1' ) { 
			// The _is_article product meta should rely now on plugin Sync Products setting.
			// Setting value and cbvalue here ensure that this meta is checked by default. -> Harvey
			$is_synced = get_post_meta( $post->ID, '_' . $this->plugin_name . '_is_article_sync', true );
			$args = array(
				'id'			=> '_' . $this->plugin_name . '_is_article_sync',
				'label'			=> esc_html__( 'Enable Article Sync with Shelfless', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'class'			=> 'shelfless_is_article_sync checkbox'. ' '. $post->ID,
				'desc_tip'		=> true,
				'cbvalue'		=> true,
				'value'			=> ( empty( $is_synced ) ? true : ( $is_synced == 'yes' ? true : false ) ),
				'description'	=> esc_html__( 'Sync this item with Shelfless', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
			);
			woocommerce_wp_checkbox( $args );
		}

		if ( get_option( $this->plugin_name . '_inventory_is_use_customs_field' ) === '1' ) { 

			if ( get_option( $this->plugin_name . '_inventory_is_use_cost_price_field' ) === '1' ) {
				$cost_price_currency = get_option( $this->plugin_name . '_inventory_cost_price_currency');
				$cost_price_currency = ( ! empty( $cost_price_currency ) ? $cost_price_currency : get_woocommerce_currency() );
				$args = array(
					'id'			=> '_' . $this->plugin_name . '_customs_cost_price',
					'label'			=> esc_html__( 'Cost Price (' . $cost_price_currency . ') - Shelfless', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
					'class'			=> 'shelfless_cost_price',
					'desc_tip'		=> true,
					'description'	=> esc_html__( 'This field is usually utilized for Customs Commercial Invoices for international orders wherein seller has the option to use cost price as the declared price for an item.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				);
				woocommerce_wp_text_input( $args );
			}
			
			$args = array(
				'id'			=> '_' . $this->plugin_name . '_customs_hs_code',
				'label'			=> esc_html__( 'Harmonized Tariff Code - Shelfless', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'class'			=> 'shelfless_customs_hs_code',
				'placeholder'	=> esc_html__( 'HS Code', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'desc_tip'		=> true,
				'description'	=> esc_html__( 'This field is usually utilized for Customs Commercial Invoices for international orders wherein seller has the option to a Harmonized Tariff System code.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
			);
			woocommerce_wp_text_input( $args );

			$default_country_of_origin = get_option( $this->plugin_name . '_inventory_default_country_of_origin');

			$wc_cntry_obj = new WC_Countries();
			$wc_countries = $wc_cntry_obj->__get('countries');
			unset( $wc_cntry_obj );

			array_unshift( $wc_countries,  esc_html__( '- Select Country -', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) ); 			
			$selected_coo = ! empty( get_post_meta( $post->ID, '_' . $this->plugin_name . '_customs_manufacture_country', true ) ) ? get_post_meta( $post->ID, '_' . $this->plugin_name . '_customs_manufacture_country', true ) : false;

			$args = array(
				'id'			=> '_' . $this->plugin_name . '_customs_manufacture_country',
				'label'			=> esc_html__( 'Country of Origin - Shelfless', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'class'			=> 'shelfless_customs_coo',
				'desc_tip'		=> true,
				'description'	=> esc_html__( 'This field is usually utilized for Customs Commercial Invoices for international orders wherein seller has the option to a Harmonized System code.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'options'		=> $wc_countries,
				'value'			=> ( ! $selected_coo ? $default_country_of_origin: $selected_coo ),
			);
			woocommerce_wp_select( $args );

		}

	}

	/**
	 * Setting to override the WooCommerce product to fulfill by Shelfless, setup custom cost price
	 *
	 * @since    	1.0.0
	 * @access		public
	 * @param		int			$post_id		Unique ID to set/update meta data
	 */
	public function shelfless_save_woocommerce_product_meta( $post_id ) { 

		$http_instance = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Http( $this->plugin_name );
		
		$is_fulfill = validate_bring_shelfless_text_field( sanitize_text_field( $_POST['_' . $this->plugin_name . '_is_fulfill'] ) );
		$cost_price = validate_bring_shelfless_text_field( sanitize_text_field( $_POST['_' . $this->plugin_name . '_customs_cost_price'] ) );
		$hs_code 	= validate_bring_shelfless_text_field( sanitize_text_field( $_POST['_' . $this->plugin_name . '_customs_hs_code'] ) );
		$coo		= validate_bring_shelfless_text_field( sanitize_text_field( $_POST['_' . $this->plugin_name . '_customs_manufacture_country'] ) );

		// article sync flag
		$is_article_sync = validate_bring_shelfless_text_field( sanitize_text_field( $_POST['_' . $this->plugin_name . '_is_article_sync'] ) );

		if ( ! empty( $is_fulfill ) ) {
			update_post_meta( $post_id, '_' . $this->plugin_name . '_is_fulfill', esc_attr( $is_fulfill ) );
		}
		else {
			$is_fulfill = 'no';
			update_post_meta( $post_id, '_' . $this->plugin_name . '_is_fulfill', esc_attr( $is_fulfill ) );
		}

		if ( ! empty( $is_article_sync ) ) {
			update_post_meta( $post_id, '_' . $this->plugin_name . '_is_article_sync', esc_attr( $is_article_sync ) );
		}
		else {
			$is_article_sync = 'no';
			update_post_meta( $post_id, '_' . $this->plugin_name . '_is_article_sync', esc_attr( $is_article_sync ) );
		}

		if ( ! empty( $cost_price ) && ( is_float( $cost_price ) || is_numeric( $cost_price ) ) ) {
			update_post_meta( $post_id, '_' . $this->plugin_name . '_customs_cost_price', esc_attr( $cost_price ) );
		}
		else {
			$cost_price = '0.00';
			update_post_meta( $post_id, '_' . $this->plugin_name . '_customs_cost_price', esc_attr( $cost_price ) );
		}

		if ( ! empty( $hs_code ) ) {
			update_post_meta( $post_id, '_' . $this->plugin_name . '_customs_hs_code', esc_attr( $hs_code ) );
		}
		else {
			update_post_meta( $post_id, '_' . $this->plugin_name . '_customs_hs_code', esc_attr( '' ) );
		}

		if ( ! empty( $coo ) ) {
			update_post_meta( $post_id, '_' . $this->plugin_name . '_customs_manufacture_country', esc_attr( $coo ) );
		}
		else {
			$default_country_of_origin = get_option( $this->plugin_name . '_inventory_default_country_of_origin');
			update_post_meta( $post_id, '_' . $this->plugin_name . '_customs_manufacture_country', esc_attr( $default_country_of_origin ) );
		}


	}

	/**
	 * Set and update fulfill flag
	 * Should not be able to change the fulfill flag if there is an ongoing order related to the product 
	 * within the number of days before the order checks
	 *
	 * @since		1.0.0
	 * @access		public
	 */
	public function shelfless_update_products_is_fulfill() { 

		header( 'Content-Type: application/json' );

		if ( ! wp_verify_nonce( $_REQUEST['mybring_shelfless_nonce'], 'nonce_' . $this->plugin_name . '_inventory_settings_pull_inv_grid_mybring' ) 
		|| ( wp_verify_nonce( $_REQUEST['mybring_shelfless_nonce'], 'nonce_' . $this->plugin_name . '_inventory_settings_pull_inv_grid_mybring' )
		&& $_REQUEST['action'] !== 'bring_shelfless_update_prod_is_fulfill' ) ) {
			wp_die( json_encode( array( 'error' => true ) ) );
		}

		$post = wc_clean( $_POST );
		if ( empty( $post['productids'] ) ) {
			wp_die( json_encode( array( 'error' => true ) ) );
		}

		$cancelled_fulfill_status = get_option( $this->plugin_name . '_order_status_cancel_status' );
		$shipped_fulfill_status = get_option( $this->plugin_name . '_order_status_shipped_status' );

		// return the number of days before to include the order checks
		$process_from_days_ago = get_option( $this->plugin_name . '_order_process_from_days_ago' ); 
		
		$cancelled_orders = $this->process_get_orders( $cancelled_fulfill_status, $process_from_days_ago ); 
		$shipped_orders = $this->process_get_orders( $shipped_fulfill_status, $process_from_days_ago ); 

		$orders_found = false;
		$existing_product_in_orders = false;
		$exit_loop = false;

		if ( ! empty( $cancelled_orders ) ) :
			$orders_found = true;
			$orders = $cancelled_orders;
		elseif ( ! empty( $shipped_orders ) ) :
			$orders_found = true;
			$orders = $shipped_orders;
		endif;

		if ( $orders_found ) {
			$cnt = count( $orders );
			$is_are = ( $cnt > 1 ? 'are' : 'is' );
			$txt_orders = ( $cnt > 1 ? 'orders' : 'order' );
			$txt_products = ( $cnt > 1 ? 'products' : 'product' );

			$url = esc_url( get_admin_url() . 'edit.php?post_type=product' );

			$product_instance = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Inventory( $this->plugin_name );
			$order_instance = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Order( $this->plugin_name );
			$products = array();
			$set_products = array();
			
			foreach ($orders as $order) { 
				$order = (object) $order;
				$order_details = $order_instance->get_order($order->id);
				$order_items = $order_details->get_items();

				// iterate through an order's items
				foreach ($order_items as $item) {
					// if one item has the product id, add it to the array and exit the loop
					if ( ! empty( $post['productids'] ) ) {
						foreach( $post['productids'] as $key => $product ) {
							$product = (object) $product;
							// check if the product id within the order is equal to the deselected product to fulfill
							// and must validate that the product to fulfill is unchecked.
							if ( ( $item->get_product_id() == $product->product_id ) && ( $product->is_fulfill == 0 ) ) {
								$products[] = (object) $product_instance->get_product_by_id( $item->get_product_id() );
								$existing_product_in_orders = true;
							} 
						} 
					} 
				} 
			} 
			
			if ( $products ) {
				$IDs = array();
				foreach( $products as $k => $product ) {
					$IDs[] = $product->id;
				}

				foreach( $post['productids'] as $key => $product ) {
					// set the products that is/are not existing in the order
					if ( ! in_array( $product['product_id'], $IDs ) ) {
						$set_product[$key] = $product;
						$product_instance->set_product_is_fulfill( $set_product );
					}
				}
			}

			unset( $product_instance );
			unset( $order_instance );
			
			if ( $existing_product_in_orders ) {

				$html = show_notice_deselect_product_existing_in_order( $products, $txt_orders );

				wp_die( json_encode(
					array( 
						'error' => true,
						'IDs'	=> implode( ',', $IDs ),
						'msg'	=> sprintf( esc_html__( $html, 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) ),
					), 
				) );
			}
		}

		if ( $existing_product_in_orders === false ) {

			$product_instance = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Inventory( $this->plugin_name );
			
			if ( true === $product_instance->set_product_is_fulfill( $post['productids'] ) ) {
				unset( $product_instance ); 

				// last parameter refers to the attribute class to be added in the element
				$type = $post['product_type'];
				if ( $post['action_source'] == 'product_grid' ) { 
					$html_msg = show_notice_via_ajax( 'The update was successful.', $type, 'product-level-notice' );
				} else { 
					if ( $type == 'simple' || empty($type) ) {
						$html_msg = show_notice_via_ajax( 'The update was successful.', $type, 'product-level-notice' );
					} else {
						$html_msg = show_notice_via_ajax( 'The update was successful and was also applied to its ', $type, 'product-level-notice' );
					}
				}

				wp_die( json_encode( 
					array( 
						'error' => false, 
						'show' => true, 
						'msg'	=> sprintf( esc_html__( $html_msg, 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) )
					), 
				) );
			}
			else {
				wp_die( json_encode(
					array( 
						'error' => true,
						'msg'	=> esc_html__( 'Some products cannot be updated or are not found.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
					), 
				) );
			}
		}
	}

	/**
	 * Set and update the manage stock options, this can override global woocommerce manage stock settings
	 *
	 * @since		1.0.0
	 * @access		private
	 * @param		string		$old_value		Current value either 0/1
	 * @param		string		$value			New value to set
	 * @param		string		$option			Field to set the value to
	 */
	public function shelfless_set_manage_stock_options( $old_value, $value, $option ) {

		/* 
		 * This is kinda tricky. So we are making sure that WooCommerce's global Manage Stock option is enabled.
		 * If it is already (set by default, by user or by a plugin), then we do no action. If is is not, we will set
		 * it to 'yes'. Since global setting does not really affect the per-product Manage Stock options, let's check it
		 * one by one. As with the global setting, if the option is already set, let it be. If not, let's set it to 'yes'.
		 * However, when Bring's Manage Stock option is turned or (set to '0' or empty string), we should not touch the
		 * settings in the global scale or in the product level (no overriding). Why? The settings might have been previously
		 * set by another plugin or by the user. -> Harvey
		*/
		
		if ( $option === $this->plugin_name . '_inventory_is_manage_stock' && $old_value !== '1' &&  $value === '1' ) {
			
			if ( $wc_manage_stock = get_option( 'woocommerce_manage_stock' ) !== 'yes' ) {
				update_option( 'woocommerce_manage_stock', 'yes', 'yes' );
			}

			$inventory = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Inventory( $this->plugin_name );
			$products = $inventory->get_products();

			if ( $products ) {

				foreach( $products as $key => $product ) {

					$product_id = $product['id'];
					
					// Get the type of product
					$type = $product['product_type'];

					if ( ! in_array( $type, array( 'variable', 'composite', 'bundle', 'grouped' ) ) ) {
						if ( get_post_meta( $product_id, '_manage_stock' ) !== 'yes' ) {
							update_post_meta( $product_id, '_manage_stock', 'yes' );
							update_post_meta( $product_id, '_' . $this->plugin_name . '_is_fulfill', "yes" );
						}
					}
					else {
						update_post_meta( $product_id, '_manage_stock', 'no' );
						update_post_meta( $product_id, '_' . $this->plugin_name . '_is_fulfill', 'no' );
					}
					
					$this->shelfless_grouped_variable_products_update( '_manage_stock', $product );

				}
			}

			unset( $inventory );
			unset( $products );

		}

	}

	/**
	 * Set and update the Enable Fulfillment with Shelfless
	 *
	 * @since		1.0.0
	 * @access		private
	 * @param		string		$old_value		Current value either 0/1
	 * @param		string		$value			New value to set
	 * @param		string		$option			Field to set the value to
	 */
	public function shelfless_set_is_sync_products( $old_value, $value, $option ) {

		if ( $option === $this->plugin_name . '_inventory_is_sync_products' && $old_value !== '1' &&  $value === '1' ) {

			$inventory = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Inventory( $this->plugin_name );
			$products = $inventory->get_products();

			if ( $products ) {

				foreach( $products as $key => $product ) {

					$product_id = $product['id'];
					
					// Get the type of product
					$type = $product['product_type'];

					if ( ! in_array( $type, array( 'variable', 'composite', 'bundle', 'grouped' ) ) ) {
						if ( get_post_meta( $product_id, '_' . $this->plugin_name . '_is_article_sync' ) !== 'yes' ) {
							update_post_meta( $product_id, '_' . $this->plugin_name . '_is_article_sync', 'yes' );
						}
					}
					else {
						update_post_meta( $product_id, '_' . $this->plugin_name . '_is_article_sync', 'no' );
					}
					
					$this->shelfless_grouped_variable_products_update( '_' . $this->plugin_name . '_is_article_sync', $product );

				}
			}

			unset( $inventory );
			unset( $products );
		}

	}

	/**
	 * If WooCommerce is intalled and active, check for linked/mapped products to Bring
	 * 
	 * @since    1.0.0
	 **/
	private function shelfless_check_marked_products() {
		
		$shelfless_fulfillment = false;

		// returns the value of 1
		$shelfless_manage_stock = ( get_option( $this->plugin_name . '_inventory_is_manage_stock' ) === '1' ? true : false );

		if ( $shelfless_manage_stock ) {

			$inventory = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Inventory( $this->plugin_name );
			$products = $inventory->get_products();

			if ( $products ) {
				foreach( $products as $key => $product ) {
					// should return yes
					$is_fulfill = get_post_meta( $product['id'], '_' . $this->plugin_name . '_is_fulfill', true );
					// will fire up when there is one or more product that Shelfless will fulfill
					if ( $is_fulfill === 'yes') {
						$shelfless_fulfillment = true;
						update_option( $this->plugin_name . '_inventory_is_fulfill_products', '1', 'yes' );
						break;
					}
				}
			}
			// Meaning, we cannot see any product at all.
			else {
				$url = esc_url( get_admin_url() . 'admin.php?page=' . $this->plugin_name );
				add_bring_shelfless_notice(
					sprintf( esc_html__( 'No products are set up to be fulfilled by Shelfless. Your Bring third-party warehouse cannot update your store\'s inventory as a result. %s to let Shelfless take care of your fulfillment and dispatching needs.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ), '<br/><a href="'. urldecode( $url ) .'">Choose products now</a>' ),
					'error',
					true,
					'no-product-to-fulfill'
				);
			}

			// If no products now is chosen to be fulfilled by Bring, set our helper marker option to 0 regardless of its previous value.
			if ( ! $shelfless_fulfillment ) {
				update_option( $this->plugin_name . '_inventory_is_fulfill_products', '0', 'yes' );
				$url = esc_url( get_admin_url() . 'admin.php?page=' . $this->plugin_name );
				add_bring_shelfless_notice(
					sprintf( esc_html__( 'No products are set up to be fulfilled by Shelfless. Your Bring third-party warehouse cannot update your store\'s inventory as a result. %s to let Shelfless take care of your fulfillment and dispatching needs.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ), '<br/><a href="'. urldecode( $url ) .'">Choose products now</a>' ),
					'error',
					true,
					'no-product-to-fulfill'
				);
			}

			unset( $inventory );
			unset( $products );

		}

	}

	/**
	 * Handles the out of stock products
	 *
	 * @since		1.0.0
	 * @access		private
	 */
	private function shelfless_outofstock_products() {

		$shelfless_manage_stock = ( get_option( $this->plugin_name . '_inventory_is_manage_stock' ) === '1' ? true : false );
		$shelfless_low_stock_notice = ( get_option( $this->plugin_name . '_inventory_is_show_notif_low_stock' ) === '1' ? true : false );
		if ( ! $shelfless_low_stock_notice ) {
			$low_threshold_value = get_option( 'woocommerce_notify_low_stock_amount' );
		}
		else {
			$low_threshold_value = get_option( $this->plugin_name . '_inventory_low_threshold_value' );
		}

		$oos = array(); // out of stock
		$lows = array();

		if ( $shelfless_manage_stock ) {
			$inventory = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Inventory( $this->plugin_name );
			$products = $inventory->get_products();

			if ( $products ) {
				foreach( $products as $key => $product ) {
					$is_manage_stock = get_post_meta( $product['id'], '_manage_stock', true );
					$is_fulfill = get_post_meta( $product['id'], '_' . $this->plugin_name . '_is_fulfill', true );
					if ( $is_manage_stock === 'yes' && $is_fulfill === 'yes' ) {
						$stock = get_post_meta( $product['id'], '_stock', true );

						if ( $stock < 1 ) {
							$oos[$product['id']] = array(
								'sku'	=> $product['id'],
								'name'	=> $product['name'],
								'qty'	=> $stock,
							);
						}

						if ( $stock > 1 && $stock <= $low_threshold_value ) { 
							$lows[$product['id']] = array(
								'sku'	=> $product['id'],
								'name'	=> $product['name'],
								'qty'	=> $stock,
							);
						}
					}
				}
			}

			$url = esc_url(
				add_query_arg(
					'post_type',
					'product',
					get_admin_url() . 'edit.php'
				)
			);

			if ( count( $oos ) ) {

				// Render admin notification for OOS if it is enabled.
				// OOS means out of stock
				if ( ! empty ( get_option( $this->plugin_name . '_inventory_is_show_notif_oos' ) ) ) {
					add_bring_shelfless_notice(
						sprintf(
							esc_html__( '%s marked as to be fulfilled by Shelfless %s out of stock. It might be caused by unmatched SKUs. %s Go to %s now to check.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
							( count( $oos ) < 2 && count( $oos ) > 0 ? 'A product' : 'Some products' ),
							( count( $oos ) < 2 && count( $oos ) > 0 ? 'is' : 'are' ),
							'<br />',
							'<a href="'. urldecode( $url ) .'">Products</a>'
						),
						'error',
						true,
						'out-of-stock'
					);
				}
			
			}

			if ( count( $lows ) ) {

				// Render admin notification for low stock if it is enabled.
				if ( ! empty( get_option( $this->plugin_name . '_inventory_is_show_notif_low_stock' ) ) ) {

					add_bring_shelfless_notice(
						sprintf(
							esc_html__( '%s marked as to be fulfilled by Shelfless %s having low stocks. It might be caused by unmatched SKUs. %s Go to %s now to check.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
							( count( $lows ) < 2 && count( $lows ) > 0 ? 'A product' : 'Some products' ),
							( count( $lows ) < 2 && count( $lows ) > 0 ? 'is' : 'are' ),
							'<br />',
							'<a href="'. urldecode( $url ) .'">Products</a>'
						),
						'warning',
						true,
						'low-stock'
					);
				}
			}
		}

	}

	/**
	 * Method to call in order to log the action performed
	 *
	 * @since		1.0.0
	 * @param		string		$expression			Expression to log
	 * @param		string		$type				Log type
	 */

  	public static function shelfless_log( $expression, $type = 'info' ) {

		$is_debug = get_option( Bring_3pl_Shelfless_Fulfillment_For_Woocommerce::get_plugin_name() . '_shelfless_debug_mode', 0 );

		if ( empty( $is_debug ) ) return false;
		
		// Let's set the return value to always true. It will return false if log is not printable. -> Harvey
		$log = wc_print_r( $expression, true );
		$source = array( 'source' => 'shelfless-by-bring' );

		$logger = wc_get_logger();

		switch ( $type ) {
			case 'ship':
				$source['source'] = $source['source'] . '-DELIVERY';
				$logger->info(
					$log,
					$source
				);
				break;
			case 'info':
				$source['source'] = $source['source'] . '-INFO';
				$logger->info(
					$log,
					$source
				);
				break;
			case 'error':
				$source['source'] = $source['source'] . '-ERROR';
				$logger->error(
					$log,
					$source
				);
				break;
			case 'warning':
				$source['source'] = $source['source'] . '-WARNING';
				$logger->warning(
					$log,
					$source
				);
				break;
			default:
			$source['source'] = $source['source'] . '-INFO';
				$logger->log(
					$log,
					$source
				);
		}
		
		unset( $logger );

	}

	/**
	 * Perform scheduled fetching of inventory updates
	 *
	 * @since		1.0.0
	 */
	public function shelfless_sched_fetch_inventory_updates() {
		
		self::shelfless_log(  __CLASS__ . '->' . __FUNCTION__, 'info' );
		
		if ( get_option( $this->plugin_name . '_inventory_is_fulfill_products' ) !== '1' ) {
			self::shelfless_log( 
				esc_html__( 'No products marked to be fulfilled. Skipping this cron.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'info'
			);
			return false;
		}

		// If Manage Stock setting option in Shelfless plugin is not enabled, let us not update the inventory.
		if ( get_option( $this->plugin_name . '_inventory_is_manage_stock' ) !== '1' ) {
			self::shelfless_log(  "Skipping inventory update as plugin Manage Stock option is disabled.", 'info' );
			return false;
		}

		$get_articles = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Http();
		$articles = $get_articles->get_article();

		self::shelfless_log( $articles );

		$processed_articles = $this->shelfless_reconstruct_article_structure( $articles );

		self::shelfless_log( $processed_articles );

		if ( ! empty( $processed_articles ) ) {

			$i = 0; // total count
			$j = 0; // total found
			$k = 0; // total found and processed

			$outofstock = wc_clean( 'outofstock' );
			$instock = wc_clean( 'instock' );
			
			foreach ( $processed_articles as $sku => $qty ) {

				$i++;

				$product_id = wc_get_product_id_by_sku( $sku );

				if ( $product_id ) {

					$j++;
					
					$type = WC_Product_Factory::get_product_type( $product_id );
					self::shelfless_log( "SKU $sku with product ID $product_id is a $type product.", 'info' );
					
					if ( in_array( $type, array( 'variation', 'simple' ) ) ) {
						$k++;
						$product = wc_get_product( $product_id );
						self::shelfless_log( "SKU $sku with product ID $product_id found.", 'info' );
						self::shelfless_log( "Current stock for SKU $sku is " . get_post_meta( $product_id, '_stock', true ) . '.', 'info' );
						self::shelfless_log( "Current stock status for SKU $sku is " . get_post_meta( $product_id, '_stock_status', true ) . '.', 'info' );
						self::shelfless_log( "Setting stock for SKU $sku to $qty.", 'info' );
						
						if ( $qty > 0 ) {
							update_post_meta( $product_id, '_stock', $qty );
							update_post_meta( $product_id, '_stock_status', $instock );
							wp_set_post_terms( $product_id, $instock, 'product_visibility', true );
							wp_remove_object_terms( $product_id,  $outofstock, 'product_visibility' );
						}
						else {
							update_post_meta( $product_id, '_stock', 0 );
							update_post_meta( $product_id, '_stock_status', $outofstock );
							wp_set_post_terms( $product_id, $outofstock, 'product_visibility', true );
							wp_remove_object_terms( $product_id, $instock, 'product_visibility' );
						}
						self::shelfless_log( "New stock for SKU $sku is " . get_post_meta( $product_id, '_stock', true ) . '.', 'info' );
						self::shelfless_log( "New stock status for SKU $sku is " . get_post_meta( $product_id, '_stock_status', true ) . '.', 'info' );
						self::shelfless_log( '---', 'info' );
					}
					else {
						self::shelfless_log( "Skipping SKU $sku.", 'info' );
					}

				}
				else {

					self::shelfless_log( "SKU $sku not found.", 'info' );
					self::shelfless_log( '---', 'info' );

				}
				
			}

			self::shelfless_log( "Total lines: $i " , 'info' );		
			self::shelfless_log( "Total products found: $j " , 'info' );	
			self::shelfless_log( "Total products processed: $k " , 'info' );

		}

	}

	/**
	 * Logs the scheduled fulfill orders
	 *
	 * @since		1.0.0
	 */
	public function shelfless_sched_fulfill_orders() {
		
		self::shelfless_log( __CLASS__ . '->' . __FUNCTION__, 'info' );

		$fulfill_status = get_option( $this->plugin_name . '_order_status_fulfill_status' );
		if ( empty( $fulfill_status ) ) return false;
		
		// If this is not set as an option, let's put in
		$offset_days = get_option( $this->plugin_name . '_order_process_from_days_ago' );
		$offset_days = ( ! empty( $offset_days ) && is_numeric( $offset_days ) ? $offset_days : 2 );

		$order_instance = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Order( $this->plugin_name );
		$args = array(
			'limit'     => 50,
			'date_created'	=> '>=' . date("Y-m-d", ( time() - ( $offset_days * DAY_IN_SECONDS ) ) ), // default past days for checking local order updates
			'status'    => array( $fulfill_status ),
			'orderby'   => 'rand',
		);

		if ( get_option( $this->plugin_name . '_order_is_process_export' ) !== '1' ) {
			$wc_country_obj = new WC_Countries();
			$wc_base_country = $wc_country_obj->get_base_country();
			$args['shipping_country'] = $wc_base_country;
		}

		$orders = $order_instance->get_orders( $args );

		if ( $orders ) {

			foreach ( $orders as $order_data ) {

				$order = $order_instance->get_order( $order_data['id'] );

				if ( 'wc-' . $order_data['status'] !== $fulfill_status ) continue;

				$is_fulfillable_all = true;
				$order_items = $order->get_items();

				foreach ( $order_items as $item ) {

					$item_data = $item->get_data();
					$sku = get_post_meta( $item_data['product_id'], '_sku', true );

					self::shelfless_log( $sku, 'info' );
					self::shelfless_log( get_post_meta( $item_data['product_id'], '_' . $this->plugin_name . '_is_fulfill', true ), 'info' );
					self::shelfless_log( $item_data, 'info' );

					$type = WC_Product_Factory::get_product_type( $item_data['product_id'] );
					
					self::shelfless_log( $type, 'info' );
					switch ( $type ) {
						case 'composite' : 
							$composite_children = get_post_meta( $item_data['product_id'], '_composite_data' );
							if ( $composite_children ) {
								foreach ( $composite_children as $child ) {
									if ( get_post_meta( $child['product_id'], '_' . $this->plugin_name . '_is_fulfill', true ) !== 'yes' ) {
										$is_fulfillable_all = false;
										break;
									}
								}
							}
							break;
						case 'grouped' : 
							$grouped_product = wc_get_product( $item_data['product_id'] );
							$grouped_children = $grouped_product->get_children();
							if ( $grouped_products ) {
								foreach ( $grouped_products as $grouped_product_id )  {
									if ( get_post_meta( $grouped_product_id, '_' . $this->plugin_name . '_is_fulfill', true ) !== 'yes' ) {
										$is_fulfillable_all = false;
										break;
									}
								}
							}
							break;
						case 'simple' : 
							self::shelfless_log( 
								array(
									'product_id' 	=> $item_data['product_id'],
									'product_type' 	=> $type,
									'sku' 			=> get_post_meta( $item_data['product_id'], '_sku', true ),
									'is_fulfill' 	=> get_post_meta( $item_data['product_id'], '_' . $this->plugin_name . '_is_fulfill', true ),
								),
							'info' );
							if ( get_post_meta( $item_data['product_id'], '_' . $this->plugin_name . '_is_fulfill', true ) !== 'yes' )
								$is_fulfillable_all = false;
							break;
						case 'variable':
							self::shelfless_log( 
								array(
									'product_id' 	=> $item_data['variation_id'],
									'product_type' 	=> $type,
									'sku' 			=> get_post_meta( $item_data['variation_id'], '_sku', true ),
									'is_fulfill' 	=> get_post_meta( $item_data['variation_id'], '_' . $this->plugin_name . '_is_fulfill', true ),
								),
							'info' );
							if ( get_post_meta( $item_data['variation_id'], '_' . $this->plugin_name . '_is_fulfill', true ) !== 'yes' )
								$is_fulfillable_all = false;
							break;
					}

				}

				if ( ! $is_fulfillable_all ) {
					$order->add_order_note( esc_html__( 'One or more products are not fulfillable by Shelfless. It cannot ship this order.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ), 0 );
					continue;
				}

				$order_encoded = $order_instance->format_order( $order );

				$is_imported = get_post_meta( $order_data['id'], '_' . $this->plugin_name . '_is_order_imported', true );

				if ( ! $is_imported && $order_encoded ) {
					
					$fulfill_order = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Http();
					$fulfillment_received = $fulfill_order->create_fulfillment_request( $order_data['id'], $order_encoded );

					if ( $fulfillment_received ) {

						$fulfilled = json_decode( $fulfillment_received['body'] );

						if ( $fulfillment_received['response']['code'] === 200 ) {

							$fulfill_request_id = $fulfilled->request_id;
							// Add notes and update metas.
							$order->add_order_note( sprintf( esc_html__( 'Order has been SENT for fulfillment with Shelfless. Request ID: %s ', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ), $fulfill_request_id ), 0 );
							update_post_meta( $order_data['id'], '_' . $this->plugin_name . '_is_order_imported', true );
							$order->update_status( 'bri-fulfill-proc' );
							update_post_meta( $order_data['id'], '_' . $this->plugin_name . '_is_order_items_updated', false );
							update_post_meta( $order_data['id'], '_' . $this->plugin_name . '_is_order_updated', false );


						}
						else if ( $fulfillment_received['response']['code'] >= 400 && $fulfillment_received['response']['code'] < 500 ) {

							$message =  $fulfilled->message;
							if ( ! empty( $fulfilled->errors ) ) {
								$message .= $order_instance->format_error_order_comments( $fulfilled->errors );
							}
							$order->add_order_note( sprintf( esc_html__( "Something went wrong when we try to send this order to Shelfless. We will try again in a few. Here\'s what we know on what might have caused it: %s ", 'bring-3pl-shelfless-fulfillment-for-woocommerce' ), $message ), 0 );

						}
						
					}
					else {
						$order->add_order_note( esc_html__( 'Something went wrong when we try to send this order to Shelfless. We will try again in a few.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ), 0 );
					}
					unset( $fulfill_order );

				}

			}

		}
		unset( $order_instance );

	}

	/**
	 * Logs the fetch order updates
	 *
	 * @since		1.0.0
	 */
	public function shelfless_sched_fetch_order_updates() {
		
		self::shelfless_log( __CLASS__ . '->' . __FUNCTION__, 'info' );

		$map_statuses = array(
			'fulfilled'				=> 'bri-fulfilled',
			'backordered'			=> 'bri-backordered',
			'partially_fulfilled'	=> preg_replace( '/wc-/', '', get_option( $this->plugin_name . '_order_status_partially_fulfilled_status' ) ),
			'shipped' 				=> preg_replace( '/wc-/', '', get_option( $this->plugin_name . '_order_status_shipped_status' ) ),
			'cancelled'				=> preg_replace( '/wc-/', '', get_option( $this->plugin_name . '_order_status_cancel_status' ) ),
		);

		// Remove the none status if it is existing in this array.
		foreach ( $map_statuses as $i => $status ) {
			if ( $status === 'bri-none' ) unset( $map_statuses[$i] );
		}

		$statuses = array(
			'wc-bri-fulfill-proc',
			'wc-bri-fulfilled',
			'wc-bri-backordered',
			get_option( $this->plugin_name . '_order_status_fulfill_status', true ),
			get_option( $this->plugin_name . '_order_status_partially_fulfilled_status', true ),
		);

		$statuses = array_unique( $statuses );

		// If this is not set as an option, let's put in
		$offset_days = get_option( $this->plugin_name . '_order_process_from_days_ago' );
		$offset_days = ( ! empty( $offset_days ) && is_numeric( $offset_days ) ? $offset_days : 2 );

		$args = array(
			'limit'     	=> 50,
			'date_created'	=> '>=' . date("Y-m-d", ( time() - ( $offset_days * DAY_IN_SECONDS ) ) ), // default past days for checking local order updates
			'status'    	=> $statuses,
			'orderby'   => 'rand',
		);

		if ( get_option( $this->plugin_name . '_order_is_process_export' ) !== '1' ) {
			$wc_country_obj = new WC_Countries();
			$wc_base_country = $wc_country_obj->get_base_country();
			$args['shipping_country'] = $wc_base_country;
		}
		
		$order_instance = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Order( $this->plugin_name );
		$orders = $order_instance->get_orders( $args );

		if ( $orders ) {
			foreach ( $orders as $order_data ) {
				self::shelfless_log( array( 'order_id' => $order_data['id'], 'order_number' => $order_data['order_number'] ), 'info' );

				$order = $order_instance->get_order( $order_data['id'] );
				$order_number = $order->get_order_number();
				$wc_canonical_status = 'wc-' . $order_data['status'];

				if ( ! in_array( $wc_canonical_status, $statuses ) ) continue;
				
				$is_imported = get_post_meta( $order_data['id'], '_' . $this->plugin_name . '_is_order_imported', true );

				if ( ! $is_imported ) continue;

				$fetch_updates = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Http();
				$fetch_received = $fetch_updates->get_fulfillment_updates( $order_number );

				$status = last_shelfless_order_status_sorted( $fetch_received );

				if ( $status ) {

					$order_items = $order->get_items();
					$items = array();
					
					foreach ( $order_items as $order_item ) {

						$item = $order_item->get_data();
						$sku = get_post_meta( $item['product_id'], '_sku', true );
						
						if ( ! empty( $sku ) )
							$items[$sku] = $item;
						else
							$items[$item['product_id']] = $item;

					}
					
					$fulfill_complete = true;

					$skip_order_statuses = array(
						'bri-fulfilled',
						preg_replace( "/wc-/", '', get_option( $this->plugin_name . '_order_status_shipped_status', true ) ),
						'completed',
					);

					$skip_order_statuses = array_unique( $skip_order_statuses );

					$fulfill_notes = array();
					$ocs_statuses = array(
						1 => 'Received',
						3 => 'Working',
						4 => 'Picked',
						5 => 'Shipped',
						6 => 'Cancelled'
					);

					foreach ( $status->items as $s_item ) {
						
						if ( array_key_exists( $s_item->article_id, $items ) ) {
							
							// if ( in_array( $status->status_code, array( 3, 5 ) ) && ! in_array( $order_data['status'], $skip_order_statuses ) ) {
							// 	$fulfill_notes[] = sprintf( esc_html__( 'Shelfless:  %1$s: %2$s x %3$s', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ), $status->status_text, $s_item->article_id, $s_item->quantity );
							// }

							$items[$s_item->article_id]['quantity_fulfilled'] = $s_item->quantity;

						}

					}

					// Received
					$acknowledged = ( true == get_post_meta( $order_data['id'], '_' . $this->plugin_name . '_is_order_acknowledged', true ) ? true : false );
					if ( $status->status_code === 1 && ! $acknowledged ) {
						update_post_meta( $order_data['id'], '_' . $this->plugin_name . '_is_order_acknowledged', true );
						$order->add_order_note( sprintf( esc_html__( 'Shelfless: Fulfillment Request Acknowledged', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) ), 0 );
					}
					
					// Confirmed/reserved/picked
					if ( in_array( $status->status_code, array( 3, 4 ) )  && ! in_array( $order_data['status'], $skip_order_statuses ) ) {
						
						foreach ( $fulfill_notes as $note ) {
							$order->add_order_note( $note, 0 );
						}
						
						if ( ! $fulfill_complete ) {
							if ( ! empty( $map_statuses['partially_fulfilled'] ) && $order_data['status'] !== $map_statuses['partially_fulfilled'] ) {
								$order->add_order_note( sprintf( esc_html__( 'Shelfless: Partially Fulfilled', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) ), 0 );
								$order->update_status( $map_statuses['partially_fulfilled'] );
							}
						}
						else {
							if ( ! empty( $map_statuses['fulfilled'] ) && $order_data['status'] !== $map_statuses['fulfilled'] ) {
								$order->add_order_note( sprintf( esc_html__( 'Shelfless: Fully Fulfilled', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) ), 0 );
								$order->update_status( $map_statuses['fulfilled'] );
							}
						}

					}

					// Completed, with tracking number
					array_shift( $skip_order_statuses );

					$tracking_url = sanitize_url( 'https://tracking.bring.com/tracking/' );

					if ( $status->status_code === 5 && ! in_array( $order_data['status'], $skip_order_statuses ) ) {
						$tracking = ( ! empty( $status->tracking_number ) ? $status->tracking_number : false );
						if ( ! $fulfill_complete ) {								
							if ( ! empty( $map_statuses['shipped'] ) && $order_data['status'] !== $map_statuses['shipped'] ) {
								$order->add_order_note( 
									sprintf( 
										esc_html__( 'Shelfless: Partially Shipped %s %s ', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ), 
										( $tracking ? ' with tracking number ' : '' ), 
										'<a href="'. $tracking_url . $tracking .'" target="_blank">'. $tracking .'</a>' 
									), 1 
								);
								$order->update_status( $map_statuses['shipped'] );
							}
						}
						else {
							if ( ! empty( $map_statuses['shipped'] ) && $order_data['status'] !== $map_statuses['shipped'] ) {
								$order->add_order_note( 
									sprintf( 
										esc_html__( 'Shelfless: Shipped %s %s ', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ), 
										( $tracking ? ' with tracking number ' : '' ), 
										'<a href="'. $tracking_url . $tracking .'" target="_blank">'. $tracking .'</a>' 
									), 1
								);
								$order->update_status( $map_statuses['shipped'] );
							}
						}
					}

					// Cancelled by warehouse
					if ( $status->status_code ===  6 ) {
						if ( ! empty( $map_statuses['cancelled'] ) && $order_data['status'] !== $map_statuses['cancelled'] ) {
							$order->add_order_note( sprintf( esc_html__( 'Shelfless: Order Cancelled by Warehouse. If this cancellation was not made locally and pushed to Shelfless, it means that this order was cancelled by the warehouse.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) ), 0 );
							$order->update_status( $map_statuses['cancelled'] );
						}
					}

					// Backordered at warehouse
					if ( $status->status_code ===  7 ) {

						if ( ! empty( $map_statuses['backordered'] ) && $order_data['status'] !== $map_statuses['backordered'] ) {
							$order->add_order_note( sprintf( esc_html__( "Backordered at Warehouse. The order cannot be fulfilled at the current time due to insufficient stock for one or more products. The order will be fulfilled automatically when stock is available.", 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) ), 0 );
							$order->update_status( $map_statuses['backordered'] );
						}
					}

					update_post_meta( $order_data['id'], '_' . $this->plugin_name . '_is_order_items_updated', false );
					update_post_meta( $order_data['id'], '_' . $this->plugin_name . '_is_order_updated', false );
					
					if ( array_key_exists( $status->status_code, $ocs_statuses ) )
						update_post_meta( $order_data['id'], '_' . $this->plugin_name . '_last_order_status_msg', $ocs_statuses[$status->status_code] );
					
				}
				unset( $order );
				unset( $fetch_updates );

			}

		}
		unset( $order_instance );

	}

	/**
	 * Logs the push in-store order updates
	 *
	 * @since		1.0.0
	 */
	public function shelfless_sched_push_in_store_order_updates() {

		self::shelfless_log( __CLASS__ . '->' . __FUNCTION__, 'info' );
	
		$bri_statuses = array(
			get_option( $this->plugin_name . '_order_status_fulfill_status' ),
			get_option( $this->plugin_name . '_order_status_cancel_fulfill_status' ),
			'wc-bri-fulfill-proc',
		);

		$wc_statuses = array(
			'wc-processing',
			'wc-cancelled',
		);

		$statuses = array_merge( $bri_statuses, $wc_statuses );
		$statuses = array_unique( $statuses );

		$cancel_status = preg_replace( '/wc-/', '', get_option( $this->plugin_name . '_order_status_cancel_fulfill_status' ) );
		$cancel_status = ( $cancel_status !== 'bri-none' || empty( $cancel_status ) ? $cancel_status : false );

		// If this is not set as an option, let's put in
		$offset_days = get_option( $this->plugin_name . '_order_process_from_days_ago' );
		$offset_days = ( ! empty( $offset_days ) && is_numeric( $offset_days ) ? $offset_days : 2 );

		$order_instance = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Order( $this->plugin_name );
		$args = array(
			'limit'     	=> 50,
			'date_modified'	=> '>=' . date("Y-m-d", ( time() - ( $offset_days * DAY_IN_SECONDS ) ) ), // Past orders for checking local order updates
			'status'    	=> $statuses,
			'orderby'   	=> 'rand',
		);

		if ( get_option( $this->plugin_name . '_order_is_process_export' ) !== '1' ) {
			$wc_country_obj = new WC_Countries();
			$wc_base_country = $wc_country_obj->get_base_country();
			$args['shipping_country'] = $wc_base_country;
		}
		
		$orders = $order_instance->get_orders( $args );

		if ( $orders ) {

			foreach ( $orders as $order_data ) {

				$order = $order_instance->get_order( $order_data['id'] );
				$order_number = $order->get_order_number();
				$status = $order->get_status();
				
				$wc_canonical_status = 'wc-' . $order_data['status'];

				if ( ! in_array( $wc_canonical_status, $statuses ) ) continue;

				$is_imported = get_post_meta( $order_data['id'], '_' . $this->plugin_name . '_is_order_imported', true );
				$is_items_updated = get_post_meta( $order_data['id'], '_' . $this->plugin_name . '_is_order_items_updated', true );
				$is_order_updated = get_post_meta( $order_data['id'], '_' . $this->plugin_name . '_is_order_updated', true );
				$cancelled = ( true == get_post_meta( $order_data['id'], '_' . $this->plugin_name . '_is_order_cancelled', true ) ? true : false );

				if ( $is_imported && $status == $cancel_status &&  ! $cancelled ) {

					$cancel_order = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Http();
					$cancel_received = $cancel_order->cancel_fulfillment_request( $order_number );

					if ( $cancel_received ) {

						$cancel = json_decode( $cancel_received['body'] );

						if ( $cancel_received['response']['code'] === 200) {

							$cancel_request_id = $cancel->request_id;
							$order->add_order_note( sprintf( esc_html__( 'Order cancellation detected in the platform. A CANCEL FULFILLMENT attempt has been SENT to Shelfless for possible cancellation of fulfillment. If the order items have been picked or inventory has been reserved, the request may not be processed. Request ID: %s ', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ), $cancel_request_id  ), 0 );
						
						}
						else if ( $cancel_received['response']['code'] >= 400 && $cancel_received['response']['code'] < 500 ) {
							
							$message = $cancel->message;
							$order->add_order_note( sprintf( esc_html__( 'A previous cancellation request for this order has been received and recorded already by Shelfless. Additional information: %s ' , 'bring-3pl-shelfless-fulfillment-for-woocommerce' ), $message ) , 0 );
						}
						
						update_post_meta( $order_data['id'], '_' . $this->plugin_name . '_is_order_items_updated', false );
						update_post_meta( $order_data['id'], '_' . $this->plugin_name . '_is_order_updated', false );

					}
					else {
						$order->add_order_note( esc_html__( 'Order cancellation detected in the platform, however, cancellation is not possible with Shelfless at this time.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ), 0 );
					}

					update_post_meta( $order_data['id'], '_' . $this->plugin_name . '_is_order_cancelled', true );

					unset( $cancel_order );

				}
					
				if ( $is_imported && in_array( $status, array( 'processing', 'bri-fulfill-proc' ) ) &&  ( $is_order_updated || $is_items_updated ) ) {

					$order_encoded = $order_instance->format_order( $order );
					
					$update_received = false;

					if ( $order_encoded ) {
						$update_order = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Http();
						$update_received = $update_order->update_fulfillment_request( $order_data['id'], $order_encoded );
					}

					if ( $update_received ) {

						$update = json_decode( $update_received['body'] );

						if ( $update_received['response']['code'] === 200 ) {

							$update_request_id = $update->request_id;

							$order->add_order_note( sprintf( esc_html__( 'Order update detected in the platform. An UPDATE attempt has been SENT to Shelfless for possible fulfillment update. If the order items have been picked or inventory has been reserved, the update request may not be processed. Request ID: %s ', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ), $update_request_id  ), 0 );

						}
						else if ( $update_received['response']['code'] >= 422 && $update_received['response']['code'] < 500) {
							
							$message = $update->message;
                            if ( ! empty( $update->errors ) ) {
                                $message .= $order_instance->format_error_order_comments( $update->errors );
                            }
							$order->add_order_note( sprintf( esc_html__( 'Order update not possible  at this time: %s ', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ), $message ) , 0 );

						}

						update_post_meta( $order_data['id'], '_' . $this->plugin_name . '_is_order_items_updated', false );
						update_post_meta( $order_data['id'], '_' . $this->plugin_name . '_is_order_updated', false );

					}
					else {
						$order->add_order_note( esc_html__( 'Order update detected in the platform, however, updating the order information in Shelfless is not possible  at this time.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ), 0 );
					}

					unset( $update_order );

				}

			}

		}
		unset( $order_instance );

	}

	/**
	 * Scheduled updates to run via cron job
	 *
	 * @since		1.0.0
	 */
	public function shelfless_schedule_action_updates() {
		
		if ( ! wp_verify_nonce( $_REQUEST['nonce_scheduled_actions'], 'nonce_' . $this->plugin_name . '_scheduled_actions_mybring' ) 
			|| ( wp_verify_nonce( $_REQUEST['nonce_scheduled_actions'], 'nonce_' . $this->plugin_name . '_scheduled_actions_mybring' )
			&& $_REQUEST['action'] !== 'bring_shelfless_schedule_action_updates' ) ) {

			add_bring_shelfless_notice(
				sprintf ( esc_html__( 'There was an error processing scheduled actions, and they could not be saved as a result. %s Please try again or contact Shelfless if this error keeps on happening.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ), '<br/>' ),
				'error',
				true,
				'scheduled-action-updates'
			);

			$url = esc_url( sanitize_url( $_SERVER['HTTP_REFERER'] ) );
			exit ( wp_safe_redirect( urldecode( $url ) ) );

		}

		$post = wc_clean( $_POST );
		$wizard = ( validate_bring_shelfless_text_field( sanitize_text_field ( $post['initiator'] ) ) === 'wizard' ? true : false );

		$actions = array(
			$this->plugin_name . '_sched_fetch_inventory_updates',
			$this->plugin_name . '_sched_send_orders',
			$this->plugin_name . '_sched_fetch_order_updates',
		);
		$sched = array( 'hourly', 'weekly', 'daily', 'disable' );
		$error = false;
		$msg = '';

		foreach ( $actions as $action ) {
			if ( ! empty( validate_bring_shelfless_text_field( sanitize_text_field( $post[$action] ) ) ) ) {
				if ( in_array( $post[$action], $sched ) ) {
					update_option( $action, $post[$action], 'yes' );
				} 
				else { 
					// Multiple spaces will be replaced with single space.
					$output = preg_replace( '/\s+/', ' ', $post[$action . '_specific_time'] );

					if ( ! empty( $specific_time = validate_bring_shelfless_text_field( sanitize_text_field( $output ) ) ) ) {
						
						$cron_pattern = '/(@(annually|yearly|monthly|weekly|daily|hourly|reboot))|(@every (\d+(ns|us|µs|ms|s|m|h))+)|((((\d+,)+\d+|(\d+(\/|-)\d+)|\d+|\*) ?){5,7})/is';
						if ( preg_match( $cron_pattern, $specific_time, $matches, PREG_OFFSET_CAPTURE, 0 ) ) { 
							update_option( $action, $specific_time, 'yes' );
						}
						else {
							if ( ! $wizard ) {
								add_bring_shelfless_notice(
									esc_html__( 'The custom cron value for one of the scheduled actions is invalid. Please correct the issue and try again.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
									'error',
									true,
									'scheduled-action-cron-value'
								);
							}
							else {
								$msg = esc_html__( 'The custom cron value for one of the scheduled actions is invalid. Please correct the issue and try again.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' );
							}
							$error = true;
						}
					}
				}
			}
		}
		
		if ( ! $error ) {
			if ( ! $wizard ) {
				add_bring_shelfless_notice(
					esc_html__( 'Scheduled actions saved.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
					'success',
					true,
					'scheduled-action-saved'
				);
			}
			else {
				$msg = esc_html__( 'Scheduled actions saved.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' );
			}

			if ( function_exists( 'as_unschedule_all_actions' ) ) {
				$args = array();
				$group_name = 'shelfless-by-bring';
				as_unschedule_all_actions( 'shelfless_fetch_inventory_updates', $args, $group_name );
				as_unschedule_all_actions( 'shelfless_fulfill_orders', $args, $group_name );
				as_unschedule_all_actions( 'shelfless_fetch_order_updates', $args, $group_name );

			}
		}

		if ( ! $wizard ) {
			$url = esc_url( sanitize_url( $_SERVER['HTTP_REFERER'] ) );
			exit (wp_safe_redirect( urldecode( $url ) ) );
		}
		else {
			wp_die(
				json_encode(
					array(
						'error' => $error,
						'msg'	=> $msg
					)
				)
			);
		}

	}

	/**
	 * Sets the flag for the updated order items
	 *
	 * @since		1.0.0
	 * @param		int			$orderid			Specified order ID
	 * @param		array		$items				items/products included in the order exclusively
	 */
	public function shelfless_set_flag_for_updated_order_items( $orderid, $items ) {

		$order_instance = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Order( $this->plugin_name );
		$order = $order_instance->get_order( $orderid );

		if ( in_array( $order->get_status(), array( 'processing', 'bri-fulfill-proc' ) ) )
			update_post_meta( $orderid, '_' . $this->plugin_name . '_is_order_items_updated', true );
		
		
		self::shelfless_log( __CLASS__ . '->' . __FUNCTION__ );

	}

	/**
	 * Sets the flag for the updated orders
	 *
	 * @since		1.0.0
	 * @param		int			$orderid			Specified order ID
	 * @param		array		$post				Post details/info
	 * @param		bool		$update				Flag to determine if the post is updated
	 */
	public function shelfless_set_flag_for_updated_orders( $orderid, $post, $update ) {

		if ( ! $update ) return;

		$order_instance = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Order( $this->plugin_name );
		$order = $order_instance->get_order( $orderid );

		if ( in_array( $order->get_status(), array( 'processing', 'bri-fulfill-proc' ) ) )
			update_post_meta( $orderid, '_' . $this->plugin_name . '_is_order_updated', true );
		
		self::shelfless_log( __CLASS__ . '->' . __FUNCTION__ );
		
	}

	/**
	 * Update quantity of product with respect to the meta field in group or variable product
	 *
	 * @since		1.0.0
	 * @param		string    	$meta   		meta field to update
	 * @param		array		$product		Array of product details
	 */
	private function shelfless_grouped_variable_products_update( $meta, $product ) {

		$product_id = $product['id'];
		$product_type = $product['product_type'];

		// determine the product type
		if ( $product_type === 'grouped' ) {
			// get the grouped product details
			$grouped_product = wc_get_product( $product_id );
			// get the linked products from the grouped product details
			$linked_products = $grouped_product->get_children();

			if ( ! empty( $linked_products ) ) {
				foreach( $linked_products as $key => $linked_product_id ) {
					// purposely force to update the grouped product manage stock at product level
					if ( $meta === '_manage_stock' ) {
						if ( get_post_meta( $linked_product_id, '_manage_stock' ) !== 'yes' ) {
							update_post_meta( $linked_product_id,'_manage_stock', 'yes' );
						}

						if ( get_post_meta( $linked_product_id, '_' . $this->plugin_name . '_is_fulfill' ) !== 'yes' ) {
							update_post_meta( $linked_product_id, '_' . $this->plugin_name . '_is_fulfill', 'yes' );
						}
					}

					if ( $meta === '_' . $this->plugin_name . '_is_article_sync' ) {
						if ( get_post_meta( $linked_product_id, '_' . $this->plugin_name . '_is_article_sync' ) !== 'yes' ) {
							update_post_meta( $linked_product_id, '_' . $this->plugin_name . '_is_article_sync', 'yes' );
						}
					}
				}
			}
		}

		if ( $product_type === 'variable' ) {
			// purposely force to update the manage stock at product variable level
			if ( $meta === '_manage_stock' ) {
				update_post_meta( $product_id, "_manage_stock", "yes" );
			}

			// get the product variations that are enabled and published
			$variations = get_posts( array(
				'post_parent'   => $product_id,
				'posts_per_page'=> -1,
				'post_type'   => 'product_variation',
				'post_status'  => 'publish'
			) );

			if ( ! empty( $variations ) ) {
				foreach( $variations as $key => $variation ) {
					// purposely force to update the variation manage stock at product variation level
					if ( $meta === '_manage_stock' ) {
						if ( get_post_meta( $variation->ID, '_manage_stock' ) !== 'yes' ) {
							update_post_meta( $variation->ID, '_manage_stock', 'yes' );
						}

						if ( get_post_meta( $variation->ID, '_' . $this->plugin_name . '_is_fulfill' ) !== 'yes' ) {
							update_post_meta( $variation->ID, '_' . $this->plugin_name . '_is_fulfill', 'yes' );
						}
					}

					if ( $meta === '_' . $this->plugin_name . '_is_article_sync' ) {
						if ( get_post_meta( $variation->ID, '_' . $this->plugin_name . '_is_article_sync' ) !== 'yes' ) {
							update_post_meta( $variation->ID, '_' . $this->plugin_name . '_is_article_sync', 'yes' );
						}
					}
				}
			}
		}

	}

	/**
	 * Add Order settings section
	 *
	 * @since		1.0.0
	 */
	public function shelfless_order_settings_section() {

		add_settings_section( $this->plugin_name . '_order_settings_general', '', array( $this, 'shelfless_order_settings_content' ), $this->plugin_name . '_order_settings_general' );
		
	}

	/**
	 * Generate and display Order settings content
	 *
	 * @since		1.0.0
	 */
	public function shelfless_order_settings_content() {

		generate_bring_shelfless_order_settings_content();

	}
	
	/**
	 * Add Order settings section fields that maps the content section
	 *
	 * @since		1.0.0
	 */
	public function shelfless_order_settings_status_maps_section() {

		add_settings_section( $this->plugin_name . '_order_status_maps_settings', '', array( $this, 'shelfless_order_settings_status_maps_content' ), $this->plugin_name . '_order_status_maps_settings' );

	}

	/**
	 * Generate and display Order settings status that maps content
	 *
	 * @since		1.0.0
	 */
	public function shelfless_order_settings_status_maps_content() { 

		generate_bring_shelfless_order_settings_status_maps_content();

	}

	/**
	 * Add Order settings section fields that maps the content section
	 *
	 * @since		1.0.0
	 */
	public function shelfless_order_settings_shipping_maps_section() {

		add_settings_section( $this->plugin_name . '_order_shipping_maps_settings', '', array( $this, 'shelfless_order_settings_shipping_maps_content' ), $this->plugin_name . '_order_shipping_maps_settings' );

	}

	/**
	 * Generate and display Order settings shipping that maps content
	 *
	 * @since		1.0.0
	 */
	public function shelfless_order_settings_shipping_maps_content() {

		generate_bring_shelfless_order_settings_shipping_maps_content();

	}

	/**
	 * Register Order settings section fields
	 *
	 * @since		1.0.0
	 */
	public function shelfless_order_settings_section_fields() { 

		// This part identifies to hide some fields during setup wizard
		// then add class attribute - "hidden" to the field setting
		$wizard = false;
		if ( get_option( $this->plugin_name . '_shelfless_api_setup_is_complete' ) == 0 ) {
			$wizard = true;
		}

		$bring_statuses = array(
			'wc-bri-req-fulfill' 		=> __( 'Request Fulfillment by Shelfless', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
			'wc-bri-part-fulfill'		=> __( 'Partially Fulfilled by Shelfless', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
			'wc-bri-shipped'			=> __( 'Shipped by Shelfless', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
			'wc-bri-cancelled'			=> __( 'Cancelled by Shelfless', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
		);

		$bring_enabled_statuses = get_option( $this->plugin_name . '_order_enabled_bring_statuses' );
		
		$fields = array(
			array(
				'fid' 			=> $this->plugin_name . '_order_process_from_days_ago',
				'label' 		=> esc_html__( 'Include orders in the last number of days for processing', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'section' 		=> $this->plugin_name . '_order_settings_general',
				'type' 			=> 'text',
				'options' 		=> false,
				'placeholder' 	=> esc_html__( '5', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'tooltip' 		=> esc_html__( 'The span of days you set determines which orders will be included in the processing. If an order is changed it will also be included in the processing if they are within the chosen range. The default range is set to 2 days.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'supplemental' 	=> esc_html__( 'Include Orders In the Last Number of Days for Processing', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'default' 		=> '5',
				'label_for'		=> $this->plugin_name . '_order_process_from_days_ago',
				'class'			=> ( $wizard ? 'hidden' : '' ),
			),
			array(
				'fid' 			=> $this->plugin_name . '_order_is_process_export',
				'label' 		=> esc_html__( 'Process international orders (exports)', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'section' 		=> $this->plugin_name . '_order_settings_general',
				'type' 			=> 'checkbox',
				'options' 		=> array(
					array(
						'text'		=> esc_html__( 'Let Shelfless process and ship international orders.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
						'value'		=> '1',
						'default' 		=> '0',
					),
				),				
				'placeholder'	=> '',
				'tooltip' 		=> esc_html__( 'Let Shelfless process and ship international orders (orders exported from default country set in WooCommerce settings). Exporting should have a corresponding agreement with Bring. Default: "no".', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'ttip_w_html'	=> true,
				'default'		=> '0',
				'label_for'		=> $this->plugin_name . '_order_is_process_export',
				'class'			=> ( $wizard ? 'hidden' : '' ),
			),
			array(
				'fid' 			=> $this->plugin_name . '_order_add_bring_statuses',
				'label' 		=> esc_html__( 'Use Shelfless custom statuses', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'section' 		=> $this->plugin_name . '_order_settings_general',
				'type' 			=> 'checkbox',
				'options' 		=> array(
					array(
						'text'		=> esc_html__( 'Use Shelfless custom statuses in WooCommerce. These statuses can be used to identify which orders will be fulfilled and managed by Shelfless.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
						'value'		=> '1',
						'default' 		=> '1',
					),
				),				
				'placeholder'	=> '',
				'tooltip' 		=> esc_html__( 'By enabling custom Shelfless statuses, additional statuses will be added to WooCommerce. You can use the Shelfless statuses to tag orders to let you identity which orders will be fulfilled by Shelfless.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'ttip_w_html'	=> true,
				'default'		=> '1',
				'label_for'		=> $this->plugin_name . '_order_add_brings_tatuses',
				'class'			=> ( $wizard ? 'hidden' : '' ),
			),
			array(
				'fid' 			=> $this->plugin_name . '_order_enabled_bring_statuses',
				'label' 		=> esc_html__( 'Select custom statuses', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'section' 		=> $this->plugin_name . '_order_settings_general',
				'type' 			=> 'multiselect', 
				'options' 		=> $bring_statuses,	
				'placeholder'	=> '',
				'tooltip' 		=> esc_html__( 'Select which custom Shelfless statuses you would like to use. The additional statuses will be added to WooCommerce. You can use the Shelfless statuses to tag orders to let you identity which orders will be fulfilled by Shelfless. Can only  be added if "use Shelfless Custom statuses" has been marked with "yes".', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'ttip_w_html'	=> true,
				'default'		=> $bring_enabled_statuses,
				'label_for'		=> $this->plugin_name . '_order_enabled_bring_statuses',
				'class'			=> ( $wizard ? 'hidden' : '' ),
			),
			array(
				'fid' 			=> $this->plugin_name . '_order_is_show_notif_cancelled',
				'label' 		=> esc_html__( 'Admin notification when an order is cancelled from warehouse', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'section' 		=> $this->plugin_name . '_order_settings_general',
				'type' 			=> 'checkbox',
				'options' 		=> array(
					array(
						'text'		=> esc_html__( 'Allow Shelfless to show admin notifications if an order is cancelled by Shelfless.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
						'value'		=> '1',
						'default' 	=> '1',
					),
				),				
				'placeholder'	=> '',
				'tooltip' 		=> esc_html__( 'If an order is cancelled from the warehouse get notified so the order can be handled. Default: No.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'ttip_w_html'	=> true,
				'default'		=> '1',
				'label_for'		=> $this->plugin_name . '_order_is_show_notif_cancelled',
				'class'			=> ( $wizard ? 'hidden' : '' ),
			),
		);

		$this->shelfless_register_fields( $fields, $this->plugin_name . '_order_settings_general' );

	}

	/**
	 * Register order setting statuses that maps section fields
	 *
	 * @since		1.0.0
	 */
	public function shelfless_order_settings_status_maps_section_fields() {

		$wc_order_statuses = wc_get_order_statuses();
		$no_status = array( 'wc-bri-none' => 'None' );
		$wc_order_statuses = array_merge( $no_status, $wc_order_statuses );

		$fields = array(
			array(
				'fid' 			=> $this->plugin_name . '_order_status_fulfill_status',
				'label' 		=> esc_html__( 'WooCommerce order status to start fulfilling an order', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'section' 		=> $this->plugin_name . '_order_status_maps_settings',
				'type' 			=> 'select',
				'options' 		=> $wc_order_statuses,				
				'placeholder'	=> '',
				'tooltip' 		=> esc_html__( 'Select a WooCommerce order status that triggers Shelfless to fulfill an order. Default: "Processing".', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'ttip_w_html'	=> true,
				'default'		=> 'wc-processing',
				'label_for'		=> $this->plugin_name . '_order_status_fulfill_status',
			),
			array(
				'fid' 			=> $this->plugin_name . '_order_status_cancel_fulfill_status',
				'label' 		=> esc_html__( 'WooCommerce order status to cancel a fulfillment', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'section' 		=> $this->plugin_name . '_order_status_maps_settings',
				'type' 			=> 'select',
				'options' 		=> $wc_order_statuses,				
				'placeholder'	=> '',
				'tooltip' 		=> esc_html__( 'Select a WooCommerce order status that triggers Shelfless to <b>cancel</b> fulfillment of an order. Default: "Cancelled".', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'ttip_w_html'	=> true,
				'default'		=> 'wc-cancelled',
				'label_for'		=> $this->plugin_name . '_order_status_cancel_fulfill_status',
			),
			array(
				'fid' 			=> $this->plugin_name . '_order_status_partially_fulfilled_status',
				'label' 		=> esc_html__( 'Order status to move to when an order is partially fulfilled by Shelfless', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'section' 		=> $this->plugin_name . '_order_status_maps_settings',
				'type' 			=> 'select',
				'options' 		=> $wc_order_statuses,				
				'placeholder'	=> '',
				'tooltip' 		=> esc_html__( 'Select a WooCommerce order status to move orders to when Shelfless cannot fully fulfill an order, and can only ship part of it. Default: "Processing".', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'ttip_w_html'	=> true,
				'default'		=> 'wc-processing',
				'label_for'		=> $this->plugin_name . '_order_status_partially_fulfilled_status',
			),
			array(
				'fid' 			=> $this->plugin_name . '_order_status_shipped_status',
				'label' 		=> esc_html__( 'Order status to move to when a tracking number has been created and order is shipped by Shelfless', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'section' 		=> $this->plugin_name . '_order_status_maps_settings',
				'type' 			=> 'select',
				'options' 		=> $wc_order_statuses,				
				'placeholder'	=> '',
				'tooltip' 		=> esc_html__( 'Select a WooCommerce order status to move orders to when Shelfless has generated a tracking number and the order has been marked shipped at the warehouse. Default: "Completed".', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'ttip_w_html'	=> true,
				'default'		=> 'wc-completed',
				'label_for'		=> $this->plugin_name . '_order_status_shipped_status',
			),
			array(
				'fid' 			=> $this->plugin_name . '_order_status_cancel_status',
				'label' 		=> esc_html__( 'Order status to move to when an order is marked cancelled by Shelfless', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'section' 		=> $this->plugin_name . '_order_status_maps_settings',
				'type' 			=> 'select',
				'options' 		=> $wc_order_statuses,				
				'placeholder'	=> '',
				'tooltip' 		=> esc_html__( 'Select a WooCommerce order status to move orders to when Shelfless receives that an order is cancelled from warehouse. Default: "Cancelled".', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'ttip_w_html'	=> true,
				'default'		=> 'wc-failed',
				'label_for'		=> $this->plugin_name . '_order_status_cancel_status',
			),
		);

		$this->shelfless_register_fields( $fields, $this->plugin_name . '_order_status_maps_settings' );
	
	}

	/**
	 * Register statuses for mapping
	 *
	 * @since		1.0.0
	 */
	public function shelfless_register_bring_statuses() {

		register_post_status( 'wc-bri-req-fulfill', array(
			'label'                     => 'Request Fulfillment by Shelfless',
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Request Fulfillment by Shelfless <span class="count">( %s )</span>', 'Request Fulfillment by Shelfless <span class="count">( %s )</span>', 'bring-3pl-shelfless-fulfillment-for-woocommerce' )
		) );

		register_post_status( 'wc-bri-fulfill-proc', array(
			'label'                     => 'Fulfillment Processed by Shelfless',
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Fulfillment Processed by Shelfless <span class="count">( %s )</span>', 'Fulfillment Processed by Shelfless <span class="count">( %s )</span>', 'bring-3pl-shelfless-fulfillment-for-woocommerce' )
		) );

		register_post_status( 'wc-bri-backordered', array(
			'label'                     => 'Backordered on Shelfless',
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Backordered on Shelfless <span class="count">( %s )</span>', 'Backordered on Shelfless <span class="count">( %s )</span>', 'bring-3pl-shelfless-fulfillment-for-woocommerce' )
		) );
		
		register_post_status( 'wc-bri-fulfilled', array(
			'label'                     => 'Fulfilled by Shelfless',
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Fulfilled by Shelfless <span class="count">( %s )</span>', 'Fulfilled by Shelfless <span class="count">( %s )</span>', 'bring-3pl-shelfless-fulfillment-for-woocommerce' )
		) );

		register_post_status( 'wc-bri-part-fulfill', array(
			'label'                     => 'Partially Fulfilled by Shelfless',
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Partially Fulfilled by Shelfless <span class="count">( %s )</span>', 'Partially Fulfilled by Shelfless <span class="count">( %s )</span>', 'bring-3pl-shelfless-fulfillment-for-woocommerce' )
		) );
		
		register_post_status( 'wc-bri-shipped', array(
			'label'                     => 'Shipped by Shelfless',
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Shipped by Shelfless <span class="count">( %s )</span>', 'Shipped by Shelfless <span class="count">( %s )</span>', 'bring-3pl-shelfless-fulfillment-for-woocommerce' )
		) );

		register_post_status( 'wc-bri-cancelled', array(
			'label'                     => 'Cancelled by Shelfless',
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Cancelled by Shelfless <span class="count">( %s )</span>', 'Cancelled by Shelfless <span class="count">( %s )</span>', 'bring-3pl-shelfless-fulfillment-for-woocommerce' )
		) );

	}

	/**
	 * Set custom statuses for Bring Shelfless
	 *
	 * @since		1.0.0
	 * @param		array    	$order_statuses   		Order statuses by Shelfless
	 */
	public function shelfless_custom_bring_statuses( $order_statuses ) {

		$bring_statuses = array(
			'wc-bri-req-fulfill' 		=> __( 'Request Fulfillment by Shelfless', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
			'wc-bri-fulfill-proc' 		=> __( 'Fulfillment Processed by Shelfless', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
			'wc-bri-backordered'		=> __( 'Backordered on Shelfless', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
			'wc-bri-fulfilled'			=> __( 'Fulfilled by Shelfless', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
			'wc-bri-part-fulfill'		=> __( 'Partially Fulfilled by Shelfless', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
			'wc-bri-shipped'			=> __( 'Shipped by Shelfless', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
			'wc-bri-cancelled'			=> __( 'Cancelled by Shelfless', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
		);

		$is_add_bring_statuses = ( get_option( $this->plugin_name . '_order_add_bring_statuses' ) == 1 ? true : false );

		if ( $is_add_bring_statuses ) {
			$bring_enabled_statuses = get_option( $this->plugin_name . '_order_enabled_bring_statuses' );
			if ( ! empty ( $bring_enabled_statuses ) ) {
				foreach ( $bring_enabled_statuses as $key => $status ) {
					$order_statuses[$status] = _x( $bring_statuses[$status], 'Order status', 'bring-3pl-shelfless-fulfillment-for-woocommerce' );
				}
			}
		}

		// Below are the default Shelfless statuses.
		$order_statuses['wc-bri-backordered']	= _x( $bring_statuses['wc-bri-backordered'], 'Order status', 'bring-3pl-shelfless-fulfillment-for-woocommerce' );
		$order_statuses['wc-bri-fulfill-proc'] 	= _x( $bring_statuses['wc-bri-fulfill-proc'], 'Order status', 'bring-3pl-shelfless-fulfillment-for-woocommerce' );
		$order_statuses['wc-bri-fulfilled']		= _x( $bring_statuses['wc-bri-fulfilled'], 'Order status', 'bring-3pl-shelfless-fulfillment-for-woocommerce' );
		
		return $order_statuses;

	}

	/**
	 * Set custom statuses for WooCommerce
	 *
	 * @since		1.0.0
	 * @param		string    	$value   		New order status value
	 * @param		string 		$old_value		Current order status value
	 * @param		string		$option			Order status field
	 */
	public function shelfless_set_custom_statuses_options( $value, $old_value, $option ) {

		// Check if the new value excludes statuses that are currently used by mappings.
		// If it is, send a notification. This is the best thing we can do. -> Harvey
		$statuses = array(
			get_option( $this->plugin_name . '_order_status_fulfill_status' ),
			get_option( $this->plugin_name . '_order_status_cancel_fulfill_status' ),
			get_option( $this->plugin_name . '_order_status_partially_fulfilled_status' ),
			get_option( $this->plugin_name . '_order_status_shipped_status' ),
			get_option( $this->plugin_name . '_order_status_cancel_status' ),
		);

		$is_status_used = false;

		foreach ( $old_value as $status ) {
			if ( in_array( $status, $statuses ) ) $is_status_used = true;
		}

		if ( $is_status_used ) {
			add_bring_shelfless_notice(
				esc_html__( 'One or more custom statuses set by Shelfless is currently used as a status. If you made changes to the selected custom statuses, make sure you update your Status Mappings.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'warning',
				true,
				'status-mappings'
			);
		}
		
		return $value;
		
	}

	/**
	 * Check for partially fulfilled orders in WooCommerce by Bring
	 *
	 * @since		1.0.0
	 */
	/*
	private function shelfless_check_for_partially_fullfilled_orders() {

		$show_admin_notif_partially_fulfilled = get_option( $this->plugin_name . '_order_is_show_notif_partial_shipment' );

		// show admin notice for partially fulfilled/shipped orders, is set and true
		if ( $show_admin_notif_partially_fulfilled == 1 ) {

			// should return a string 'wc-bri-part-fulfill'
			$fulfill_status = get_option( $this->plugin_name . '_order_status_partially_fulfilled_status' );
			
			// return the number of days before to include the order checks
			$process_from_days_ago = get_option( $this->plugin_name . '_order_process_from_days_ago' );

			// if there is custom status for partially fulfilled is being setup, then the following will be processed
			if ( ! empty( $fulfill_status ) ) {
				$orders = $this->process_get_orders( $fulfill_status, $process_from_days_ago );

				if ( ! empty( $orders ) ) {
					$cnt = count( $orders );
					$an_some = ( $cnt > 1 ? 'Some' : 'An' );
					$is_are = ( $cnt > 1 ? 'are' : 'is' );
					$txt_orders = ( $cnt > 1 ? 'orders' : 'order' );

					$url = esc_url( get_admin_url() . 'edit.php?post_type=shop_order' );

					add_bring_shelfless_notice(
						sprintf( esc_html__( $an_some .' '. $txt_orders .' '. $is_are .' reported %s by Shelfless. %s to correct the issue.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ), '<strong>partially fulfilled</strong>', '<br/><a href="'. urldecode( $url ) .'">Check orders page now</a>' ),
						'info',
						true,
						'partially-fulfilled'
					);
				}
			}
		}
	}
	*/

	/**
	 * Check for cancelled orders in WooCommerce to fulfill by Bring
	 *
	 * @since		1.0.0
	 */
	private function shelfless_check_for_cancelled_orders() {

		$show_admin_notif_cancelled = get_option( $this->plugin_name . '_order_is_show_notif_cancelled' );

		// show admin notice for cancelled orders, is set and true
		if ( $show_admin_notif_cancelled == 1 ) {
			
			// should return a string 'wc-bri-cancelled'
			$fulfill_status = get_option( $this->plugin_name . '_order_status_cancel_status' );

			// return the number of days before to include the order checks
			$process_from_days_ago = get_option( $this->plugin_name . '_order_process_from_days_ago' );

			// if there is custom status for cancelled is being setup, then the following will be processed
			if ( ! empty( $fulfill_status ) ) {
				$orders = $this->process_get_orders( $fulfill_status, $process_from_days_ago );

				if ( ! empty( $orders ) ) {
					$cnt = count( $orders );
					$an_some = ( $cnt > 1 ? 'Some' : 'An' );
					$is_are = ( $cnt > 1 ? 'are' : 'is' );
					$txt_orders = ( $cnt > 1 ? 'orders' : 'order' );

					$url = esc_url( get_admin_url() . 'edit.php?post_type=shop_order' );

					add_bring_shelfless_notice(
						sprintf( esc_html__( $an_some .' '. $txt_orders .' '. $is_are .' reported %s by Shelfless. %s to correct the issue.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ), '<strong>cancelled</strong>', '<br/><a href="'. urldecode( $url ) .'">Check orders page now</a>' ),
						'info',
						true,
						'cancelled'
					);
				}
			}
		}
	}

	/**
	 * Get orders by status, partially fulfilled or cancelled
	 * 
	 * @since		1.0.0
	 * @param		string		$status		Order status
	 * @param		string		$days		Number of days
	 */
	private function process_get_orders( $status, $days ) {

		if ( ! $status )
			return false;

		$order_instance = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Order( $this->plugin_name );

		$args = array(
			'limit'     => -1,
			'status'    => array( $status ),
			'orderby'   => 'rand',
			'order'     => 'desc',
			'date_query' => array(
				array(
					'after' => date('Y-m-d', strtotime("-{$days} days")),
					'before' => date('Y-m-d', strtotime('today')),
					'inclusive' => true,
				)
			)
		);

		$orders = $order_instance->get_orders( $args );

		unset( $order_instance );

		return $orders;

	}

	/**
	 * Register order setting shipping that maps section fields
	 *
	 * @since		1.0.0
	 */
	public function shelfless_order_settings_shipping_maps_section_fields() { 

		$bring_services = array(
			'3584' 			=> array( 'group' => 'Bring', 'name' => esc_html__( 'Pakke i postkassen', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) ),
			'3570'			=> array( 'group' => 'Bring', 'name' => esc_html__( 'Pakke i postkassen med RFID', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) ),
			'1736' 			=> array( 'group' => 'Bring', 'name' => esc_html__( 'På Døren', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) ),
			'5800' 			=> array( 'group' => 'Bring', 'name' => esc_html__( 'Pakke til hentested', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) ),
			'1202' 			=> array( 'group' => 'Bring', 'name' => esc_html__( 'Klimanøytral Servicepakke', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) ),
			'5600' 			=> array( 'group' => 'Bring', 'name' => esc_html__( 'Pakke levert hjem', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) ),
			'5600~2012'		=> array( 'group' => 'Bring', 'name' => esc_html__( 'Pakke levert hjem - Samme dag', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) ),
			'5000' 			=> array( 'group' => 'Bring', 'name' => esc_html__( 'Pakke til bedrift', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) ),
			'4850' 			=> array( 'group' => 'Bring', 'name' => esc_html__( 'Ekspress neste dag', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) ),
			'1000' 			=> array( 'group' => 'Bring', 'name' => esc_html__( 'Bedriftspakke', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) ),
			'1002' 			=> array( 'group' => 'Bring', 'name' => esc_html__( 'Bedriftspakke Ekspress - Over natten', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) ),

			'BMNOPIPA' 		=> array( 'group' => 'Bring (nShift)', 'name' => esc_html__( 'Pakke i Postkassen A-post', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) ),
			
			'ASPO' 			=> array( 'group' => 'DHL', 'name' => esc_html__( 'DHL ServicePoint', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) ),
			'AEX' 			=> array( 'group' => 'DHL', 'name' => esc_html__( 'DHL Paket', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) ),
			'APCS' 			=> array( 'group' => 'DHL', 'name' => esc_html__( 'DHL Parcel Connect (Ombud)', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) ),
			
			'SERVICEGUIDE'	=> array( 'group' => 'DreamPack', 'name' => esc_html__( 'DreamPack Service Guide (Dream Logistics)', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) ),
			
			'FDXIPP' 		=> array( 'group' => 'FedEx', 'name' => esc_html__( 'FedEx International Priority', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) ),
			'FDXIEP' 		=> array( 'group' => 'FedEx', 'name' => esc_html__( 'FedEx International Economy', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) ),

			'PAE' 			=> array( 'group' => 'PostNord', 'name' => esc_html__( 'PostNord Varubrev - 1:a klass', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) ),

			'UPSSTDP' 		=> array( 'group' => 'UPS', 'name' => esc_html__( 'UPS Standard', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) ),
			'UPSSAVP' 		=> array( 'group' => 'UPS', 'name' => esc_html__( 'UPS Express Saver', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) ),
		);

		$fields = array();

		$zone_ids = array_keys( array('') + WC_Shipping_Zones::get_zones() );
		foreach ( $zone_ids as $zone_id ) {

			$shipping_zone = new WC_Shipping_Zone($zone_id);
			$shipping_methods = $shipping_zone->get_shipping_methods( true, 'values' );

			foreach ( $shipping_methods as $instance_id => $shipping_method ) {

				if  (  in_array( $shipping_method->id, array( 'flat_rate', 'free_shipping') ) ) {
					$field = array(
						'fid' 			=> $this->plugin_name . '_order_shipping_map_instance_' . $instance_id,
						'label' 		=> $shipping_method->title,
						'section' 		=> $this->plugin_name . '_order_shipping_maps_settings',
						'type' 			=> 'select',
						'options' 		=> $bring_services,
						'placeholder'	=> '',
						'tooltip' 		=> esc_html__( 'Select a Bring shipping service for this shipping method. Default: "Pakke i postkassen".', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
						'ttip_w_html'	=> true,
						'default'		=> '3584',
						'label_for'		=> $this->plugin_name . '_order_shipping_map_instance_' . $instance_id,
					);
					$fields[] = $field;
				}
			}
		}
		$this->shelfless_register_fields( $fields, $this->plugin_name . '_order_shipping_maps_settings' );

	}

	/**
	 * Register fields to correct sections
	 *
	 * @since		1.0.0
	 * @param		array		$fields		Array of fields that need to be registered in settings
	 * @param		string		$section	The section field to register in settings
	 */
	private function shelfless_register_fields( $fields, $section ) {

		foreach( $fields as $field ) {
			add_settings_field( $field['fid'], $field['label'], 'get_bring_shelfless_fields', $section, $field['section'], $field );
			if ( $field['type'] !== 'checkbox' || ( $field['type'] === 'checkbox' && count( $field['options'] ) < 2 ) ) {
				register_setting( $section, $field['fid'] );
			}
			else {
				if ( count( $field['options'] ) > 1 ) {
					foreach ( $field['options'] as $key => $option ) {
						register_setting( $section, $field['fid'] . '_' . $key );
					}
				}
			}
		}

	}

	/**
	 * Get method to inquire an article SKU by ID from the OCS
	 *
	 * @since		1.1.3
	 * @param		int			$postid		The post ID 
	 */
	private function get_article_sync_sku_by_id( $postid ) {

		$id = intval( $postid );

		$inventory_instance = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Inventory( $this->plugin_name );

		$article = $inventory_instance->get_product_by_id( $id );

		$param = '?sku='.$article['sku'];

		return $param;
	}

	/**
	 * Get method to inquire an article SKU by ID from the OCS
	 * this hook pass 3 arguments: 
	 * $post_ID, $post (object) and $update that is a boolean (true or false) that is true when you perform an update, 
	 * in fact this hook is fired also when a post is saved for first time.
	 *
	 * @since		1.1.3
	 * @param		int				$postid		The post ID 
	 * @param		object			$post		Object post data 
	 * @param		boolean			$update		The update flag
	 */
	public function shelfless_article_sync_create_update_on_product_save( $postid, $post, $update ) {
		self::shelfless_log( __CLASS__ . '->' . __FUNCTION__ );

		// General inventory setting
		$is_general_article_sync = get_option( $this->plugin_name . '_inventory_is_sync_products' );

		// Get product level setting
		$is_article_sync = '_' . $this->plugin_name . '_is_article_sync';
		$is_product_article_sync = get_post_meta( $postid, $is_article_sync, true);
		$is_product_article_sync  = ( ! empty( $is_product_article_sync ) ? $is_product_article_sync : ( isset( $_POST[$is_article_sync] ) ? validate_bring_shelfless_text_field( sanitize_text_field( $_POST[$is_article_sync] ) ) : '0' ) );

		// check if the global article flag is enabled and
		// if the artice sync is enabled on product level
		if ( $is_general_article_sync == 1 && ( $is_product_article_sync == 1 | $is_product_article_sync === 'yes' ) ) { 
			// this to prevent twice insert by save_post_product
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			} else {
				// _bring_3pl_shelfless_fulfillment_for_woocommerce_article_sync_operation values
				// 0 - no operation, 1 - create, 2 - update
				// check if new post, so insert
				if ( ( strpos( wp_get_raw_referer(), 'post-new' ) > 0 ) && ( get_post_status( $postid ) === 'publish' ) ) {
					// create
					update_post_meta( $postid, '_' . $this->plugin_name . '_article_sync_operation', 1 );
				}
				else {
					// update
					update_post_meta( $postid, '_' . $this->plugin_name . '_article_sync_operation', 2 );
				}
			}
		} 

		// shippable with Shelfess by Bring
		$is_shippable_field = '_' . $this->plugin_name . '_is_shippable';
		$is_shippable_value = 0;
		if ( isset( $_POST[$is_shippable_field] ) ) {
			$is_shippable_value = ( sanitize_text_field( $_POST[$is_shippable_field] ) === 'yes' ? '1' : '0' );
		}
		update_post_meta( $postid, $is_shippable_field, $is_shippable_value );

	}

	/**
	 * Update meta value for article sync via products grid view
	 *
	 * @since		1.1.3
	 * @access		public
	 */
	public function shelfless_create_update_products_is_article_sync_on_product_grid() { 

		header( 'Content-Type: application/json' );

		if ( ! wp_verify_nonce( $_REQUEST['mybring_shelfless_nonce'], 'nonce_' . $this->plugin_name . '_inventory_settings_pull_inv_grid_mybring' ) 
		|| ( wp_verify_nonce( $_REQUEST['mybring_shelfless_nonce'], 'nonce_' . $this->plugin_name . '_inventory_settings_pull_inv_grid_mybring' )
		&& $_REQUEST['action'] !== 'bring_shelfless_update_prod_is_article_sync' ) ) { 
			wp_die( json_encode( array( 'error' => true, 'message' => 'Shelfless nonce' ) ) );
		}

		$post = wc_clean( $_POST );
		if ( empty( $post['productids'] ) ) {
			wp_die( json_encode( array( 'error' => true ) ) );
		} 

		$product_instance = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Inventory( $this->plugin_name );
		
		if ( true === $product_instance->set_product_is_article_sync( $post['productids'] ) ) {
			unset( $product_instance ); 

			// last parameter refers to the attribute class to be added in the element
			$type = $post['product_type'];
			if ( $post['action_source'] == 'product_grid' ) { 
				$html_msg = show_notice_via_ajax( 'The update was successful.', $type, 'product-level-notice' );
			} else { 
				if ( $type == 'simple' || empty($type) ) {
					$html_msg = show_notice_via_ajax( 'The update was successful.', $type, 'product-level-notice' );
				} else {
					$html_msg = show_notice_via_ajax( 'The update was successful and was also applied to its ', $type, 'product-level-notice' );
				}
			}

			wp_die( json_encode( 
				array( 
					'error' => false, 
					'show' => true, 
					'msg'	=> esc_html__( $html_msg, 'bring-3pl-shelfless-fulfillment-for-woocommerce' )
				), 
			) );
		}
		else {
			wp_die( json_encode(
				array( 
					'error' => true,
					'msg'	=> esc_html__( 'Some products cannot be updated or are not found.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				), 
			) );
		}

	}

	/**
	 * Scheduled cron on updating meta data _article_sync_operation
	 *
	 * @since		1.2.0
	 * @access		public
	 */
	public function shelfless_sched_push_product_changes() { 

		self::shelfless_log( __CLASS__ . '->' . __FUNCTION__ );

		$inventory = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Inventory( $this->plugin_name );

        $products = $inventory->get_products(
            array(
                'limit'         => 50,
                'status'        => 'publish',
                'meta_key'      => '_' . $this->plugin_name . '_article_sync_operation',
                'meta_compare'  => '>',
                'meta_value'    => '0',
				'orderby'		=> 'rand'
            )
        );

		if ( $products ) {

			$http_instance = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Http( $this->plugin_name );
			
			foreach( $products as $product ) {
				
				$id = $product['id'];
				$type = WC_Product_Factory::get_product_type( $id );

				// get post meta article sync per product level and check if checked or not
				$sync_product_meta = get_post_meta( $id, '_' . $this->plugin_name . '_is_article_sync', true );
				$is_sync_product = ( ( $sync_product_meta === 'yes' || $sync_product_meta == 1 ) ? true : false );
				
				// check if sync product setting in product level is setup and enabled
				// if not enabled, then don't create/update article to OCS
				if ( $is_sync_product ) {

					$response = array();

					if ( $type === 'simple' ) {

						$response = $http_instance->get_article_sync_create_update( $id, 'update' );

					} 
					else if ( $type === 'variable' ) { 
						
						$response = $http_instance->get_article_sync_create_update( $id, 'update' );

						// get the product variations that are enabled and published
						$variations = get_posts( array(
							'post_parent'    => $id,
							'posts_per_page' => -1,
							'post_type'      => 'product_variation',
							'post_status'    => 'publish'
						) );
						
						if ( ! empty( $variations ) ) {

							foreach( $variations as $key => $variation ) { 

								$variant_id = $variation->ID;
								$obj = $inventory->get_product_by_id( $variant_id );
								$variant = $obj['product_obj']->get_data();
								
								$response = $http_instance->get_article_sync_create_update( $variant_id, 'update' );
								
								// update the meta data of the variant - article_sync_operation = 0, in order not to include in the next round of article sync
								if ( $response['code'] == 200 ) {
									update_post_meta( $variant_id, '_' . $this->plugin_name . '_article_sync_operation', 0 );
								}
							}

						}
					}

					// update the meta data of the product/parent - article_sync_operation = 0, in order not to include in the next round of article sync
					if ( ! empty( $response ) && $response['code'] == 200 ) {
						update_post_meta( $id, '_' . $this->plugin_name . '_article_sync_operation', 0 ); 
					}

				} else {
					// update the meta data of the product/parent - article_sync_operation = 0, in order not to include in the next round of article sync
					update_post_meta( $id, '_' . $this->plugin_name . '_article_sync_operation', 0 );
				}
				
			}
			
		}

	}

	/**
	 * JSON Payload format for create article
	 *
	 * @since		1.2.0
	 * @access		private
	 * @param		$product_id		Unique product ID for article create
	 * @return		$response		JSON of endpoint response
	 */
	public function shelfless_article_sync_create_data_format( $product_id ) {
		
		self::shelfless_log( __CLASS__ . '->' . __FUNCTION__ );

		$product = wc_get_product( $product_id );

		// get customer number
		$customer_id = get_option( $this->plugin_name . '_mybring_customer_id' );

		// get basic unit of measure
		$uom_code = get_option( $this->plugin_name . '_inventory_basic_unit', 'PCE' );

		// get shippable flag
		$is_shippable_product = ( get_post_meta( $product_id, '_' . $this->plugin_name . '_is_shippable', true ) == 1 ? true : false );

		$uom = array();
		$base_uom = array( 'uom_code' => $uom_code, 'uom_name' => 'Pieces', 'unit_quantity' => 1, 'shippable' => $is_shippable_product );
		$uom[] = $base_uom;

		// get default country of origin, if in any case the COO is left blank, CN will be the default
		$origin_country = get_option( $this->plugin_name . '_inventory_default_country_of_origin' );
		$origin_country = ( ! empty( $origin_country ) ? $origin_country : 'CN' );

		// attributes
		$attributes = array();
		$attributes_obj = $product->get_attributes();
		if ( ! empty( $attributes_obj ) ) { 
			foreach( $attributes_obj as $attribute_key => $attribute ) { 
				if ( ( ! is_object( $attribute ) ) && ( ! is_array( $attribute ) ) ) {
					$attr_name = ucwords( str_replace('pa_', '', $attribute_key) );
					$attributes[] = array( 'attribute_name' => $attr_name, 'attribute_value' => $attribute );
				} else { 
					if ( ( ! is_object( $attribute ) ) && ( is_array( $attribute ) ) ) { 
						$attr_name = ucwords( str_replace('pa_', '', $attribute_key) );
						foreach( $attribute as $key => $val ) { 
							$attributes[] = array( 'attribute_name' => $attr_name, 'attribute_value' => $val ); 
						}
					} else { 
						$name = $attribute->get_name();
						$article_attrs = wc_get_product_terms( $product->get_id(), $name, array( 'fields' => 'names' ) );
						$attr_name = ucwords( str_replace('pa_', '', $name) ); 
						
						if ( ! empty( $article_attrs ) ) {
							foreach( $article_attrs as $attr_key => $attr_value ) { 
								$attributes[] = array( 'attribute_name' => $attr_name, 'attribute_value' => $attr_value );
							}
						}
					}
				}
			}
		}

		// for variant product having parent
		$sku_group = '';
		if ( ! empty ( $product->parent_id ) ) {
			$parent_obj = wc_get_product( $product->parent_id );
			$parent = $parent_obj->get_data();
			$sku_group = $parent['sku'];
		}

		// product categories
		$categories = strip_tags( $product->get_categories() );
		
		// product gallery images
		$product_images = array();
		$product_image_gallery_id = ( isset( $_POST['product_image_gallery'] ) ? wc_clean( $_POST['product_image_gallery'] ) : '' );

		// main product image
		// include to the product image gallery
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_id() ), 'single-post-thumbnail' );
		if ( ! empty( $image ) ) { 
			$image_name = basename( $image[0] );
			$product_images[] = array( 'photo_filename' => $image_name, 'photo_url' => $image[0] );
		}
		// product image gallery
		if ( ! empty( $product_image_gallery_id ) ) {
			$gallery_ids = explode(',', $product_image_gallery_id);
			foreach( $gallery_ids as $gallery_key => $img_id ) {
				$image = wp_get_attachment_image_src( $img_id, 'full' );
				$image_name = basename( get_attached_file( $img_id ) );
				$product_images[] = array( 'photo_filename' => $image_name, 'photo_url' => $image[0] );
			}
		}
		
		// product details/information
		$data = $product->get_data();

		$stock_status = ( $data['stock_status'] == 'instock' ? 'available' : 'not available');

		$article_data = array (
			'meta' => array (
				'version' => '1',
			),
			'article_master' => array (
				'customer_number' 		=> $customer_id,
				'article_number'		=> $data['sku'],
				'article_name' 			=> process_article_name( $data['name'] ),
				'base_uom' 				=> $base_uom,
				'default_unit_quantity'	=> 1,
				'default_selling_price'	=> floatval( $data['regular_price'] ),
				'country_of_origin'		=> $origin_country,
				'description' 			=> ( ! empty ( $data['description'] ) ? process_article_description( $data['description'] ) : '' ),
				'short_description' 	=> ( ! empty ( $data['short_description'] ) ? process_article_description( $data['short_description'] ) : '' ),
				'sku_group'				=> $sku_group,
				'uom' 					=> $uom,
				'status'				=> 'available' // OCS expects this to be 'available' and 'not available' which translates to enabled and disabled, respectively
			),
		);
		
		if ( ! empty( $categories ) ) $article_data['article_master']['category'] = $categories;
		if ( ! empty( $attributes ) ) $article_data['article_master']['attributes'] = $attributes;
		if ( ! empty( $product_images ) ) $article_data['article_master']['product_photos'] = $product_images;
		if ( ! empty( $data['purchase_note'] ) ) $article_data['article_master']['remark'] = process_article_description( $data['purchase_note'] );

		// push sale price to array $article_data if not empty
		if ( ( floatval( $data['sale_price'] ) > 0 ) && ( ! empty( $data['date_on_sale_from'] ) ) ) { 
			
			$date_on_sale_from = array_values( (array) $data['date_on_sale_from'] );
			$date_on_sale_to = array_values( (array) $data['date_on_sale_to'] );

			$article_data['article_master']['selling_price'] = array ( 
				array (
					'selling_price'		=> floatval( $data['sale_price'] ),
					'effective_on'		=> date( "Y-m-d", strtotime( $date_on_sale_from[1] ) ),
					'effective_until'	=> date( "Y-m-d", strtotime( $date_on_sale_to[1] ) ),
					'current_price'		=> ( floatval( $data['sale_price'] ) > 0 ? true : false ),
				),
			);
			
			array_push( $article_data, $arr_selling_price );
		}

		// push hs codes to array $article_data if not empty
		$harmonized_code = get_post_meta( $product_id, '_' . $this->plugin_name . '_customs_hs_code', true );
		$country_code = get_post_meta( $product_id, '_' . $this->plugin_name . '_customs_manufacture_country', true );

		if ( ( ! empty( $country_code ) ) && ( ! empty( $harmonized_code ) ) ) { 
			$article_data['article_master']['hs_codes'] = array( 
				array(
					'country_code'		=> $country_code,
					'harmonized_code'	=> $harmonized_code
				)
			);
		}

		$article_json = json_encode( $article_data, JSON_PRETTY_PRINT );

		return $article_json;

	}

	/**
	 * JSON Payload format for update article
	 *
	 * @since		1.2.0
	 * @access		private
	 * @param		$product_id		Unique product ID for article update
	 * @return		$response		Array of endpoint response
	 */
	public function shelfless_article_sync_update_data_format( $product_id ) { 
		self::shelfless_log( __CLASS__ . '->' . __FUNCTION__ );

		$product = wc_get_product( $product_id );
		$data = $product->get_data();

		$article_data = array (
			'meta' => array (
				'version' => '1',
			),
			'article_master' => array (
				'article_number' 	=> $data['sku'],
				'article_name' 		=> process_article_name( $data['name'] ),
				'description' 		=> process_article_description( $data['description'] )
			),
		);
		
		// push hs codes to array $article_data if not empty
		$harmonized_code = get_post_meta( $product_id, '_' . $this->plugin_name . '_customs_hs_code', true );
		$country_code = get_post_meta( $product_id, '_' . $this->plugin_name . '_customs_manufacture_country', true );

		if ( ( ! empty( $country_code ) ) && ( ! empty( $harmonized_code ) ) ) { 
			$article_data['article_master']['hs_codes'] = array( 
				array(
					'country_code'		=> $country_code,
					'harmonized_code'	=> $harmonized_code
				)
			);
		}
		
		$article_json = json_encode( $article_data, JSON_PRETTY_PRINT );

		self::shelfless_log( $article_json );

		return $article_json;
	}

	/**
	 * Return the plugin name for JS scripts
	 *
	 * @since		1.2.0
	 * @return		$this->plugin_name		The plugi name
	 */
	public function shelfless_return_plugin_name_to_js_script() {
		header( 'Content-Type: application/json' );
		
		if ( $_REQUEST['action'] !== 'bring_shelfless_return_plugin_name_to_js_script' ) { 
			wp_die( json_encode( array( 'error' => true, 'message' => 'Incorrect method called.' ) ) );
		}

		wp_die( json_encode( $this->plugin_name ) );
		
	}

	/**
	 * Set the value-added services 
	 *
	 * @since		1.2.0
	 * @param		string    	$value   		New vas code value
	 * @param		string 		$old_value		Current vas code value
	 * @param		string		$option			Vas code option field
	 */
	public function shelfless_set_vas_codes_options( $value, $old_value, $option ) { 
		
		$vas_codes = array();

		$vas_codes_options = $this->plugin_name . '_order_settings_value_added_services_codes';

		$post = wc_clean( $_POST );

		// the expression will force to skip here and go to else for the ajax driven in setup wizard
		if ( ! empty( $post[$vas_codes_options] ) || ! empty( $value ) ) { 
			// normal state of updating in admin dashboard
			if ( isset( $post[$vas_codes_options] ) )
				$vas_codes = $post[$vas_codes_options];

			if ( is_array( $value ) ) : 
				$vas_codes = $value;
			else : 
				// push the $value to the first index
				array_unshift( $vas_codes, $value );
			endif;
		}

		return $vas_codes;
		
	}

	/**
	 * Setup the Shippable option with Shelfless
	 *
	 * @since		1.2.0
	 */
	public function shelfless_shippable() {
		global $post;

		$is_shippable_product = get_post_meta( $post->ID, '_' . $this->plugin_name . '_is_shippable', true );

		// The _is_fulfill product meta should not rely now on plugin Manage Stock setting.
		$args = array(
			'id'			=> '_' . $this->plugin_name . '_is_shippable',
			'label'			=> esc_html__( 'Ship with Shelfless', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
			'class'			=> 'shelfless_is_shippable checkbox'. ' '. $post->ID,
			'desc_tip'		=> true,
			'description'	=> esc_html__( 'Check this item to make it shippable with Shelfless', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
			'value'			=> ( ( $is_shippable_product == '' ) ? 'yes' : ( $is_shippable_product == 1 ? 'yes' : '' ) ),
		);
		woocommerce_wp_checkbox( $args );

	}
	
	/**
	 * Display link for myBring Preview to order page
	 *
	 * @since		1.2.1
	 * @param 		object 		$order			Order details in object type
	 */
	public function shelfless_linkify_to_mybring( $order ) {

		$customer_id = get_option( $this->plugin_name . '_mybring_customer_id', '0' );
		$order_id = $order->get_id();
		$order_number = $order->get_order_number();
		$is_imported = get_post_meta( $order_id, '_' . $this->plugin_name . '_is_order_imported', true );
		$is_acknowledged = get_post_meta( $order_id, '_' . $this->plugin_name . '_is_order_acknowledged', true );
		$last_shelfless_status = get_post_meta( $order_id, '_' . $this->plugin_name . '_last_order_status_msg', true );

		if ( $is_imported && $is_acknowledged ) {
			print_mybring_link( $customer_id, $order_number, $last_shelfless_status );
		}

	}

	/**
	 * Display link for myBring Preview to order page
	 *
	 * @since		1.2.1
	 * @param 		array 		$order_data		Array of order data
	 * @param 		object 		$order			Order details in object type
	 * @return		array		$order_data
	 */
	public function shelfless_linkify_to_mybring_preview_data( $order_data, $order ) {

		$customer_id = get_option( $this->plugin_name . '_mybring_customer_id', '0' );
		$order_id = $order->get_id();
		$order_number = $order->get_order_number();
		$is_imported = get_post_meta( $order_id, '_' . $this->plugin_name . '_is_order_imported', true );
		$is_acknowledged = get_post_meta( $order_id, '_' . $this->plugin_name . '_is_order_acknowledged', true );
		$last_shelfless_status = get_post_meta( $order_id, '_' . $this->plugin_name . '_last_order_status_msg', true );

		if ( $is_imported && $is_acknowledged ) {
			$order_data['mybring_data'] = print_mybring_link_preview( $customer_id, $order_number, $last_shelfless_status);
		}

		return $order_data;

	}

	/**
	 * Display link for myBring Preview to order page
	 *
	 * @since		1.2.1
	 */
	public function shelfless_linkify_to_mybring_preview() {

		echo '
			<# if ( data.mybring_data ) { #>
				{{{ data.mybring_data }}}
			<# } #>
		';

	}

	/**
	 * Add Dream Logistics settings section
	 *
	 * @since		1.2.1
	 */
	public function shelfless_dream_logistics_settings_section() {

		add_settings_section( $this->plugin_name . '_dream_logistics_settings', '', array( $this, 'shelfless_dream_logistics_settings_content' ), $this->plugin_name . '_dream_logistics_settings' );
		
	}

	/**
	 * Generate and display Dream Logistics settings content
	 *
	 * @since		1.0.0
	 */
	public function shelfless_dream_logistics_settings_content() {

		generate_bring_shelfless_dream_logistics_settings_content();

	}

	/**
	 * Generates Shelfless Dream Logistics Settings field registrations.
	 *
	 * @since    1.2.1
	 */
	public function shelfless_dream_logistics_settings_section_fields() {
		
		$fields = array(
			array(
				'fid' 			=> $this->plugin_name . '_dream_logistics_use_of_warehouse',
				'label' 		=> esc_html__( 'Use Dream Logistics', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'section' 		=> $this->plugin_name . '_dream_logistics_settings',
				'type' 			=> 'checkbox',
				'options' 		=> array(
					array(
						'text'		=> esc_html__( 'Use Dream Logistics 3PL solutions in your fulfillment.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
						'value'		=> '1',
						'default' 	=> '0',
					),
				),				
				'placeholder'	=> '',
				'tooltip' 		=> esc_html__( 'When enabled, you can set up Dream Logistics for your order fulfillment. Default: "no".', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'ttip_w_html'	=> true,
				'default'		=> '1',
				'label_for'		=> $this->plugin_name . '_dream_logistics_use_of_warehouse',
				'class'			=> 'dream_logistics_warehouse_field',
			),
			array(
				'fid' 			=> $this->plugin_name . '_dream_logistics_webshop_id',
				'label' 		=> esc_html__( 'Web Shop ID', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'section' 		=> $this->plugin_name . '_dream_logistics_settings',
				'type' 			=> 'text',
				'placeholder' 	=> esc_html__( '', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'tooltip' 		=> esc_html__( 'If you have one webshop leave the field blank. If you have more than one webshop please enter Web Shop ID for this webshop.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'supplemental' 	=> esc_html__( 'Web Shop ID', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'default' 		=> '',
				'label_for'		=> $this->plugin_name . '_dream_logistics_webshop_id',
				'class'			=> 'dream_logistics_wrapper',
			)
		);

		$this->shelfless_register_fields( $fields, $this->plugin_name . '_dream_logistics_settings' );

	}

	/**
	 * Add scheduled actions section fields that maps the content section
	 *
	 * @since		1.2.1
	 */
	public function shelfless_scheduled_actions_maps_section() {

		add_settings_section( $this->plugin_name . '_scheduled_actions_mybring', '', array( $this, 'shelfless_scheduled_actions_maps_content' ), $this->plugin_name . '_scheduled_actions_mybring' );

	}

	/**
	 * Generate and display scheduled actions that maps content
	 *
	 * @since		1.2.1
	 */
	public function shelfless_scheduled_actions_maps_content() { 

		generate_bring_shelfless_scheduled_actions_maps_content();

	}

	/**
	 * Displays wizard to save the all data setup for Dream Logistics
	 *
	 * @since    	1.2.1
	 */
	public function shelfless_wizard_save_dream_logistics() {

		header( 'Content-Type: application/json' );

		if ( ! wp_verify_nonce( $_REQUEST['mybring_shelfless_nonce'], 'nonce_' . $this->plugin_name . '_dream_logistics_settings_mybring' ) 
		|| ( wp_verify_nonce( $_REQUEST['mybring_shelfless_nonce'], 'nonce_' . $this->plugin_name . '_dream_logistics_settings_mybring' )
		&& $_REQUEST['action'] !== 'bring_shelfless_wizard_save_dream_logistics' ) ) {
			wp_die( json_encode( array( 'error' => true ) ) );
		}

		$post = wc_clean( $_POST );

		foreach ( $post as $key => $value ) { 
			if ( preg_match( '/^shelfless_/', $key ) ) {
				if ( in_array( $value, array( 'true', 'false' ) ) ) {
					$value = ( $value === 'true' ? 1 : 0 );
				}

				$temp_key = preg_replace( '/^shelfless_/', '', $key );

				// when saving 1 or more data from a multiple select field
				if ( is_array($value) ) {
					foreach( $value as $k => $v ) {
						$temp_value[$k] = validate_bring_shelfless_text_field( sanitize_text_field( $v ) );
					}
					$post[$this->plugin_name . '_' . $temp_key] = $temp_value;
				} else {
					$post[$this->plugin_name . '_' . $temp_key] = validate_bring_shelfless_text_field( sanitize_text_field( $value ) );
				}
				
				unset( $post[$key] );
			}
			else {
				unset( $post[$key] );
			}
		}
		
		foreach ( $post as $k => $val ) { 
			
			update_option( $k, $val, 'yes' );
		}

		wp_die( json_encode( array( 'error' => false, 'data' => $post ) ) );

	}

	/**
	 * Generates Shelfless Product Inventory settings section.
	 *
	 * @since    1.2.1
	 */
	public function shelfless_inventory_settings_products_sections() {

		add_settings_section( $this->plugin_name . '_inventory_settings_products', '', array( $this, 'shelfless_inventory_settings_products_section_content' ), $this->plugin_name . '_inventory_settings_products' );

	}

	/**
	 * Generates Shelfless Product Inventory settings section content.
	 *
	 * @since    1.2.1
	 */
	public function shelfless_inventory_settings_products_section_content() {

		generate_bring_shelfless_inventory_settings_products_section_content();

	}

	/**
	 * Generates Shelfless Product Inventory Settings field registrations.
	 *
	 * @since    1.2.1
	 */
	public function shelfless_inventory_settings_products_section_fields() { 

		// This part identifies to hide some fields during setup wizard
		// then add class attribute - "hidden" to the field setting
		$wizard = false;
		if ( get_option( $this->plugin_name . '_shelfless_api_setup_is_complete' ) == 0 ) {
			$wizard = true;
		}

		$wc_currencies = get_woocommerce_currencies();
		$currencies = array();
		foreach ($wc_currencies as $cur => $wc_currency) {
			$currencies[$cur] = "$cur - " . $wc_currency;
		}
		$wc_country_obj = new WC_Countries();
		$wc_countries = $wc_country_obj->__get('countries');
		unset( $wc_country_obj );
	
		$fields = array(
			array(
				'fid' 			=> $this->plugin_name . '_inventory_is_sync_products',
				'label' 		=> esc_html__( 'Synchronize products', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'section' 		=> $this->plugin_name . '_inventory_settings_products',
				'type' 			=> 'checkbox',
				'options' 		=> array(
					array(
						'text'		=> esc_html__( 'Allow WooCommerce to synchronize product creations and updates to Shelfless', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
						'value'		=> '1',
						'default' 	=> '0',
					),
				),				
				'placeholder'	=> '',
				'tooltip' 		=> esc_html__( 'If <b>Synchronize Products</b> is enabled, it allows product creations and product updates to be sent to Shelfless, for all products. If you want to exclude specific products from being synced, you can disable product synchronization at product level later. Default: "Yes".', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'ttip_w_html'	=> true,
				'default'		=> '0',
				'label_for'		=> $this->plugin_name . '_inventory_is_sync_products',
			),
			array(
				'fid' 			=> $this->plugin_name . '_customs_settings_separator_customs_settings',
				'label' 		=> '',
				'section' 		=> $this->plugin_name . '_inventory_settings_products',
				'type' 			=> 'separator',
				'options' 		=> array(
					'heading'	=> esc_html__( 'Customs Settings', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
					'text'		=> esc_html__( 'This applies for orders to other countries (exports). If only selling domestically, you can leave these settings as is.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				),				
				'placeholder'	=> '',
				'tooltip' 		=> '',
				'ttip_w_html'	=> false,
				'default'		=> '0',
				'label_for'		=> '',
				'class'			=> ( $wizard ? 'hidden' : '' ),
			),
			array(
				'fid' 			=> $this->plugin_name . '_inventory_is_use_customs_field',
				'label' 		=> esc_html__( 'Add Customs fields', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'section' 		=> $this->plugin_name . '_inventory_settings_products',
				'type' 			=> 'checkbox',
				'options' 		=> array(
					array(
						'text'		=> esc_html__( 'Allow Shelfless to install Customs fields named "Harmonized Systems Code" and "Country of Origin" ', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
						'value'		=> '1',
						'default' 	=> '0',
					),
				),				
				'placeholder'	=> '',
				'tooltip' 		=> esc_html__( 'This field is usually utilized for Customs Commercial Invoices for international orders wherein seller has the option to include Customs information in the inventory. Default: "no".', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'ttip_w_html'	=> true,
				'default'		=> '0',
				'label_for'		=> $this->plugin_name . '_inventory_is_use_customs_field',
				'class'			=> ( $wizard ? 'hidden' : '' ),
			),
			array(
				'fid' 			=> $this->plugin_name . '_inventory_default_country_of_origin',
				'label' 		=> esc_html__( 'Default country of origin', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'section' 		=> $this->plugin_name . '_inventory_settings_products',
				'type' 			=> 'select',
				'options' 		=> $wc_countries,			
				'placeholder'	=> '',
				'tooltip' 		=> esc_html__( 'This field is usually utilized for Customs Commercial Invoices for international orders wherein seller has the option to set the default Country of Origin if an item has not been set with it. This will not be used if the field above (<b>Add Customs Fields</b>) is not checked. Default: CN - China.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'ttip_w_html'	=> true,
				'default'		=> 'CN',
				'label_for'		=> $this->plugin_name . '_inventory_default_country_of_origin',
				'class'			=> ( $wizard ? 'hidden' : '' ),
			),
			array(
				'fid' 			=> $this->plugin_name . '_inventory_is_use_cost_price_field',
				'label' 		=> esc_html__( 'Use cost price field', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'section' 		=> $this->plugin_name . '_inventory_settings_products',
				'type' 			=> 'checkbox',
				'options' 		=> array(
					array(
						'text'		=> esc_html__( 'Allow Shelfless to install a custom field named "Cost Price"', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
						'value'		=> '1',
						'default' 		=> '0',
					),
				),				
				'placeholder'	=> '',
				'tooltip' 		=> esc_html__( 'This field is usually utilized for Customs Commercial Invoices for international orders wherein seller has the option to use cost price as the declared price for an item. Default: "no".', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'ttip_w_html'	=> true,
				'default'		=> '',
				'label_for'		=> $this->plugin_name . '_inventory_is_use_use_cost_price_field',
				'class'			=> ( $wizard ? 'hidden' : '' ),
			),
			array(
				'fid' 			=> $this->plugin_name . '_inventory_cost_price_currency',
				'label' 		=> esc_html__( 'Cost price currency', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'section' 		=> $this->plugin_name . '_inventory_settings_products',
				'type' 			=> 'select',
				'options' 		=> $currencies,			
				'placeholder'	=> '',
				'tooltip' 		=> esc_html__( 'This field is usually utilized for Customs Commercial Invoices for international orders wherein seller has the option to use cost price as the declared price for an item. Default: Global WooCommerce Currency.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'ttip_w_html'	=> true,
				'default'		=> get_woocommerce_currency(),
				'label_for'		=> $this->plugin_name . '_inventory_cost_price_currency',
				'class'			=> ( $wizard ? 'hidden' : '' ),
			),
			array(
				'fid' 			=> $this->plugin_name . '_inventory_basic_unit',
				'label' 		=> esc_html__( 'Basic inventory unit', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'section' 		=> $this->plugin_name . '_inventory_settings_products',
				'type' 			=> 'select',
				'options' 		=> array(
					'PCE' 		=> 'Piece (PCE)',
				),			
				'placeholder'	=> '',
				'tooltip' 		=> esc_html__( 'Determines the basic inventory unit when countin quantities of inventory. Default: "PCS".', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'ttip_w_html'	=> true,
				'default'		=> 'PCE',
				'label_for'		=> $this->plugin_name . '_inventory_basic_unit',
				'class'			=> ( $wizard ? 'hidden' : '' ),
			),
		);

		$this->shelfless_register_fields( $fields, $this->plugin_name . '_inventory_settings_products' );

	}

	/**
	 * Calls Shelfless shipping settings page.
	 *
	 * @since    1.2.1
	 */
	public function shelfless_shipping_settings_page() {

		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/views/bring-3pl-admin-shipping-settings.php';

	}

	/**
	 * Generates Shelfless Shipping settings section.
	 *
	 * @since    1.2.1
	 */
	public function shelfless_shipping_settings_fallback_sections() {

		add_settings_section( $this->plugin_name . '_shipping_settings_fallback', '', array( $this, 'shelfless_shipping_settings_fallback_section_content' ), $this->plugin_name . '_shipping_settings_fallback' );

	}

	/**
	 * Generates Shelfless Shipping settings section content.
	 *
	 * @since    1.2.1
	 */
	public function shelfless_shipping_settings_fallback_section_content() {

		generate_bring_shelfless_shipping_settings_fallback_section_content();

	}

	/**
	 * Register Shipping settings - fallback carriers tab section fields
	 *
	 * @since		1.0.0 
	 */
	public function shelfless_shipping_settings_fallback_section_fields() { 

		// This part identifies to hide some fields during setup wizard
		// then add class attribute - "hidden" to the field setting
		$wizard = false;
		if ( get_option( $this->plugin_name . '_shelfless_api_setup_is_complete' ) == 0 ) {
			$wizard = true;
		}

		$bring_statuses = array(
			'wc-bri-req-fulfill' 		=> __( 'Request Fulfillment by Shelfless', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
			'wc-bri-part-fulfill'		=> __( 'Partially Fulfilled by Shelfless', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
			'wc-bri-shipped'			=> __( 'Shipped by Shelfless', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
			'wc-bri-cancelled'			=> __( 'Cancelled by Shelfless', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
		);

		$bring_enabled_statuses = get_option( $this->plugin_name . '_order_enabled_bring_statuses' );
		
		$fields = array(
			array(
				'fid' 			=> $this->plugin_name . '_order_fallback_service_carrier',
				'label' 		=> esc_html__( 'Fallback carrier', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'section' 		=> $this->plugin_name . '_shipping_settings_fallback',
				'type' 			=> 'text',
				'options' 		=> array(
					array(
						'text'		=> esc_html__( 'Set the code for your default carrier/shipping service provider. This code will be used if Shelfless could not properly determine the carrier in the order.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
					),
				),	
				'placeholder' 	=> esc_html__( 'BPN', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'tooltip' 		=> esc_html__( 'Enter a fallback carrier if it cannot be detected in the system. For flat rates, this defaults to "BPN".', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'supplemental' 	=> esc_html__( 'Fallback Carrier', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'default' 		=> 'BPN',
				'label_for'		=> $this->plugin_name . '_order_fallback_service_carrier',
			),
			array(
				'fid' 			=> $this->plugin_name . '_order_fallback_service_code',
				'label' 		=> esc_html__( 'Fallback service (code)', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'section' 		=> $this->plugin_name . '_shipping_settings_fallback',
				'type' 			=> 'text',
				'options' 		=> array(
					array(
						'text'		=> esc_html__( 'Set the code for your default carrier shipping method. This code will be used if Shelfless could not properly determine the shipping method in the order.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
					),
				),
				'placeholder' 	=> esc_html__( '3584', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'tooltip' 		=> esc_html__( 'Enter a fallback service code if the service cannot be determined. For Bring, example services are: 5800, 5600, 4850', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'supplemental' 	=> esc_html__( 'Fallback Service (Code)', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'default' 		=> '3584',
				'label_for'		=> $this->plugin_name . '_order_fallback_service_code',
			),
			array(
				'fid' 			=> $this->plugin_name . '_order_value_added_services_codes',
				'label' 		=> esc_html__( 'Default fallback addon codes', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'section' 		=> $this->plugin_name . '_shipping_settings_fallback',
				'type' 			=> 'text',
				'options' 		=> array(
					array(
						'text'		=> esc_html__( 'Set the default fallback addon code which will be used by Shelfless if there is no addon code specified in the order. You can add multiple default fallback addon codes.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
					),
				),
				'placeholder' 	=> esc_html__( '1091', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'tooltip' 		=> esc_html__( 'Enter an approved value-added services codes, click the plus icon to add more. Default is 1091.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'supplemental' 	=> esc_html__( 'Default fallback addon codes', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'default' 		=> '1091',
				'label_for'		=> $this->plugin_name . '_order_value_added_services_codes', 
				'class'			=> 'vas_codes_wrapper',
				'container'		=> 'vas_codes_container',
				'icon'			=> 'vas_codes_add_new',
			),
		);

		$this->shelfless_register_fields( $fields, $this->plugin_name . '_shipping_settings_fallback' );

	}

	/**
	 * Generates Bring API field registrations for Shelfless Checkout section.
	 *
	 * @since    1.2.1
	 */
	public function shelfless_bring_settings_sections() {

		add_settings_section( $this->plugin_name . '_bring_settings_mybring', '', array( $this, 'shelfless_bring_settings_section_content' ), $this->plugin_name . '_bring_settings_mybring' );

	}

	/**
	 * Bring API field registrations for Shelfless Checkout content.
	 *
	 * @since    1.2.1
	 */
	public function shelfless_bring_settings_section_content() {

		generate_bring_shelfless_mybring_section_content();

	}

	/**
	 * Generates Bring API field registrations for Shelfless Checkout.
	 *
	 * @since    1.2.3
	 */
	public function shelfless_bring_settings_section_fields() {

		$fields = array(
			array(
				'fid' 			=> $this->plugin_name . '_alternate_mybring_customer_id',
				'label' 		=> esc_html__( 'Alternate Mybring Customer ID (no need to enter if the same as the one entered in Shelfless)', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'section' 		=> $this->plugin_name . '_bring_settings_mybring',
				'type' 			=> 'text',
				'options' 		=> false,
				'placeholder' 	=> esc_html__( 'Alternate Customer ID', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'tooltip' 		=> esc_html__( 'Enter your Mybring customer ID . If you don\'t know your customer ID - please contact Mybring. If this is empty, Shelfless will use the Mybring customer number entered in the Shelfless API Credentials tab.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'supplemental' 	=> esc_html__( 'You may get your Mybring Customer ID from Bring.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'default' 		=> '',
				'label_for'		=> $this->plugin_name . '_alternate_mybring_customer_id',
			),
			array(
				'fid' 			=> $this->plugin_name . '_mybring_api_key',
				'label' 		=> esc_html__( 'Mybring API Key', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'section' 		=> $this->plugin_name . '_bring_settings_mybring',
				'type' 			=> 'password',
				'options' 		=> false,
				'placeholder' 	=> esc_html__( 'Mybring API Key', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'tooltip' 		=> esc_html__( "Enter your Mybring API key. If you don't have a Mybring API key, you can generate a new one on the Mybring portal's API page.", 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'supplemental' 	=> esc_html__( 'You may get your Mybring API Key from Bring.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'default' 		=> '',
				'label_for'		=> $this->plugin_name . '_mybring_api_key',
			),
			array(
				'fid' 			=> $this->plugin_name . '_mybring_api_email_address',
				'label' 		=> esc_html__( 'Mybring Email Address', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'section' 		=> $this->plugin_name . '_bring_settings_mybring',
				'type' 			=> 'text',
				'options' 		=> false,
				'placeholder' 	=> esc_html__( 'Email Address', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'tooltip' 		=> esc_html__( 'Enter your Mybring email address. This is usually the email address used when you log in to the Mybring portal. Make sure that this email address was the one used in generating the API key above.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'supplemental' 	=> esc_html__( 'Most of the time this is the email address used in accessing your Mybring account.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'default' 		=> '',
				'label_for'		=> $this->plugin_name . '_mybring_api_email_address',
			),
		);

		$this->shelfless_register_fields( $fields, $this->plugin_name . '_bring_settings_mybring' );

	}

	/**
	 * Generates Shelfless Delivery settings section.
	 *
	 * @since    1.2.5
	 */
	public function shelfless_shipping_settings_deliveries_sections() {

		add_settings_section( $this->plugin_name . '_shipping_settings_deliveries', '', array( $this, 'shelfless_shipping_settings_deliveries_section_content' ), $this->plugin_name . '_shipping_settings_deliveries' );

	}

	/**
	 * Generates Shelfless Delivery settings section content.
	 *
	 * @since    1.2.5
	 */
	public function shelfless_shipping_settings_deliveries_section_content() {

		generate_bring_shelfless_shipping_settings_deliveries_section_content();

	}

	/**
	 * Register Shelfless Delivery settings tab section fields
	 *
	 * @since		1.2.5
	 */
	public function shelfless_shipping_settings_deliveries_section_fields() { 
		
		$fields = array(
			array(
				'fid' 			=> $this->plugin_name . '_sd_use_default_dimensions',
				'label' 		=> esc_html__( 'Use default dimensions when missing', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'section' 		=> $this->plugin_name . '_shipping_settings_deliveries',
				'type' 			=> 'checkbox',
				'options' 		=> array(
					array(
						'text'		=> esc_html__( 'Let Shelfless delivery add default dimensions (can be set below) when missing to ensure order fulfillment', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
						'value'		=> '1',
						'default' 	=> '1',
						'disabled'	=> 'disabled'
					),
				),				
				'placeholder'	=> '',
				'tooltip' 		=> esc_html__( 'If a product lacks dimensions Shelfless delivery cannot fulfill it. By ticking the box Shelfless will add the default dimensions of your choice (typically the smallest item in your store) if any dimension is missing so that the order can be fulfilled. The final package size during shipment will be determined by the warehouse for optimal shipping.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'ttip_w_html'	=> true,
				'default'		=> '1',
				'label_for'		=> $this->plugin_name . '_sd_use_default_dimensions',
			),
			array(
				'fid' 			=> $this->plugin_name . '_sd_separator',
				'label' 		=> '',
				'section' 		=> $this->plugin_name . '_shipping_settings_deliveries',
				'type' 			=> 'separator',
				'options' 		=> array(
					'heading'	=> esc_html__( '', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
					'text'		=> esc_html__( 'Default dimensions for an item.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				),				
				'placeholder'	=> '',
				'tooltip' 		=> '',
				'ttip_w_html'	=> false,
				'default'		=> '',
				'label_for'		=> '',
			), 
			array(
				'fid' 			=> $this->plugin_name . '_sd_use_default_dimensions_length',
				'label' 		=> esc_html__( 'Length (cm)', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'section' 		=> $this->plugin_name . '_shipping_settings_deliveries',
				'type' 			=> 'text',
				'placeholder' 	=> '5',
				'tooltip' 		=> esc_html__( 'Enter length of the smallest item in your webshop in centimeter.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'ttip_w_html'	=> true,
				'supplemental' 	=> esc_html__( 'Length (cm)', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'default' 		=> '5',
				'label_for'		=> $this->plugin_name . '_sd_use_default_dimensions_length',
				'class'			=> 'advanced_settings_field'
			), 
			array(
				'fid' 			=> $this->plugin_name . '_sd_use_default_dimensions_width',
				'label' 		=> esc_html__( 'Width (cm)', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'section' 		=> $this->plugin_name . '_shipping_settings_deliveries',
				'type' 			=> 'text',
				'placeholder' 	=> '3',
				'tooltip' 		=> esc_html__( 'Enter width of the smallest item in your webshop in centimeter.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'ttip_w_html'	=> true,
				'supplemental' 	=> esc_html__( 'Width (cm)', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'default' 		=> '3',
				'label_for'		=> $this->plugin_name . '_sd_use_default_dimensions_width',
				'class'			=> 'advanced_settings_field'
			), 
			array(
				'fid' 			=> $this->plugin_name . '_sd_use_default_dimensions_height',
				'label' 		=> esc_html__( 'Height (cm)', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'section' 		=> $this->plugin_name . '_shipping_settings_deliveries',
				'type' 			=> 'text',
				'placeholder' 	=> '3',
				'tooltip' 		=> esc_html__( 'Enter height of the smallest item in your webshop in centimeter.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'ttip_w_html'	=> true,
				'supplemental' 	=> esc_html__( 'Height (cm)', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'default' 		=> '3',
				'label_for'		=> $this->plugin_name . '_sd_use_default_dimensions_height',
				'class'			=> 'advanced_settings_field'
			),
			array(
				'fid' 			=> $this->plugin_name . '_sd_use_default_dimensions_weight',
				'label' 		=> esc_html__( 'Weight (kg)', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'section' 		=> $this->plugin_name . '_shipping_settings_deliveries',
				'type' 			=> 'text',
				'placeholder' 	=> '0.10',
				'tooltip' 		=> esc_html__( 'Enter weight of the smallest item in your webshop in kilogram.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'ttip_w_html'	=> true,
				'supplemental' 	=> esc_html__( 'Weight (kg)', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'default' 		=> '0.10',
				'label_for'		=> $this->plugin_name . '_sd_use_default_dimensions_weight',
				'class'			=> 'advanced_settings_field'
			),
			array(
				'fid' 			=> $this->plugin_name . '_sd_use_default_dimensions_maximum_weight',
				'label' 		=> esc_html__( 'Maximum package weight (kg)', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'section' 		=> $this->plugin_name . '_shipping_settings_deliveries',
				'type' 			=> 'text',
				'placeholder' 	=> '35',
				'tooltip' 		=> esc_html__( 'Enter the maximum weight of the package. Packages more than the maximum value indicated here will not be rated for shipment by Shelfless. You may still allow for a separate shipping method (usually flat rates) for packages that are heavier than the maximum weight indicated here.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'ttip_w_html'	=> true,
				'supplemental' 	=> esc_html__( 'Maximum weigh (kg)', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'default' 		=> '35',
				'label_for'		=> $this->plugin_name . '_sd_use_default_dimensions_maximum_weight',
				'class'			=> 'advanced_settings_field'
			),
			array(
				'fid' 			=> $this->plugin_name . '_sd_use_default_dimensions_maximum_items_in_cart',
				'label' 		=> esc_html__( 'Maximum total quantity of items in an order for allowing Shelfless Delivery services', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'section' 		=> $this->plugin_name . '_shipping_settings_deliveries',
				'type' 			=> 'text',
				'placeholder' 	=> '100',
				'tooltip' 		=> esc_html__( 'Enter the maximum items in the cart for the package. Packages with more than the number of items indicated here will not be rated for shipment by Shelfless. You may still allow for a separate shipping method (usually flat rates) for packages that contain more quantities than the maximum quantity indicated here. ', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'ttip_w_html'	=> true,
				'supplemental' 	=> esc_html__( 'Maximum items in the cart for the package', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
				'default' 		=> '100',
				'label_for'		=> $this->plugin_name . '_sd_use_default_dimensions_maximum_items_in_cart',
				'class'			=> 'advanced_settings_field'
			),
		);

		$this->shelfless_register_fields( $fields, $this->plugin_name . '_shipping_settings_deliveries' );

	}

	/**
	 * Set the options for Shelfless Delivery and the selected services, and 
	 * return the value of the current registered field in WP | triggering field - use default dimension
	 *
	 * @since		1.2.5
	 * @param		string    	$value   		New data value
	 * @param		string 		$old_value		Current data value
	 * @param		string		$option			option field
	 */
	public function shelfless_set_shipping_deliveries( $value, $old_value, $option ) { 
		
		$post = wc_clean( $_POST );

		if ( $post['option_page'] !== $this->plugin_name . '_shipping_settings_deliveries' )
			return;

		$template_methods = shelfless_delivery_services_methods();

		// Intended for Bring - Shelfless Delivery services
		$temp_key = 'bring'; 
		foreach ( $post as $key => $val ) { 
			if ( $temp_key === $key ) {
				$methods[$key] = $val;
			}
		}

		if ( ! empty( $methods ) ) { 
			foreach( $methods as $carrier => $service ) { 
				foreach( $service as $service_id => $val ) { 

					if ( empty( $val['enabled'] ) ) continue;

					foreach( $template_methods as $temp_carrier => $temp_service ) {
						$method_name = $temp_service[$service_id]['name'];
						$carrier_code = $temp_service[$service_id]['carrier_code'];
						break;
					}

					$new_post[$service_id]['enabled'] = ( isset( $val['enabled'] ) ? true : false );
					$new_post[$service_id]['method_name'] = $method_name;
					$new_post[$service_id]['is_title'] = ( isset( $val['is_title'] ) ? true : false );
					$new_post[$service_id]['title'] = ( ! empty( $val['title'] ) ? validate_bring_shelfless_text_field( sanitize_text_field( $val['title'] ) ) : $method_name );
					$new_post[$service_id]['is_price'] = ( isset( $val['is_price'] ) ? true : false );
					$new_post[$service_id]['price'] = ( ! empty( $val['price'] ) ? validate_bring_shelfless_text_field( sanitize_text_field( shelfless_process_sds_price( trim( $val['price'] ) ) ) ) : false );
					$new_post[$service_id]['is_free_shipping'] = (  isset( $val['is_free_shipping'] ) ? true : false );
					$new_post[$service_id]['free_shipping_threshold'] = ( ! empty( $val['free_shipping_threshold'] ) ? validate_bring_shelfless_text_field( sanitize_text_field( shelfless_process_sds_price( trim( $val['free_shipping_threshold'] ) ) ) ) : false );
					$new_post[$service_id]['addons'] = ( isset( $val['addons'] ) ? $val['addons'] : false );
					$new_post[$service_id]['carrier_code'] = $carrier_code;
					$new_post[$service_id]['sorting_areas'] = ( isset( $val['sorting_areas'] ) ? $val['sorting_areas'] : false );

				}
			}
		}

		if ( ! empty( $new_post ) ) { 
			$data[$temp_key] = $new_post;
		} else {
			$data[$temp_key] = array();
		}

		// Shelfless Delivery Services - Bring | custom option name to store serialized data
		$option_name = $this->plugin_name . '_sd_services_bring';
		update_option( "{$option_name}", $data );

		// always set this to true for _sd_use_default_dimensions
		$value = true;
		
		return $value;
	}

	/**
	 * Set the option data in the default dimensions - Advance settings, and 
	 * return the value of the current registered field in WP | triggering field - weight (default dimensions)
	 *
	 * @since		1.2.5
	 * @param		string    	$value   		New data value
	 * @param		string 		$old_value		Current data value
	 * @param		string		$option			option field
	 */
	public function shelfless_set_default_dimensions( $value, $old_value, $option ) { 
		
		$post = wc_clean( $_POST );
		$localised = false;

		if ( $post['option_page'] !== $this->plugin_name . '_shipping_settings_deliveries' )
			return;

		// if data/value have dot/comma as decimal separator, it should apply the regex expression to clean and format to float value
		// otherwise, directly localise with decimal or thousand separator from settings without passing here
		if( strpos( $value, ',' ) !== false || strpos( $value, '.' ) !== false ) { 
			$localised = true;
			$value = shelfless_clean_price( $value );
		}
		
		// localise the data/value with decimal or thousand separator from settings
		if( $option != $this->plugin_name . '_sd_use_default_dimensions_maximum_items_in_cart' && $localised === true )
			$value = shelfless_process_display_local_price_settings( $value );
		
		return $value;
	}
	
}
