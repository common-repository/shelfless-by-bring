<?php
/**
 * Bring_3pl_Shelfless_Fulfillment_For_Woocommerce Admin Functions
 *
 * Contains general core functions for administration of Shelfless by Bring.
 *
 * @link       https://www.bring.no/
 * @since      1.0.0
 *
 * @package    Bring_3pl_Shelfless_Fulfillment_For_Woocommerce
 * @subpackage Bring_3pl_Shelfless_Fulfillment_For_Woocommerce/includes
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Fills in the form for the API settings page.
 *
 * @since    1.0.0
 */
function generate_bring_shelfless_api_credentials_form() {

    $classes = generate_bring_shelfless_btn_classes();
    $screen = get_current_screen();
    $wizard = false;

    if ( $screen->id === 'toplevel_page_bring_3pl_shelfless_fulfillment_for_woocommerce_setup' ) {
        $wizard = true;
    }

    echo '<form 
        method="POST" 
        action="' . esc_html( ( ! $wizard ? admin_url( 'options.php' ) : admin_url( 'admin-ajax.php' ) ) ). '" 
        id="' . get_bring_shelfless_plugin_name() . '_api_creds_settings" >';
    
    if ( $wizard ) {
        $nonce = wp_create_nonce( 'nonce_' . get_bring_shelfless_plugin_name() . '_api_creds_wizard_mybring' );
        echo '<input type="hidden"
            id="nonce_wizard" 
            name="nonce_wizard" 
            value="' . esc_attr( $nonce ) . '" />';
    }
    else {
        settings_fields( get_bring_shelfless_plugin_name() );
    }
    do_settings_sections( get_bring_shelfless_plugin_name() );
    echo get_submit_button('', $classes, 'submit_settings', false);
    echo '<div id="shelfless-setup-loading" class="spinner-border text-secondary hide" role="status"><span class="sr-only">Loading...</span></div>';
    echo '<span id="shelfless-setup-saved" class="dashicons hide"></span>';
    echo '</form>';
    
}

/**
 * Builds the content (top portion) for the API settings page.
 *
 * @since    1.0.0
 */
function generate_bring_shelfless_api_section_content() { 
    $screen = get_current_screen();
    $wizard = false;

    if ( $screen->id === 'toplevel_page_bring_3pl_shelfless_fulfillment_for_woocommerce_setup' ) {
        $wizard = true;
    }

    if ( $wizard ) { 
        echo '<p class="lead">';
        esc_html_e( 'Welcome to the Setup Wizard for Shelfless by Bring. Follow these four quick steps to let Shelfless start to fulfill your orders and make happier customers!', 'bring-3pl-shelfless-fulfillment-for-woocommerce' );
        echo '</p>';
    } 

    if ( ! $wizard ) { 
        echo '<p class="lead">';
    } else {
        echo '<p>';
    }
    
    esc_html_e( 'Enter the information below. The information is provided by Shelfless.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' );
    echo '</p>';

}

/**
 * Builds the content (top portion) for the API diagnostic section content.
 *
 * @since    1.0.0
 */
function generate_bring_shelfless_api_diagnostic_section_content() { 

    $screen = get_current_screen();
    $wizard = false;

    if ( $screen->id === 'toplevel_page_bring_3pl_shelfless_fulfillment_for_woocommerce_setup' ) {
        $wizard = true;
    }

    if ( ! $wizard ) { 
        echo '<hr>';
    }

    echo '<p class="lead">';
    esc_html_e( 'Run test to check if you can connect with Shelfless using the above information.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' );
    echo '</p>';

}

/**
 * Builds the content (top portion) for the inventory settings section content.
 *
 * @since    1.0.0
 */
function generate_bring_shelfless_inventory_settings_section_content() {
    echo '<p class="lead">';
    esc_html_e( 'The options below let you customize how Shelfless manages your WooCommerce stocks.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' );
    echo '</p>';
}

/**
 * Builds the form for the API diagnostics page.
 *
 * @since    1.0.0
 */
function generate_bring_shelfless_api_diagnostics_form() {

    $classes = generate_bring_shelfless_btn_classes();

    echo '<form method="POST" 
        action="'. esc_html( admin_url( 'admin-ajax.php' ) ) .'" 
        id="'. get_bring_shelfless_plugin_name() . '_api_settings_diagnostics_mybring' . '">';
    $nonce = wp_create_nonce( 'nonce_' . get_bring_shelfless_plugin_name() . '_api_settings_diagnostics_mybring' );
    echo '<input type="hidden"
        id="nonce" 
        name="nonce" 
        value="' . esc_attr( $nonce ) . '" />';
    do_settings_sections( get_bring_shelfless_plugin_name() . '_api_settings_diagnostics_mybring' );
    echo '<button id="run-diagnostics-api-settings" type="button" class="' . esc_attr( $classes ) . '">' . esc_html__( 'Run Test', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) .  '</button>';
    echo '<div id="diagnostics-results" class="diagnostics-results">';
    echo '<p class="lead">' . esc_html__( 'Diagnostics Results', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) . '</p>';
    echo '<div id="shelfless-diagnostics-loading" class="spinner-border text-secondary" role="status"><span class="sr-only">Loading...</span></div>';
    echo '<pre id="diagnostics-results-text">Diagnostics results are displayed here...</pre>';
    echo '</div>';
    echo '</form>';

}

/**
 * Builds the form for the API diagnostics delete transient section.
 *
 * @since    1.0.0
 */
