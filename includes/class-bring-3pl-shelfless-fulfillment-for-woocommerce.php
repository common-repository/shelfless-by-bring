<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used for all
 * Shelfless operations.
 *
 * @link       https://www.bring.no/
 * @since      1.0.0
 *
 * @package    Bring_3pl_Shelfless_Fulfillment_For_Woocommerce
 * @subpackage Bring_3pl_Shelfless_Fulfillment_For_Woocommerce/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization and admin-specific hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Bring_3pl_Shelfless_Fulfillment_For_Woocommerce
 * @subpackage Bring_3pl_Shelfless_Fulfillment_For_Woocommerce/includes
 * @author     Tiqqe <bring_team@tiqqe.com>
 */
class Bring_3pl_Shelfless_Fulfillment_For_Woocommerce {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The Shelfless Delivery instance
	 *
	 * @since    1.2.5
	 * @access   protected
	 * @var      Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Shelfless_Delivery    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $shelfless_delivery;


	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @static      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	static $plugin_name;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @static      string    $app_name    The title of this plugin as shown to users.
	 */
	static $app_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @static      string    $version    The current version of the plugin.
	 */
	static $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		if ( ! WOOCOMMERCE_NETWORK_ACTIVATED 
			&& ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { return; }

		if ( defined( 'BRING_3PL_SHELFLESS_FULFILLMENT_FOR_WOOCOMMERCE_VERSION' ) ) {
			self::$version = BRING_3PL_SHELFLESS_FULFILLMENT_FOR_WOOCOMMERCE_VERSION;
		} else {
			self::$version = '1.3.0';
		}
		
		self::$plugin_name = 'bring_3pl_shelfless_fulfillment_for_woocommerce';
		self::$app_name = 'Shelfless by Bring';

		$this->load_dependencies();
		$this->define_shelfless_delivery();
		$this->define_universal_shelfless_hooks();

		if ( is_admin() && current_user_can( 'manage_options' ) ) {
			$this->define_admin_hooks();
		}

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bring-3pl-shelfless-fulfillment-for-woocommerce-loader.php';

		/**
		 * The class responsible for defining HTTP functionality.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bring-3pl-shelfless-fulfillment-for-woocommerce-http.php';

		/**
		 * The Shelfless Delivery class functionality.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bring-3pl-shelfless-fulfillment-for-woocommerce-delivery.php';
		
		/**
		 * The third-party LAFF 3D packing algorithm class functionality.
		 */
		if ( ! class_exists( 'Packer' ) ) {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/packer-php-laff/Packer.php';
		}
		
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/bring-3pl-shelfless-fulfillment-for-woocommerce-admin-functions.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bring-3pl-shelfless-fulfillment-for-woocommerce-order.php';
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bring-3pl-shelfless-fulfillment-for-woocommerce-inventory.php';
		
		$this->loader = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Loader();

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		if ( ! is_admin() || ( is_admin() && ! current_user_can( 'manage_options' ) ) ) return;

		$plugin_admin = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin( self::get_plugin_name(), self::get_version() );

		$this->loader->add_action( 'admin_notices', $plugin_admin, 'shelfless_show_notices' );
		$this->loader->add_action( 'bring_3pl_shelfless_log', $plugin_admin, 'shelfless_log', 10, 3 );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles', 99 );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_filter( 'plugin_action_links_' . BRING_3PL_SHELFLESS_FULFILLMENT_FOR_WOOCOMMERCE_BASE, $plugin_admin, 'shelfless_admin_plugin_settings_link' );

		if ( ! get_option( self::get_plugin_name() .  '_shelfless_api_setup_is_complete' ) ) {
			$this->loader->add_action( 'admin_head', $plugin_admin, 'shelfless_setup_wizard_page_hide_update_nag' );
			$this->loader->add_action( 'admin_menu', $plugin_admin, 'shelfless_setup_wizard_page_load' );
			$this->loader->add_action( 'admin_post_bring_shelfless_setup_wizard_page_finished', $plugin_admin, 'shelfless_setup_wizard_page_finished' );
			$this->loader->add_action( 'wp_ajax_bring_shelfless_wizard_save_api_creds', $plugin_admin, 'shelfless_wizard_save_api_creds' );
			$this->loader->add_action( 'wp_ajax_bring_shelfless_wizard_save_inventory_settings', $plugin_admin, 'shelfless_wizard_save_inventory_settings' );
			$this->loader->add_action( 'wp_ajax_bring_shelfless_wizard_save_order_settings', $plugin_admin, 'shelfless_wizard_save_order_settings' );
			$this->loader->add_action( 'wp_ajax_bring_shelfless_wizard_save_shipping_mappings', $plugin_admin, 'shelfless_wizard_save_shipping_mappings' );
			$this->loader->add_action( 'wp_ajax_bring_shelfless_schedule_action_updates', $plugin_admin, 'shelfless_schedule_action_updates' );
			$this->loader->add_action( 'wp_ajax_bring_shelfless_wizard_save_dream_logistics', $plugin_admin, 'shelfless_wizard_save_dream_logistics' );
		}
		else {
			$this->loader->add_action( 'admin_menu', $plugin_admin, 'shelfless_admin_settings_page', 100 );
		}
		
		$this->loader->add_action( 'admin_init', $plugin_admin, 'shelfless_api_settings_sections' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'shelfless_api_settings_section_fields' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'shelfless_api_settings_diagnostic_sections' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'shelfless_inventory_settings_sections' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'shelfless_inventory_settings_section_fields' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'shelfless_inventory_settings_product_grid' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'shelfless_order_settings_section' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'shelfless_order_settings_section_fields' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'shelfless_order_settings_status_maps_section' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'shelfless_order_settings_status_maps_section_fields' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'shelfless_dream_logistics_settings_section' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'shelfless_dream_logistics_settings_section_fields' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'shelfless_scheduled_actions_maps_section' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'shelfless_inventory_settings_products_sections' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'shelfless_inventory_settings_products_section_fields' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'shelfless_order_settings_shipping_maps_section' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'shelfless_order_settings_shipping_maps_section_fields' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'shelfless_shipping_settings_fallback_sections' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'shelfless_shipping_settings_fallback_section_fields' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'shelfless_shipping_settings_deliveries_sections' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'shelfless_shipping_settings_deliveries_section_fields' );

		$this->loader->add_action( 'admin_init', $plugin_admin, 'shelfless_bring_settings_sections' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'shelfless_bring_settings_section_fields' );

		$this->loader->add_action( 'wp_ajax_bring_shelfless_api_settings_diagnostics', $plugin_admin, 'shelfless_api_settings_diagnostics' );
		$this->loader->add_action( 'wp_ajax_bring_shelfless_api_settings_diagnostics_delete_transients', $plugin_admin, 'shelfless_api_settings_diagnostics_delete_transients' );
		$this->loader->add_action( 'wp_ajax_bring_shelfless_update_prod_is_fulfill', $plugin_admin, 'shelfless_update_products_is_fulfill' );

		$this->loader->add_action( 'wp_ajax_bring_shelfless_inventory_datatables_endpoint', $plugin_admin, 'shelfless_pull_inventory_datatables' );
		$this->loader->add_action( 'wp_ajax_bring_shelfless_stock_adjustment_report_datatables_endpoint', $plugin_admin, 'shelfless_pull_stock_adjustment_report_datatables' );

		$this->loader->add_action( 'update_option_' . self::get_plugin_name() . '_inventory_is_manage_stock', $plugin_admin, 'shelfless_set_manage_stock_options', 10, 3 );
		$this->loader->add_action( 'update_option_' . self::get_plugin_name() . '_inventory_is_sync_products', $plugin_admin, 'shelfless_set_is_sync_products', 10, 3 );

		$this->loader->add_action( 'woocommerce_variation_options', $plugin_admin, 'shelfless_variation_options', 10, 3);
		$this->loader->add_action( 'woocommerce_save_product_variation', $plugin_admin, 'shelfless_save_product_variation', 10 );
		// Store custom field value into WooCommerce variation data - Add New Variation Settings
		$this->loader->add_filter( 'woocommerce_available_variation', $plugin_admin, 'shelfless_load_variation_settings_fields' );

		$this->loader->add_action( 'woocommerce_product_options_inventory_product_data', $plugin_admin, 'shelfless_manage_stock' );
		$this->loader->add_action( 'woocommerce_process_product_meta', $plugin_admin, 'shelfless_save_woocommerce_product_meta' );
		$this->loader->add_action( 'woocommerce_product_options_shipping_product_data', $plugin_admin, 'shelfless_shippable' );

		$this->loader->add_action( 'save_post_product', $plugin_admin, 'shelfless_article_sync_create_update_on_product_save', 10, 3);
		$this->loader->add_action( 'wp_ajax_bring_shelfless_update_prod_is_article_sync', $plugin_admin, 'shelfless_create_update_products_is_article_sync_on_product_grid' );

		$this->loader->add_action( 'pre_update_option_' . self::get_plugin_name() . '_order_enabled_bring_statuses', $plugin_admin, 'shelfless_set_custom_statuses_options', 10, 3 );

		$this->loader->add_action( 'admin_post_bring_shelfless_schedule_action_updates', $plugin_admin, 'shelfless_schedule_action_updates' );

		// Order actions
		$this->loader->add_action( 'woocommerce_saved_order_items', $plugin_admin, 'shelfless_set_flag_for_updated_order_items', 10, 2 );
		$this->loader->add_action( 'save_post_shop_order', $plugin_admin, 'shelfless_set_flag_for_updated_orders', 10, 3 );

		// Pre-update the data POSTed before saving to DB
		$this->loader->add_action( 'wp_ajax_bring_shelfless_return_plugin_name_to_js_script', $plugin_admin, 'shelfless_return_plugin_name_to_js_script' );
		$this->loader->add_action( 'pre_update_option_'. self::get_plugin_name() .'_order_value_added_services_codes', $plugin_admin, 'shelfless_set_vas_codes_options', 10, 3 );

		// Link to Mybring
		$this->loader->add_action( 'woocommerce_admin_order_data_after_order_details', $plugin_admin, 'shelfless_linkify_to_mybring', 10, 1 );
		$this->loader->add_filter( 'woocommerce_admin_order_preview_get_order_details', $plugin_admin, 'shelfless_linkify_to_mybring_preview_data', 10, 2 );
		$this->loader->add_action( 'woocommerce_admin_order_preview_start', $plugin_admin, 'shelfless_linkify_to_mybring_preview' );
		
		// process Shelfless Delivery data before saving
		$this->loader->add_action( 'pre_update_option_' . self::get_plugin_name() . '_sd_use_default_dimensions', $plugin_admin, 'shelfless_set_shipping_deliveries', 10, 3 );

		
		// process default dimensions data before saving
		$this->loader->add_action( 'pre_update_option_' . self::get_plugin_name() . '_sd_use_default_dimensions_length', $plugin_admin, 'shelfless_set_default_dimensions', 10, 3 );
		$this->loader->add_action( 'pre_update_option_' . self::get_plugin_name() . '_sd_use_default_dimensions_width', $plugin_admin, 'shelfless_set_default_dimensions', 10, 3 );
		$this->loader->add_action( 'pre_update_option_' . self::get_plugin_name() . '_sd_use_default_dimensions_height', $plugin_admin, 'shelfless_set_default_dimensions', 10, 3 );
		$this->loader->add_action( 'pre_update_option_' . self::get_plugin_name() . '_sd_use_default_dimensions_weight', $plugin_admin, 'shelfless_set_default_dimensions', 10, 3 );
		$this->loader->add_action( 'pre_update_option_' . self::get_plugin_name() . '_sd_use_default_dimensions_maximum_weight', $plugin_admin, 'shelfless_set_default_dimensions', 10, 3 );
		$this->loader->add_action( 'pre_update_option_' . self::get_plugin_name() . '_sd_use_default_dimensions_maximum_items_in_cart', $plugin_admin, 'shelfless_set_default_dimensions', 10, 3 );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {

		if ( ! WOOCOMMERCE_NETWORK_ACTIVATED 
			&& ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { return; }

		$this->loader->run();

	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {

		return $this->loader;

	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public static function get_plugin_name() {

		return self::$plugin_name;

	}

	/**
	 * The official name of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public static function get_app_name() {

		return self::$app_name;

	}	

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public static function get_version() {

		return self::$version;
		
	}

	public function define_user_scripts() {

		if ( ! is_checkout() && ! is_cart() ) return;
		
		wp_enqueue_script( self::$plugin_name, plugin_dir_url( dirname( __FILE__ ) ) . 'users/js/bring-3pl-shelfless-fulfillment-for-woocommerce-shelfless-delivery.js', array( 'jquery', 'jquery-ui-core' ), self::$version, true );
		wp_enqueue_style( self::$plugin_name, plugin_dir_url( dirname( __FILE__ ) ) . 'users/css/bring-3pl-shelfless-fulfillment-for-woocommerce-shelfless-delivery.css', array(), self::$version, 'all' );

	}

	/**
	 * Define actions and filters for Shelfless Delivery
	 *
	 * @since     1.2.5
	 */
	public function define_shelfless_delivery() {

		$this->loader->add_action( 'wp_enqueue_scripts', $this, 'define_user_scripts', 100 );
		$this->loader->add_filter( 'woocommerce_shipping_methods', $this, 'add_shelfless_delivery' );
		
		$this->shelfless_delivery = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Shelfless_Delivery();

		$this->loader->add_action( 'woocommerce_after_shipping_rate', $this->shelfless_delivery, 'build_pickup_points', 20, 2 );
		$this->loader->add_action( 'woocommerce_review_order_after_shipping', $this->shelfless_delivery, 'build_addons' );
		$this->loader->add_filter( 'woocommerce_package_rates', $this, 'sort_shelfless_delivery', 20, 2 );

		$this->loader->add_action( 'wp_ajax_pickup_point_selection', $this->shelfless_delivery, 'save_selected_pickup_point' );
		$this->loader->add_action( 'wp_ajax_nopriv_pickup_point_selection', $this->shelfless_delivery, 'save_selected_pickup_point' );
		//$this->loader->add_action( 'wp_ajax_addon_selection', $this->shelfless_delivery, 'save_selected_addon' );
		//$this->loader->add_action( 'wp_ajax_nopriv_addon_selection', $this->shelfless_delivery, 'save_selected_addon' );
		$this->loader->add_action( 'woocommerce_checkout_create_order_shipping_item', $this->shelfless_delivery, 'update_shipment_meta_data', 20, 4 );

	}

	public function sort_shelfless_delivery( $rates, $package ) {

		if ( ! is_array( $rates ) ) return array();

		if ( empty( $rates) ) return $rates;

		uasort( $rates, function ( $a, $b ) {
			if ( $a == $b ) return 0;
			return ( $a->cost < $b->cost ) ? -1 : 1;
		} );

		return $rates;

	}

	/**
	 * Initiate Shelfless Delivery shipping methods
	 *
	 * @since     1.2.5
	 */
	public function add_shelfless_delivery( $methods ) {

		$methods['shelfless_delivery'] = 'Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Shelfless_Delivery';

		return $methods;

	}

	public function define_universal_shelfless_hooks() {

		$this->loader->add_filter( 'woocommerce_hidden_order_itemmeta', $this, 'hide_internal_metas', 20, 1 );
		$this->loader->add_filter( 'woocommerce_order_item_display_meta_key', $this, 'display_metas', 20, 3 );

	}

	public function hide_internal_metas( $hidden_metas ) {

		$hidden_metas[] = 'carrier_name';
		$hidden_metas[] = 'carrier_code';
		$hidden_metas[] = 'bring_product';
		$hidden_metas[] = 'pickup_point_id';
		$hidden_metas[] = 'shelfless_vas_codes';

		return $hidden_metas;

	}

	public function display_metas( $key, $meta, $item ) {

		$metas = array(
			'items'					=> esc_html__( 'Items', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
			'carrier_name'			=> esc_html__( 'Carrier', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
			'bring_product'			=> esc_html__( 'Service Code', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
			'service_name'			=> esc_html__( 'Service', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
			'expected_delivery'		=> esc_html__( 'Expected Delivery Date', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
			'pickup_point_location'	=> esc_html__( 'Pickup Point Location', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
			'pickup_point_id'		=> esc_html__( 'Pickup Point Agent ID', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
		);

		if ( array_key_exists( $key, $metas ) ) { $key = $metas[$key]; }

		return $key;

	}
}
