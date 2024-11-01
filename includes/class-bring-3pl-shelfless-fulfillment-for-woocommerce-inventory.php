<?php
/**
 * Class to create separate product inventory settings out from WooCommerce
 * 
 * @since      1.0.0
 * @package Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Inventory
 * @subpackage Bring_3pl_Shelfless_Fulfillment_For_Woocommerce/includes
*/

class Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Inventory {


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
	 * @param      string    $plugin_name       The name of this plugin.
	 */
	public function __construct( $plugin_name ) {

		$this->plugin_name = $plugin_name;

	}

	/**
	 * Function to retrieve all products
	 *
	 * @since    1.0.0
	 * @access   public
	 * @param    array                $args            Accepts a string or array of strings.
	 */
	public function get_products( $args = array() ) {

		if ( ! is_array( $args ) ) {
			$args = array( $args );
		}

		// Fetching products of all visibility status
		if ( empty( $args ) ) { 
			$args = array( 'limit' => -1 );
		}
		
		$products = array();

		// Get an instance of the WC_Product object
		$obj_products = wc_get_products( $args );

		if ( ! count( $obj_products ) ) return false;

		// Iterating through each WC_Product objects
		foreach ($obj_products as $product_key => $product ) {
			$products[$product_key] = $product->get_data();

			// Inject the product type
			$products[$product_key]['product_type'] = $product->get_type();

		}

		return $products;
	}

	/**
	 * Function to retrieve product by ID
	 *
	 * @since    1.0.0
	 * @access   public
	 * @param    int		$product_id
	 */
	public function get_product_by_id( $product_id ) { 

		// Get an instance of the WC_Product object
		$obj_product = wc_get_product( $product_id );
		
		if ( ! $obj_product ) return false;

		// Product object
		$product = $obj_product->get_data();

		// Inject the product type
		$product['product_type'] = $obj_product->get_type();
		// Inject the raw data
		$product['product_obj'] = $obj_product;
		// Inject the product data itself
		$product['product_data'] = $product;
		
		return $product;
	}

	/**
	 * Function to set is_fulfill custom field.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @param    array		$products
	 * @param    string		$is_on_product_level
	 */
	public function set_product_is_fulfill( $products, $is_on_product_level=false ) {

		if ( ! is_array( $products ) ) $products = array( $products );

		$is_no_error = true;
		
		if ( is_array( $products ) ) {
			foreach ( $products as $product ) { 
				$item_product_id = sanitize_text_field( $product['product_id'] );
				$item_is_fulfill = sanitize_text_field( $product['is_fulfill'] );

				$product['product_id'] = validate_bring_shelfless_text_field( $item_product_id );
				$product['is_fulfill'] = validate_bring_shelfless_text_field( $item_is_fulfill );

				$this_product = $this->get_product_by_id( $product['product_id'] );
				
				if ( ! $this_product ) {
					$is_no_error = false;
					continue;
				}
				
				if ( $product['is_fulfill'] === '1' ) {
					update_post_meta( $this_product['id'], '_' . $this->plugin_name . '_is_fulfill', 'yes' );
				}
				else {
					update_post_meta( $this_product['id'], '_' . $this->plugin_name . '_is_fulfill', 'no' );
				}

				$type = $this->get_product_type( $this_product['id'] );

				switch ( $type ) { 

					case 'composite' : 
						
						$composite_product = wc_get_product( $this_product['id'] );
						$components = $composite_product->get_composite_data();
						
						if ( $components ) {
							foreach ( $components as $key => $component ) {
								if ( ! empty( $component['assigned_ids'] ) ) {
									foreach( $component['assigned_ids'] as $k => $component_id ) {
										$this->product_type_is_fulfill($component_id, $product['is_fulfill']);
									}
								}
								
							}
						}

						break;

					case 'grouped' :
						
						$grouped_product = wc_get_product( $this_product['id'] );
						$grouped_children = $grouped_product->get_children();

						if ( $grouped_children ) {
							foreach ( $grouped_children as $grouped_product_id ) { 
								$this->product_type_is_fulfill($grouped_product_id, $product['is_fulfill']);
							}
						}

						break;

					case 'variable' : 

						$variable_product = wc_get_product( $this_product['id'] );
						$variable_children = $variable_product->get_children();

						if ( $variable_children ) {
							foreach ( $variable_children as $child_product_id ) { 
								$this->product_type_is_fulfill($child_product_id, $product['is_fulfill']);
							}
						}

						break;

					default : 
						break;

				}

			}
		}

		return $is_no_error;

	}

