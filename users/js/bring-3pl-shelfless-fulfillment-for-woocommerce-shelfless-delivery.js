(function( $ ) {
	'use strict';

	var plugin_name = 'bring-3pl-shelfless-fulfillment-for-woocommerce';

	$(document).ready(function($) {

		shelfless_delivery_select_pickup_point();
    
    });

	function shelfless_delivery_select_pickup_point() {
		
		$('form.checkout, form.shelfless_delivery_pickup_points').on( 'change', 'select#shelfless-delivery-bring-pickup-point-id', function( e ) {
			
			if (typeof wc_checkout_params === 'undefined' && typeof wc_cart_params === 'undefined' ) return false;

			e.preventDefault();

			var url;

			if (typeof wc_checkout_params !== 'undefined')
				url = wc_checkout_params.ajax_url;

			if (typeof wc_cart_params !== 'undefined')
				url = wc_cart_params.ajax_url;

			var pickup_point_id = $(this).val();
			var pickup_point_loc = $(this).find(':selected').text();

			$('#shelfless-delivery-pickup-location').val(pickup_point_loc);

			console.log('SHELFLESS DELIVERY: Pickup point selected is ' + pickup_point_id + ' - ' + pickup_point_loc);

			var data = {
				'action': 'pickup_point_selection',
				'pickup_point_id': pickup_point_id,
				'pickup_point_location': pickup_point_loc,
				'shelfless_delivery': $('#shelfless-delivery-pickup-nonce').val()
			}

			$.ajax({
				type: 'POST',
				url: url,
				data: data,
				success: function( response ) {
					console.log('SHELFLESS DELIVERY: Pickup point ID updated in the order.');
				},
				error: function (xhr, status, http_error) {
					console.log('SHELFLESS DELIVERY ERROR: ' + http_error);
				}
			});

		});
		
	}

})( jQuery );