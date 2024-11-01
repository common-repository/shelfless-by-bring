<div id="help-order-settings" class="row">
    
    <div class="col-sm-12">
        <p><?php echo __( 'This section gives the user the options to set up/manage Orders and relevant notifications and Status Mappings.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></p>

        <p class="mb-0"><strong><?php echo __( 'Orders', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></strong></p>
        <p><?php echo __( 'The user will set up/manage the Orders and its corresponding notifications.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></p>

        <!-- thumbnail image wrapped in a link -->
        <a class="img-help-wrapper" href="javascript:;" data-url="img_help_orders">
            <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'assets/help/orders.jpg'; ?>" alt="Orders"/>
        </a>
        
        <!-- lightbox container hidden with CSS -->
        <div class="help-lightbox" id="img_help_orders">
            <div class="popup" >
                <a href="javascript:;" class="help-close" data-close="img_help_orders">&times;</a>
                <span style="background-image: url('<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'assets/help/orders.jpg'; ?>')"></span>
            </div>
        </div>

        <ol class="ol-main">
            <li><?php echo __( 'Include Orders in the Last Number of Days for Processing', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?>
                <ol>
                    <li><?php echo __( 'Default: 2, for two days backward from now', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                    <li><?php echo __( 'Enter a number for days for which Shelfless will compute the date range for fulfillment processing. Orders older than this value will not be included. Modified orders that fall within the range will also be attempted for re-processing.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                </ol>
            </li>
            <li><?php echo __( 'Process International Orders (Exports)', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?>
                <ol>
                    <li><?php echo __( 'Default: no', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                    <li><?php echo __( 'Let Shelfless process and ship international orders (orders exported from default country set in Woocommerce settings). Exporting should have a corresponding agreement with Bring.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                </ol>
            </li>
            <li><?php echo __( 'Use Shelfless Custom Statuses', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?>
                <ol>
                    <li><?php echo __( 'Default: no', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                    <li><?php echo __( 'By adding custom status, you have the control which orders are to be fulfilled by Shelfless (in addition to mapping statuses which will be processed by Bring).', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                </ol>
            </li>
            <li><?php echo __( 'Select Custom Statuses', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?>
                <ol>
                    <li><?php echo __( 'Default: no', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                    <li><?php echo __( 'Custom statuses to select, you have the control which orders are to be fulfilled by Shelfless (in addition to mapping statuses which will be processed by Bring).', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                </ol>
            </li>
            <!--
            <li><?php //echo __( 'Show Admin Notifications if an Order is Partially Shipped by Warehouse', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?>
                <ol>
                    <li><?php //echo __( 'Default: no', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                    <li><?php //echo __( 'When an order is partially shipped by a warehouse for various reasons, let Shelfless notify the user so the user can check the orders and statuses.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                </ol>
            </li>
            -->
            <li><?php echo __( 'Show Admin Notifications if an Order is Cancelled from Warehouse', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?>
                <ol>
                    <li><?php echo __( 'Default: no', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                    <li><?php echo __( 'When an order was cancelled from within the warehouse for various reasons, let Shelfless notify the user so the user can check the orders and statuses.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                </ol>
            </li>
            <li><?php echo __( 'Fallback Carrier', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?>
                <ol>
                    <li><?php echo __( 'Default: BPN, for flat rates', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                    <li><?php echo __( 'Enter a fallback carrier if it cannot be detected in the system.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                </ol>
            </li>
            <li><?php echo __( 'Fallback Service Code', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?>
                <ol>
                    <li><?php echo __( 'Default: 5600, 5800, 4850 – For bring, example services', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                    <li><?php echo __( 'Enter a fallback service code if the service cannot be determined.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                </ol>
            </li>
        </ol>
    </div>

    <div class="col-sm-12">

        <p class="mb-0"><strong><?php echo __( 'Status Mappings', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></strong></p>
        <p><?php echo __( 'This tab will let the user set up/manage the order statuses provided by Shelfless, this is possible when Manage Status is enabled.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></p>

        <a class="img-help-wrapper" href="javascript:;" data-url="img_help_status_mappings">
            <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'assets/help/status-mappings.jpg'; ?>" alt="Orders"/>
        </a>
        
        <!-- lightbox container hidden with CSS -->
        <div class="help-lightbox" id="img_help_status_mappings">
            <div class="popup" >
                <a href="javascript:;" class="help-close" data-close="img_help_status_mappings">&times;</a>
                <span style="background-image: url('<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'assets/help/status-mappings.jpg'; ?>')"></span>
            </div>
        </div>
        <ol class="ol-main">
            <li><?php echo __( 'WooCommerce Order Status to Start Fulfilling an Order', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?>
                <ol>
                    <li><?php echo __( 'Default: Processing', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                    <li><?php echo __( 'Select a WooCommerce order status that triggers Shelfless to fulfill an order.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                </ol>
            </li>
            <li><?php echo __( 'WooCommerce Order Status to Cancel A Fulfillment', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?>
                <ol>
                    <li><?php echo __( 'Default: Cancelled', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                    <li><?php echo __( 'Select a WooCommerce order status that triggers Shelfless to cancel an order.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                </ol>
            </li>
            <li><?php echo __( 'Order Status to Move to When an Order is Partially Fulfilled by Shelfless', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?>
                <ol>
                    <li><?php echo __( 'Default: Processing', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                    <li><?php echo __( 'Select a WooCommerce order status to move orders to when Shelfless cannot fully fulfill an order, and only shipped partial of it.
', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                </ol>
            </li>
            <li><?php echo __( 'Order Status to Move to When a Tracking Number Has Been Created and Order is Shipped by Shelfless', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?>
                <ol>
                    <li><?php echo __( 'Default: Completed', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                    <li><?php echo __( 'Select a WooCommerce order status to move orders to when Shelfless has generated a tracking number and the order has been marked shipped at the warehouse.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                </ol>
            </li>
            <li><?php echo __( 'Order Status to Move to When an Order is Marked Cancelled by Shelfless', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?>
                <ol>
                    <li><?php echo __( 'Default: Cancelled', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                    <li><?php echo __( 'Select a WooCommerce order status to move orders to when Shelfless receives notification that an order was cancelled from the warehouse.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                </ol>
            </li>
        </ol>
    </div>

    <div class="col-sm-12">

        <p class="mb-0"><strong><?php echo __( 'Shipping Mappings', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></strong></p>
        <p><?php echo __( 'This tab will let the user select/choose for the existing flat-rate-based shipping method.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></p>

        <a class="img-help-wrapper" href="javascript:;" data-url="img_help_shipping_mappings">
            <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'assets/help/shipping-mappings.jpg'; ?>" alt="Orders"/>
        </a>
        
        <!-- lightbox container hidden with CSS -->
        <div class="help-lightbox" id="img_help_shipping_mappings">
            <div class="popup" >
                <a href="javascript:;" class="help-close" data-close="img_help_shipping_mappings">&times;</a>
                <span style="background-image: url('<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'assets/help/shipping-mappings.jpg'; ?>')"></span>
            </div>
        </div>
        <p>
            <strong><?php echo __( 'Flat Rate', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></strong> - <?php echo __( 'Options', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?>
        </p>
        <p>Pakke i postkassen, På Døren, Pakke til hentested, Pakke levert hjem, Pakke til bedrift, Ekspress neste dag, Stykkgods til bedrift, Partigods til bedrift, Bedriftspakke, Bedriftspakke Ekspress-Over natten</p>
    </div>
</div>