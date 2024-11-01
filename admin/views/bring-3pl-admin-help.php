<?php
/**
 * Help page.
 *
 * @link       https://www.bring.no/
 * @since      1.0.0
 *
 * @package    Bring_3pl_Shelfless_Fulfillment_For_Woocommerce
 * @subpackage Bring_3pl_Shelfless_Fulfillment_For_Woocommerce/admin/views
 */

$tab = isset( $_REQUEST['userguide'] ) ? $_REQUEST['userguide'] : '';
echo $tab;
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
                <li class="nav-item <?php echo ( empty($tab) || $tab == 'faqs' ? 'active' : '' ); ?>">
                    <a class="nav-link <?php echo ( empty($tab) || $tab == 'faqs' ? 'active' : '' ); ?>" id="bring-faq-tab" data-toggle="tab" href="#bring-faq" role="tab" aria-controls="bring-faq" aria-selected="false"><?php echo __( 'FAQ', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></a>
                </li>
                <li class="nav-item <?php echo ( $tab == 'shelfless_delivery' ? 'active' : '' ); ?>">
                    <a class="nav-link <?php echo ( $tab == 'shelfless_delivery' ? 'active' : '' ); ?>" id="bring-user-guide-tab" data-toggle="tab" href="#bring-user-guide" role="tab" aria-controls="bring-user-guide" aria-selected="true"><?php echo __( 'User Guide', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></a>
                </li>
            </ul>
            <div class="tab-content bring-settings-tabs-content" id="bring-settings-tab-content">
                <div class="tab-pane <?php echo ( empty($tab) || $tab == 'faqs' ? 'active' : '' ); ?>" role="tab-panel" id="bring-faq" aria-labelledby="bring-faq-tab">
                    <?php generate_help_faq_tab_settings(); ?>
                </div>
                <div class="tab-pane <?php echo ( $tab == 'shelfless_delivery' ? 'active' : '' ); ?>" role="tab-panel" id="bring-user-guide" aria-labelledby="bring-user-guide-tab">
                    <?php generate_help_user_guide_tab_settings(); ?>
                </div>
            </div>
        </div>
    </div>

</div>
