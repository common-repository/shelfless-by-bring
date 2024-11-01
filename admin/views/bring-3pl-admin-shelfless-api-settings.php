<?php
/**
 * General Settings page.
 *
 * @link       https://www.bring.no/
 * @since      1.0.0
 *
 * @package    Bring_3pl_Shelfless_Fulfillment_For_Woocommerce
 * @subpackage Bring_3pl_Shelfless_Fulfillment_For_Woocommerce/admin/views
 */
?>

<div class="wrap">

    <div class="row">
        <div class="col">
            <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'assets/bring_logo_extra_small.png'; ?>" alt="Bring"/>
        </div>
    </div>
    
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    
    <?php settings_errors(); ?>

    <div class="row bring-row">
        <div class="col-lg col-md col-sm-12">
            <ul class="nav nav-tabs bring-settings-tabs" id="bring-settings-tabs" role="tablist">
                <li class="nav-item active">
                    <a class="nav-link active" id="bring-api-settings-tab" data-toggle="tab" href="#bring-api-settings" role="tab" aria-controls="bring-api-settings" aria-selected="true"><?php echo __( 'Shelfless API', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="bring-scheduled-actions-tab" data-toggle="tab" href="#bring-scheduled-actions" role="tab" aria-controls="bring-scheduled-actions" aria-selected="false"><?php echo __( 'Scheduled Actions', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="bring-dream-logistics-tab" data-toggle="tab" href="#bring-dream-logistics" role="tab" aria-controls="bring-dream-logistics" aria-selected="false"><?php echo __( 'Dream Logistics', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="bring-mybring-logistics-tab" data-toggle="tab" href="#bring-mybring-logistics" role="tab" aria-controls="bring-mybring-logistics" aria-selected="false"><?php echo __( 'Mybring API for Bring Shipping', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></a>
                </li>
            </ul>
            <div class="tab-content bring-settings-tabs-content" id="bring-settings-tab-content">
                <div class="tab-pane active" role="tab-panel" id="bring-api-settings" aria-labelledby="bring-api-settings-tab">
                    <?php 
                        generate_bring_shelfless_api_credentials_form(); 
                        generate_bring_shelfless_api_diagnostics_form(); 
                        generate_bring_shelfless_api_diagnostics_delete_transient_form();    
                    ?>
                </div>
                <div class="tab-pane" role="tab-panel" id="bring-scheduled-actions" aria-labelledby="bring-scheduled-actions-tab">
                    <?php generate_bring_shelfless_scheduled_actions_form(); ?>
                </div>
                <div class="tab-pane" role="tab-panel" id="bring-dream-logistics" aria-labelledby="bring-dream-logistics-tab">
                    <?php generate_bring_shelfless_dream_logistics_form(); ?>
                </div>
                <div class="tab-pane" role="tab-panel" id="bring-mybring-logistics" aria-labelledby="bring-mybring-logistics-tab">
                    <?php generate_bring_shelfless_mybring_form(); ?>
                </div>
            </div>
        </div>
    </div>

</div>
