<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Establishing scheduled actions.
 *
 * @link       https://www.bring.no/
 * @since      1.0.0
 *
 * @package    Bring_3pl_Shelfless_Fulfillment_For_Woocommerce
 * @subpackage Bring_3pl_Shelfless_Fulfillment_For_Woocommerce/includes
 */

class Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Actions {

    /**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

    private $version;

    /**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name ) {

		$this->plugin_name = $plugin_name;
        
        if ( defined( 'BRING_3PL_SHELFLESS_FULFILLMENT_FOR_WOOCOMMERCE_VERSION' ) ) {
			$this->version = BRING_3PL_SHELFLESS_FULFILLMENT_FOR_WOOCOMMERCE_VERSION;
		} else {
			$this->version = '1.3.0';
		}

        $admin = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin( $this->plugin_name, $this->version);
        add_action( 'init', array( $admin, 'shelfless_register_bring_statuses' ) );
        add_filter( 'wc_order_statuses', array( $admin, 'shelfless_custom_bring_statuses' ) );
        unset( $admin );
        
		add_action( 'init', array( $this, 'shelfless_cron' ) );
		add_action( 'shelfless_fetch_inventory_updates', array( $this, 'shelfless_sched_fetch_inventory_updates' ) );
        add_action( 'shelfless_fulfill_orders', array( $this, 'shelfless_sched_fulfill_orders' ) );
        add_action( 'shelfless_fetch_order_updates', array( $this, 'shelfless_sched_fetch_order_updates' ) );
        add_action( 'shelfless_push_in_store_order_updates', array( $this, 'shelfless_sched_push_in_store_order_updates' ) );
        add_action( 'shelfless_sync_product_changes', array( $this, 'shelfless_sched_sync_product_changes' ) );        

	}

    /**
	 * Cron job actions
	 * Fetch inventory updates, fulfill orders, fetch order updates, push in store order updates product sync
	 *
	 * @since		1.0.0
	 */
	public function shelfless_cron() {

		if ( ! function_exists( 'as_next_scheduled_action' ) ) {
			return false;
		}

		$fetch_inventory_updates = get_option( $this->plugin_name . '_sched_fetch_inventory_updates' );
		$fulfill_orders = get_option( $this->plugin_name . '_sched_send_orders' );
		$fetch_order_updates = get_option( $this->plugin_name . '_sched_fetch_order_updates' );
		$push_order_updates = 'hourly'; // Fixed interval to run every hour
		$group_name = 'shelfless-by-bring';

        $sched = array( 'hourly', 'weekly', 'daily' );
        $args = array();

        if ( $fetch_inventory_updates !== 'disable' && false === as_next_scheduled_action( 'shelfless_fetch_inventory_updates', $args, $group_name ) ) {
            $fetch_inventory_updates = ( ! empty( $fetch_inventory_updates ) ? $fetch_inventory_updates : 'hourly' );
            as_schedule_cron_action( time(), ( in_array( $fetch_inventory_updates, $sched ) ? '@' : '' ) . $fetch_inventory_updates, 'shelfless_fetch_inventory_updates', $args, $group_name );
        }

        if ( $fulfill_orders !== 'disable' && false === as_next_scheduled_action( 'shelfless_fulfill_orders', $args, $group_name ) ) {
            $fulfill_orders = ( ! empty( $fulfill_orders ) ? $fulfill_orders : 'hourly' );
            as_schedule_cron_action( time(), ( in_array( $fulfill_orders, $sched ) ? '@' : '' ) . $fulfill_orders, 'shelfless_fulfill_orders', $args, $group_name );
        }

        if ( $fetch_order_updates !== 'disable' && false === as_next_scheduled_action( 'shelfless_fetch_order_updates', $args, $group_name ) ) {
            $fetch_order_updates = ( ! empty( $fetch_order_updates ) ? $fetch_order_updates : 'hourly' );
            as_schedule_cron_action( time(), ( in_array( $fetch_order_updates, $sched ) ? '@' : '' ) . $fetch_order_updates, 'shelfless_fetch_order_updates', $args, $group_name );
        }

        if ( $push_order_updates !== 'disable' && false === as_next_scheduled_action( 'shelfless_push_in_store_order_updates' ) ) {
            as_schedule_cron_action( time(), ( in_array( $push_order_updates, $sched ) ? '@' : '' ) . $push_order_updates, 'shelfless_push_in_store_order_updates', $args, $group_name );
        }

        if ( true === $this->shelfless_is_pending_product_changes() ) {
            if ( false === as_next_scheduled_action( 'shelfless_sync_product_changes', $args, $group_name ) ) {
                as_enqueue_async_action( 'shelfless_sync_product_changes', $args, $group_name );
            }
        }

	}

    /**
     * Cron job action
     * Fetch inventory updates
     *
     * @since		1.0.0
     * @access      public
     */
    public function shelfless_sched_fetch_inventory_updates() {

        Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( __CLASS__ . '->' . __FUNCTION__ );
        $admin = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin( $this->plugin_name, $this->version);
        $admin->shelfless_sched_fetch_inventory_updates();
        unset( $admin );

    }

    /**
     * Cron job action
     * Fulfill orders
     *
     * @since		1.0.0
     * @access      public
     */
    public function shelfless_sched_fulfill_orders() {

        Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( __CLASS__ . '->' . __FUNCTION__ );
        $admin = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin( $this->plugin_name, $this->version);
        $admin->shelfless_sched_fulfill_orders();
        unset( $admin );

    }

    /**
     * Cron job action
     * Fetch order updates
     *
     * @since		1.0.0
     * @access      public
     */
    public function shelfless_sched_fetch_order_updates() {

        Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( __CLASS__ . '->' . __FUNCTION__ );
        $admin = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin( $this->plugin_name, $this->version);
        $admin->shelfless_sched_fetch_order_updates();
        unset( $admin );

    }

    /**
     * Cron job action
     * Push in store order updates
     *
     * @since		1.0.0
     * @access      public
     */
    public function shelfless_sched_push_in_store_order_updates() {

        Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( __CLASS__ . '->' . __FUNCTION__ );
        $admin = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin( $this->plugin_name, $this->version);
        $admin->shelfless_sched_push_in_store_order_updates();
        unset( $admin );

    }

    /**
     * Cron job action
     * Sync product changes
     *
     * @since		1.0.0
     * @access      public
     */
    public function shelfless_sched_sync_product_changes() {

        Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( __CLASS__ . '->' . __FUNCTION__ );
        $admin = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin( $this->plugin_name, $this->version);

        // Inventory Settings -> Stocks tab
        // get Sync product setting value to decide for article create/update 
        $is_sync = ( get_option( $this->plugin_name . '_inventory_is_sync_products' ) == 1 ? true : false );

        if ( $is_sync ) { 
            $admin->shelfless_sched_push_product_changes();
            unset( $admin );
        }

    }

    /**
     * Cron job action
     * Checks for pending product changes
     * 1 - create, 2 - update operations
     *
     * @since		1.2.0
     * @access      private
     */
    private function shelfless_is_pending_product_changes() { 

        $is_pending_product_changes = false;

        $inventory = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Inventory( $this->plugin_name );

        $products = $inventory->get_products(
            array(
                'limit'         => -1,
                'status'        => 'publish',
                'meta_key'      => '_' . $this->plugin_name . '_article_sync_operation',
                'meta_compare'  => '>',
                'meta_value'    => '0'
            )
        );

        if ( ! empty( $products ) )
            $is_pending_product_changes = true;

        unset( $inventory );
        
        return $is_pending_product_changes;

    }

}