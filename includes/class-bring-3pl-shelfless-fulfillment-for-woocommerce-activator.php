<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Fired during plugin activation
 *
 * @link       https://www.bring.no/
 * @since      1.0.0
 *
 * @package    Bring_3pl_Shelfless_Fulfillment_For_Woocommerce
 * @subpackage Bring_3pl_Shelfless_Fulfillment_For_Woocommerce/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Bring_3pl_Shelfless_Fulfillment_For_Woocommerce
 * @subpackage Bring_3pl_Shelfless_Fulfillment_For_Woocommerce/includes
 * @author     Tiqqe <bring_team@tiqqe.com>
 */
class Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Activator {

	protected $plugin_name;


	public function __construct( $plugin_name ) {
		$this->plugin_name = $plugin_name;
		require_once __DIR__ . '/bring-3pl-shelfless-fulfillment-for-woocommerce-helper-functions.php';
	}

	/**
	 * Performs actions during activation.
	 *
	 * @since    1.0.0
	 */
	public function activate() {
		
		if ( ! WOOCOMMERCE_NETWORK_ACTIVATED
			&& ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) ) {

			deactivate_plugins( BRING_3PL_SHELFLESS_FULFILLMENT_FOR_WOOCOMMERCE_BASE );
            wp_die( 
				sprintf( 
					esc_html__( 'This plugin requires WooCommerce. It will not work without WooCommerce properly set up and activated. %s', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
					'<br /><a href="' . wp_nonce_url( admin_url( 'plugins.php' ), '', 'missed_pluggable_deps' ) . '">' . esc_html__( 'Go back to Plugins page.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) . '</a>' 
				)
			);
			
			return false;
		}
		$this->set_shelfless_api_settings();
	}

	/**
	 * Adds/sets options for API settings.
	 * 
	 * @since    1.0.0
	*/
	private function set_shelfless_api_settings() {
		$api_settings = array(
			// API settings options
			$this->plugin_name . '_mybring_customer_id',
			$this->plugin_name . '_shelfless_api_key',
			$this->plugin_name . '_shelfless_api_secret_key',
			$this->plugin_name . '_shelfless_api_mode',
			$this->plugin_name . '_shelfless_api_version',
			$this->plugin_name . '_shelfless_debug_mode',
			$this->plugin_name . '_shelfless_api_setup_is_complete',
			$this->plugin_name . '_shelfless_inventory_linkages',

			// Inventory settings options
			$this->plugin_name . '_inventory_is_manage_stock',
			$this->plugin_name . '_inventory_is_show_notif_low_stock',
			$this->plugin_name . '_inventory_is_show_notif_oos',
			$this->plugin_name . '_inventory_is_use_cost_price_field',
			$this->plugin_name . '_inventory_cost_price_currency',
			$this->plugin_name . '_inventory_low_threshold_value',
			$this->plugin_name . '_inventory_is_use_customs_field',
			$this->plugin_name . '_inventory_default_country_of_origin',
			$this->plugin_name . '_inventory_basic_unit',
			$this->plugin_name . '_inventory_is_fulfill_products',

			// Order settings options
			$this->plugin_name . '_order_process_from_days_ago',
			$this->plugin_name . '_order_is_process_export',
			$this->plugin_name . '_order_add_bring_statuses',
			$this->plugin_name . '_order_enabled_bring_statuses',
			$this->plugin_name . '_order_is_show_notif_partial_shipment',
			$this->plugin_name . '_order_is_show_notif_cancelled',
			$this->plugin_name . '_order_status_fulfill_status',
			$this->plugin_name . '_order_status_cancel_fulfill_status',
			$this->plugin_name . '_order_status_partially_fulfilled_status',
			$this->plugin_name . '_order_status_shipped_status',
			$this->plugin_name . '_order_status_complete_status',
			$this->plugin_name . '_order_status_cancel_status',
			$this->plugin_name . '_order_fallback_service_carrier',
			$this->plugin_name . '_order_fallback_service_code',

			// Scheduler settings options
			$this->plugin_name . '_sched_fetch_inventory_updates',
			$this->plugin_name . '_sched_send_orders',
			$this->plugin_name . '_sched_fetch_order_updates',
			$this->plugin_name . '_sched_push_in_store_order_updates',

		);

		foreach ( $api_settings as $api_setting ) {
			add_option( $api_setting );
		}

		// Setting up default values for some options so plugin may work properly if it is not set yet.
		if ( empty( get_option( $this->plugin_name . '_shelfless_api_mode' ) ) )
			update_option( $this->plugin_name . '_shelfless_api_mode', 'sandbox', 'yes' );
		
		if ( empty( get_option( $this->plugin_name . '_shelfless_api_version' ) ) )
			update_option( $this->plugin_name . '_shelfless_api_version', '1.0.0', 'yes' );

		if ( empty( get_option( $this->plugin_name . '_shelfless_debug_mode' ) ) )
			update_option( $this->plugin_name . '_shelfless_debug_mode', '1', 'yes' );

		if ( empty( get_option( $this->plugin_name . '_inventory_is_manage_stock' ) ) )
			update_option( $this->plugin_name . '_inventory_is_manage_stock', '0', 'yes' );
		
		if ( empty( get_option( $this->plugin_name . '_inventory_is_show_notif_oos' ) ) )
			update_option( $this->plugin_name . '_inventory_is_show_notif_oos', '0', 'yes' );

		if ( empty( get_option( $this->plugin_name . '_inventory_is_use_customs_field' ) ) )
			update_option( $this->plugin_name . '_inventory_is_use_customs_field', '0', 'yes' );

		if ( empty( get_option( $this->plugin_name . '_inventory_basic_unit' ) ) )
			update_option( $this->plugin_name . '_inventory_basic_unit', 'PCS', 'yes' );

		if ( empty( get_option( $this->plugin_name . '_order_process_from_days_ago' ) ) )
			update_option( $this->plugin_name . '_order_process_from_days_ago', '5', 'yes' );
		
		if ( empty( get_option( $this->plugin_name . '_order_is_process_export' ) ) )
			update_option( $this->plugin_name . '_order_is_process_export', '0', 'yes' );

		if ( empty( get_option( $this->plugin_name . '_order_status_fulfill_status' ) ) )
			update_option( $this->plugin_name . '_order_status_fulfill_status', 'wc-processing', 'yes' );

		if ( empty( get_option( $this->plugin_name . '_order_status_cancel_fulfill_status' ) ) )
			update_option( $this->plugin_name . '_order_status_cancel_fulfill_status', 'wc-cancelled', 'yes' );

		if ( empty( get_option( $this->plugin_name . '_order_status_partially_fulfilled_status' ) ) )
			update_option( $this->plugin_name . '_order_status_partially_fulfilled_status', 'wc-processing', 'yes' );

		if ( empty( get_option( $this->plugin_name . '_order_status_shipped_status' ) ) )
			update_option( $this->plugin_name . '_order_status_shipped_status', 'wc-completed', 'yes' );

		if ( empty( get_option( $this->plugin_name . '_order_status_complete_status' ) ) )
			update_option( $this->plugin_name . '_order_status_complete_status', 'wc-completed', 'yes' );

		if ( empty( get_option( $this->plugin_name . '_order_status_cancel_status' ) ) )
			update_option( $this->plugin_name . '_order_status_cancel_status', 'wc-failed', 'yes' );

		if ( empty( get_option( $this->plugin_name . '_sched_fetch_inventory_updates' ) ) )
			update_option( $this->plugin_name . '_sched_fetch_inventory_updates', 'weekly', 'yes' );

		if ( empty( get_option( $this->plugin_name . '_sched_send_orders' ) ) )
			update_option( $this->plugin_name . '_sched_send_orders', 'weekly', 'yes' );

		if ( empty( get_option( $this->plugin_name . '_sched_fetch_order_updates' ) ) )
			update_option( $this->plugin_name . '_sched_fetch_order_updates', 'weekly', 'yes' );

		if ( empty( get_option( $this->plugin_name . '_sched_push_in_store_order_updates' ) ) )
			update_option( $this->plugin_name . '_sched_push_in_store_order_updates', 'weekly', 'yes' );

		add_action( 'activated_plugin', array( $this, 'redirect_after_activation' ), 10, 3 );
		
	}

	/**
	 * Redirects the user to the plugin general settings after activation
	 * @param string $plugin
	 * 
	 * @since    1.0.0
	*/
	public function redirect_after_activation( $plugin, $network_activation ) {

		if ( $plugin ===  BRING_3PL_SHELFLESS_FULFILLMENT_FOR_WOOCOMMERCE_BASE ) {

			if ( ! $network_activation ) {

				if ( ! get_option( $this->plugin_name .  '_shelfless_api_setup_is_complete') ) {
					redirect_bring_shelfless_page( $this->plugin_name . '_setup' );
				}

			}
		}

	}

}
