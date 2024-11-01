<?php
/**
 * Plugin Name:             Shelfless by Bring
 * Plugin URI:              https://www.bring.no/radgivning/netthandel/shelfless
 * Description:             Shelfless by Bring plugin integrates Bring's Mybring and Shelfless solutions to WooCommerce. This plugin needs an account from Bring such as Mybring Customer ID, API Key and API Secret Key in order to work. You may get these details from your Mybring account or from a Bring executive handling your account.
 * Author:                  Bring
 * Author URI:              https://www.bring.no/
 * Text Domain:             bring-3pl-shelfless-fulfillment-for-woocommerce
 * Domain Path:             /languages
 * Version:                 1.3.0
 * Requires at least:       5.6.1
 * Tested up to:            6.1
 * License:					GPLv2 or later
 * License URI:				https://www.gnu.org/licenses/gpl-2.0.html
 *
 * WC requires at least:    5.2.2
 * WC tested up to:         6.7.0
 *
 * @package                 WooCommerce
 * @category                Inventory and Fulfillment
 * @author                  Shelfless by Bring
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! defined( 'BRING_3PL_SHELFLESS_FULFILLMENT_FOR_WOOCOMMERCE_VERSION' ) ) {
	define( 'BRING_3PL_SHELFLESS_FULFILLMENT_FOR_WOOCOMMERCE_VERSION', '1.3.0' );
}

if ( ! defined( 'BRING_3PL_SHELFLESS_FULFILLMENT_FOR_WOOCOMMERCE_BASE' ) ) {
	define( 'BRING_3PL_SHELFLESS_FULFILLMENT_FOR_WOOCOMMERCE_BASE', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'BRING_3PL_SHELFLESS_FULFILLMENT_FOR_WOOCOMMERCE_ARTICLE_DESC_MAX_LENGTH' ) ) {
	define( 'BRING_3PL_SHELFLESS_FULFILLMENT_FOR_WOOCOMMERCE_ARTICLE_DESC_MAX_LENGTH', 255 ); 
}

if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

if ( ! defined( 'WOOCOMMERCE_NETWORK_ACTIVATED' ) ) {
	if ( is_multisite() && is_plugin_active_for_network( 'woocommerce/woocommerce.php') ) {
		define( 'WOOCOMMERCE_NETWORK_ACTIVATED', true );
	}
	else {
		define( 'WOOCOMMERCE_NETWORK_ACTIVATED', false );
	}
}

/**
 * This action is documented in includes/class-bring-3pl-shelfless-fulfillment-for-woocommerce-activator.php
 */
function activate_bring_3pl_shelfless_fulfillment_for_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bring-3pl-shelfless-fulfillment-for-woocommerce-activator.php';
	$shelfless = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Activator( 'bring_3pl_shelfless_fulfillment_for_woocommerce' );
	$shelfless->activate();
}

/**
 * This action is documented in includes/class-bring-3pl-shelfless-fulfillment-for-woocommerce-deactivator.php
 */
function deactivate_bring_3pl_shelfless_fulfillment_for_woocommerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bring-3pl-shelfless-fulfillment-for-woocommerce-deactivator.php';
	$shelfless = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Deactivator( 'bring_3pl_shelfless_fulfillment_for_woocommerce' );
	$shelfless->deactivate();
}

register_activation_hook( __FILE__, 'activate_bring_3pl_shelfless_fulfillment_for_woocommerce' );
register_deactivation_hook( __FILE__, 'deactivate_bring_3pl_shelfless_fulfillment_for_woocommerce' );

/**
 * Check if WooCommerce is active. This may fail if for any reason, WooCommerce folder is renamed to something else.
 * Also, this one is used because checking the WooCommerce install via class_exists yield a false-positive.
 * However, during activation, class_exists work. - Harvey
 **/
if ( ! is_network_admin() ) {

	if ( ! WOOCOMMERCE_NETWORK_ACTIVATED 
		&& ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		add_action(
			'admin_notices',
			function() {
				echo '<div class="error"><p><strong>' . sprintf( esc_html__( 'Shelfless by Bring requires the WooCommerce plugin to be installed and active. You can download %s here.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ), '<a href="https://wordpress.org/plugins/woocommerce/" target="_blank">WooCommerce</a>' ) . '</strong></p></div>';
			}
		);
	}

}

/**
 * Shelfless by Bring.
 * 
 * This is the main class for Shelfless by Bring and is responsible for
 * jobs such as connecting to Tiqqe's API, operations, internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-bring-3pl-shelfless-fulfillment-for-woocommerce.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-bring-3pl-shelfless-fulfillment-for-woocommerce-admin.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-bring-3pl-shelfless-fulfillment-for-woocommerce-actions.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-bring-3pl-shelfless-fulfillment-for-woocommerce-i18n.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-bring-3pl-shelfless-fulfillment-for-woocommerce-inventory.php';

$actions = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Actions( 'bring_3pl_shelfless_fulfillment_for_woocommerce' );
$plugin_i18n = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_i18n();
//$shelfless_delivery = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Shelfless_Delivery();

/**
 * Begins execution of Shelfless by Bring.
 *
 * @since    1.0.0
 */
function run_bring_3pl_shelfless_fulfillment_for_woocommerce() {

	$shelfless = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce();
	$shelfless->run();

}

add_action( 'plugins_loaded', 'run_bring_3pl_shelfless_fulfillment_for_woocommerce' );
add_action( 'plugins_loaded', array( $plugin_i18n, 'load_plugin_textdomain' ) );
