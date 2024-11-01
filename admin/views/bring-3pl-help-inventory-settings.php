<div id="help-inventory-settings" class="row">
    
    <div class="col-sm-12">
        <p><?php echo __( 'This is where the user sets up the Stocks that will override the WooCommerce Manage Stocks settings, Products to fulfill by Shelfless.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></p>

        <p class="mb-0"><strong><?php echo __( 'Stocks', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></strong></p>
        <p><?php echo __( 'This tab serves as the options to override the WooCommerce Manage Stock settings.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></p>

        <!-- thumbnail image wrapped in a link -->
        <a class="img-help-wrapper" href="javascript:;" data-url="img_help_manage_stocks">
            <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'assets/help/stocks.jpg'; ?>" alt="Scheduled Actions"/>
        </a>
        
        <!-- lightbox container hidden with CSS -->
        <div class="help-lightbox" id="img_help_manage_stocks">
            <div class="popup" >
                <a href="javascript:;" class="help-close" data-close="img_help_manage_stocks">&times;</a>
                <span style="background-image: url('<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'assets/help/stocks.jpg'; ?>')"></span>
            </div>
        </div>

        <ol class="ol-main">
            <li><?php echo __( 'Manage Stock', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?>
                <ol>
                    <li><?php echo __( 'Default: yes', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                    <li><?php echo __( 'If it is not enabled in WooCommerce, this will allow Shelfless to control and enable it. In order for Bring to update stocks in the inventory, this option must be enabled in WooCommerce global settings and in each product.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                </ol>
            </li>
            <li><?php echo __( 'Show Admin Notifications if Product is Out of Stock', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?>
                <ol>
                    <li><?php echo __( 'Default: yes', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                    <li><?php echo __( 'When stock is below 1, Selfless by Bring invokes a notification.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                </ol>
            </li>
            <li><?php echo __( 'Show Admin Notifications if Product Stock is Low', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?>
                <ol>
                    <li><?php echo __( 'Default: yes', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                    <li><?php echo __( 'When stock is below the low threshold, Shelfless invokes a notification.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                </ol>
            </li>
            <li><?php echo __( 'Use WooCommerce Low Stock Threshold Value', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?>
                <ol>
                    <li><?php echo __( 'Default: no', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                    <li><?php echo __( 'Use the WooCommerce Inventory settings value for the Low Sock Threshold.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                </ol>
            </li>
            <li><?php echo __( 'Low Stock Threshold', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?>
                <ol>
                    <li><?php echo __( 'Default: 5', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                    <li><?php echo __( 'If no Low stock threshold is set in the WooCommerce inventory setting, or if the User WooCommerce Low Stock Threshold Value field above is not enabled, use this value.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                </ol>
            </li>
            <li><?php echo __( 'Add Customs fields', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?>
                <ol>
                    <li><?php echo __( 'Default: no', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                    <li><?php echo __( 'This field is usually utilized for Customs Commercial Invoices for International orders wherein the seller has the option to include Customs information in the inventory.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                </ol>
            </li>
            <li><?php echo __( 'Default Country of Origin', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?>
                <ol>
                    <li><?php echo __( 'Default: CN-China', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                    <li><?php echo __( 'This field is usually utilized for Customs Commercial Invoices for international orders wherein seller has the option to set the default Country of Origin for an item has not been set with it. This will not be used if the field is not checked.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                </ol>
            </li>
            <li><?php echo __( 'Use Cost Price Field', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?>
                <ol>
                    <li><?php echo __( 'Default: yes', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                    <li><?php echo __( 'This field is usually utilized for Custom Commercial Invoices for International orders wherein the seller has the option to use cost price as the declared price value for the item.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                </ol>
            </li>
            <li><?php echo __( 'Cost Price Currency', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?>
                <ol>
                    <li><?php echo __( 'Default: Global WooCommerce Currency', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                    <li><?php echo __( 'This field is used for Custom Commercial Invoices for International orders wherein the seller has the option to use cost price as the declared price value for the item.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                </ol>
            </li>
            <li><?php echo __( 'Basic Inventory Unit', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?>
                <ol>
                    <li><?php echo __( 'Default: pcs', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                    <li><?php echo __( 'Determines the basic inventory unit when counting quantities.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                </ol>
            </li>
        </ol>
    </div>

    <div class="col-sm-12">

        <p class="mb-0"><strong><?php echo __( 'Products', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></strong></p>
        <p><?php echo __( 'This is where the user will be able to select all products, select one or more products that Shelfless will fulfill.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></p>

        <!-- thumbnail image wrapped in a link -->
        <a class="img-help-wrapper" href="javascript:;" data-url="img_help_products">
            <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'assets/help/products.jpg'; ?>" alt="Products"/>
        </a>
        
        <!-- lightbox container hidden with CSS -->
        <div class="help-lightbox" id="img_help_products">
            <div class="popup" >
                <a href="javascript:;" class="help-close" data-close="img_help_products">&times;</a>
                <span style="background-image: url('<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'assets/help/products.jpg'; ?>')"></span>
            </div>
        </div>
        <ol>
            <li><?php echo __( 'Select All products that Shelfless will fulfill.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
            <li><?php echo __( 'Select one or more products that Shelfless will fulfill.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
            <li><?php echo __( 'Navigate to a specific page.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
            <li><?php echo __( 'Number of products to show in the list.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
            <li><?php echo __( 'Go to the next or previous page of the products list.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
            <li><?php echo __( 'Click the refresh button whenever you want to verify the changes made or just refresh the product list.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
        </ol>
        <p><strong>SKU - Stock Keeping Unit</strong>, is a unique number that is assigned to a product for the purpose of inventroy management and ease of record-keeping.</p>
        <p><strong>Shelfless</strong>, determines if the product is found in the warehouse.</p>
        <p><strong>Type</strong>, product type that is to be fulfilled by the warehouse</p>
    </div>
</div>