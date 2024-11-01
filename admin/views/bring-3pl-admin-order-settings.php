<?php
/**
 * Order Settings page.
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
                    <a class="nav-link active" id="bring-orders-tab" data-toggle="tab" href="#bring-orders" role="tab" aria-controls="bring-orders-general" aria-selected="true"><?php echo __( 'Orders', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="bring-orders-statuses-tab" data-toggle="tab" href="#bring-orders-statuses" role="tab" aria-controls="bring-orders" aria-selected="false"><?php echo __( 'Status Mappings', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></a>
                </li>
            </ul>
            <div class="tab-content bring-settings-tabs-content" id="bring-settings-tab-content">
                <div class="tab-pane show active" role="tab-panel" id="bring-orders" aria-labelledby="bring-orders-tab">
                    <?php generate_bring_shelfless_order_settings_form(); ?>
                </div>
                <div class="tab-pane" role="tab-panel" id="bring-orders-statuses" aria-labelledby="bring-orders-statuses-tab">
                    <?php generate_bring_shelfless_order_status_maps_settings_form(); ?>
                </div>
            </div>
        </div>
    </div>

</div>
