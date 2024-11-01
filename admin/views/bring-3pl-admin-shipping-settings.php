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

$tab = ( ! empty($_GET['tab']) ? $_GET['tab'] : 'shipping' );
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
            <ul class="nav nav-tabs bring-settings-tabs bring-shippings-tabs" id="bring-shippings-tabs" role="tablist">
                <li class="nav-item <?php echo ( $tab == 'shipping' ? 'active' : '' ); ?>">
                    <a class="nav-link <?php echo ( $tab == 'shipping' ? 'active' : '' ); ?>" id="bring-shipping-tab" data-toggle="tab" href="#bring-shipping" role="tab" aria-controls="bring-shipping" aria-selected="false"><?php echo __( 'Shipping Mappings', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></a>
                </li>
                <li class="nav-item <?php echo ( $tab == 'fallback' ? 'active' : '' ); ?>">
                    <a class="nav-link <?php echo ( $tab == 'fallback' ? 'active' : '' ); ?>" id="bring-shipping-fallback-tab" data-toggle="tab" href="#bring-shipping-fallback" role="tab" aria-controls="bring-shipping-fallback" aria-selected="false"><?php echo __( 'Fallback Carriers', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></a>
                </li>
                <li class="nav-item <?php echo ( $tab == 'deliveries' ? 'active' : '' ); ?>">
                    <a class="nav-link <?php echo ( $tab == 'deliveries' ? 'active' : '' ); ?>" id="bring-shipping-deliveries-tab" data-toggle="tab" href="#bring-shipping-deliveries" role="tab" aria-controls="bring-shipping-deliveries" aria-selected="false"><?php echo __( 'Shelfless Delivery', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></a>
                </li>
            </ul>
            <div class="tab-content bring-shippings-tabs-content" id="bring-shippings-tab-content">
                <div class="tab-pane <?php echo ( $tab == 'shipping' ? 'show active' : '' ); ?>" role="tab-panel" id="bring-shipping" aria-labelledby="bring-shipping-tab">
                    <?php generate_bring_shelfless_order_shipping_maps_settings_form(); ?>
                </div>
                <div class="tab-pane <?php echo ( $tab == 'fallback' ? 'show active' : '' ); ?>" role="tab-panel" id="bring-shipping-fallback" aria-labelledby="bring-shipping-fallback-tab">
                    <?php generate_bring_shelfless_shipping_setting_fallback_form(); ?>
                </div>
                <div class="tab-pane <?php echo ( $tab == 'deliveries' ? 'show active' : '' ); ?>" role="tab-panel" id="bring-shipping-deliveries" aria-labelledby="bring-shipping-deliveries-tab">
                    <?php generate_bring_shelfless_shipping_setting_deliveries_form(); ?>
                </div>
            </div>
        </div>
    </div>

</div>