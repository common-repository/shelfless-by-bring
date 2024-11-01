(function( $ ) {
	'use strict';

    var plugin_name;

	$(document).ready(function($) { 

        // initialize the plugin_name as data value
		plugin_name = get_plugin_name();

        $('html').removeClass('wp-toolbar');
    
        $('#bring-setup-step-indicators a[data-toggle="tab"]').on('show.bs.tab', function (e) {
            $(e.target).find('span').addClass('active');
            $(e.relatedTarget).find('span').removeClass('active');
        });

        $('.step-navigator .prev-step, .step-navigator .next-step, .step-navigator .finish').on('click', function() {
            var tab = $(this).data('toggle') + '-tab';
            if ( tab !== 'finish') {
                $(tab).tab('show');
            }
        });

        var $plugin_name = '#bring_3pl_shelfless_fulfillment_for_woocommerce'; 
        
        // API Settings
        $($plugin_name + '_api_creds_settings #submit_settings.btn-setup-creds').on('click', function (e) {
            $('#shelfless-setup-saved').removeClass('dashicons-no').addClass('dashicons-yes');
            $('#shelfless-setup-saved').removeClass('show').addClass('hide');
            $('#shelfless-setup-loading').removeClass('hide').addClass('show');

            $(this).prop('disabled', true);

            var data = {
				'mybring_shelfless_nonce': $('#nonce_wizard').val(),
				'action': 'bring_shelfless_wizard_save_api_creds',
                'shelfless_mybring_customer_id': $($plugin_name + '_mybring_customer_id').val(),
                'shelfless_api_key': $($plugin_name + '_shelfless_api_key').val(),
                'shelfless_api_secret_key': $($plugin_name + '_shelfless_api_secret_key').val(),
                'shelfless_api_mode': $($plugin_name + '_shelfless_api_mode').val(),
                'shelfless_debug_mode': $($plugin_name + '_shelfless_debug_mode').val(),
			}

            $.ajax({
                url: ajaxurl,
				method: 'POST',
				data: data,
				dataType: 'json',
				success: function (response) {
                    if (response.error === false) {
                        // The data sent earlier was sanitized. There is a possibility that the
                        // final data is not the same input so let's show the sanitized data here. -> Harvey
                        $.each(response.data, function(i, v) {
                            $('#' + i).val(v);
                        });

                        $('#shelfless-setup-loading').removeClass('show').addClass('hide');
                        $('#shelfless-setup-saved').removeClass('dashicons-no').addClass('dashicons-yes');
                        $('#shelfless-setup-saved').removeClass('hide').addClass('show');
                    }
                    else {
                        $('#shelfless-setup-loading').removeClass('show').addClass('hide');
                        $('#shelfless-setup-saved').removeClass('dashicons-yes').addClass('dashicons-no');
                        $('#shelfless-setup-saved').removeClass('hide').addClass('show');
                    }
                    $($plugin_name + '_api_creds_settings #submit_settings.btn-setup-creds').prop('disabled', false);
                },
                error: function (xhr, status, http_error) {
                    $('#shelfless-setup-loading').removeClass('show').addClass('hide');
                    $('#shelfless-setup-saved').removeClass('dashicons-yes').addClass('dashicons-no');
                    $('#shelfless-setup-saved').removeClass('hide').addClass('show');
                    $($plugin_name + '_api_creds_settings #submit_settings.btn-setup-creds').prop('disabled', false);
                } 
            });
        });

        // Inventory Stock Settings
        $($plugin_name + '_inventory_settings_mybring #submit_inventory_stock_settings.btn-setup-creds').on('click', function (e) {
            $('#shelfless-inv-setup-saved').removeClass('dashicons-no').addClass('dashicons-yes');
            $('#shelfless-inv-setup-saved').removeClass('show').addClass('hide');
            $('#shelfless-inv-setup-loading').removeClass('hide').addClass('show');

            $(this).prop('disabled', true);

            var data = {
				'mybring_shelfless_nonce': $('#nonce_inv_settings').val(),
				'action': 'bring_shelfless_wizard_save_inventory_settings',
                'shelfless_inventory_is_manage_stock': $($plugin_name + '_inventory_is_manage_stock').is(':checked'),
                'shelfless_inventory_is_sync_products': $($plugin_name + '_inventory_is_sync_products').is(':checked'),
                'shelfless_inventory_is_show_notif_oos': $($plugin_name + '_inventory_is_show_notif_oos').is(':checked'),
                'shelfless_inventory_is_show_notif_low_stock': $($plugin_name + '_inventory_is_show_notif_low_stock').is(':checked'),
                'shelfless_inventory_low_threshold_value': $($plugin_name + '_inventory_low_threshold_value').val(),
                'shelfless_inventory_is_use_cost_price_field': $($plugin_name + '_inventory_is_use_cost_price_field').is(':checked'),
                'shelfless_inventory_cost_price_currency': $($plugin_name + '_inventory_cost_price_currency').val(),
                'shelfless_inventory_basic_unit': $($plugin_name + '_inventory_basic_unit').val(),
			}

            $.ajax({
                url: ajaxurl,
				method: 'POST',
				data: data,
				dataType: 'json',
				success: function (response) {
                    if (response.error === false) {
                        // The data sent earlier was sanitized. There is a possibility that the
                        // final data is not the same input so let's show the sanitized data here. -> Harvey
                        $.each(response.data, function(i, v) {
                            $('#' + i).val(v);
                        });

                        $('#shelfless-inv-setup-loading').removeClass('show').addClass('hide');
                        $('#shelfless-inv-setup-saved').removeClass('dashicons-no').addClass('dashicons-yes');
                        $('#shelfless-inv-setup-saved').removeClass('hide').addClass('show');
                    }
                    else {
                        $('#shelfless-inv-setup-loading').removeClass('show').addClass('hide');
                        $('#shelfless-inv-setup-saved').removeClass('dashicons-yes').addClass('dashicons-no');
                        $('#shelfless-inv-setup-saved').removeClass('hide').addClass('show');
                    }
                    $($plugin_name + '_inventory_settings_mybring #submit_inventory_stock_settings.btn-setup-creds').prop('disabled', false);
                },
                error: function (xhr, status, http_error) {
                    $('#shelfless-inv-setup-loading').removeClass('show').addClass('hide');
                    $('#shelfless-inv-setup-saved').removeClass('dashicons-yes').addClass('dashicons-no');
                    $('#shelfless-inv-setup-saved').removeClass('hide').addClass('show');
                    $($plugin_name + '_inventory_settings_mybring #submit_inventory_stock_settings.btn-setup-creds').prop('disabled', false);
                } 
            });
        });

        // Order Settings
        $($plugin_name + '_order_settings_mybring #submit_order_settings.btn-setup-creds').on('click', function (e) {
            $('#shelfless-order-setup-saved').removeClass('dashicons-no').addClass('dashicons-yes');
            $('#shelfless-order-setup-saved').removeClass('show').addClass('hide');
            $('#shelfless-order-setup-loading').removeClass('hide').addClass('show');

            $(this).prop('disabled', true);

            var dataValues = {};
            $('.vas_codes_wrapper').find('input').each(
                function(idx, child) { 
                    dataValues[idx] = child.value;
                }
            );

            var data = {
				'mybring_shelfless_nonce': $('#nonce_order_settings').val(),
				'action': 'bring_shelfless_wizard_save_order_settings',
                'shelfless_order_process_from_days_ago': $($plugin_name + '_order_process_from_days_ago').val(),
                'shelfless_order_add_bring_statuses': $($plugin_name + '_order_add_bring_statuses').is(':checked'),
                'shelfless_order_enabled_bring_statuses': $($plugin_name + '_order_enabled_bring_statuses').val(),
                'shelfless_order_is_show_notif_partial_shipment': $($plugin_name + '_order_is_show_notif_partial_shipment').is(':checked'),
                'shelfless_order_is_show_notif_cancelled': $($plugin_name + '_order_is_show_notif_cancelled').is(':checked'),
                'shelfless_order_fallback_service_carrier': $($plugin_name + '_order_fallback_service_carrier').val(),
                'shelfless_order_fallback_service_code': $($plugin_name + '_order_fallback_service_code').val(),
                'shelfless_order_value_added_services_codes': dataValues,
			}

            $.ajax({
                url: ajaxurl,
				method: 'POST',
				data: data,
				dataType: 'json',
				success: function (response) {
                    if (response.error === false) {
                        // The data sent earlier was sanitized. There is a possibility that the
                        // final data is not the same input so let's show the sanitized data here. -> Harvey
                        $.each(response.data, function(i, v) { 
                            // don't include the VAS codes fields
                            var field = plugin_name + '_order_value_added_services_codes';
                            if( ! ( i == field ) )
                                $('#' + i).val(v);
                        });

                        $('#shelfless-order-setup-loading').removeClass('show').addClass('hide');
                        $('#shelfless-order-setup-saved').removeClass('dashicons-no').addClass('dashicons-yes');
                        $('#shelfless-order-setup-saved').removeClass('hide').addClass('show');
                    }
                    else {
                        $('#shelfless-order-setup-loading').removeClass('show').addClass('hide');
                        $('#shelfless-order-setup-saved').removeClass('dashicons-yes').addClass('dashicons-no');
                        $('#shelfless-order-setup-saved').removeClass('hide').addClass('show');
                    }
                    $($plugin_name + '_order_settings_mybring #submit_order_settings.btn-setup-creds').prop('disabled', false);
                },
                error: function (xhr, status, http_error) {
                    $('#shelfless-order-setup-loading').removeClass('show').addClass('hide');
                    $('#shelfless-order-setup-saved').removeClass('dashicons-yes').addClass('dashicons-no');
                    $('#shelfless-order-setup-saved').removeClass('hide').addClass('show');
                    $($plugin_name + '_order_settings_mybring #submit_order_settings.btn-setup-creds').prop('disabled', false);
                } 
            });
        });

        // Shipping Mappings
        $($plugin_name + '_order_shipping_maps_settings_mybring #submit_order_shipping_maps_settings.btn-setup-creds').on('click', function (e) {
            $('#shelfless-shipping-maps-setup-saved').removeClass('dashicons-no').addClass('dashicons-yes');
            $('#shelfless-shipping-maps-setup-saved').removeClass('show').addClass('hide');
            $('#shelfless-shipping-maps-setup-loading').removeClass('hide').addClass('show');

            $(this).prop('disabled', true);

            var data = {
				'mybring_shelfless_nonce' : $('#nonce_order_shipping_maps_settings').val(),
				'action' : 'bring_shelfless_wizard_save_shipping_mappings',
			}

            $($plugin_name + '_order_shipping_maps_settings_mybring .form-table').find('select').each(function() { 

                data[$(this).attr('id').replace("bring_3pl_shelfless_fulfillment_for_woocommerce", "shelfless")] = $(this).val();

            });

            $.ajax({
                url: ajaxurl,
				method: 'POST',
				data: data,
				dataType: 'json',
				success: function (response) {
                    if (response.error === false) {
                        // The data sent earlier was sanitized. There is a possibility that the
                        // final data is not the same input so let's show the sanitized data here. -> Harvey
                        $.each(response.data, function(i, v) {
                            $('#' + i).val(v);
                        });

                        $('#shelfless-shipping-maps-setup-loading').removeClass('show').addClass('hide');
                        $('#shelfless-shipping-maps-setup-saved').removeClass('dashicons-no').addClass('dashicons-yes');
                        $('#shelfless-shipping-maps-setup-saved').removeClass('hide').addClass('show');
                    }
                    else {
                        $('#shelfless-shipping-maps-setup-loading').removeClass('show').addClass('hide');
                        $('#shelfless-shipping-maps-setup-saved').removeClass('dashicons-yes').addClass('dashicons-no');
                        $('#shelfless-shipping-maps-setup-saved').removeClass('hide').addClass('show');
                    }
                    $($plugin_name + '_order_shipping_maps_settings_mybring #submit_order_shipping_maps_settings.btn-setup-creds').prop('disabled', false);
                },
                error: function (xhr, status, http_error) {
                    $('#shelfless-shipping-maps-setup-loading').removeClass('show').addClass('hide');
                    $('#shelfless-shipping-maps-setup-saved').removeClass('dashicons-yes').addClass('dashicons-no');
                    $('#shelfless-shipping-maps-setup-saved').removeClass('hide').addClass('show');
                    $($plugin_name + '_order_shipping_maps_settings_mybring #submit_order_shipping_maps_settings.btn-setup-creds').prop('disabled', false);
                } 
            });
            
        });

        // Scheduled Actions
        $($plugin_name + '_scheduled_actions_mybring #submit_action_sched.btn-setup-creds').on('click', function (e) {
            $('#shelfless-sched-action-saved').removeClass('dashicons-no').removeClass('dashicons-yes');
            $('#shelfless-sched-action-saved').removeClass('show').addClass('hide');
            $('#shelfless-sched-action-saved').removeClass('hide').addClass('show');

            $(this).prop('disabled', true);

            var data = $($plugin_name + '_scheduled_actions_mybring').serialize();
            
            $.ajax({
                url: ajaxurl,
				method: 'POST',
				data: data,
				dataType: 'json',
				success: function (response) {
                    if (response.error === false) {
                        $('#shelfless-sched-action-loading').removeClass('show').addClass('hide');
                        $('#shelfless-sched-action-saved').removeClass('dashicons-no').addClass('dashicons-yes');
                        $('#shelfless-sched-action-saved').removeClass('hide').addClass('show');
                    }
                    else {
                        
                        $('#shelfless-sched-action-loading').removeClass('show').addClass('hide');
                        $('#shelfless-sched-action-saved').removeClass('dashicons-yes').addClass('dashicons-no');
                        $('#shelfless-sched-action-saved').removeClass('hide').addClass('show');
                        alert(response.msg);
                    }
                    $($plugin_name + '_scheduled_actions_mybring #submit_action_sched.btn-setup-creds').prop('disabled', false);
                },
                error: function (xhr, status, http_error) {
                    $('#shelfless-sched-action-loading').removeClass('show').addClass('hide');
                    $('#shelfless-sched-action-saved').removeClass('dashicons-yes').addClass('dashicons-no');
                    $('#shelfless-sched-action-saved').removeClass('hide').addClass('show');
                    $($plugin_name + '_scheduled_actions_mybring #submit_action_sched.btn-setup-creds').prop('disabled', false);
                } 
            });
        });

        // Dream Logistics - setup wizard
        $($plugin_name + '_dream_logistics_settings_mybring #submit_dream_logistics_settings.btn-setup-creds').on('click', function (e) {
            $('#shelfless-dream-logistics-saved').removeClass('dashicons-no').addClass('dashicons-yes');
            $('#shelfless-dream-logistics-saved').removeClass('show').addClass('hide');
            $('#shelfless-dream-logistics-loading').removeClass('hide').addClass('show');

            $(this).prop('disabled', true);
                
            var dream_logistics = '';
            if( $($plugin_name + '_dream_logistics_use_of_warehouse').is(':checked') ) {
                dream_logistics = $($plugin_name + '_dream_logistics_use_of_warehouse').val();
            }  

            var data = {
				'mybring_shelfless_nonce': $('#nonce_dream_logistics_settings').val(),
				'action': 'bring_shelfless_wizard_save_dream_logistics',
                'shelfless_dream_logistics_use_of_warehouse': dream_logistics,
                'shelfless_dream_logistics_webshop_id': $($plugin_name + '_dream_logistics_webshop_id').val(),
			}

            $.ajax({
                url: ajaxurl,
				method: 'POST',
				data: data,
				dataType: 'json',
				success: function (response) { 
                    if (response.error === false) {
                        // The data sent earlier was sanitized. There is a possibility that the
                        // final data is not the same input so let's show the sanitized data here. -> Harvey
                        $.each(response.data, function(i, v) { 
                            $('#' + i).val(v);
                        });

                        $('#shelfless-dream-logistics-loading').removeClass('show').addClass('hide');
                        $('#shelfless-dream-logistics-saved').removeClass('dashicons-no').addClass('dashicons-yes');
                        $('#shelfless-dream-logistics-saved').removeClass('hide').addClass('show');
                    }
                    else {
                        $('#shelfless-dream-logistics-loading').removeClass('show').addClass('hide');
                        $('#shelfless-dream-logistics-saved').removeClass('dashicons-yes').addClass('dashicons-no');
                        $('#shelfless-dream-logistics-saved').removeClass('hide').addClass('show');
                    }
                    $($plugin_name + '_dream_logistics_settings_mybring #submit_dream_logistics_settings.btn-setup-creds').prop('disabled', false);
                },
                error: function (xhr, status, http_error) {
                    $('#shelfless-dream-logistics-loading').removeClass('show').addClass('hide');
                    $('#shelfless-dream-logistics-saved').removeClass('dashicons-yes').addClass('dashicons-no');
                    $('#shelfless-dream-logistics-saved').removeClass('hide').addClass('show');
                    $($plugin_name + '_dream_logistics_settings_mybring #submit_dream_logistics_settings.btn-setup-creds').prop('disabled', false);
                } 
            });
        });

    });

    function get_plugin_name() { 
		var data = {
			'action' : 'bring_shelfless_return_plugin_name_to_js_script'
		}
		
		$.ajax({
			url: ajaxurl,
			method: 'POST',
			data: data,
			dataType: 'json',
			context: this,
			complete: function( response ) {
				var result = JSON.parse( JSON.stringify(response) );
				plugin_name = result['responseJSON'];
				$( '#action_dream_logistics_plugin_name' ).val( plugin_name );
			},
			error: function (xhr, status, http_error) { 
				console.log('ERROR: ' + http_error);
			}
		});
		
	}

})( jQuery );