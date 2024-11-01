<?php
/**
 * Provides a setup wizard for new customers or for customers who
 * are not done setting up Bring_3pl_Shelfless_Fulfillment_For_Woocommerce.
 *
 * @link       https://www.bring.no/
 * @since      1.0.0
 *
 * @package    Bring_3pl_Shelfless_Fulfillment_For_Woocommerce
 * @subpackage Bring_3pl_Shelfless_Fulfillment_For_Woocommerce/admin/views
 */
?>
<div class="container bring-setup-wizard">
    <div class="row">
        <div class="col-md col-sm">
        <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'assets/bring_logo_extra_small.png'; ?>" class="mx-auto d-block" alt="Bring"/>
        </div>
    </div>
    <div class="row bring-row">
        <div class="col-md col-sm">
        <p class="h3 center"><?php echo get_bring_shelfless_app_name() .' - '. esc_html__( 'Setup Wizard', 'bring-3pl-shelfless-fulfillment-for-woocommerce' );  ?></p>
        </div>
    </div>
    <div class="row bring-row">
        <div class="col-md col-sm">
            <div id="notify"></div>
        </div>
    </div>
    <div class="row bring-row">
        <div class="col-lg col-md col-sm-12">
            <div class="row">
                <div class="col-sm-2">
                    <ul class="nav nav-tabs flex-column" id="bring-setup-step-indicators" role="tablist">
                        <li class="col nav-item"><a class="nav-link active" href="#api-credentials" id="api-credentials-tab" data-toggle="tab"><span class="bring-shelfless-wizard-tooltip bring-shelfless-wizard-steps active step-1"></span><?php esc_html_e( 'API Credentials', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></a></li>
                        <li class="col nav-item"><a class="nav-link" href="#stock-settings" id="stock-settings-tab" data-toggle="tab"><span class="bring-shelfless-wizard-tooltip bring-shelfless-wizard-steps step-2"></span><?php esc_html_e( 'Products & Stocks', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></a></li>
                        <li class="col nav-item"><a class="nav-link" href="#scheduler-inventory" id="scheduler-inventory-tab" data-toggle="tab"><span class="bring-shelfless-wizard-tooltip bring-shelfless-wizard-steps step-3"></span><?php esc_html_e( 'Scheduled Actions', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></a></li>
                        <li class="col nav-item"><a class="nav-link" href="#order-settings" id="order-settings-tab" data-toggle="tab"><span class="bring-shelfless-wizard-tooltip bring-shelfless-wizard-steps step-4"></span><?php esc_html_e( 'Shipping', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></a></li>
                    </ul>
                </div>
                <div class="col-sm-10">
                    <div class="tab-content bring-setup-step-content" id="bring-setup-step-content">
                        <div class="tab-pane show active" role="tab-panel" id="api-credentials" aria-labelledby="api-credentials-tab">
                            <div class="container">
                                <div class="row credentials">
                                    <div class="col-sm-12"><?php generate_bring_shelfless_api_credentials_form(); ?></div>
                                </div>
                                <div class="row diagnostics mt-5">
                                    <div class="col-sm-12"><?php generate_bring_shelfless_api_diagnostics_form(); ?></div>
                                </div>
                                <div class="row logistics mt-5">
                                    <div class="col-sm-12"><?php generate_bring_shelfless_dream_logistics_form(); ?></div>
                                </div>
                                <div class="row">
                                    <div class="col step-navigator">
                                        <button type="button" data-toggle="#stock-settings" class="btn btn-secondary next next-step"><?php esc_html_e( 'Next', 'bring-3pl-shelfless-fulfillment-for-woocommerce' );  ?><span class="dashicons dashicons-arrow-right-alt2"></span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" role="tab-panel" id="stock-settings" aria-labelledby="stock-settings-tab">
                            <div class="container">
                                <div class="row">
                                    <div class="col">
                                        <?php generate_bring_shelfless_inventory_settings_form(); ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col step-navigator">
                                        <button type="button" data-toggle="#api-credentials" class="btn btn-secondary prev prev-step"><span class="dashicons dashicons-arrow-left-alt2"></span><?php esc_html_e( 'Previous', 'bring-3pl-shelfless-fulfillment-for-woocommerce' );  ?></button>    
                                        <button type="button" data-toggle="#scheduler-inventory" class="btn btn-secondary next next-step"><?php esc_html_e( 'Next', 'bring-3pl-shelfless-fulfillment-for-woocommerce' );  ?><span class="dashicons dashicons-arrow-right-alt2"></span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" role="tab-panel" id="scheduler-inventory" aria-labelledby="scheduler-inventory-tab">
                            <div class="container">
                                <div class="row">
                                    <div class="col">
                                        <?php generate_bring_shelfless_scheduled_actions_form();  ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col step-navigator">
                                        <button type="button" data-toggle="#stock-settings" class="btn btn-secondary prev prev-step"><span class="dashicons dashicons-arrow-left-alt2"></span><?php esc_html_e( 'Previous', 'bring-3pl-shelfless-fulfillment-for-woocommerce' );  ?></button>    
                                        <button type="button" data-toggle="#order-settings" class="btn btn-secondary next next-step"><?php esc_html_e( 'Next', 'bring-3pl-shelfless-fulfillment-for-woocommerce' );  ?><span class="dashicons dashicons-arrow-right-alt2"></span></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" role="tab-panel" id="order-settings" aria-labelledby="order-settings-tab">
                            <div class="container">
                                <div class="row">
                                    <div class="col">
                                        <?php generate_bring_shelfless_order_shipping_maps_settings_form(); ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col step-navigator">
                                        <?php generate_bring_shelfless_api_wizard_finish_form(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center col-sm-12 mt-4"><?php generate_bring_shelfless_api_wizard_skip_form(); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="wrap">
    
        <div class="container-fluid">
            <div class="col-lg col-md">
                <div class="row">
                    <div class="col-lg col-md"></div>
                </div>
            </div>
        </div>
    </header>
</div>
