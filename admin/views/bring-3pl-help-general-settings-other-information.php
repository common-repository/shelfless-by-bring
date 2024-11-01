<div id="general-settings-other-information" class="row">
    <div class="col-sm-12">
        <p><?php echo __( 'This is where the user sets up the API Credentials, Tests the connection in the Diagnostics tab, and Scheduled Actions when the configuration was skipped during the installation wizard phase.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></p>

        <p class="mb-0"><strong><?php echo __( 'API Credentials', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></strong></p>
        <p><?php echo __( 'This is where you are able to set up and store the information provided by Mybring after the Shelfless by Bring plugin was installed and activated.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></p>

        <!-- thumbnail image wrapped in a link -->
        <a class="img-help-wrapper" href="javascript:;" data-url="img_help_api_credentials">
            <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'assets/help/api-credentials.png'; ?>" alt="API Credentials"/>
        </a>
        
        <!-- lightbox container hidden with CSS -->
        <div class="help-lightbox" id="img_help_api_credentials">
            <div class="popup" >
                <a href="javascript:;" class="help-close" data-close="img_help_api_credentials">&times;</a>
                <span style="background-image: url('<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'assets/help/api-credentials.png'; ?>')"></span>
            </div>
        </div>

        <p><?php echo __( 'The following information can be obtained from Mybring portal provided by the Bring account:', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></p>
        <ol>
            <li><?php echo __( 'Mybring Customer ID, unique customer identification number', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
            <li><?php echo __( 'API Key, should be partnered with a Shelfless API Secret Key', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
            <li><?php echo __( 'Secret Key, should be partnered with a Shelfless API Key', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
            <li><?php echo __( 'Mode', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?>
                <ol>
                    <li><?php echo __( 'can be set to Development/Staging wherein the development/testing is in progress', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                    <li><?php echo __( 'must be set to Live when the plugin is ready for production', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
                </ol>
            </li>
            <li><?php echo __( 'Debug Mode, Defaut is No', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
        </ol>
    </div>

    <div class="col-sm-12">
        <p class="mb-0"><strong><?php echo __( 'Diagnostics', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></strong></p>
        <p><?php echo __( 'This is to test the connection between Bring 3PL Shelfless and Bring using the API Credentials set from the API Credentials tab.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></p>

        <!-- thumbnail image wrapped in a link -->
        <a class="img-help-wrapper" href="javascript:;" data-url="img_help_diagnostics">
            <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'assets/help/diagnostics.jpg'; ?>" alt="Diagnostics"/>
        </a>
        
        <!-- lightbox container hidden with CSS -->
        <div class="help-lightbox" id="img_help_diagnostics">
            <div class="popup" >
                <a href="javascript:;" class="help-close" data-close="img_help_diagnostics">&times;</a>
                <span style="background-image: url('<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'assets/help/diagnostics.jpg'; ?>')"></span>
            </div>
        </div>

        <p><?php echo __( 'Click the Run Test button in order to successfully test the connection between Bring 3PL Shelfless and Bring using the API Credentials set from the previous tab.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></p>

        <p><?php echo __( 'Successful connection result:', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></p>
        
        <!-- thumbnail image wrapped in a link -->
        <a class="img-help-wrapper" href="javascript:;" data-url="img_help_successful_connection">
            <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'assets/help/successful-connection.jpg'; ?>" alt="Successful Connection"/>
        </a>
        
        <!-- lightbox container hidden with CSS -->
        <div class="help-lightbox" id="img_help_successful_connection">
            <div class="popup" >
                <a href="javascript:;" class="help-close" data-close="img_help_successful_connection">&times;</a>
                <span style="background-image: url('<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'assets/help/successful-connection.jpg'; ?>')"></span>
            </div>
        </div>

        <p class="mt-3"><?php echo __( 'Failed connection result:', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></p>
        
        <!-- thumbnail image wrapped in a link -->
        <a class="img-help-wrapper" href="javascript:;" data-url="img_help_failed_connection">
            <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'assets/help/failed-connection.jpg'; ?>" alt="Failed Connection"/>
        </a>
        
        <!-- lightbox container hidden with CSS -->
        <div class="help-lightbox" id="img_help_failed_connection">
            <div class="popup" >
                <a href="javascript:;" class="help-close" data-close="img_help_failed_connection">&times;</a>
                <span style="background-image: url('<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'assets/help/failed-connection.jpg'; ?>')"></span>
            </div>
        </div>
    </div>

    <div class="col-sm-12 mt-3">
        <p class="mb-0"><strong><?php echo __( 'Scheduled Actions', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></strong></p>
        <p><?php echo __( 'This might be set up during the installation wizard phase, wherein the user sets up/manages the scheduled actions like Fetch Inventory updates, Fulfill Orders, Fetch Order Updates and Send In-store Order Updates in terms of Hourly, Daily, Weekly schedules and Cron-style commands in Custom field.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></p>
        <p><?php echo __( 'These actions happen in the background and will keep all relevant data up to date.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></p>

        <!-- thumbnail image wrapped in a link -->
        <a class="img-help-wrapper" href="javascript:;" data-url="img_help_scheduled_actions">
            <img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'assets/help/scheduled-actions.jpg'; ?>" alt="Scheduled Actions"/>
        </a>
        
        <!-- lightbox container hidden with CSS -->
        <div class="help-lightbox" id="img_help_scheduled_actions">
            <div class="popup" >
                <a href="javascript:;" class="help-close" data-close="img_help_scheduled_actions">&times;</a>
                <span style="background-image: url('<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'assets/help/scheduled-actions.jpg'; ?>')"></span>
            </div>
        </div>

        <ol class="mt-3">
            <li><?php echo __( 'Set of actions to perform, like Fetch Inventory Updates, Fulfill Orders, Fetch Order Updates and Send In-store Order Updates', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
            <li><?php echo __( 'Schedule of when the action will be performed', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ); ?></li>
            <li><?php echo sprintf( 
                esc_html__( 'Schedule the action for a certain specific time, e.g., cron-style command, like:%s %s', 'bring-3pl-shelfless-fulfillment-for-woocommerce'), 
                '<br>', 
                '<strong>*/30 * * * *</strong>'); ?></li>
        </ol>
    </div>
</div>