	/**
	 * Function to set is_article_sync custom field.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @param    array		$products
	 * @param    string		$is_on_product_level
	 */
	public function set_product_is_article_sync( $products, $is_on_product_level=false ) {

		if ( ! is_array( $products ) ) $products = array( $products );

		$is_no_error = true;
		
		if ( is_array( $products ) ) {
			foreach ( $products as $prod ) { 
				$item_product_id = sanitize_text_field( $prod['product_id'] );
				$item_is_sync = sanitize_text_field( $prod['is_sync'] );

				$product['product_id'] = validate_bring_shelfless_text_field( $item_product_id );
				$product['is_sync'] = validate_bring_shelfless_text_field( $item_is_sync );

				$this_product = $this->get_product_by_id( $product['product_id'] );
				
				if ( ! $this_product ) {
					$is_no_error = false;
					continue;
				}
				
				if ( $product['is_sync'] == '1' ) { 
					update_post_meta( $this_product['id'], '_' . $this->plugin_name . '_is_article_sync', 'yes' );
				}
				else {
					update_post_meta( $this_product['id'], '_' . $this->plugin_name . '_is_article_sync', 'no' );
				}

				$type = $this->get_product_type( $this_product['id'] );
				switch ( $type ) { 

					case 'composite' : 
						$composite_product = wc_get_product( $this_product['id'] );
						$components = $composite_product->get_composite_data();
						if ( $components ) {
							foreach ( $components as $key => $component ) {
								if ( ! empty( $component['assigned_ids'] ) ) {
									foreach( $component['assigned_ids'] as $k => $component_id ) {
										$this->product_type_is_article_sync($component_id, $product['is_sync']);
									}
								}
								
							}
						}

						break;

					case 'grouped' :
						$grouped_product = wc_get_product( $this_product['id'] );
						$grouped_children = $grouped_product->get_children();
						if ( $grouped_children ) {
							foreach ( $grouped_children as $grouped_product_id ) { 
								$this->product_type_is_article_sync($grouped_product_id, $product['is_sync']);
							}
						}

						break;

					case 'variable' : 
						$variable_product = wc_get_product( $this_product['id'] );
						$variable_children = $variable_product->get_children();
						if ( $variable_children ) {
							foreach ( $variable_children as $child_product_id ) { 
								$this->product_type_is_article_sync($child_product_id, $product['is_sync']);
							}
						}

						break;

					default : 
						break;

				}
			}
		}

		return $is_no_error;

	}

	/**
	 * Method to get product by SKU.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @param    string		$sku
	 */
	public function get_product_id_by_sku( $sku ) { 

		$product_id = wc_get_product_id_by_sku( $sku );

		return $product_id;
	}

	/**
	 * Method to get product type by ID.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @param    int		$product_id
	 */
	public function get_product_type( $product_id ) {
		
		$type = WC_Product_Factory::get_product_type( $product_id );

		return $type;

	}

	/**
	 * Method to update post meta is_fulfill by ID.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @param    int		$id
	 * @param    int		$is_fulfill
	 */
	private function product_type_is_fulfill($id, $is_fulfill) { 

		if ( empty($id) ) return false;

		if ( $is_fulfill === '1' ) {
			update_post_meta( $id, '_' . $this->plugin_name . '_is_fulfill', 'yes' );
		}
		else {
			update_post_meta( $id, '_' . $this->plugin_name . '_is_fulfill', 'no' );
		}

	}

	/**
	 * Method to update post meta is_article_sync by ID.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @param    int		$id
	 * @param    int		$is_sync
	 */
	private function product_type_is_article_sync($id, $is_sync) { 

		if ( empty($id) ) return false;

		if ( $is_sync == '1' ) {
			update_post_meta( $id, '_' . $this->plugin_name . '_is_article_sync', 'yes' );
		}
		else {
			update_post_meta( $id, '_' . $this->plugin_name . '_is_article_sync', 'no' );
		}

	}

}
