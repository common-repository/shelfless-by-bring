<?php
/**
 * Inventory Settings page.
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

    <div id="notify"></div>

    <div class="row bring-row">
        <div class="col-lg col-md col-sm-12">
            <ul class="nav nav-tabs bring-settings-tabs" id="bring-settings-tabs" role="tablist">
                <li class="nav-item active">
                    <a class="nav-link active" id="bring-inventory-stocks-tab" data-toggle="tab" href="#bring-inventory-stocks" role="tab" aria-controls="bring-inventory-stocks" aria-selected="true"><?php echo __( 'Stocks', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="bring-inventory-products-tab" data-toggle="tab" href="#bring-inventory-products" role="tab" aria-controls="bring-inventory-products" aria-selected="false"><?php echo __( 'Products', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="bring-inventory-fulfill-sync-tab" data-toggle="tab" href="#bring-inventory-fulfill-sync" role="tab" aria-controls="bring-inventory-fulfill-sync" aria-selected="false"><?php echo __( 'Fulfill & Sync', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></a>
                </li>
            </ul>
            <div class="tab-content bring-settings-tabs-content" id="bring-settings-tab-content">
                <div class="tab-pane show active" role="tab-panel" id="bring-inventory-stocks" aria-labelledby="bring-stocks-tab">
                    <?php generate_bring_shelfless_inventory_settings_form(); ?>
                </div>
                <div class="tab-pane" role="tab-panel" id="bring-inventory-products" aria-labelledby="bring-products-tab">
                    <?php generate_bring_shelfless_products_form();  ?>
                </div>
                <div class="tab-pane" role="tab-panel" id="bring-inventory-fulfill-sync" aria-labelledby="bring-fulfill-sync-tab">
                    <?php generate_bring_shelfless_inventory_grid();  ?>
                </div>
            </div>
        </div>
    </div>
</div>
