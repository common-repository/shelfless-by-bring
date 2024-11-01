(function( $ ) {
	'use strict';

	var plugin_name;

	$(document).ready(function($) {	
		
		// initialize the plugin_name as data value
		plugin_name = get_plugin_name();

		$('[data-toggle="tooltip"]').tooltip({
			trigger: 'hover focus',
			placement: 'right',
		});

		$('#shelfless-diagnostics-message').hide();
		$('#shelfless-diagnostics-message').alert();

		$('#diagnostics-results, #delete-transients-results').hide();
		$('#shelfless-diagnostics-loading, #shelfless_delete_transients_loading').hide();
		$('#diagnostics-results-text, #delete_transients_results_text').html('');
		
		$('#shelfless-diagnostics-message').on('close.bs.alert', function(e) {
			e.preventDefault();
			$(this).hide();
		});

		$('#run-diagnostics-api-settings').on('click', function(e) {
			$('#diagnostics-results').show();
			$('#diagnostics-results-text').html('');
			$('#shelfless-diagnostics-loading').show();
			
			$(this).prop('disabled', true);

			var data = {
				'mybring_shelfless_nonce': $('#nonce').val(),
				'action': 'bring_shelfless_api_settings_diagnostics',
			}

			$('#diagnostics-results-text').append('Connecting...<br />');
			$.ajax({
				url: ajaxurl,
				method: 'POST',
				data: data,
				dataType: 'json',
				success: function (response) {
					$('#shelfless-diagnostics-loading').hide();
					$('#diagnostics-results-text').append('Connected...<br />');
					if (response.error === false && response.data.http_code === 200) {
						$('#shelfless-diagnostics-message-content').html('').html('Bring 3PL Shelfless successfully connected to Bring using the API credentials.');
						$('#shelfless-diagnostics-message').removeClass('alert-danger alert-warning').addClass('alert-success');
						$('#shelfless-diagnostics-message').hide().show();
						$.each( response.data, function(i, v) {
							$('#diagnostics-results-text').append(i + ': ' + v + '<br />');
						});
						$('#diagnostics-results-text').append('<span class="badge badge-success">Connection SUCCESSFUL.</span>');
					}
					else {
						$('#shelfless-diagnostics-message-content').html('').html('Bring 3PL Shelfless connection attempt to Bring unsuccessful. Please set correct API credentials and try again.');
						$('#shelfless-diagnostics-message').removeClass('alert-success alert-warning').addClass('alert-danger');
						$('#shelfless-diagnostics-message').hide().show();
						$.each( response.data, function(i, v) {
							$('#diagnostics-results-text').append(i + ': ' + v + '<br />');
						});
						$('#diagnostics-results-text').append('<span class="badge badge-danger">Connection TERMINATED.</span>');
						
					}
					$('#run-diagnostics-api-settings').prop('disabled', false);
				},
				error: function (xhr, status, http_error) {
					$('#shelfless-diagnostics-loading').hide();
					$('#shelfless-diagnostics-message').removeClass('alert-success alert-warning').addClass('alert-danger');
					$('#shelfless-diagnostics-message').hide().show();
					$('#shelfless-diagnostics-message-content').html('').html('Bring 3PL Shelfless connection attempt to Bring unsuccessful. An error occured while attempting to connect. Please try again.');
					$('#shelfless-diagnostics-message').show();
					$('#diagnostics-results-text').append(status + ': ');
					$('#diagnostics-results-text').append(http_error + '<br />');
					$('#diagnostics-results-text').append('<span class="badge badge-danger">Connection UNSUCCESSFUL.</span>');
					$('#run-diagnostics-api-settings').prop('disabled', false);
				}
			});
		});

		$('#run_diagnostics_delete_transients').on('click', function(e) {
			$('#delete_transients_results_text').html('');
			$('#shelfless_delete_transients_loading').show();
			
			$(this).prop('disabled', true);

			var data = {
				'mybring_shelfless_nonce': $('#nonce_delete_transients').val(),
				'action': 'bring_shelfless_api_settings_diagnostics_delete_transients',
			}

			$('#delete_transients_results_text').append('Deleting transients...<br />');
			$.ajax({
				url: ajaxurl,
				method: 'POST',
				data: data,
				dataType: 'json',
				success: function (result) {
					$('#shelfless_delete_transients_loading').hide();
					if ( result.error === false ) {
						$('#delete_transients_results_text').append('Deleted transients...<br />');
						$('#shelfless-diagnostics-message-content').html('').html('Shelfless successfully deleted all related transients.');
						$('#shelfless-diagnostics-message').removeClass('alert-warning').addClass('alert-success');
						$('#shelfless-diagnostics-message').hide().show();

						$.each( result.data, function(i, v) {
							$('#delete_transients_results_text').append(i + ': ' + v.name + '<br />');
						});
						$('#delete_transients_results_text').append('<span class="badge badge-success">Delete transients SUCCESSFUL.</span>');
					} else {
						$('#shelfless-diagnostics-message-content').html('').html('No records found of Shelfless related transients, please try again.');
						$('#shelfless-diagnostics-message').removeClass('alert-success').addClass('alert-warning');
						$('#shelfless-diagnostics-message').hide().show();
						$('#delete_transients_results_text').append('<span class="badge badge-warning">Delete transients TERMINATED.</span>');
					}
					$('#run_diagnostics_delete_transients').prop('disabled', false);
				},
				error: function (xhr, status, http_error) { 
					console.log(xhr);
				}
			});
		});

		bring_3pl_shelfless_inventory_datatables();

		bring_3pl_shelfless_stock_adjustment_report_datatables();

		$('.shelfless-action-sched').on('change', function() { 
			
			var name = $(this).prop('name');
			var elementID = $('input[name=' + name + '_specific_time]');
			if( $(this).val() == 'custom' ) {
				elementID.prop('disabled', false)
				.focus();
			}
			else {
				elementID.val('')
				.prop('disabled', true);
				isValid = true;
			}
		});

		$( '.sched-action-specific-time' ).on( 'keyup', function(e) { 
			var cronInput = $.trim( $(this).val() ); 

			console.log( cronInput );

			var isValid = isCronValid( cronInput );

			console.log( isValid );

			/**
			 * if isValid is false, it should disable the save button
			 */
			var btnSubmit = $( '#submit_action_sched' );
			var elementNotify = $( '#scheduled-actions .table caption #notify' );
			if( ! isValid ) {
				btnSubmit.prop( 'disabled', true );
				elementNotify.html('<p class="lead text-danger"><strong>Invalid cron expression</strong></p>');
				$(this).focus();
			} else {
				btnSubmit.prop( 'disabled', false );
				elementNotify.html('');
				$(this).focus();
			}

		});

		// Dismiss notice within #notify element
		$( '#notify' ).on( 'click', function( e ) { 
			$( '#notify .notice' ).remove();
		} );
		
		// this applies to product level
		// deselecting the Enable to fulfill if there is/are ongoing orders
		$( '#_bring_3pl_shelfless_fulfillment_for_woocommerce_is_fulfill' ).on( 'change', function(e) { 

			var postid = $(this).attr('class').split(' ');
			var rows = $(this);
			var productids = {};
			var product_type = $('#product-type').val();
			
			if( ! $(this).is(':checked') ) { 
				$.each(rows, function(index) {
					productids[index] = {
						'product_id': postid[2],
						'is_fulfill': 0,
					};
				});

				bring_3pl_shelfless_update_products_is_fulfill( productids, product_type, 'product_level' );

			} else {

				$.each(rows, function(index) {
					productids[index] = {
						'product_id': postid[2],
						'is_fulfill': 1,
					};
				});

				bring_3pl_shelfless_update_products_is_fulfill( productids, product_type, 'product_level' );

			}
		} );

		// this applies to product level
		// deselecting the Enable Article Sync with Shelfless
		$( '#_bring_3pl_shelfless_fulfillment_for_woocommerce_is_article_sync' ).on( 'change', function(e) { 

			var postid = $(this).attr('class').split(' ');
			var rows = $(this);
			var productids = {};
			var product_type = $('#product-type').val();
			
			if( ! $(this).is(':checked') ) { 
				$.each(rows, function(index) {
					productids[index] = {
						'product_id': postid[2],
						'is_sync': 0,
					};
				});

				bring_3pl_shelfless_create_update_products_is_sync( productids, product_type, 'product_level' );

			} else {

				$.each(rows, function(index) {
					productids[index] = {
						'product_id': postid[2],
						'is_sync': 1,
					};
				});

				bring_3pl_shelfless_create_update_products_is_sync( productids, product_type, 'product_level' );

			}
		} );

		// custom lightbox
		$( '.img-help-wrapper' ).on( 'click', function() {
			var $this = $(this);
			var $id = $this.data('url');

			$( '#' + $id ).addClass('show');
		} );

		// custom lightbox close
		$( '.help-close' ).on( 'click', function( e ) { 
			var $this = $(this);
			var $id = $this.data('close');

			$( '#' + $id ).removeClass('show');
		} );

		// Refresh datatable/grid - Products tab
		$('#populate_inventory_grid').on('click', function() {
			$('#bring-shelfless-inventory-datatables').DataTable().ajax.reload();
		});

		// Refresh datatable/grid - Stock Adjustment tab
		$('#populate_inventory_stock_adjustment_report_grid').on('click', function() { 
			$('#bring-shelfless-stock-adjustment-datatables').DataTable().ajax.reload();
		});

		// Add new value-added services codes		
		var codesDiv = $('#vas_codes_container');
		var i = $('#vas_codes_container p').size() + 1;
		
		$('#vas_codes_add_new').on( 'click', function() { 
			$('<p><label for="row_vas_code"><input type="text" id="' + plugin_name + '_order_value_added_services_codes_' + i + '" name="' + plugin_name + '_order_settings_value_added_services_codes[]" value="1091" placeholder="1091" class="vas_codes_wrapper"/></label> <a href="javascript:;" class="remove-vas-code"><span class="bring-shelfless-remove dashicons dashicons-no"></a></p>').appendTo( codesDiv );
			i++;
			return false;
		} );

		// remove the newly added VAS code
		$('#vas_codes_container').on('click', '.remove-vas-code', function() { 
			$(this).parents('p').remove();
			i--;
			return false;
		} );

		// auto remove non-numeric characters
		$('.vas_codes_wrapper, .dream_logistics_wrapper').on( 'keyup blur focus', 
		'input.vas_codes_wrapper, input.dream_logistics_wrapper',  function(e) { 
			var fieldVal = $(this).val();
			if( /\D/g.test( fieldVal ) ) { 
				// Filter non-digits from input value.
				this.value = fieldVal.replace( /\D/g, '' );
			}
		});

		// auto remove non-numeric characters except comma and period
		$('.advanced_settings_field').on( 'keyup blur focus', 
		'input.advanced_settings_field',  function(e) { 
			var fieldVal = $(this).val();
			if( /[^\d.,]/g.test( fieldVal ) ) { 
				// Filter non-digits from input value.
				this.value = fieldVal.replace( /[^\d.,]/g, '' );
			}
		});

		$('.service-method').on('click', function() { 
			var idx = $(this).attr('id');
			if( $(this).is(':checked') ) {
				$('#' + idx + '_card').removeClass('d-none');
				$('#' + idx + '_title').removeAttr('disabled');
				$('#' + idx + '_price').removeAttr('disabled');

				
				$('#' + idx + '_title').attr('required', true);
				$('#' + idx + '_price').attr('required', true);
				
				$('#' + idx + '_title').focus();
			} else {
				$('#' + idx + '_card').addClass('d-none');
				$('#' + idx + '_title').attr('disabled', true);
				$('#' + idx + '_price').attr('disabled', true);

				
				$('#' + idx + '_title').removeAttr('required');
				$('#' + idx + '_price').removeAttr('required');
			}
		});

		// Addon toggle to enable each corresponding input field
		$('.addon-toggle').on('click', function() { 
			var idx = $(this).data('idx');

			// clear input field value
			$('#'+idx).val('');
			
			if( ! $(this).is(':checked') ) { 
				$('#'+idx).attr('disabled', true);
			} else {
				$('#'+idx).removeAttr('disabled');
			}
		} );

		$('.select-multiple-js').select2();

		$('#defaultDimensionsHeading .btn-link').on('click', function() { 
			var classes = $( this ).children('span').attr( 'class' );
			
			$(this).children('span').removeClass( classes );
			if( $(this).hasClass('collapsed') ) {
				$(this).children('span').addClass('dashicons dashicons-arrow-down-alt2');
			} else {
				$(this).children('span').addClass('dashicons dashicons-arrow-right-alt2');
			}

		});

	});
	
	function bring_3pl_shelfless_update_products_is_fulfill( productids, type='', action_source='' ) {

		if (typeof productids === 'undefined') return false;

		var data = {
			'mybring_shelfless_nonce' : $('#nonce_pull_inv_grid').val(),
			'action' : 'bring_shelfless_update_prod_is_fulfill',
			'productids' : productids,
			'product_type' : type,
			'action_source' : action_source,
		}

		var result = '';
		$.ajax({
			url: ajaxurl,
			method: 'POST',
			data: data,
			dataType: 'json',
			context: this,
			success: function (response) {
				if( response.error == true ) {
					$('#notify').html( '' ).html( response.msg ).html( $('#notify').text() );
				} else {
					if( response.show == true ) {
						$('#notify').html( '' ).html( response.msg ).html( $('#notify').text() );
					} else {
						$('#notify').html( '' );
					}
				}
			},
			error: function (xhr, status, http_error) {
				console.log('ERROR: ' + http_error);
			}
		});
	}

	function bring_3pl_shelfless_create_update_products_is_sync(productids, type='', action_source='') {

		if (typeof productids === 'undefined') return false;

		var data = {
			'mybring_shelfless_nonce' : $('#nonce_pull_inv_grid').val(),
			'action' : 'bring_shelfless_update_prod_is_article_sync',
			'productids' : productids,
			'product_type' : type,
			'action_source' : action_source,
		}

		var result = '';
		$.ajax({
			url: ajaxurl,
			method: 'POST',
			data: data,
			dataType: 'json',
			context: this,
			success: function (response) {
				if( response.error == true ) {
					$('#notify').html( '' ).html( response.msg ).html( $('#notify').text() );
				} else {
					if( response.show == true ) { 
						$('#notify').html( '' ).html( response.msg ).html( $('#notify').text() );
					} else {
						$('#notify').html( '' );
					}
				}
			},
			error: function (xhr, status, http_error) { 
				console.log('ERROR: ' + http_error);
			}
		});
	}

	function bring_3pl_shelfless_inventory_datatables() { 

		// alert, throw, none... set to none in order not to visibly show warning to end users
		$.fn.dataTable.ext.errMode = 'none'; 

		var data = {
			'mybring_shelfless_nonce' : $('#nonce_pull_inv_grid').val(),
			'action' : 'bring_shelfless_inventory_datatables_endpoint',
		}

		var table = $('#bring-shelfless-inventory-datatables').DataTable({ 
			'processing': true,
			'serverSide': true,
			'serverMethod': 'post',
			'ajax' : {
				url : ajaxurl,
				data : data,
				cache : false,
			},
			'columns' : [
				{ data : 'chkbx' }, 
				{ data : 'chkbx_sync' }, 
				{ data : 'image' }, 
				{ data : 'product_edit_link' }, 
				{ data : 'sku' }, 
				{ data : 'type' }, 
				{ data : 'matched' },
			],
			'bSortable' : true,
        	'bRetrieve' : true,
			'aoColumnDefs' : [
				{ "aTargets": [ 0 ], "bSortable": false },
				{ "aTargets": [ 1 ], "bSortable": false },
				{ "aTargets": [ 2 ], "bSortable": false },
				{ "aTargets": [ 3 ], "bSortable": true },
				{ "aTargets": [ 4 ], "bSortable": true },
				{ "aTargets": [ 5 ], "bSortable": false },
				{ "aTargets": [ 6 ], "bSortable": true }
			],
			'aaSorting' : [[3, 'asc']],
			'language' : {
				"processing": "Currently processing records to display...",
				"loadingRecords": "Please wait - loading..."
			},
			responsive: true,
			"drawCallback": function( settings ) {
				var api = this.api();
				var fulfillAll = $('#inventory-select-all');
				var syncAll = $('#inventory-sync-all');
		 
				// Output the data for the visible rows to the browser's console
				var data_rows = api.rows( {page:'current'} ).data();
				var fulfill_rows = [];
				var sync_rows = [];
				$( api.rows( {page:'current'} ).data().each(function (value, index) { 
					if( value.is_fulfill ) {
						fulfill_rows.push(index);
					}
					if( value.is_sync ) {
						sync_rows.push(index);
					}
				} ) );
				
				if( data_rows.length == fulfill_rows.length ) { 
					fulfillAll.prop({
						'checked': true
					});
				} else {
					fulfillAll.prop({
						'checked': false
					});
				}
				
				if( data_rows.length == sync_rows.length ) { 
					syncAll.prop({
						'checked': true
					});
				} else {
					syncAll.prop({
						'checked': false
					});
				}
			}
		});

		var rows;
		var temp_rows;
		var productID;
		var prod_id = [];
		var productids = [];
		
		// Handle click on "Select all" control for is_fulfill products
		$('#inventory-select-all').on('click', function() { 
			// Check/uncheck all checkboxes in the table
			rows = table.rows({ 'search': 'applied' }).nodes();
			$('.inv-check-is-fulfill', rows).prop('checked', this.checked);
			
			$( rows ).each(function (index, row) {
				productID = $(this).find('.inv-check-is-fulfill').data('productid');
				
				if( $(this).find('.inv-check-is-fulfill').is(':checked') ) {
					productids[index] = {
						'product_id': productID,
						'is_fulfill': 1,
					};
			  	} else {
					productids[index] = {
						'product_id': productID,
						'is_fulfill': 0,
					};
				}
			});
			
			bring_3pl_shelfless_update_products_is_fulfill(productids);

		});

		// Handle click on checkbox to set state of "Select all" control for is_fulfill products
		$('#bring-shelfless-inventory-datatables tbody').on('change', '.inv-check-is-fulfill', function() { 
			var selectAll = $('#inventory-select-all');
			var idx = $( this ).index( this );
			productID = $(this).data('productid');
			
			rows = table.rows({ 'search': 'applied' }).nodes();
			temp_rows = $( table.$('.inv-check-is-fulfill').map(function () {
				return $(this).prop("checked") ? $(this).closest('tr') : null;
			} ) );

			// If checkbox is not checked
			if( ! this.checked ) {
				
				var el = selectAll.get(0);
				
				// If "Select all" control is checked and has 'indeterminate' property
				if(el && el.checked && ('indeterminate' in el)) {
					// Set visual state of "Select all" control 
					// as 'indeterminate'
					el.indeterminate = true;
			   	}

				if( el.indeterminate ) {
					$(el).prop({
						'checked': false
					});
				}

				prod_id[idx] = {
					'product_id' : productID,
					'is_fulfill' : 0
				}

			} else {
				prod_id[idx] = {
					'product_id' : productID,
					'is_fulfill' : 1
				}
			}
			
			bring_3pl_shelfless_update_products_is_fulfill(prod_id, '', 'product_grid');
			
			// if all rows is equals to all checked rows, the set the status of the select all checkbox to checked
			if( rows.length == temp_rows.length ) { 
				// Set visual state of "Select all" control
				// as 'indeterminate' to false when all checkboxes are checked
				selectAll.indeterminate = false;
				selectAll.prop({
					'checked': true
				});
			}
		});

		// Handle click on "Select all" control for is_sync products
		$('#inventory-sync-all').on('click', function() { 
			// Check/uncheck all checkboxes in the table
			rows = table.rows({ 'search': 'applied' }).nodes();
			$('.inv-check-is-sync', rows).prop('checked', this.checked);
			
			$( rows ).each(function (index, row) {
				productID = $(this).find('.inv-check-is-sync').data('productid');
				
				if( $(this).find('.inv-check-is-sync').is(':checked') ) {
					productids[index] = {
						'product_id': productID,
						'is_sync': 1,
					};
			  	} else {
					productids[index] = {
						'product_id': productID,
						'is_sync': 0,
					};
				}
			});
			
			bring_3pl_shelfless_create_update_products_is_sync(productids);

		});

		// Handle click on checkbox to set state of "Select all" control for is_sync products
		$('#bring-shelfless-inventory-datatables tbody').on('change', '.inv-check-is-sync', function() { 
			var syncAll = $('#inventory-sync-all');
			var idx = $( this ).index( this );
			productID = $(this).data('productid');
			
			rows = table.rows({ 'search': 'applied' }).nodes();
			temp_rows = $( table.$('.inv-check-is-sync').map(function () {
				return $(this).prop("checked") ? $(this).closest('tr') : null;
			} ) );


			// If checkbox is not checked
			if( ! this.checked ) { 
				
				var el = syncAll.get(0); 
				
				// If "Select all" control is checked and has 'indeterminate' property
				if(el && el.checked && ('indeterminate' in el)) {
					// Set visual state of "Select all" control 
					// as 'indeterminate'
					el.indeterminate = true;
			   	}

				if( el.indeterminate ) {
					$(el).prop({
						'checked': false
					});
				}

				prod_id[idx] = {
					'product_id' : productID,
					'is_sync' : 0
				}

			} else {
				prod_id[idx] = {
					'product_id' : productID,
					'is_sync' : 1
				}
			}
			
			bring_3pl_shelfless_create_update_products_is_sync(prod_id, '', 'product_grid');

			// if all rows is equals to all checked rows, then set the status of the select all checkbox to checked
			if( rows.length == temp_rows.length ) { 
				// Set visual state of "Select all" control
				// as 'indeterminate' to false when all checkboxes are checked
				syncAll.indeterminate = false;
				syncAll.prop({
					'checked': true
				});
			}
		});

	}

	function bring_3pl_shelfless_stock_adjustment_report_datatables() { 

		// alert, throw, none... set to none in order not to visibly show warning to end users
		$.fn.dataTable.ext.errMode = 'none'; 

		var data = {
			'mybring_shelfless_nonce' : $('#nonce_pull_stock_adjustment_report').val(),
			'action' : 'bring_shelfless_stock_adjustment_report_datatables_endpoint',
		}

		var table = $('#bring-shelfless-stock-adjustment-datatables').DataTable({ 
			'serverMethod': 'post',
			'ajax' : {
				url : ajaxurl,
				data : data,
				cache : false,
			},
			'columns' : [
				{ data : 'customerNumber' }, 
				{ data : 'sku' }, 
				{ data : 'warehouseId' }, 
				{ data : 'adjustment' }, 
				{ data : 'unit' }, 
				{ data : 'balanceType' },
				{ data : 'reason' },
				{ data : 'sourceCreatedEpoch' },
				{ data : 'eventCreated' },
				{ data : 'batchNumber' },
			],
			'bSortable' : true,
        	'bRetrieve' : true,
			'aoColumnDefs' : [
				{ "aTargets": [ 0 ], "bSortable": true },
				{ "aTargets": [ 1 ], "bSortable": true },
				{ "aTargets": [ 2 ], "bSortable": true },
				{ "aTargets": [ 3 ], "bSortable": false },
				{ "aTargets": [ 4 ], "bSortable": false },
				{ "aTargets": [ 5 ], "bSortable": false },
				{ "aTargets": [ 6 ], "bSortable": false },
				{ "aTargets": [ 7 ], "bSortable": true },
				{ "aTargets": [ 8 ], "bSortable": true },
				{ "aTargets": [ 9 ], "bSortable": true }
			],
			'aaSorting' : [
				[ 7, 'desc' ]
			],
			'language' : {
				"loadingRecords": "Please wait - loading..."
			},
			responsive: true
		});

	}

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

	function isCronValid(freq) {
		var cronregex = new RegExp(/^(\*|(?:[0-9]|(?:[1-5][0-9]))|\*\/([0-9]|1[0-9]|2[0-9]|3[0-9]|4[0-9]|5[0-9])) (\*|[0-9]|1[0-9]|2[0-3]) (\*|[1-9]|(?:[12][0-9])|3[01]) (\*|[1-9]|1[012]) (\*|([0-6])|\*\/([0-6]))$/);
		return cronregex.test(freq);
	}

})( jQuery );
