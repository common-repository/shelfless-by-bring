<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Fired during plugin deactivation
 *
 * @link       https://www.bring.no/
 * @since      1.0.0
 *
 * @package    Bring_3pl_Shelfless_Fulfillment_For_Woocommerce
 * @subpackage Bring_3pl_Shelfless_Fulfillment_For_Woocommerce/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Bring_3pl_Shelfless_Fulfillment_For_Woocommerce
 * @subpackage Bring_3pl_Shelfless_Fulfillment_For_Woocommerce/includes
 * @author     Tiqqe <bring_team@tiqqe.com>
 */
class Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Deactivator {

	protected $plugin_name;

	public function __construct( $plugin_name ) {
		$this->plugin_name = $plugin_name;
	}

	/**
	 * Performs actions during deactivation.
	 * 
	 * @since    1.0.0
	 */
	public function deactivate() {

		delete_transient( $this->plugin_name . '_articles_inventory_' . get_current_blog_id() );
		delete_transient( $this->plugin_name . '_endpoint_token_' . get_current_blog_id() . '_' .  get_current_user_id() );

		// Deactivate pending scheduled actions
		if ( function_exists( 'as_unschedule_all_actions' ) ) {
			$args = array();
			$group_name = 'shelfless-by-bring';
			as_unschedule_all_actions( 'shelfless_fetch_inventory_updates', $args, $group_name );
			as_unschedule_all_actions( 'shelfless_fulfill_orders', $args, $group_name );
			as_unschedule_all_actions( 'shelfless_fetch_order_updates', $args, $group_name );
			as_unschedule_all_actions( 'shelfless_push_in_store_order_updates', $args, $group_name );
		}

		wp_cache_flush();

	}

}
