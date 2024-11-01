<?php

/**
 * Class to call WooCommerce orders and set order statuses based on Shelfless fulfillment movements
 * 
 * @since      1.0.0
 * @package Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Orders
 * @subpackage Bring_3pl_Shelfless_Fulfillment_For_Woocommerce/includes
*/

class Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Order {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name   The name of this plugin.
	 * @param      string    $version       The version of this plugin.
	 */
	public function __construct( $plugin_name ) {

		$this->plugin_name = $plugin_name;

	}


	/**
	 * Function to list all orders
	 *
	 * @since    1.0.0
	 * @access   public
	 * @param    array		$args		Accepts an array of strings: by default is set to the keys of wc_get_order_statuses().
	 */
	public function get_orders( $args = array() ) {

		if ( ! is_array( $args ) ) {
			$args = array( $args );
		}

        if ( ! isset( $args['limit'] ) ) $args['limit'] = -1;

		$orders = array();

		// Get an instance of the WC_Order object
		$order_objs = wc_get_orders( $args );

		if ( ! count($order_objs) ) {
			return false;
		}
		else {
			// Iterating through each WC_Order objects
			foreach ( $order_objs as $order_key => $order ) {
				$orders[$order_key] = $order->get_data();
				// Explicitly calling woocommerce_order_number filter to add as order number field. Plugins respecting this filter to add order number sequence should work.
				$orders[$order_key]['order_number'] = $order->get_order_number();
			}
		}

		unset( $order_objs );
		
		return $orders;
	}

	/**
	 * function to display order by ID
	 *
	 * @since    1.0.0
	 * @access   public
	 * @param    int		$order_id
	 */
	public function get_order( $order_id ) { 

		if ( ! $order_id ) return false;
		
		// Get an instance of the WC_Order object
		$order_obj = wc_get_order( $order_id );

		if ( ! $order_obj ) return false;
		
		return $order_obj;
	}

