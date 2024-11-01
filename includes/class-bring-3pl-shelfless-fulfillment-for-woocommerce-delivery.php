<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Functionalities of the core Bring Delivery shipping methods.
 *
 * @link       https://www.bring.no/
 * @since      1.0.0
 *
 * @package    Bring_3pl_Shelfless_Fulfillment_For_Woocommerce
 * @subpackage Bring_3pl_Shelfless_Fulfillment_For_Woocommerce/includes
 */

/**
 * Functionalities of the core Bring Delivery shipping methods.
 *
 * Defines the Bring Delivery shipping methods using Bring carrier.
 *
 * @package    Bring_3pl_Shelfless_Fulfillment_For_Woocommerce
 * @subpackage Bring_3pl_Shelfless_Fulfillment_For_Woocommerce/includes
 * @author     Tiqqe <bring_team@tiqqe.com>
 */
class Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Shelfless_Delivery extends WC_Shipping_Method {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	protected $plugin_name ='bring_3pl_shelfless_fulfillment_for_woocommerce';

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	protected $version = '1.3.0';

	protected $services;

	protected $from_country;

	protected $from_postalcode;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $instance_id = 0 ) {

		if ( $instance_id ) {
			parent::__construct( $instance_id );
		}
        
        $this->id           		= 'shelfless_delivery';
        $this->method_title 		= __( 'Shelfless Delivery', 'bring-3pl-shelfless-fulfillment-for-woocommerce' );
        $this->method_description	= __( 'Use Shelfless Delivery when using Bring as carrier to give the customer ability to choose pickup point from dropdown and only show available delivery options based on postal code at checkout.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' );
		$this->supports     		= array(
			'shipping-zones',
			'settings',
			'instance-settings',
		);
		$this->title        		= __( 'Shelfless Delivery', 'bring-3pl-shelfless-fulfillment-for-woocommerce' );

		$this->init_form_fields();
        $this->init_settings();


		$this->enabled = true;
		if ( isset( $this->settings['enabled'] ) ) {
			$this->enabled = $this->settings['enabled'];
		}

		// Removing this for now and are not needed.
		// $this->availability = $this->settings['availability'];
		// $this->countries    = $this->settings['countries'];
		// $this->fee          = $this->settings['handling_fee'];

        add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );

		$this->init();

	}

	public function init() {

		$country_obj 			= new WC_Countries();
		$this->services 		= $this->get_services();
		$this->from_country 	= $country_obj->get_base_country();
		$this->from_postalcode 	= $country_obj->get_base_postcode();

	}
	
	/**
	 * Get and return all allowed and supported Shelfless Delivery services. 
	 *  @since    1.2.6
	 * 
	 * @return 		array 		$services 		Shelfless delivery services
	 */
	public function get_services() { 

		$services = shelfless_delivery_services();

		return $services;

	}
	
	/**
	 * Calculate shipping rates
	 *  @since    1.2.6
	 * 
	 * @param 		array 		$package		Cart information
	 * 
	 */
	public function calculate_shipping( $package = array() ) {

		$max_items = get_option( $this->plugin_name . '_sd_use_default_dimensions_maximum_items_in_cart' );

		if ( ! empty( $max_items ) && is_numeric( $max_items ) && WC()->cart && WC()->cart->get_cart_contents_count() > $max_items ) {
			wc_add_notice( sprintf( esc_html( 'Shelfless Delivery could not be used in this order. Order quantity exceeds %s, the maximum allowed.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ), 'notice' ), $max_items );
			return;
		}
		
		$this->get_rates( $package );

	}

	/**
	 * Parse package data for the products
	 *  @since    1.2.6
	 * 
	 * @param 		array 		$package		Cart information
	 * @return 		array 		$products		Products information
	 * 
	 */
	private function parse_package( $package = array() ) {

		$products = array(
			'destination'	=> $package['destination'],
			'items'			=> '• ',
			'package_dims'	=> array(
				'length'	=> 0,
				'width'		=> 0,
				'height'	=> 0,
			)
		);

		$gross_weight	= 0;
		$boxes			= array();

		if ( ! $package )
			return false;
		
		$length = $width = $height = $weight = 0;

		// Get the default dimension settings.
		$use_default = true; // Need to make this always true.
		
		if ( $use_default ) {
			$length	= ! empty( get_option( $this->plugin_name . '_sd_use_default_dimensions_length', false ) ) ? get_option( $this->plugin_name . '_sd_use_default_dimensions_length', false ) : 1 ;
			$width	= ! empty( get_option( $this->plugin_name . '_sd_use_default_dimensions_width', false ) ) ? get_option( $this->plugin_name . '_sd_use_default_dimensions_width', false ) : 1;
			$height	= ! empty( get_option( $this->plugin_name . '_sd_use_default_dimensions_height', false ) ) ? get_option( $this->plugin_name . '_sd_use_default_dimensions_height', false ) : 1;
			$weight	= ! empty( get_option( $this->plugin_name . '_sd_use_default_dimensions_weight', false ) ) ? get_option( $this->plugin_name . '_sd_use_default_dimensions_weight', false ) : 1;
		}
		
		foreach ( $package['contents'] as $product ) {

			$product_obj	= $product['variation_id'] ? wc_get_product( $product['variation_id'] ) : wc_get_product( $product['product_id'] );
			$product_data	= $product_obj->get_data();

			// Let's create an array of item boxes before we pack. Essentially, we are making a list of items to be packed.
			$i = $product['quantity'];

			while ( $i ) {
				
				$boxes[] = array(
					'length'	=> ! empty( $product_data['length'] ) ? $product_data['length'] : $length,
					'width'		=> ! empty( $product_data['width'] ) ? $product_data['width'] : $width,
					'height'	=> ! empty( $product_data['height'] ) ? $product_data['height'] : $height,
				);
				$i--;

			}

			$qty 	= isset( $products['products'][$product_data['id']]['quantity'] ) ? (int) $products['products'][$product_data['id']]['quantity'] : 0;
			$weight	= $product_data['weight'] > 0 ? $product_data['weight'] : $weight;
			
			$products['products'][$product_data['id']]['quantity']  = $qty + (int) $product['quantity'];
			$gross_weight = $gross_weight + ( (float) $weight * (int) $product['quantity'] );
			$products['products'][$product_data['id']]['is_dims'] 	= (bool) $product_data['length'] && (bool) $product_data['width'] && (bool) $product_data['height'] && (bool) $product_data['weight'];
			$products['items'] .= $product_data['name'] . ' x ' . $product['quantity'] . ' • ';

		}

		if ( ! $boxes ) { return false; }

		$package = new Cloudstek\PhpLaff\Shelfless\Packer();
		$package->pack( $boxes );
		$container = $package->get_container_dimensions();

		if ( ! $container ) { return false; }

		$products['package_dims'] = $container;
		$products['package_dims']['grossWeight'] = $gross_weight;

		// If we see any sides as 0, or a weight of 0, let's return false.
		// This will also supress fetching rates without the required dimensions.
		if ( in_array( 0, $products['package_dims'] ) ) { return false; }

		return $products;

	}

	/**
	 * Get and return shipping rates for the products
	 *  @since    1.2.6
	 * 
	 * @param 		array 		$package		Cart information
	 * 
	 */
	private function get_rates( $package = array() ) {

		$package_items = $this->parse_package( $package );

		if ( ! $package || ! $package_items) { return false; }
		
		$max_weight = get_option( $this->plugin_name . '_sd_use_default_dimensions_maximum_weight', 500 );
		if ( ! empty( $max_weight ) && is_numeric( $max_weight ) && $package_items['package_dims']['grossWeight'] > $max_weight ) {
			return false;		
		}
		
		$is_locale = preg_match( "/^([a-z]{2,3})(_*)([A-Z]*)$/", get_locale(), $locale );

		if ( $is_locale ) {
			if ( in_array( $locale[1], array( 'en', 'no', 'se', 'sv', 'de', 'da', 'fi', 'nl', 'pl' ) ) )
				$language = $locale[1];
			else
				$language = 'en';
		}
		else
			$language = 'en';

		// If hour is more than 1pm, let's make sure the ship date is next day.
		// If day is weekend, let's make sure the ship date is next Monday.
		// If day is Friday and hour is more than 2pm, let's make sure the ship date is next Monday.
		$ship_date = getdate( $this->get_optimal_ship_date() );

		$bring_products	= $bring_addons = array();

		$bring_shipping_cust_num  = get_option( $this->plugin_name . '_alternate_mybring_customer_id', false );
		$bring_shipping_cust_num =  ! empty( $bring_shipping_cust_num ) ?  $bring_shipping_cust_num : get_option( $this->plugin_name . '_mybring_customer_id', false );

		$services = $this->services;
		$is_express = false;

		$sorting_areas = array();

		foreach ($services as $code => $bring_method ) {

			$bring_addon = false;

			// Special arrangement for Bring's Pakke levert hjem - samme dag
			if ( '5600_2012' === $code ) {

				$sorting_areas = is_array( $bring_method['sorting_areas'] ) ? $bring_method['sorting_areas'] : array( $bring_method['sorting_areas'] );
				$is_express 	= true;
				$bring_product	= array( 'id' => 5600 );
				$bring_addon	= array( 'id' => '2012', 'leadTimeFromCustomerInMinutes' => 120, 'sortingAreas' => $sorting_areas );
			}
			else {
				$bring_product = array( 'id' => $code );
			}

			if ( $bring_shipping_cust_num ) {
				$bring_product['customerNumber'] = (string) $bring_shipping_cust_num;
			}

			$bring_products[] = $bring_product;

			if ( ! empty( $bring_addon ) ) {
				$bring_addons[] = $bring_addon;
			}
		}

		if ( ! $bring_products ) { return false; }

		if ( empty( $package_items['destination']['postcode'] ) || empty( $package_items['destination']['country'] ) ) { return false; }

		$consignment = array(
			'products'			=> $bring_products,
			'fromCountryCode'	=> $this->from_country,
			'fromPostalCode'	=> $this->from_postalcode,
			'toCountryCode'		=> $package_items['destination']['country'],
			'toPostalCode'		=> $package_items['destination']['postcode'],
			'addressLine'		=> $package_items['destination']['address'],
			'shippingDate'		=> array(
				'year'			=> $ship_date['year'],
				'month'			=> $ship_date['mon'],
				'day'			=> $ship_date['mday'],
				'hour'			=> $ship_date['hours'],
				'minute'		=> $ship_date['minutes']
			),
			'packages'			=> array( $package_items['package_dims'] )
		);

		if ( ! empty( $bring_addons ) ) {
			$consignment['additionalServices'] = $bring_addons;
		}

		$payload = array(
			'language'				=> $language,
			'withPrice'				=> true,
			'withExpectedDelivery'	=> true,
			'withEstimatedDelivery'	=> true,
			'withGuiInformation'	=> true,
			'edi'					=> true,
			'withEnvironmentalData'	=> false,
			'trace'					=> true,
			'consignments'			=> array( $consignment ),
		);

		$shipping = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Http;

		$get_services = $shipping->get_bring_rates( $payload );


		if ( $get_services ) {

			foreach ( $get_services->consignments as $consignment ) {

				foreach ( $consignment as $field => $rated_methods ) {

					// New Bring API responses will break the system because the consignments have one more field detected. We need to make sure that
					// the field we are looking at is the one that contains the shipping services.
					if ( $field !== 'products' ) continue;
					
					foreach ( $rated_methods as $method ) {

						if ( isset( $method->errors ) ) continue;
						
						// There are times that mailbox parcels will not produce cost when there are no weights sent to payload.
						// If this is the case, remove them only if there are no price override set on settings.
						if ( ! isset( $method->price->listPrice->priceWithAdditionalServices->amountWithVAT ) && ! isset( $services[$method->id]['is_price'] ) ) continue;

						// Set label if set from settings. Set cost if set from settings, else, 0.
						$label	= ! empty( $services[$method->id] ) && true == $services[$method->id]['is_title'] ? $services[$method->id]['title'] : $method->guiInformation->shortName;
						$cost	= ! empty( $services[$method->id] ) && true == $services[$method->id]['is_price'] ? $services[$method->id]['price'] : 0.00;

						if ( isset( $services[$method->id] ) && true == $services[$method->id]['is_free_shipping']
							&& $package['contents_cost'] >= $services[$method->id]['free_shipping_threshold'] ) {
								$cost = 0.00;
						}
						
						if ( array_key_exists( $method->id, $services ) ) {

							$rate = array(
								'id'					=> 'shelfless_delivery_' . $method->id,
								'label'					=> $label,
								'cost'					=> $cost,
								'meta_data'				=> array(
									'items'				=> $package_items['items'],
									'carrier_name'		=> 'bring',
									'carrier_code'		=> $services[$method->id]['carrier_code'],
									'service_name'		=> $method->guiInformation->shortName,
									'bring_product'		=> $method->id,
									'expected_delivery'	=> ! empty( $method->expectedDelivery->formattedExpectedDeliveryDate ) ? $method->expectedDelivery->formattedExpectedDeliveryDate : '',
								)
							);

							$this->add_rate( $rate );
						}

						// Special arrangement for Bring's Pakke levert hjem - samme dag
						if ( '5600' == $method->id && $is_express ) {
							// Set label if set from settings. Set cost if set from settings, else, 0.
							$label	= ! empty( $services['5600_2012'] ) && true == $services['5600_2012']['is_title'] ? $services['5600_2012']['title'] : $method->guiInformation->shortName . ' - samme dag';
							$cost	= ! empty( $services['5600_2012'] ) && true == $services['5600_2012']['is_price'] ? $services['5600_2012']['price'] : 0.00;

							if ( isset( $services['5600_2012'] ) && true == $services['5600_2012']['is_free_shipping']
							&& $package['contents_cost'] >= $services['5600_2012']['free_shipping_threshold'] ) {
								$cost = 0.00;
							}

							if ( ! empty( $method->expectedDelivery->sameDayDelivery->routeInformation->routeId ) && ! empty( $sorting_areas ) ) {
								if ( in_array( $method->expectedDelivery->sameDayDelivery->routeInformation->routeId, $sorting_areas ) ) {

									$rate = array(
										'id'					=> 'shelfless_delivery_' . '5600_2012',
										'label'					=> $label,
										'cost'					=> $cost,
										'meta_data'				=> array(
											'items'				=> $package_items['items'],
											'carrier_name'		=> 'bring',
											'carrier_code'		=> $services['5600_2012']['carrier_code'],
											'service_name'		=> $label,
											'bring_product'		=> '5600_2012',
											'expected_delivery'	=> ! empty( $method->expectedDelivery->sameDayDelivery->formattedExpectedDeliveryDate ) ? $method->expectedDelivery->sameDayDelivery->formattedExpectedDeliveryDate : '',
										)
									);
			
									$this->add_rate( $rate );

								}
							}
							
						}

					}

				}

			}

		}

	}

	/**
	 * Get and return optimal ship date for the shipping delivery
	 *  @since    1.2.6
	 * 
	 * @return date $ship_date
	 */
	public function get_optimal_ship_date() {

		$tz 		= wp_timezone();
		$local_date	= new DateTime(null, $tz);
		$this_date	= getdate( $local_date->getTimestamp() + $local_date->getOffset() );
		$ship_date	= mktime( 13 , 0, 0, $this_date['mon'], $this_date['mday'], $this_date['year'] );

		if ( $this_date['hours'] > 13 && $this_date['wday'] == 5 ) {
			$ship_date += (3 * 86400);
		}
		elseif ( $this_date['wday'] == 6 ) {
			$ship_date += (2 * 86400);
		}
		elseif ( $this_date['wday'] == 0 ) {
			$ship_date += 86400;
		}
		elseif ( $this_date['hours'] > 13 && in_array( $this_date['wday'], array( 1, 2, 3, 4 ) ) ) {
			$ship_date += 86400;
		}

		return $ship_date;

	}

	/**
	 * Build and display pickup points
	 *  @since    1.2.6
	 * 
	 * @param 		object 		$method			Pickup point method ID
	 * @param 		string 		$index			Pickup point selected method ID
	 */
	public function build_pickup_points( $method, $index ) {

		if ( ! is_checkout() && ! is_cart() ) return;

		if ( $method->get_id() !== 'shelfless_delivery_5800' ) return;
		
		$selected_method_id = WC()->session->chosen_shipping_methods[$index];

		// Let's get the current package. If none is seen, let's recalculate shipping to produce packages.
		$packages = WC()->shipping()->get_packages();
		$destination = array();

		if ( empty( $packages ) ) {
			WC()->cart->calculate_shipping();
			WC()->cart->calculate_totals();
			$packages = WC()->shipping()->get_packages();
		}

		// Cart might be composed of many packages. We are interested in the destination.
		foreach ( $packages as $package ) {
			if ( isset( $package['destination'] ) ) {
				$destination = $package['destination'];
				break;
			}
		}

		if ( $method->get_id() === $selected_method_id ) {

			$pickup_points = WC()->session->get('shelfless-delivery-bring-pickup-points-' . $destination['postcode'] );
			
			if ( empty( $pickup_points ) ) {

				$pickup_points = $this->get_pickup_points( $destination, false );
				if ( empty( $pickup_points ) ) return false;
				WC()->session->set('shelfless-delivery-bring-pickup-points-' . $destination['postcode'], $pickup_points );

			}

			// Let's check if there are previously identified Bring pickup point in the session. If none, let's dig deeper.
			$selected_pickup_point_id = WC()->session->get('shelfless-delivery-bring-pickup-point-id');
			$selected_pickup_point_id = ! empty( $selected_pickup_point_id ) ? $selected_pickup_point_id : WC()->checkout->get_value( 'shelfless-delivery-bring-pickup-point-id' );

			// If none still, let's assign the first default Bring pickup point in the said postal code.
			if ( empty( $selected_pickup_point_id ) || ! array_key_exists( $selected_pickup_point_id, $pickup_points ) ) {
				$default_pickup_points = $this->get_pickup_points( $destination, true );
				if ( ! empty( $default_pickup_points ) ) {
					foreach ( $default_pickup_points as $id => $default_pickup_point ) {
						$selected_pickup_point_id = $id;
						WC()->session->set( 'shelfless-delivery-bring-pickup-point-id', sanitize_key( $selected_pickup_point_id ) );
						WC()->session->set( 'shelfless-delivery-pickup-location', sanitize_text_field( $default_pickup_point ) );
						break;
					}
				}
			}

			shelfless_delivery_pickup_points( $pickup_points, $selected_pickup_point_id );

		}

	}

	/**
	 * Get and return pickup points
	 *  @since    1.2.6
	 * 
	 * @param 		array 		$destination		Country
	 * @param 		boolean 	$default			Default URI for pickup points
	 * @return 		date 		$ship_date
	 */
	public function get_pickup_points( $destination, $default = false ) {

		$pickup_points = array();

		$shipping = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Http;

		$bring_pickup_points = $shipping->get_bring_pickup_points( $destination, $default );

		if ( empty( $bring_pickup_points->pickupPoint ) )
			return false;
		
		$pickup_points = array();
		
		foreach( $bring_pickup_points->pickupPoint as $bring_pickup_point ) {
			$pickup_points[$bring_pickup_point->id] = $bring_pickup_point->name . ' (' . $bring_pickup_point->address . ')';
		}

		return $pickup_points;

	}

	/**
	 * Build and display addons implementation for services template data structure
	 *  @since    1.2.6
	 */
	public function build_addons() { 

		if ( ! is_checkout() && ! is_cart() ) return;

		// For now, let's get the first index of the chosen_shipping_method considering that
		// we are only allowed to send one package dimension to the warehouse.
		// TODO: addons should be based on the chosen method per package (if in case the order contains more than one).
		$selected_method_id = WC()->session->chosen_shipping_methods[0];

		if ( ! $selected_method_id ) return;

		$services 	= $this->services;
		$addons		= shelfless_delivery_services_addons();
		$matched 	= preg_match( "/(shelfless_delivery)\_{1}([0-9]+)/", $selected_method_id, $matches );

		if ( ! $matched ) return;

		$selected_addons 	= WC()->session->get('shelfless-delivery-bring-addons-' . $selected_method_id );
		$available_addons 	= array();

		if ( $matched && isset( $services[$matches[2]] ) ) {

			$enabled_addons = array();

			if ( ! empty( $services[$matches[2]]['addons']['customer'] ) && is_array( $services[$matches[2]]['addons']['customer'] ) ) {
				$enabled_addons = $services[$matches[2]]['addons']['customer'];
			}

			foreach ( $enabled_addons as $addon ) {
				$available_addons[$addon] = $addons[$addon];
			}

		}

		if ( $available_addons ) {
			shelfless_delivery_addons( $available_addons );
		}

	}

	/**
	 * Save selected pickup point
	 *  @since    1.2.6
	 */
	public function save_selected_pickup_point() {

		header( 'Content-Type: application/json' );

		if ( ! wp_verify_nonce( $_REQUEST['shelfless_delivery'], 'nonce_' . $this->plugin_name . 'shelfless_delivery' ) 
		|| ( wp_verify_nonce( $_REQUEST['shelfless_delivery'], 'nonce_' . $this->plugin_name . 'shelfless_delivery' )
		&& $_REQUEST['action'] !== 'pickup_point_selection' ) ) {
			wp_die( json_encode( array( 'error' => true ) ) );
		}

		$post = wc_clean( $_POST );

		if ( ! empty( $post['pickup_point_id'] ) ) {
			WC()->session->set( 'shelfless-delivery-bring-pickup-point-id', sanitize_key( $post['pickup_point_id'] ) );
			WC()->session->set( 'shelfless-delivery-pickup-location', validate_bring_shelfless_text_field( sanitize_text_field( $post['pickup_point_location'] ) ) );
			wp_die( json_encode( array( 'error' => false ) ) );
		}

	}

	/**
	 * Update shipment meta data
	 *  @since    1.2.6
	 * 
	 * @param 		object 		$item 	Function/method
	 * 
	 */
	public function update_shipment_meta_data( &$item, $package_key, $package, $order ) {

		$post = wc_clean( $_POST );

		if ( $post ) {

			foreach( $post['shipping_method'] as $method ) {

				$matched = preg_match( "/^(shelfless_delivery)\_{1}([0-9]{4,6}[_~]{0,1}[0-9]{0,4})$/m", $method, $matches );

				if ( $matched && ! empty( $matches[2] ) ) {

					$item->update_meta_data('bring_product', validate_bring_shelfless_text_field( sanitize_text_field( $matches[2] ) ) );

					// Determining if delivery is Shelfless Delivery pickup, then let's save the pickup-point data.
					if ( '5800' == $matches[2] && isset( $post['shelfless-delivery-bring-pickup-point-id'] )) {
						$item->update_meta_data('pickup_point_id', validate_bring_shelfless_text_field( sanitize_text_field( $post['shelfless-delivery-bring-pickup-point-id'] ) ) );
						$item->update_meta_data('pickup_point_location', validate_bring_shelfless_text_field( sanitize_text_field( $post['shelfless-delivery-pickup-location'] ) ) );
					}

					if ( ! empty( $post['shelfless-delivery-addon'] ) ) {

						$vas_codes = array();

						foreach ( $post['shelfless-delivery-addon'] as $addon_code => $addon) {
							if ( '1' == $addon ) { $vas_codes[] = validate_bring_shelfless_text_field( sanitize_text_field( $addon_code ) ); }
						}

						if ( ! empty ( $vas_codes ) ) {
							$item->update_meta_data('shelfless_vas_codes', $vas_codes );
						}

					}

				}

			}

		}

	}

}