function generate_bring_shelfless_api_diagnostics_delete_transient_form() {

    $classes = generate_bring_shelfless_btn_classes();

    echo '<form method="POST" action="'. esc_html( admin_url( 'admin-ajax.php' ) ) .'" id="'. get_bring_shelfless_plugin_name() .'_api_settings_diagnostics_delete_transient_mybring">';
    $nonce_transients = wp_create_nonce( 'nonce_' . get_bring_shelfless_plugin_name() . '_api_settings_diagnostics_delete_transient_mybring' );
    echo '<input type="hidden" id="nonce_delete_transients" name="nonce_delete_transients" value="'. $nonce_transients .'"/>';
    do_settings_sections( get_bring_shelfless_plugin_name() . '_api_settings_diagnostics_delete_transient_mybring' );
    echo '<hr>';
    $url = sanitize_url('https://developer.wordpress.org/apis/handbook/transients/');
    echo '<p class="lead">'. sprintf( 'The button below will delete all Shelfless related <a href="%s" target="_blank">transients/cached</a> data.', esc_url( $url )) .'</p>';
    echo '<p>'. __( 'Shelfless updates transients and cached automatically (usually at every hour). If you see that Shelfless has been storing the same data for quite some time, you can force delete them to have Shelfless fetch again before the next schedule.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) .'</p>';
    echo '<button id="run_diagnostics_delete_transients" type="button" class="' . esc_attr( $classes ) . '">' . esc_html__( 'Delete Transients', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) .'</button>';
    echo '<div id="delete_transients_results" class="delete_transients_results">';
    echo '<div id="shelfless_delete_transients_loading" class="spinner-border text-secondary" role="status"><span class="sr-only">Loading...</span></div>';
    echo '<pre id="delete_transients_results_text">Deleted transients will be displayed here...</pre>';
    echo '</div>';
    echo '</form>';

}

/**
 * Builds the API wizard finish/complete form
 *
 * @since    1.0.0
 */
function generate_bring_shelfless_api_wizard_finish_form() {

    echo '<form 
        method="POST" 
        action="' . esc_html( admin_url( 'admin-post.php' ) ). '" 
        id="' . get_bring_shelfless_plugin_name() . '_api_creds_wizard_mybring_finish' . '" >';
    $nonce = wp_create_nonce( 'nonce_' . get_bring_shelfless_plugin_name() . '_api_creds_wizard_mybring_finished' );
    echo '<input type="hidden"
        id="nonce_finished" 
        name="nonce_finished" 
        value="' . esc_attr( $nonce ) . '" />';
    echo '<input type="hidden"
        id="action_finished" 
        name="action" 
        value="bring_shelfless_setup_wizard_page_finished" />';
    echo '<button type="button" data-toggle="#scheduler-inventory" class="btn btn-secondary prev prev-step"><span class="dashicons dashicons-arrow-left-alt2"></span>' . esc_html__( 'Previous', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) . '</button> ';
    echo '<button type="submit" data-toggle="finished" class="btn btn-success finish">' . esc_html__( 'Finish', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) . '<span class="dashicons dashicons-yes"></span></button>';
    echo '</form>';

}

/**
 * Builds the API wizard skip form
 *
 * @since    1.0.0
 */
function generate_bring_shelfless_api_wizard_skip_form() {
    echo '<form 
        method="POST" 
        action="' . esc_html( admin_url( 'admin-post.php' ) ). '" 
        id="' . get_bring_shelfless_plugin_name() . '_api_creds_wizard_mybring_skip' . '" >';
    $nonce = wp_create_nonce( 'nonce_' . get_bring_shelfless_plugin_name() . '_api_creds_wizard_mybring_finished' );
    echo '<input type="hidden"
        id="nonce_skip_wizard" 
        name="nonce_finished" 
        value="' . esc_attr( $nonce ) . '" />';
    echo '<input type="hidden"
        id="action_skip_wizard" 
        name="action" 
        value="bring_shelfless_setup_wizard_page_finished" />';
    echo '<button type="submit" data-toggle="finished" class="btn btn-light finish">' . esc_html__( 'Skip Configuration and Close This Wizard', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) . '</button>';
    echo '</form>';
}

/**
 * Fills in the form for the Product Inventory Settings - Global level
 *
 * @since    1.0.0
 */
function generate_bring_shelfless_inventory_settings_form() {

    $classes = generate_bring_shelfless_btn_classes();
    $screen = get_current_screen();
    $wizard = false;

    if ( $screen->id === 'toplevel_page_bring_3pl_shelfless_fulfillment_for_woocommerce_setup' ) {
        $wizard = true;
    }

    echo '<form 
        method="POST" 
        action="' . esc_html( ( ! $wizard ? admin_url( 'options.php' ) : admin_url( 'admin-ajax.php' ) ) ) . '" 
        id="' . get_bring_shelfless_plugin_name() . '_inventory_settings_mybring' . '" >';

    if ( $wizard ) {
        $nonce = wp_create_nonce( 'nonce_' . get_bring_shelfless_plugin_name() . '_inventory_settings_mybring' );
        echo '<input type="hidden"
            id="nonce_inv_settings" 
            name="nonce" 
            value="' . esc_attr( $nonce ) . '" />';
    }
    else {
        settings_fields( get_bring_shelfless_plugin_name() . '_inventory_settings' );
    }
    do_settings_sections( get_bring_shelfless_plugin_name() . '_inventory_settings' );

    echo get_submit_button('', $classes, 'submit_inventory_stock_settings', false);
    echo '<div id="shelfless-inv-setup-loading" class="spinner-border text-secondary hide" role="status"><span class="sr-only">Loading...</span></div>';
    echo '<span id="shelfless-inv-setup-saved" class="dashicons hide"></span>';
    echo '</form>';

}

/**
 * Builds the content (top portion) for the inventory settings product grid content.
 *
 * @since    1.0.0
 */
function generate_bring_shelfless_inventory_settings_product_grid_content() {

    echo '<p class="lead">';
    esc_html_e( 'Below you can choose, per product, which articles should be fulfilled and synchronized with Shelfless.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' );
    echo '</p>';

}

/**
 * Builds the display for the products grid form content allowing user to fulfill and sync.
 *
 * @since    1.0.0
 */
function generate_bring_shelfless_inventory_grid() {

    $classes = generate_bring_shelfless_btn_classes();

    do_settings_sections( get_bring_shelfless_plugin_name() . '_inventory_settings_product_grid' );

    echo '<div id="bring-shelfless-inventory-datatables-container">
        <table id="bring-shelfless-inventory-datatables" class="display" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th><input name="select_all" value="1" id="inventory-select-all" type="checkbox" /> Fulfill</th>
                    <th><input name="sync_all" value="1" id="inventory-sync-all" type="checkbox" /> Sync</th>
                    <th>Image</th>
                    <th>Product</th>
                    <th>SKU</th>
                    <th>Type</th>
                    <th>Shelfless</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>Fulfill</th>
                    <th>Sync</th>
                    <th>Image</th>
                    <th>Product</th>
                    <th>SKU</th>
                    <th>Type</th>
                    <th>Shelfless</th>
                </tr>
            </tfoot>
        </table>
    </div>';

    echo '<br />';
    echo '<form 
            method="POST" 
            action="' . esc_html( admin_url( 'admin-ajax.php' ) ) . '" 
            id="' . get_bring_shelfless_plugin_name() . '_inventory_settings_pull_inv_grid_mybring' . '" >';
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
    echo '<button id="populate_inventory_grid" type="button" class="' . esc_attr( $classes ) . '">' . esc_html__( 'Refresh Grid', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) .  '</button>';

    echo '</form>';
  
}

/**
 * Builds the display for the stock adjustment report submenu under Reports left menu
 * 
 * @since   1.0.0
 */
function generate_bring_shelfless_stock_adjustment_report() { 

    $classes = generate_bring_shelfless_btn_classes();

    do_settings_sections( get_bring_shelfless_plugin_name() . '_inventory_settings_stock_adjustment_report' );

    $tr = '<tr>
            <th>Customer #</th>
            <th>SKU</th>
            <th>Warehouse</th>
            <th>Adjustment</th>
            <th>Unit</th>
            <th>BalanceType</th>
            <th>Reason</th>
            <th>Source Created</th>
            <th>Event Created</th>
            <th>Batch #</th>
        </tr>';

    echo '<div id="bring-shelfless-stock-adjustment-datatables-container">
        <table id="bring-shelfless-stock-adjustment-datatables" class="display" cellspacing="0" width="100%">
            <thead>
                '. $tr .'
            </thead>
            <tfoot>
                '. $tr .'
            </tfoot>
        </table>
    </div>';

    echo '<br />';
    echo '<form 
            method="POST" 
            action="' . esc_html( admin_url( 'admin-ajax.php' ) ) . '" 
            id="' . get_bring_shelfless_plugin_name() . '_inventory_settings_pull_stock_adjustment_report_mybring' . '" >';
    $nonce = wp_create_nonce( 'nonce_' . get_bring_shelfless_plugin_name() . '_inventory_settings_pull_stock_adjustment_report_mybring' );
    echo '<input type="hidden"
        id="nonce_pull_stock_adjustment_report" 
        name="nonce_pull_stock_adjustment_report" 
        value="' . esc_attr( $nonce ) . '" />';
        
    $nonce_match = wp_create_nonce( 'nonce_' . get_bring_shelfless_plugin_name() . '_inventory_settings_match_inv_grid_mybring' );
    echo '<input type="hidden"
        id="nonce_match_stock_adjustment_report" 
        name="nonce_match_stock_adjustment_report" 
        value="' . esc_attr( $nonce_match ) . '" />';
    echo '<button id="populate_inventory_stock_adjustment_report_grid" type="button" class="' . esc_attr( $classes ) . '">' . esc_html__( 'Refresh Report', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) .  '</button>';

    echo '</form>';

}

/**
 * Builds the content (top portion) for the order settings content.
 *
 * @since    1.0.0
 */
function generate_bring_shelfless_order_settings_content() {

    echo '<p class="lead">';
    esc_html_e( 'These will serve as default values for Shelfless to use in all the orders.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' );
    echo '</p>';

}

/**
 * Builds the content (top portion) for the order settings status mapping content.
 *
 * @since    1.0.0
 */
function generate_bring_shelfless_order_settings_status_maps_content() {

    echo '<p class="lead">';
    esc_html_e( 'Shelfless needs to know the correct statuses on WooCommerce to set to when processing and shipping orders.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' );
    echo '</p>';

}

/**
 * Builds the form content for the order settings left menu.
 *
 * @since    1.0.0
 */
function generate_bring_shelfless_order_settings_form() {

    $classes = generate_bring_shelfless_btn_classes();
    $screen = get_current_screen();
    $wizard = false;

    if ( $screen->id === 'toplevel_page_bring_3pl_shelfless_fulfillment_for_woocommerce_setup' ) {
        $wizard = true;
    }

    echo '<form 
        method="POST" 
        action="' . esc_html( ( ! $wizard ? admin_url( 'options.php' ) : admin_url( 'admin-ajax.php' ) ) ) . '" 
        id="' . get_bring_shelfless_plugin_name() . '_order_settings_mybring' . '" >';

    if ( $wizard ) {
        $nonce = wp_create_nonce( 'nonce_' . get_bring_shelfless_plugin_name() . '_order_settings_mybring' );
        echo '<input type="hidden"
            id="nonce_order_settings" 
            name="nonce" 
            value="' . esc_attr( $nonce ) . '" />';
    }
    else {
        settings_fields( get_bring_shelfless_plugin_name() . '_order_settings_general' );
    }
    do_settings_sections( get_bring_shelfless_plugin_name() . '_order_settings_general' );

    echo get_submit_button('', $classes, 'submit_order_settings', false);
    echo '<div id="shelfless-order-setup-loading" class="spinner-border text-secondary hide" role="status"><span class="sr-only">Loading...</span></div>';
    echo '<span id="shelfless-order-setup-saved" class="dashicons hide"></span>';
    echo '</form>';

}

/**
 * Builds the form content for the order status mapping settings.
 *
 * @since    1.0.0
 */
function generate_bring_shelfless_order_status_maps_settings_form() {

    $classes = generate_bring_shelfless_btn_classes();
    $screen = get_current_screen();
    $wizard = false;

    if ( $screen->id === 'toplevel_page_bring_3pl_shelfless_fulfillment_for_woocommerce_setup' ) {
        $wizard = true;
    }

    echo '<form 
        method="POST" 
        action="' . esc_html( ( ! $wizard ? admin_url( 'options.php' ) : admin_url( 'admin-ajax.php' ) ) ) . '" 
        id="' . get_bring_shelfless_plugin_name() . '_order_status_maps_settings_mybring' . '" >';

    if ( $wizard ) {
        $nonce = wp_create_nonce( 'nonce_' . get_bring_shelfless_plugin_name() . '_order_status_maps_settings_mybring' );
        echo '<input type="hidden"
            id="nonce_order_status_maps_settings" 
            name="nonce" 
            value="' . esc_attr( $nonce ) . '" />';
    }
    else {
        settings_fields( get_bring_shelfless_plugin_name() . '_order_status_maps_settings' );
    }
    do_settings_sections( get_bring_shelfless_plugin_name() .'_order_status_maps_settings' );

    echo get_submit_button('', $classes, 'submit_order_status_maps_settings', false);
    echo '<div id="shelfless-inv-setup-loading" class="spinner-border text-secondary hide" role="status"><span class="sr-only">Loading...</span></div>';
    echo '<span id="shelfless-inv-setup-saved" class="dashicons hide"></span>';
    echo '</form>';

}

/**
 * Builds the form content for the scheduled cron job actions.
 *
 * @since    1.0.0
 */
function generate_bring_shelfless_scheduled_actions_form() {

    $classes = generate_bring_shelfless_btn_classes();
    $screen = get_current_screen();
    $wizard = false;

    if ( $screen->id === 'toplevel_page_bring_3pl_shelfless_fulfillment_for_woocommerce_setup' ) {
        $wizard = true;
    }

    $actions = array(
        array(
            'name'      => get_bring_shelfless_plugin_name() .'_sched_fetch_inventory_updates',
            'label'     => esc_html__( 'Fetch available stock levels from Shelfless to WooCommerce', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
            'value'     => get_option( get_bring_shelfless_plugin_name() .'_sched_fetch_inventory_updates' ),
            'default'   => 'daily'
        ),
        array(
            'name'  => get_bring_shelfless_plugin_name() .'_sched_send_orders',
            'label' => esc_html__( 'Fulfill orders', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
            'value' => get_option( get_bring_shelfless_plugin_name() .'_sched_send_orders' ),
            'default'   => 'hourly'
        ),
        array(
            'name'  => get_bring_shelfless_plugin_name() .'_sched_fetch_order_updates',
            'label' => esc_html__( 'Fetch order updates from Shelfless to WooCommerce', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
            'value' => get_option( get_bring_shelfless_plugin_name() .'_sched_fetch_order_updates' ),
            'default'   => 'hourly'
        ),
    );

    echo '<form 
        method="POST" 
        action="' . esc_html( ( ! $wizard ? admin_url( 'admin-post.php' ) : admin_url( 'admin-ajax.php' ) ) ) . '" 
        id="' . get_bring_shelfless_plugin_name() . '_scheduled_actions_mybring' . '" >';
    $nonce = wp_create_nonce( 'nonce_' . get_bring_shelfless_plugin_name() . '_scheduled_actions_mybring' );
    echo '<input type="hidden"
        id="nonce_scheduled_actions" 
        name="nonce_scheduled_actions" 
        value="' . esc_attr( $nonce ) . '" />';
    if ( $wizard ) {
        echo '<input type="hidden"
        id="initiator_schedule_actions" 
        name="initiator" 
        value="wizard" />';
    }
    echo '<input type="hidden"
        id="action_schedule_actions" 
        name="action" 
        value="bring_shelfless_schedule_action_updates" />';

    do_settings_sections( get_bring_shelfless_plugin_name() . '_scheduled_actions_mybring' );

    echo '<div id="scheduled-actions" class="row">
        <div class="col-md-8 col-lg-12 col-sm-12">
        <div class="table-responsive">
            <table class="table table-striped table-borderless">
                <thead>
                    <tr>
                        <th scope="col" id="label_action" class="align-middle align-center text-center">' . esc_html__('Actions', 'bring-3pl-shelfless-fulfillment-for-woocommerce') . '</th>
                        <th scope="col" class="p-0 text-center">
                            <table class="table mb-0">
                                <tbody>
                                    <tr class="equal-cols">
                                        <td class="text-center" colspan="5">Schedule</td>
                                    </tr>
                                </tbody>
                            </table>
                        </th>
                    </tr>
                </thead>
                <tbody>';
                $url = sanitize_url('https://wintelguy.com/crontab-generator.pl');
                if ( ! $wizard ) : 
                    echo '<caption class="text-center"><div id="notify"></div><small>' . sprintf( esc_html__( '*To set the scheduled actions custom, use format for cron expression. Go %s to generate correct format.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ), '<a href="'. esc_url( $url ) .'" target="_blank">here</a>' ) . '</small></caption>';
                endif;

                foreach ( $actions as $action ) : 
                    if ( $wizard && $action['name'] == get_bring_shelfless_plugin_name() .'_sched_push_in_store_order_updates' ) : 
                        continue;
                    endif;
                    $td = '';
                    if ( ! $wizard ) : 
                        $td = '<td>
                            <input class="shelfless-action-sched"' . ( ! in_array( $action['value'], array( 'hourly', 'weekly', 'daily', 'disable' ) ) ? ' checked' : ( empty( $action['value'] ) ? 'checked' : '' ) ) . ' type="radio" data-value="custom" value="custom" name="' . $action['name'] . '" id="' . $action['name'] . '_specific_time" /><label for="' . $action['name'] . '_specific_time">' . esc_html__('Custom *', 'bring-3pl-shelfless-fulfillment-for-woocommerce') . '</label>
                            <input class="form-control form-control-sm sched-action-specific-time" placeholder="* * * * *" value="' . ( ! in_array( $action['value'], array( 'hourly', 'weekly', 'daily', 'disable' ) ) ? $action['value'] . '" ': '" disabled' ) . ' type="text" name="' . $action['name'] . '_specific_time" />
                        </td>';
                    endif;

                    echo '<tr>
                    <td>'. esc_html__( $action['label'], 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) .'</td>
                    <td class="p-0">
                        <table class="table mb-0 bring-shelfless-schedule-actions">
                            <tbody>
                                <tr class="equal-cols">
                                    <td><input class="shelfless-action-sched"' . ( $action['value'] === 'disable' ? ' checked' : '' ) . ' type="radio" data-value="disable" value="disable" name="' . $action['name'] . '" id="' . $action['name'] . '_disable" /><label for="' . $action['name'] . '_disable">' . esc_html__('Disable', 'bring-3pl-shelfless-fulfillment-for-woocommerce') . '</label></td>
                                    <td><input class="shelfless-action-sched"' . ( $action['value'] === 'hourly' ? ' checked' : '' ) . ' type="radio" data-value="hourly" value="hourly" name="' . $action['name'] . '" id="' . $action['name'] . '_hourly" /><label for="' . $action['name'] . '_hourly">' . esc_html__('Hourly', 'bring-3pl-shelfless-fulfillment-for-woocommerce') . '</label></td>
                                    <td><input class="shelfless-action-sched"' . ( $action['value'] === 'daily' ? ' checked' : ( empty( $action['value'] ) ? 'checked' : '' ) ) . ' type="radio" data-value="daily" value="daily" name="' . $action['name'] . '" id="' . $action['name'] . '_daily" /><label for="' . $action['name'] . '_daily">' . esc_html__('Daily', 'bring-3pl-shelfless-fulfillment-for-woocommerce') . '</label></td>
                                    <td><input class="shelfless-action-sched"' . ( $action['value'] === 'weekly' ? ' checked' : '' ) . ' type="radio" data-value="weekly" value="weekly" name="' . $action['name'] . '" id="' . $action['name'] . '_weekly" /><label for="' . $action['name'] . '_weekly">' . esc_html__('Weekly', 'bring-3pl-shelfless-fulfillment-for-woocommerce') . '</label></td>
                                    '. $td .'
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>';
                endforeach;
                echo '<tr>
                        <td></td>
                        <td colspan="3">';
                echo get_submit_button('', $classes, 'submit_action_sched', false);
                echo '<div id="shelfless-sched-action-loading" class="spinner-border text-secondary hide" role="status"><span class="sr-only">Loading...</span></div>';
                echo '<span id="shelfless-sched-action-saved" class="dashicons hide"></span>';
                echo '</td>
                    </tr>';
                
                echo '</tbody>
            </table>
        </div>
        </div>
    </div>
    </form>';

}

/**
 * Build the General help content
 * 
 * @since   1.0.0
 */
function generate_help_general_settings_other_information() {
    include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/views/bring-3pl-help-general-settings-other-information.php';
}

/**
 * Build the Invetory settings help content
 * 
 * @since   1.0.0
 */
function generate_help_inventory_settings() { 
    include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/views/bring-3pl-help-inventory-settings.php';
}

/**
 * Build the Order settings help content
 * 
 * @since   1.0.0
 */
function generate_help_order_settings() { 
    include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/views/bring-3pl-help-order-settings.php';
}

/**
 * Build the Help page User Guide content
 * 
 * @since   1.0.0
 */
function generate_help_user_guide_tab_settings() { 
    include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/views/bring-3pl-help-user-guide-tab.php';
}

/**
 * Build the Help page FAQ content
 * 
 * @since   1.0.0
 */
function generate_help_faq_tab_settings() { 
    include_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/views/bring-3pl-help-faq-tab.php';
}

/**
 * Builds the content (top portion) for the order settings status mapping content.
 *
 * @since    1.0.0
 */
function generate_bring_shelfless_order_settings_shipping_maps_content() { 
    
    echo '<p class="lead">';
    esc_html_e( 'Below are the WooCommerce shipping methods that are fetched from your settings. Add a corresponding shipping service to each shipping method.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' );
    echo '</p>'; 

    $url = sanitize_url( site_url('/wp-admin/admin.php?page=wc-settings&tab=shipping') );
    echo '<p>' . sprintf( esc_html__( 'The following shipping methods can be changed from WooCommerce > Settings > %s tab.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ), '<a href="'. esc_url( $url ) .'" target="_blank">Shipping</a>' ) . '</p>';

    $table = '<table class="form-table" role="presentation">
        <tbody>
            <tr>
                <th scope="row" class="font-weight-bold"><label>WooCommerce shipping method</label></th>
                <td class="font-weight-bold"><label>Shipping service</label></td>
            </tr>
        </tbody>
    </table>';

    echo $table;

}

/**
 * Builds the form content for the order status mapping settings.
 *
 * @since    1.0.0
 */
function generate_bring_shelfless_order_shipping_maps_settings_form() {

    $classes = generate_bring_shelfless_btn_classes();
    $screen = get_current_screen();
    $wizard = false;

    if ( $screen->id === 'toplevel_page_bring_3pl_shelfless_fulfillment_for_woocommerce_setup' ) {
        $wizard = true;
    }

    echo '<form 
        method="POST" 
        action="' . esc_html( ( ! $wizard ? admin_url( 'options.php' ) : admin_url( 'admin-ajax.php' ) ) ) . '" 
        id="' . get_bring_shelfless_plugin_name() . '_order_shipping_maps_settings_mybring' . '" >';

    if ( $wizard ) {
        $nonce = wp_create_nonce( 'nonce_' . get_bring_shelfless_plugin_name() . '_order_shipping_maps_settings_mybring' );
        echo '<input type="hidden"
            id="nonce_order_shipping_maps_settings" 
            name="nonce" 
            value="' . esc_attr( $nonce ) . '" />';
    }
    else {
        settings_fields( get_bring_shelfless_plugin_name() . '_order_shipping_maps_settings' );
    }
    do_settings_sections( get_bring_shelfless_plugin_name() .'_order_shipping_maps_settings' );

    echo get_submit_button('', $classes, 'submit_order_shipping_maps_settings', false);
    echo '<div id="shelfless-shipping-maps-setup-loading" class="spinner-border text-secondary hide" role="status"><span class="sr-only">Loading...</span></div>';
    echo '<span id="shelfless-shipping-maps-setup-saved" class="dashicons hide"></span>';
    echo '</form>';

}

/**
 * Builds the content (top portion) for the dream logistics settings content.
 *
 * @since    1.2.1
 */
function generate_bring_shelfless_dream_logistics_settings_content() {

    echo '<p class="lead">';
    esc_html_e( 'If you are an existing Dream Logistics partner, enter the details below.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' );
    echo '</p>';

}

/**
 * Builds the form content for the dream logistics actions.
 *
 * @since    1.2.1
 */
function generate_bring_shelfless_dream_logistics_form() {

    $classes = generate_bring_shelfless_btn_classes();
    $screen = get_current_screen();
    $wizard = false;

    if ( $screen->id === 'toplevel_page_bring_3pl_shelfless_fulfillment_for_woocommerce_setup' ) {
        $wizard = true;
    }

    echo '<form 
        method="POST" 
        action="' . esc_html( ( ! $wizard ? admin_url( 'options.php' ) : admin_url( 'admin-ajax.php' ) ) ) . '" 
        id="' . get_bring_shelfless_plugin_name() . '_dream_logistics_settings_mybring' . '" >';

    if ( $wizard ) {
        $nonce = wp_create_nonce( 'nonce_' . get_bring_shelfless_plugin_name() . '_dream_logistics_settings_mybring' );
        echo '<input type="hidden"
            id="nonce_dream_logistics_settings" 
            name="nonce" 
            value="' . $nonce . '" />';
    }
    else {
        settings_fields( get_bring_shelfless_plugin_name() . '_dream_logistics_settings' );
    }

    do_settings_sections( get_bring_shelfless_plugin_name() . '_dream_logistics_settings' );

    echo get_submit_button('', $classes, 'submit_dream_logistics_settings', false);
    echo '<div id="shelfless-dream-logistics-loading" class="spinner-border text-secondary hide" role="status"><span class="sr-only">Loading...</span></div>';
    echo '<span id="shelfless-dream-logistics-saved" class="dashicons hide"></span>';
    echo '</form>';

}

/**
 * Builds the form content for the scheduled actions.
 *
 * @since    1.2.1
 */
function generate_bring_shelfless_scheduled_actions_maps_content() {
    $screen = get_current_screen();
    echo '<p class="lead">';
    esc_html_e( 'Below you set how often you want information to be exchanged between WooCommerce and Shelfless.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' );
    echo '</p>';

    if ( $screen->id === 'toplevel_page_bring_3pl_shelfless_fulfillment_for_woocommerce_setup' ) { 
        echo '<p>';
        esc_html_e( 'You can change this and add advanced settings later in Shelfless by Bring > General > Scheduled Actions', 'bring-3pl-shelfless-fulfillment-for-woocommerce' );
        echo '</p>';
    } else {
        echo '<p>';
        esc_html_e( 'Recommended settings for small and middles sized merchants are “daily” for fetching stock levels and “hourly” to fulfill orders and fetch order updates. The recommended settings will give your customers fast delivery and avoiding Shelfless to overwrite WooCommerce stock levels before the order has been fulfilled.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' );
        echo '</p>';
    }

}

/**
 * Fills in the form for the Products & Stocks - Product tab - Global level
 *
 * @since    1.2.1
 */
function generate_bring_shelfless_products_form() {

    $classes = generate_bring_shelfless_btn_classes();

    echo '<form 
        method="POST" 
        action="' . esc_html( admin_url( 'options.php' ) ) . '" 
        id="' . get_bring_shelfless_plugin_name() . '_inventory_settings_products_mybring' . '" >';

    
    settings_fields( get_bring_shelfless_plugin_name() . '_inventory_settings_products' );

    do_settings_sections( get_bring_shelfless_plugin_name() . '_inventory_settings_products' );

    echo get_submit_button('', $classes, 'submit_inventory_products_settings', false);
    echo '<div id="shelfless-inv-setup-loading" class="spinner-border text-secondary hide" role="status"><span class="sr-only">Loading...</span></div>';
    echo '<span id="shelfless-inv-setup-saved" class="dashicons hide"></span>';
    echo '</form>';

}

/**
 * Builds the content (top portion) for the products & stocks section content.
 *
 * @since    1.2.1
 */
function generate_bring_shelfless_inventory_settings_products_section_content() {
    $screen = get_current_screen();

    if ( $screen->id === 'toplevel_page_bring_3pl_shelfless_fulfillment_for_woocommerce_setup' ) { 
        echo '<hr>';
    } 
    
    echo '<h6>Product Management</h6>';
    echo '<p>The options below let you customize how Shelfless manages your WooCommerce products and if Shelfless is allowed to synchronize products to the warehouse.</p>';
}

/**
 * Fills in the form for the Shippings - fallback carriers tab - Global level
 *
 * @since    1.2.1
 */
function generate_bring_shelfless_shipping_setting_fallback_form() {

    $classes = generate_bring_shelfless_btn_classes();

    echo '<form 
        method="POST" 
        action="' . esc_html( admin_url( 'options.php' ) ) . '" 
        id="' . get_bring_shelfless_plugin_name() . '_shipping_settings_fallback_mybring' . '" >';

    
    settings_fields( get_bring_shelfless_plugin_name() . '_shipping_settings_fallback' );

    do_settings_sections( get_bring_shelfless_plugin_name() . '_shipping_settings_fallback' );

    echo get_submit_button('', $classes, 'submit_inventory_products_settings', false);
    echo '<div id="shelfless-inv-setup-loading" class="spinner-border text-secondary hide" role="status"><span class="sr-only">Loading...</span></div>';
    echo '<span id="shelfless-inv-setup-saved" class="dashicons hide"></span>';
    echo '</form>';

}

/**
 * Builds the content (top portion) for the shipping settings - fallback carrier section content.
 *
 * @since    1.2.1
 */
function generate_bring_shelfless_shipping_settings_fallback_section_content() {
    echo '<p class="lead">';
    esc_html_e( 'The options below let you customize how Shelfless manages your default fallback carrier/shipping provider for the warehouse to use.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' );
    echo '</p>';

    echo '<p>';
    esc_html_e( 'This will be used to make sure the order is shipped even if carrier service provider and/or shipping method cannot be detected by Shelfless.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' );
    echo '</p>';
}


/**
 * Builds the content (top portion) for the Bring API settings.
 *
 * @since    1.2.1
 */
function generate_bring_shelfless_mybring_section_content() {
    $screen = get_current_screen();
    $url = sanitize_url( 'https://www.mybring.com/useradmin/account/settings/api' );

    if ( $screen->id === 'toplevel_page_bring_3pl_shelfless_fulfillment_for_woocommerce_setup' ) { 
        echo '<hr>';
    }

    echo '<p class="lead">';
    esc_html_e( 'Set your Mybring credentials to utilize Bring shipping as part of the Shelfless Checkout.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' );
    echo '</p>';

    echo '<p>';
    esc_html_e( 'The API credentials will be used to pull matching shipping methods available for an address or zip code during checkout. If you don\'t have a Mybring API key, you can generate a new one from the Mybring portal\'s', 'bring-3pl-shelfless-fulfillment-for-woocommerce' );
    echo sprintf( esc_html__( ' %s', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ), '<a href="' . urldecode( esc_url( $url ) ) . '" target="_blank">API page</a>.') ;
    echo '</p>';
}

/**
 * Builds the form content for Mybring creds needed for Bring shipping actions.
 *
 * @since    1.2.1
 */
function generate_bring_shelfless_mybring_form() {

    $classes = generate_bring_shelfless_btn_classes();
    $screen = get_current_screen();
    $wizard = false;

    if ( $screen->id === 'toplevel_page_bring_3pl_shelfless_fulfillment_for_woocommerce_setup' ) {
        $wizard = true;
    }

    echo '<form 
        method="POST" 
        action="' . esc_html( ( ! $wizard ? admin_url( 'options.php' ) : admin_url( 'admin-ajax.php' ) ) ) . '" 
        id="' . get_bring_shelfless_plugin_name() . '_mybring_settings_mybring' . '" >';

    if ( $wizard ) {
        $nonce = wp_create_nonce( 'nonce_' . get_bring_shelfless_plugin_name() . '_mybring_settings_mybring' );
        echo '<input type="hidden"
            id="nonce_mybring_settings" 
            name="nonce" 
            value="' . $nonce . '" />';
    }
    else {
        settings_fields( get_bring_shelfless_plugin_name() . '_bring_settings_mybring' );
    }

    do_settings_sections( get_bring_shelfless_plugin_name() . '_bring_settings_mybring' );

    echo get_submit_button('', $classes, 'submit_mybring_settings', false);
    echo '<div id="shelfless-mybring-loading" class="spinner-border text-secondary hide" role="status"><span class="sr-only">Loading...</span></div>';
    echo '<span id="shelfless-mybring-saved" class="dashicons hide"></span>';
    echo '</form>';

}

/**
 * Builds the content (top portion) for the shipping settings - Shelfless Delivery section content.
 *
 * @since    1.2.5
 */
function generate_bring_shelfless_shipping_settings_deliveries_section_content() { 
    echo '<p class="mb-0">';
    esc_html_e( 'Use the default dimensions below when using Bring as carrier to successfully utilize Shelfless Delivery for order fulfillment.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' );
    echo '</p>';
}

/**
 * Fills in the form for the Shippings - Shelfless Delivery tab
 *
 * @since    1.2.5
 */
function generate_bring_shelfless_shipping_setting_deliveries_form() {

    $classes = generate_bring_shelfless_btn_classes();

    echo '<form 
        method="POST" 
        action="' . esc_html( admin_url( 'options.php' ) ) . '" 
        id="' . get_bring_shelfless_plugin_name() . '_shipping_settings_deliveries_mybring">';

    $bring_methods = shelfless_delivery_services_methods();
    $addons = shelfless_delivery_services_addons();

    $currency_symbol = get_woocommerce_currency_symbol();

    if ( ! empty( $bring_methods ) ) {
        // sds_bring means Shelfless Delivery services for Bring
        $sds_bring = get_option( get_bring_shelfless_plugin_name() . '_sd_services_bring');
        
        // Carrier
        $service_by = 'bring';
        
        echo '<div class="shipping-methods-container clearfix">';
            echo '<p class="lead">';
            esc_html_e( 'Increase conversion by starting to use Shelfless delivery.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' );
            echo '</p>';
            echo '<p class="mb-0">';
            esc_html_e( 'Use Shelfless Delivery, when using Bring as carrier, to give the customer ability to choose pickup point from dropdown and only show available delivery options based on postal code at checkout.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' );
            echo '</p>';
            $url = sanitize_url( site_url('/wp-admin/admin.php?page=bring_3pl_shelfless_fulfillment_for_woocommerce_help&userguide=shelfless_delivery#shelfless-delivery') );
            echo '<p>' . sprintf( esc_html__( 'For step-by-step instruction on how to get started: go to User guide %s.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ), '<a href="'. esc_url( $url ) .'" target="_blank"><strong>Shelfless delivery</strong></a>' ) . '</p>
            <div class="heading-title mt-4 mb-2">Shelfless Delivery Services - Bring</div>
            <div class="row">
                <div class="col-sm-12">
                    <p>Tick the box for each delivery service you like to offer to your customers. The selected services will show at checkout if the service is available at customer’s zip code. For each delivery service:</p>
                    <ul>
                        <li><p>Add the title you like to display at checkout. You can change this later if you change your mind.</p></li>
                        <li><p>Add price (without VAT) of delivery. This is the price that will be displayed at checkout, usually with VAT. You can change this later.</p></li>
                        <li><p>If you like to offer free shipping: Toggle on Free shipping and add minimum order amount (without VAT) for the shipping to be free of charge.</p></li>
                        <li><p>'. sprintf( 'Select the merchant addons you like to use. You find a description of what the addons do %s.', '<a href="https://developer.bring.com/api/services/#value-added-services-vas" target="_blank"><strong>here</strong></a>') .'</p></li>
                    </ul>
                    <p>Save your changes. Then go to WooCommerce > Settings > '. sprintf( '%s', '<a href="?page=wc-settings&tab=shipping" target="_blank"><strong>Shipping</strong></a>' ) .' and add Shelfless Delivery as a shipping method for the Shipping zones where you like to use Shelfless Delivery. Last, test your checkout so it works as you intended.</p>
                    <p>Note: if you are using other plugins for the delivery checkout, Shelfless Delivery might not be compatible.</p>
                </div>
            </div>
            <div class="container-fluid mt-3 mb-5">
                
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-sm-12">';
                    
                    foreach( $bring_methods as $s_key => $service ) { 
                        if ( ! empty( $service ) ) {
                            foreach( $service as $key => $method ) { 
                                $method = (object) $method;
                                $service_name = str_replace( ' ', '_', strtolower( $method->name ) );
                                $checked = '';
                                if ( ! empty( $sds_bring[$service_by][$key] ) ) {
                                    if ( $sds_bring[$service_by][$key]['enabled'] ) {
                                        $checked = 'checked';
                                    }
                                }

                        echo '<div class="form-check">
                            <input class="form-check-input service-method" type="checkbox" value="'. $key .'" id="'. get_bring_shelfless_plugin_name() .'_'. $service_name .'" name="bring['. $key .'][enabled]" '. $checked .'>
                            <label class="form-check-label" for="'. get_bring_shelfless_plugin_name() .'_'. $service_name .'">'. $method->name .'</label>
                        </div>';
                            }
                        }
                    }
                    
                    echo '</div>
                    <div class="col-lg-9 col-md-8 col-sm-12">
                        <div class="row">';
                        foreach( $bring_methods as $carrier => $service ) {
                            if ( ! empty( $service ) ) { 
                                foreach( $service as $key => $method ) { 
                                    $method = (object) $method;
                                    $service_name = str_replace( ' ', '_', strtolower( $method->name ) );

                                    // displaying the values to Shelfless Delivery services
                                    $show = ( isset( $sds_bring[$service_by][$key]['enabled'] ) ? ( ! $sds_bring[$service_by][$key]['enabled'] ? 'd-none' : '' ) : 'd-none' );

                                    $checked_title = ( isset( $sds_bring[$service_by][$key]['is_title'] ) ? ( $sds_bring[$service_by][$key]['is_title'] ? 'checked' : '' ) : '' );
                                    $disabled_title = ( $checked_title == 'checked' ? '' : '' );
                                    $value_title = ( ! empty ( $sds_bring[$service_by][$key]['title'] ) ? $sds_bring[$service_by][$key]['title'] : '' );

                                    $checked_price = ( isset( $sds_bring[$service_by][$key]['is_price'] ) ? ( $sds_bring[$service_by][$key]['is_price'] ? 'checked' : '' ) : '' );
                                    $disabled_price = ( isset( $sds_bring[$service_by][$key]['enabled'] ) ? ( ! $sds_bring[$service_by][$key]['enabled'] ? 'disabled' : '' ) : 'disabled' );
                                    // ( $checked_price == 'checked' ? '' : 'disabled' );
                                    $value_price = ( ! empty ( $sds_bring[$service_by][$key]['price'] ) && is_numeric( $sds_bring[$service_by][$key]['price'] ) ? shelfless_process_display_local_price_settings( $sds_bring[$service_by][$key]['price'] ) : '' );
                                    
                                    $checked_fs = ( isset( $sds_bring[$service_by][$key]['is_free_shipping'] ) ? ( $sds_bring[$service_by][$key]['is_free_shipping'] ? 'checked' : '' ) : '' );
                                    $disabled_fs = ( $checked_fs == 'checked' ? '' : 'disabled' );
                                    $value_fs = ( ! empty( $sds_bring[$service_by][$key]['free_shipping_threshold'] ) && is_numeric( $sds_bring[$service_by][$key]['free_shipping_threshold'] ) ? shelfless_process_display_local_price_settings( $sds_bring[$service_by][$key]['free_shipping_threshold'] ) : '' );

                                    if ( ! empty( $addons ) ) { 
                                        $addons_list = array();
                                        foreach( $addons as $addon_key => $addon ) { 
                                            foreach( $method->addons as $k => $a_key ) { 
                                                if ( $k == 'merchant' ) {
                                                    if ( !empty( $a_key ) ) {
                                                        foreach( $a_key as $merchant_key => $m_addon ) {
                                                            if ( $m_addon == $addon_key ) {
                                                                $addons_list[$addon_key]['addon'] = $addon;
                                                                $addons_list[$addon_key]['service'] = $service_name;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }

                                    echo '<div class="col-lg-6 col-md-12 col-sm-12 card-deck '. $show .'" id="'. get_bring_shelfless_plugin_name() .'_'. $service_name .'_card">
                                        <div class="card shadow-sm">
                                            <div class="card-header">
                                                <h6 class="my-0">'. $method->name .'</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="details">
                                                    <div class="row mb-2">
                                                        <div class="col-sm-5 custom-control custom-switch">
                                                            <input type="checkbox" class="custom-control-input title-toggle" id="'. get_bring_shelfless_plugin_name() .'_'. $service_name .'_title_toggle" name="bring['. $key .'][is_title]" data-idx="'. get_bring_shelfless_plugin_name() .'_'. $service_name .'_title" checked>
                                                            <label class="custom-control-label is_title">'. __( 'Title', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) .'</label>
                                                        </div>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control form-control-sm" '. $disabled_title .' id="'. get_bring_shelfless_plugin_name() .'_'. $service_name .'_title" name="bring['. $key .'][title]" value="'. $value_title .'" '. ( empty( $show ) ? 'required' : '' ) .'>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-sm-5 custom-control custom-switch">
                                                            <input type="checkbox" class="custom-control-input price-toggle" id="'. get_bring_shelfless_plugin_name() .'_'. $service_name .'_price_toggle" name="bring['. $key .'][is_price]" data-idx="'. get_bring_shelfless_plugin_name() .'_'. $service_name .'_price" checked>
                                                            <label class="custom-control-label is_price">'. __( 'Price ('. $currency_symbol .')', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) .'</label>
                                                        </div>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control form-control-sm" '. $disabled_price .' id="'. get_bring_shelfless_plugin_name() .'_'. $service_name .'_price" name="bring['. $key .'][price]" value="'. $value_price .'" '. ( empty( $show ) ? 'required' : '' ) .'>
                                                            <small><i>'. __( 'Without VAT', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) .'</i></small>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-5 custom-control custom-switch">
                                                            <input type="checkbox" class="custom-control-input addon-toggle" id="'. get_bring_shelfless_plugin_name() .'_'. $service_name .'_fs_toggle" name="bring['. $key .'][is_free_shipping]" data-idx="'. get_bring_shelfless_plugin_name() .'_'. $service_name .'_min_order_amount" '. $checked_fs .'>
                                                            <label class="custom-control-label" for="'. get_bring_shelfless_plugin_name() .'_'. $service_name .'_fs_toggle">'. __( 'Free shipping ('. $currency_symbol .')', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) .'</label>
                                                        </div>
                                                        <div class="col-sm-7">
                                                            <input type="text" class="form-control form-control-sm" '. $disabled_fs .' id="'. get_bring_shelfless_plugin_name() .'_'. $service_name .'_min_order_amount" name="bring['. $key .'][free_shipping_threshold]" value="'. $value_fs .'">
                                                            <small><i>'. __( 'Minimum order amount - without VAT', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) .'</i></small>
                                                        </div>
                                                    </div>
                                                </div>';

                                            if ( '5600_2012' === $key ):
                                                echo '<div class="addons row">
                                                    <div class="col-sm-12 addons-heading mt-2 mb-2">'. __( 'Same Day Delivery Areas', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) .'</div>
                                                    <div class="col-sm-12 addons-heading mt-2 mb-2">
                                                        <select class="custom-control-label select-multiple-js" name="bring['. $key .'][sorting_areas][]" multiple="multiple">';

                                                $sorting_areas_saved = ( ! empty( $sds_bring[$service_by][$key]['sorting_areas'] ) ? $sds_bring[$service_by][$key]['sorting_areas'] : array() );
                                                foreach ( $method->sorting_areas as $sort_key => $sort_station ) {
                                                    $selected = ( in_array($sort_key, $sorting_areas_saved) ? 'selected' : '' );
                                                            echo '<option value="' . $sort_key . '" '. $selected .'>' . $sort_station . '</option>';
                                                }
                                                        echo '</select>
                                                    </div>
                                                </div>';
                                                
                                            endif;

                                            if ( ! empty( $addons_list ) ) : 
                                                $addon_values = ( ! empty( $sds_bring[$service_by][$key]['addons']['merchant'] ) ? $sds_bring[$service_by][$key]['addons']['merchant'] : array() );
                                                echo '<div class="addons row">
                                                    <div class="col-sm-12 addons-heading mt-2 mb-2">'. __( 'Merchant addons', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) .'</div>';
                                                $service_key = $key;
                                                foreach( $addons_list as $key => $addon ) : 
                                                    $addon_checked = ( in_array($key, $addon_values) ? 'checked' : '' );
                                                    echo '<div class="col-sm-6"><label class="mb-0"><small><input type="checkbox" id="'. get_bring_shelfless_plugin_name() .'_'. $addon['service'] .'_'. $key .'" name="bring['. $service_key .'][addons][merchant][]" value="'. $key .'" '. $addon_checked .'> '. $addon['addon'] .'</small></label></div>';
                                                endforeach;
                                                echo '</div>';
                                            endif;

                                            echo '</div>
                                        </div>
                                    </div>';
                                }
                            }
                        }
                        echo '</div>
                    </div>
                </div>
            </div>
        </div>';

        echo '<hr>';
    }
    
    settings_fields( get_bring_shelfless_plugin_name() . '_shipping_settings_deliveries' );

    echo '<div class="accordion mb-4" id="defaultDimensions">
        <div class="card w-100 mw-100 p-0">
            <div class="card-header" id="defaultDimensionsHeading">
                <p class="lead mb-0">
                    <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#dimensions" aria-expanded="true" aria-controls="collapseOne">Advanced Settings <span class="dashicons dashicons-arrow-right-alt2"></span></button>
                </p>
            </div>
            <div id="dimensions" class="collapse" aria-labelledby="defaultDimensionsHeading" data-parent="#defaultDimensions">
                <div class="card-body">';

                    do_settings_sections( get_bring_shelfless_plugin_name() . '_shipping_settings_deliveries' );

                echo '</div>
            </div>
        </div>
    </div>';
    
    echo get_submit_button('', $classes, 'submit_shipping_settings_deliveries', false);
    echo '<div id="shelfless-shipping-deliveries-loading" class="spinner-border text-secondary hide" role="status"><span class="sr-only">Loading...</span></div>';
    echo '<span id="shelfless-shipping-deliveries-saved" class="dashicons hide"></span>';
    echo '</form>';

}

/**
 * Process the retrieved data from options
 * @param array $arr_values
 * @param string $service
 * @param string $service_name
 * @param string $value
 * @param string $default
 * @param string $type
 * @return string
 * @since    1.2.5
 */
function shelfless_delivery_services_return_data_settings( $arr_values, $service, $service_name, $value, $default, $type ) {

    if ( $type == 'show' ) {
        if ( ! empty( $arr_values[get_bring_shelfless_plugin_name() . $service . $service_name] ) ) {
            if ( $arr_values[get_bring_shelfless_plugin_name() . $service . $service_name] == $value ) {
                $value = '';
            }
        } else {
            $value = $default;
        }
    }

    if ( $type == 'toggle' ) {
        if ( ! empty( $arr_values[get_bring_shelfless_plugin_name() . $service . $service_name] ) ) {
            if ( $arr_values[get_bring_shelfless_plugin_name() . $service . $service_name] == $value ) {
                $value = true;
            }
        } else {
            $value = $default;
        }
    }

    return $value;
}

/**
 * Get all supported Shelfless Delivery services methods. This data structure may be
 * expanded to include more services, incuding B2B ones.
 * @param string $services_by 
 * @since    1.2.5
 */
function shelfless_delivery_services_methods( $services_by = 'bring' ) {
    $methods = array(
        'bring' => array(
            '5800' => array(
                'enabled' 		=> false,
                'name' 			=> 'Pakke til hentested',
                'free_shipping' => false, 
                'price' 		=> '', 
                'addons' 		=> array(
					'merchant'	=> array(
						'1091', '1133', '1134', '2086', '1082',
					),
					'customer'	=> array(
					),
				),
                'carrier_code'  => 'BPN',
            ),
            '5600' => array(
                'enabled' 		=> false,
                'name' 			=> 'Pakke levert hjem',
                'free_shipping' => false, 
                'price' 		=> '', 
                'addons' 		=> array(
					'merchant'	=> array(
						'1133', '1134', '0041', '1082',
					),
					'customer'	=> array(
					),
				),
                'carrier_code'  => 'BPN',
            ),
            // We will use this art here later for same day.
            '5600_2012' => array(
                'enabled' 		=> false,
                'name' 			=> 'Pakke levert hjem - samme dag',
                'free_shipping' => false, 
                'price' 		=> '', 
                'addons' 		=> array(
					'merchant'	=> array(
						'1133', '1134', '0041', '1082',
					),
					'customer'	=> array(
					),
				),
                'carrier_code'  => 'BPN',
                'sorting_areas' => array(
                    100 => 'Oslo',
                    160 => 'Fredrikstad',
                    300 => 'Drammen',
                    320 => 'Stokke',
                    400 => 'Stavanger',
                    460 => 'Kristiansand',
                    500 => 'Bergen',
                    700 => 'Trondheim',
                ),
            ),
            '3584' => array(
                'enabled' 		=> false,
                'name' 			=> 'Pakke i postkassen',
                'free_shipping' => false, 
                'price' 		=> '', 
                'addons' 		=> array(
					'merchant'	=> array(
						'1091',
					),
					'customer'	=> array(
						'1081',
					),
				),
                'carrier_code'  => 'BPN',
            ),
            '3570' => array(
                'enabled' 		=> false,
                'name' 			=> 'Pakke i postkassen med RFID',
                'free_shipping' => false, 
                'price' 		=> '', 
                'addons' 		=> array(
					'merchant'	=> array(
						'1091',
					),
					'customer'	=> array(
						'1081',
					),
				),
                'carrier_code'  => 'BPN',
            ),
            '0340' => array(
                'enabled' 		=> false,
                'name' 			=> 'Pickup Parcel',
                'free_shipping' => false, 
                'price' 		=> '', 
                'addons' 		=> array(
					'merchant'	=> array(
						'0068', '1091', '1134', '1288', '0003', '0073',
					),
					'customer'	=> array(
					),
				),
                'carrier_code'  => 'BPN',
            ),
            '0342' => array(
                'enabled' 		=> false,
                'name' 			=> 'Pickup Parcel Bulk',
                'free_shipping' => false, 
                'price' 		=> '', 
                'addons' 		=> array(
					'merchant'	=> array(
						'0068', '1091', '1133', '1134', '1288', '0003', '0073',
					),
					'customer'	=> array(
					),
				),
                'carrier_code'  => 'BPN',
            ),
            '0349' => array(
                'enabled' 		=> false,
                'name' 			=> 'Home Delivery Parcel',
                'free_shipping' => false, 
                'price' 		=> '', 
                'addons' 		=> array(
					'merchant'	=> array(
						'0068', '1091', '0015', '0041', '1133', '1134', '1288', '0003', '0073', '1280'
					),
					'customer'	=> array(
					),
				),
                'carrier_code'  => 'BPN',
            ),
			'0349' => array(
                'enabled' 		=> false,
                'name' 			=> 'Home Delivery Mailbox',
                'free_shipping' => false, 
                'price' 		=> '', 
                'addons' 		=> array(
					'merchant'	=> array(
						'0039',
					),
					'customer'	=> array(
					),
				),
                'carrier_code'  => 'BPN',
            ),
            '3150' => array(
                'enabled' 		=> false,
                'name' 			=> 'Home Delivery Single Indoor',
                'free_shipping' => false, 
                'price' 		=> '', 
                'addons' 		=> array(
					'merchant'	=> array(
						'1128', '1141', '1082', '1127',
					),
					'customer'	=> array(
					),
				),
                'carrier_code'  => 'BPN',
            ),
            '2870' => array(
                'enabled' 		=> false,
                'name' 			=> 'Home Delivery Indoor',
                'free_shipping' => false, 
                'price' 		=> '', 
                'addons' 		=> array(
					'merchant'	=> array(
						'1140', '1128', '1133', '1134', '1141', '1280', '1082', '1127',
					),
					'customer'	=> array(
					),
				),
                'carrier_code'  => 'BPN',
            ),
            '3123' => array(
                'enabled' 		=> false,
                'name' 			=> 'Home Delivery Curbside',
                'free_shipping' => false, 
                'price' 		=> '', 
                'addons' 		=> array(
					'merchant'	=> array(
						'1140', '0041', '1133', '1134', '1280', '1082',
					),
					'customer'	=> array(
					),
				),
                'carrier_code'  => 'BPN',
            ),
            '3457' => array(
                'enabled' 		=> false,
                'name' 			=> 'Home Delivery Curbside Evening',
                'free_shipping' => false, 
                'price' 		=> '', 
                'addons' 		=> array(
					'merchant'	=> array(
						'1128', '1141', '1133', '1280', '1280', '1082', '1127',
					),
					'customer'	=> array(
					),
				),
                'carrier_code'  => 'BPN',
            ),
            '3332' => array(
                'enabled' 		=> false,
                'name' 			=> 'Urban Home Delivery',
                'free_shipping' => false, 
                'price' 		=> '', 
                'addons' 		=> array(
					'merchant'	=> array(
						'0041', '1133', '1134', '1280', '1082',
					),
					'customer'	=> array(
					),
				),
                'carrier_code'  => 'BPN',
            ),
        )
    );

    return $methods;
}