	/**
	 * Format the order data before sending to MyBring
	 *
	 * @since		1.2.1
	 * @param 		object 		$order_obj			Order details in object type
	 * @return		array		$order_data			Array of order details in json format
	 */
	public function format_order( $order_obj ) {

		Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( __CLASS__ . '->' . __FUNCTION__ );
		Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( $order_obj );

		if ( ! $order_obj ) return false;

		// Shipping methods assigned
		$shipping_methods = $order_obj->get_shipping_methods();
		
		$shipping = array();
		$shipping_total_cost = 0;

		$shipping_carrier = get_option( $this->plugin_name . '_order_fallback_service_carrier' );
		$shipping_carrier = ( ! empty( $shipping_carrier ) ? $shipping_carrier : 'BPN' );

		$shipping_service_code = get_option( $this->plugin_name . '_order_fallback_service_code' );
		$shipping_service_code =  ( ! empty( $shipping_service_code ) ? $shipping_service_code : '1736' );

		// Flag for correctly tagging express/rush orders
		$is_express = false;

		// The OCS endpoint only accepts one service, so let's discard (for now) the rest if
		// we see more than one shipping method used. -> Harvey
		if ( $shipping_methods ) {

			// Because warehouse expects us to send one dimension package, we only need one shipping method, preferably the first one.
			// TODO: Orders could contain multiple packages, and thus multiple assigned shipping method per package. Warehouse
			// should support this in the future.
			foreach ( $shipping_methods as $id => $shipping_method ) {
				$shipping = $shipping_method->get_data();
				break;
			}

			if ( $shipping ) {

				$shipping_total_cost += (float) $shipping['total'];
				$ship_meta_data = $shipping['meta_data'];
				
				if ( preg_match( '/bring|flat_rate|free_shipping/', $shipping['method_id'] ) ) {
					$shipping_carrier = 'BPN';
				}
				else if ( preg_match( '/dhl/', $shipping['method_id'] ) ) {
					$shipping_carrier = 'DHL';
				}

				// Let's try mapping this out from our settings, if not then shipping could be provided by other plugin.
				if ( preg_match( '/flat_rate|free_shipping/', $shipping['method_id'] ) ) {
					$instance_id = $shipping['instance_id'];
					$code = get_option( $this->plugin_name . '_order_shipping_map_instance_' . $instance_id );

					$shipping['service'] = ( ! empty( $code  )? $code : $shipping_service_code  );

					// Bring: Shipping carrier will vary on the type of Bring service one uses. Codes 5100 and 5300 are cargo services.
					if ( in_array( $shipping['service'], array( '5100', '5300' ) ) ) {
						$shipping_carrier = 'BCN';
					}

					Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( array( 'order_id' => $shipping['order_id'], 'instance_id' => $instance_id, 'title' => $shipping['method_title'], 'code' => $code, 'service_code' => $shipping['service'], 'carrier' => $shipping_carrier ) );
				}
				
				// Let's try to see if we can get some data.
				// Below is for Media Strategi / Oktagon nShift plugin
				else if ( preg_match( '/msudco/', $shipping['method_id'] ) ) {
					$services = $this->process_msudco( $shipping );

					foreach ( $services as $service ) {
						$shipping_carrier = $shipping['carrier'] = $service['carrier'];
						$shipping['service'] = $service['service'];
						$shipping['pickup_point_id'] = $service['pickup_point_id'];
						// We are not supporting multiple services for now. Let Shelfless handle the packaging.
						break;
					}
				}
				
				else {

					foreach ( $ship_meta_data as $ship_meta ) {

						$ship_data = $ship_meta->get_data();
						if ( preg_match( '/bring_product/', $ship_data['key'] ) ) {
							$shipping['service'] = $ship_data['value'];
						}
						elseif ( preg_match( '/carrier_code/', $ship_data['key'] ) ) {
							$shipping_carrier = $shipping['carrier'] = $ship_data['value'];
						}
						elseif ( preg_match( '/pickup_point_id/', $ship_data['key'] ) ) {
							$shipping['pickup_point_id'] = $ship_data['value'];
						}
						elseif ( preg_match( '/shelfless_vas_codes/', $ship_data['key'] ) ) {
							$shipping['vas_codes'] = $ship_data['value'];
						}
						elseif ( preg_match( '/expected_delivery/', $ship_data['key'] ) ) {
							$shipping['expected_delivery_date'] = $ship_data['value'];
						}
						else {
							$shipping[$ship_data['key']] = $ship_data['value'];
						}

					}
					
				}

				$shipping_service_code = $chosen_service_code = ( ! empty( $shipping['service'] ) ? $shipping['service'] : $shipping_service_code );

				// Bring Pakke levert hjem - Samme dag
				if ( preg_match( '/^(5600)[~_]{1}(2012)$/m', $shipping_service_code, $codes ) ) {
					$shipping_service_code = $codes[1];
					$vas_code = array( $codes[2] );

					unset( $codes );
				}

				Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( $shipping );

			}
		}

		$order_data = array(
			'meta' => array(
				'version' => '1'
			),
		);

		$order = $order_obj->get_data();
		$order_number = $order_obj->get_order_number();
		$data = array(
			'order_id' => (string) $order_number,
		);
		
		// order dates
		$data['order_dates']['created_at'] = $order['date_created']->date('Y-m-d');
		$data['order_dates']['requested_delivery_at'] = ( ! empty( $shipping['expected_delivery_date'] ) ? date( 'Y-m-d', strtotime( $shipping['expected_delivery_date'] ) ) : $order['date_created']->date('Y-m-d') );

		$data['service']['code'] = $shipping_service_code;

		if ( array_key_exists( 'pickup_point_id', $shipping ) && ! empty( $shipping['pickup_point_id'] ) ) {
			$data['pickup_point'] = array( 'id' =>  $shipping['pickup_point_id'] );
		}

		// Let's consolidate default value-added services only for Bring Shelfless customers.
		if ( get_option( $this->plugin_name . '_dream_logistics_use_of_warehouse' ) !== '1' ) {

			$vas_codes = get_option( $this->plugin_name . '_order_value_added_services_codes' );

			if ( is_serialized( $vas_codes ) ) {
				$vas_codes = unserialize( $vas_codes );
			}
			else {
				if ( empty( $vas_codes ) )
					$vas_codes = array( '1091' );
				
				if ( ! is_array( $vas_codes) )
					$vas_codes = array( $vas_codes );
			}
			$codes = $vas_codes;

			if ( ! empty( $shipping['vas_codes']) ) {
				$codes = array_merge( $shipping['vas_codes'], $codes );
			}

			if ( is_array( $vas_codes) && ! empty( $vas_code ) ) {
				$codes = array_merge( $vas_code, $codes );
			}

			$allowed_services = shelfless_delivery_services();

			if ( ! empty( $chosen_service_code ) && array_key_exists( $chosen_service_code, $allowed_services ) ) {

				// Get the merchant-enabled addons and merge with $codes
				$merchant_codes = $allowed_services[$chosen_service_code]['addons']['merchant'];

				Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( $merchant_codes );

				if ( ! empty( $merchant_codes ) ) {
					$codes = array_merge( $merchant_codes, $codes );
				}

			}

			// $codes - array of value-added services codes
			$codes = array_unique( $codes, SORT_STRING );
			if ( $codes ) { $data['service']['addon_codes'] = $codes; }

			// Let's check if this order is express. Also, the haystack is an array because this might be added with non-standard codes, and not just Bring ones.
			if ( in_array( $shipping_service_code, array( '5600' ) ) && in_array( '2012', $codes ) ) { $is_express = true; }

		}

		// Dream Logistics: include Webshop ID to the payload if needed
		if ( get_option( $this->plugin_name . '_dream_logistics_use_of_warehouse' ) === '1' ) {
			$webshop_id = get_option( $this->plugin_name . '_dream_logistics_webshop_id', '0' );
			$data['source_system_identifier'] = $webshop_id;
		}

		/**
		 * delivery
		 * if the shipping information are not set, the billing information will be used
		 */
		$delivery_party_shipping = false;
		if ( ! empty( $order['shipping']['city'] ) ) {
			$delivery_party_shipping = true;
		}
		
		$data['delivery']['delivery_information'] 	= array();
		$data['delivery']['delivery_instructions']	= array();

		if ( $delivery_party_shipping ) {
			// shipping details are used here
			$data['delivery_party'] = array(
				'name'		=> $order['shipping']['first_name'] . ' ' . $order['shipping']['last_name'],
				'address'	=> array(
					'street'		=> $order['shipping']['address_1'] . ' ' . $order['shipping']['address_2'],
					'city'			=> $order['shipping']['city'],
					'zip_code'		=> $order['shipping']['postcode'],
					'country_code'	=> $order['shipping']['country']
				),
			);
		}
		else {
			// billing details are used here
			$data['delivery_party'] = array(
				'name'		=> $order['billing']['first_name'] . ' ' . $order['billing']['last_name'],
				'address'	=> array(
					'street'		=> $order['billing']['address_1'] . ' ' . $order['billing']['address_2'],
					'city'			=> $order['billing']['city'],
					'zip_code'		=> $order['billing']['postcode'],
					'country_code'	=> $order['billing']['country']
				),
			);
		}
		
		$wc_export = false;
		if ( get_option( $this->plugin_name . '_order_is_process_export' ) === '1' ) {
			$wc_country_obj = new WC_Countries();
			$wc_base_country = $wc_country_obj->get_base_country();
			if ($data['delivery_party']['address']['country_code'] !== $wc_base_country ) $wc_export = true;
		}
		
		// This is for INCOTERMS used in Customs soon
		$data['delivery']['terms_of_delivery'] = array();

		// contacts
		$contacts = array();
		if ( $delivery_party_shipping ) {
			// shipping details are used here
			$contacts[] = array(
				'name'				=> $order['shipping']['first_name'] .' '. $order['shipping']['last_name'],
				'phone_no'			=> ( ! empty( $order['shipping']['phone'] ) ? $order['shipping']['phone'] : $order['billing']['phone'] ),
				'cell_phone_no' 	=> ( ! empty( $order['shipping']['phone'] ) ? $order['shipping']['phone'] : $order['billing']['phone'] ),
				'email'				=> ( ! empty( $order['shipping']['email'] ) ? $order['shipping']['email'] : $order['billing']['email'] ),
			);
		} else {
			// billing details are used here
			$contacts[] = array(
				'name'				=> $order['billing']['first_name'] .' '. $order['billing']['last_name'],
				'phone_no'			=> $order['billing']['phone'],
				'cell_phone_no' 	=> $order['billing']['phone'],
				'email'				=> $order['billing']['email'],
			);
		}
		$data['delivery_party']['contacts'] = $contacts;

		// freight
		$data['freight_cost'] = array(
			'price'		=> $shipping_total_cost,
			'currency'	=> 	$order['currency']
		);

		// get product items by order
		$items = $order_obj->get_items();

		$line_items = array();

		$is_all_marked_fulfilled = true;
		$line_num = 1;

		if ( $items ) {

			foreach ( $items as $item ) {
				
				$item_data = $item->get_data();
				$sku = get_post_meta( $item_data['product_id'], '_sku', true );

				$type = WC_Product_Factory::get_product_type( $item_data['product_id'] );
				switch ( $type ) {
					case 'composite':
						$composite_children = get_post_meta( $item_data['product_id'], '_composite_data' );
						if ( $composite_children ) {
							foreach ( $composite_children as $child ) {
								if ( get_post_meta( $child['product_id'], '_' . $this->plugin_name . '_is_fulfill', true ) !== 'yes' ) {
									$is_all_marked_fulfilled = false;
									break;
								}
							}
						}
						break;
					case 'grouped':
						$grouped_product = wc_get_product( $item_data['product_id'] );
							$grouped_children = $grouped_product->get_children();
							if ( $grouped_products ) {
								foreach ( $grouped_products as $grouped_product_id )  {
									if ( get_post_meta( $grouped_product_id, '_' . $this->plugin_name . '_is_fulfill', true ) !== 'yes' ) {
										$is_fulfillable_all = false;
										break;
									}
								}
							}
						break;
					case 'simple':
						if ( get_post_meta( $item_data['product_id'], '_' . $this->plugin_name . '_is_fulfill', true ) !== 'yes' )
							$is_all_marked_fulfilled = false;
						break;
					case 'variable':
						if ( get_post_meta( $item_data['variation_id'], '_' . $this->plugin_name . '_is_fulfill', true ) !== 'yes' )
							$is_all_marked_fulfilled = false;
						break;
				}

				if ( ! $is_all_marked_fulfilled ) {

					$order_obj->add_order_note( esc_html__( 'Formatting order for Shelfless has failed.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ), 0 );
					$order_obj->add_order_note( esc_html__( 'One or more products are not fulfillable by Shelfless. It cannot ship this order.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ), 0 );
					return false;

				}
				
				if ( ! in_array( $type, array( 'composite', 'bundle' ) ) ) {

					$product_id = ( $type === 'simple' ?  $item_data['product_id'] : $item_data['variation_id'] );
					$sku = get_post_meta( $product_id, '_sku', true );

					// If this is to be exported outside of default country.
					if ( $wc_export ) {
						$customs_data = array(
							'hs_code'			=> get_post_meta( $product_id, '_' . $this->plugin_name . '_customs_hs_code', true ),
							'country_of_origin'	=> get_post_meta( $product_id, '_' . $this->plugin_name . '_customs_manufacture_country', true ),
						);
						$unit_price = get_post_meta( $product_id, '_' . $this->plugin_name . '_customs_cost_price', true );
					}

					// Structure the every product for the order and its order items
					$line_item = array(
						'article_id' 		=> array(
							'customer_item_no' 	=> $sku,
							'vendor_item_no'	=> (string) $product_id,
						),
						'quantity'			=> $item_data['quantity'],
						'article_line_id'	=> (string) $line_num,
						'name'				=> esc_html__( $item_data['name'], 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
						'unit_price'		=> array(
							'price'				=> (float) ( $wc_export ?  $unit_price : $item_data['subtotal'] / $item_data['quantity'] ),
							'currency'			=> $order['currency'],
						),
						'descriptions'		=> array(
							'sku_description'	=> $sku,
						),
					);

					if ( $wc_export ) {
						$line_item['customs'] = $customs_data;
					}
					
					$line_items[] = $line_item;
					$line_num++;

				}
				elseif ( $type == 'composite' ) {

					$composite_children = get_post_meta( $item_data['product_id'], '_composite_data' );

					if ( $composite_children ) {
						foreach ( $composite_children as $child ) {

							$product_id = $child['product_id'];
								$sku = get_post_meta( $product_id, '_sku', true );

								// If this is to be exported outside of default country.
								if ( $wc_export ) {
									$customs_data = array(
										'hs_code'			=> get_post_meta( $product_id, '_' . $this->plugin_name . '_customs_hs_code', true ),
										'country_of_origin'	=> get_post_meta( $product_id, '_' . $this->plugin_name . '_customs_manufacture_country', true ),
									);
									$unit_price = get_post_meta( $product_id, '_' . $this->plugin_name . '_customs_cost_price', true );
								}

								// Structure the every product for the order and its order items
								$line_item = array(
									'article_id' 		=> array(
										'customer_item_no' 	=> $sku,
										'vendor_item_no'	=> (string) $product_id,
									),
									'quantity'			=> $item_data['quantity'],
									'article_line_id'	=> (string) $key,
									'name'				=> esc_html__( $item_data['name'], 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
									'unit_price'		=> array(
										'price'				=> (float) ( $wc_export ?  $unit_price : $item_data['subtotal'] / $item_data['quantity'] ),
										'currency'			=> $order['currency'],
									),
									'descriptions'		=> array(
										'sku_description'	=> $sku,
									),
								);

								if ( $wc_export ) {
									$line_item['customs'] = $customs_data;
								}

								$line_items[] = $line_item;
								$line_num++;
						}
					}

				}
            }
		}

		$data['order_items'] 	= $line_items;
		$data['order_type_id']	= ( $is_express ? '225' : '' );

		// transport
		$data['transport_company']['party_id'] = $shipping_carrier;
		$order_data['order'] = $data;
		
		Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( json_encode( $order_data, JSON_PRETTY_PRINT ) );
		return json_encode( $order_data, JSON_PRETTY_PRINT );

	}

	private function process_msudco( $shipping ) {

		$is_dream = get_option( $this->plugin_name . '_dream_logistics_use_of_warehouse' );

		$carriers = array();

		$carriers_known = array(
			'bring'	=> array(
				'5800' => 'BPN',
				'0349' => 'BPN',
				'0330' => 'BPN',
				'3584' => 'BPN',
				'3570' => 'BPN',
				'5000' => 'BPN',
				'1736' => 'BPN',
				'1202' => 'BPN',
				'1002' => 'BPN',
				'4850' => 'BPN',
			),
		);

		$methods_known = array(
			'BPPP'			=> '5800',
			'BHDP'			=> '5600',
			'BPBP'			=> '5000',
			'BMNOPIPA'		=> '3584',
			'BMNOPIPAR'		=> '3570',
			'BPBDD'			=> '5000',
			'BPPD'			=> '1736',
			'BPSP'			=> '1202',
			'BPBE09'		=> '1002',
			'BPND'			=> '4850',
		);

		$carrier_data = get_post_meta( $shipping['order_id'], '_msudco_order_widget_carrier_id', true );
		$service_id_data = get_post_meta( $shipping['order_id'], '_msudco_order_widget_service_id', true );
		$pickup_point_id_data = get_post_meta( $shipping['order_id'], '_msudco_order_widget_agent', true );

		foreach( $service_id_data as $i => $s ) {
			if ( $methods_known[$s] && ! $is_dream )
				$carriers[$i]['service'] = $methods_known[$s];
			else
				$carriers[$i]['service'] = $s;
		}

		foreach( $carrier_data as $i => $c ) {
			if ( $carriers_known[$c] && ! $is_dream )
				$carriers[$i]['carrier'] = $carriers_known[$c][$carriers[$i]['service']];
			else
				$carriers[$i]['carrier'] = $c;
		}

		foreach( $pickup_point_id_data as $i => $p ) {
			$carriers[$i]['pickup_point_id'] = $p;
		}

		return $carriers;

	}

	/**
	 * Format the errors from API response for Order comment
	 *
	 * @since		1.2.1
	 * @param 		array|string    $errors			Errors from API response
	 * @return		string			$error_message	Return cleaned order comments
	 */
	public function format_error_order_comments( $errors ) {

		$error_message = '';

        if ( ! empty( $errors ) ) {
            if ( is_array ( $errors ) || is_object ( $errors ) ) {
            	$errors_html = '';
	            foreach ( $errors as $sku => $error ) {
	                $errors_html .= '<li>' . $sku;
					if ( ! empty( $error ) ) {
						$errors_html .= '<ul>';
						foreach ( $error as $key => $value ) {
							$errors_html .= '<li>' . $key . ' => ' . $value .  '</li>';
						}
						$errors_html .= '</ul>';
					}
					$errors_html .= '</li>';
	            }

	            $error_message = '<ul class="sbb-error-comments">' . $errors_html . '</ul>';
            } else {
            	$error_message = $errors;
            }
        }

		return $error_message;

	}

}
