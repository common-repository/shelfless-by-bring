<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @link       https://www.bring.no/
 * @since      1.0.0
 *
 * @package    Bring_3pl_Shelfless_Fulfillment_For_Woocommerce\Uninstall
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb, $wp_version;

$plugin_name = 'bring_3pl_shelfless_fulfillment_for_woocommerce';

$api_settings = array(
	// API settings options
	$plugin_name . '_mybring_customer_id',
	$plugin_name . '_shelfless_api_key',
	$plugin_name . '_shelfless_api_secret_key',
	$plugin_name . '_shelfless_api_mode',
	$plugin_name . '_shelfless_api_version',
	$plugin_name . '_shelfless_debug_mode',
	$plugin_name . '_shelfless_api_setup_is_complete',
	$plugin_name . '_shelfless_inventory_linkages',
	
	// Inventory settings options
	$plugin_name . '_inventory_is_manage_stock',
	$plugin_name . '_inventory_is_show_notif_low_stock',
	$plugin_name . '_inventory_is_show_notif_oos',
	$plugin_name . '_inventory_is_use_cost_price_field',
	$plugin_name . '_inventory_cost_price_currency',
	$plugin_name . '_inventory_low_threshold_value',
	$plugin_name . '_inventory_is_use_customs_field',
	$plugin_name . '_inventory_default_country_of_origin',
	$plugin_name . '_inventory_basic_unit',
	$plugin_name . '_inventory_is_fulfill_products',

	// Order settings options
	$plugin_name . '_order_process_from_days_ago',
	$plugin_name . '_order_is_process_export',
	$plugin_name . '_order_add_bring_statuses',
	$plugin_name . '_order_enabled_bring_statuses',
	$plugin_name . '_order_is_show_notif_partial_shipment',
	$plugin_name . '_order_is_show_notif_cancelled',
	$plugin_name . '_order_status_fulfill_status',
	$plugin_name . '_order_status_cancel_fulfill_status',
	$plugin_name . '_order_status_partially_fulfilled_status',
	$plugin_name . '_order_status_shipped_status',
	$plugin_name . '_order_status_complete_status',
	$plugin_name . '_order_status_cancel_status',
	$plugin_name . '_order_fallback_service_carrier',
	$plugin_name . '_order_fallback_service_code',

	// Schedule settings options
	$plugin_name . '_sched_fetch_inventory_updates',
	$plugin_name . '_sched_send_orders',
	$plugin_name . '_sched_fetch_order_updates',
	$plugin_name . '_sched_push_in_store_order_updates',
);

// Deleting everything.
foreach ( $api_settings as $api_setting ) {

	delete_option( $api_setting );

}

delete_transient( $plugin_name . '_articles_inventory_' . get_current_blog_id() );
delete_transient( $plugin_name . '_endpoint_token_' . get_current_blog_id() . '_' . get_current_user_id() );

// Deactivate pending scheduled actions
if ( function_exists( 'as_unschedule_all_actions' ) ) {

	$args = array();
	$group_name = 'shelfless-by-bring';

	as_unschedule_all_actions( 'shelfless_fetch_inventory_updates', $args, $group_name );
	as_unschedule_all_actions( 'shelfless_fulfill_orders', $args, $group_name );
	as_unschedule_all_actions( 'shelfless_fetch_order_updates', $args, $group_name );
	as_unschedule_all_actions( 'shelfless_push_in_store_order_updates', $args, $group_name );

}

// Clear any cached data that has been removed. - Harvey
wp_cache_flush();