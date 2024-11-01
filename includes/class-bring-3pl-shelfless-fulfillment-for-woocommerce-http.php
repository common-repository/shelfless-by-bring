<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.bring.no/
 * @since      1.0.0
 *
 * @package    Bring_3pl_Shelfless_Fulfillment_For_Woocommerce
 * @subpackage Bring_3pl_Shelfless_Fulfillment_For_Woocommerce/includes
 */

/**
 * HTTP calls to Bring 3PL Shelfless API.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Bring_3pl_Shelfless_Fulfillment_For_Woocommerce
 * @subpackage Bring_3pl_Shelfless_Fulfillment_For_Woocommerce/admin
 * @author     Tiqqe <bring_team@tiqqe.com>
 */
class Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Http {

    /**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	protected $version;

	/**
	 * Service Endpoint.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $auth_service_uri    Authentication endpoint URI.
	 */
	protected $service_uri;

	/**
	 * Authentication Path.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $auth_service_path    Authentication endpoint path.
	 */
	protected $auth_service_path;

    /**
	 * Order Path.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $order_service_path    Order endpoint path.
	 */
	protected $order_service_path;

	/**
	 * Article Path.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $article_service_path    Article endpoint path.
	 */
	protected $article_service_path;

	/**
	 * Environment.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $environment    Environment to use: sandbox vs production.
	 */
	protected $environment;

	/**
	 * API Version.
	 * 
	 * API versioning is not used at the moment but this could be included either in
	 * the final URI or in the payload.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $api_version    API version to use when connecting to endpoints. Not used now.
	 */
	protected $api_version;

	protected $api_key;

	protected $api_key_secret;

	protected $bring_shipping_uri;

	protected $bring_pickup_point_uri;

    /**
	 * Define the HTTP functionality of the plugin.
	 *
	 * As much as possible, we will use WP's HTTP API as our base instead of building
     * our own cURL wrappers or using WooCommerce's HTTPS libs which is also limited
     * in functionality. 
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = get_bring_shelfless_plugin_name();

		if ( defined( 'BRING_3PL_SHELFLESS_FULFILLMENT_FOR_WOOCOMMERCE_VERSION' ) ) {
			$this->version = BRING_3PL_SHELFLESS_FULFILLMENT_FOR_WOOCOMMERCE_VERSION;
		} else {
			$this->version = '1.3.0';
		}

		$this->environment = get_option( $this->plugin_name . '_shelfless_api_mode' );
		$this->api_version = get_option( $this->plugin_name . '_shelfless_api_version' );
		$this->api_key = get_option( $this->plugin_name . '_shelfless_api_key' );
		$this->api_key_secret = get_option( $this->plugin_name . '_shelfless_api_secret_key' );

		switch( $this->environment ) {
			case 'live':
				$this->service_uri = sanitize_url( 'https://apigateway.shelfless.bring.com' );
				break;
			case 'sandbox':
				$this->service_uri = sanitize_url( 'https://apigateway.shelfless.qa.bring.com' );
				break;
			case 'development':
			default:
				if ( defined( 'SHELFLESS_DEV' ) ) {
					$this->service_uri = sanitize_url( SHELFLESS_DEV );
				}
				else {
					$this->service_uri = sanitize_url( 'https://apigateway.shelfless.qa.bring.com' );
					Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( 'Using ' . $this->service_uri . ' on DEVELOPMENT MODE.');
				}
		}

		$this->auth_service_path = '/auth/token';
		$this->order_service_path = '/os/order';
		$this->article_service_path = '/article'; 
		$this->stock_adjustment_path = '/stock-adjustments';
		$this->article_master_data_path = '/master-data/article';

		// Endpoints for the Bring Shipping Guide API and Pickup Point API
		$this->bring_shipping_uri = sanitize_url( 'https://api-new.bring.com/shippingguide/v2/products' );
		$this->bring_pickup_point_uri = sanitize_url( 'https://api-new.bring.com/pickuppoint/api/pickuppoint' );

    }

	/**
	 * Services API Key Authentication
	 *
	 * @since    	1.0.0
	 * @access		private
	 */
	private function authenticate() {

		$headers = array(
			'Authorization' => 'Basic ' . base64_encode( $this->api_key . ':' . $this->api_key_secret ),
			'Connection'	=> 'keep-alive',
			'Accept'		=> 'application/json'
		);

		$response = wp_remote_post( 
			$this->service_uri . $this->auth_service_path,
			array(
				'headers'	=> $headers,
				'body'		=> array(),
			)
		);

		Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( array( $this->service_uri . $this->auth_service_path, $headers, $response ) );

		if ( $this->is_error( $response ) )
			return false;

		// Attempt and process response for token. set_token() will check for non-200 status code. -> Harvey
		$this->set_token( $response );

		return $response;

	}

	/**
	 * Returns token from the services api key authentication
	 * Can renegerate token if expired
	 *
	 * @since    	1.0.0
	 * @access		private
	 */
	private function get_token() {
		
		$token = get_transient( $this->plugin_name . '_endpoint_token_' . get_current_blog_id() . '_' .  get_current_user_id() );

		$response = false;

		// If no token or token expired, let's generate and get the transient. If still not successful, return false. -> Harvey
		if ( ! $token ) {
			if ( $response = $this->authenticate() ) {
				$token = get_transient( $this->plugin_name . '_endpoint_token_' . get_current_blog_id() . '_' .  get_current_user_id() );
			}
		}
		
		if ( ! $token && $response ) {
			add_bring_shelfless_notice(
				sprintf(
					esc_html__( 'Last authentication call to Shelfless Endpoint returned an error: %1$s => %2$s.', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
					$response['response']['code'],
					$response['response']['message']
				),
				'error',
				true
			);
		}

		if ( $this->is_error( $token ) || $this->is_error( $response ) )
			return false;
		
		return $token;

	}

	/**
	 * Sets token from the services api key authentication
	 *
	 * @since    	1.0.0
	 * @access		private
	 * @param		array		$conn		Connection tokens
	 */
	private function set_token( $conn ) {

		// During tests, when wrong API creds were entered after a successful one, last transient token generated from
		// last correct credentials can still be used and might cause a leak if not expired. So we need to make sure we
		// delete the transient IF we set it to a new one. -> Harvey
		delete_transient( $this->plugin_name . '_endpoint_token_' . get_current_blog_id() . '_' . get_current_user_id() );
		
		if ( $this->is_error( $conn ) )
			return false;

		if ( $conn['response']['code'] === 200) {
			$body = json_decode( $conn['body'] );
			if ( isset ( $body->expires_in ) ) {
				$token = array(
					'token'		=> $body->access_token,
					'expiry'	=> $body->expires_in,
				);
				set_transient( $this->plugin_name . '_endpoint_token_' . get_current_blog_id() . '_' . get_current_user_id(), $token, $token['expiry'] );
			}
		}

	}

	/**
	 * To verify and test the connection
	 *
	 * @since    	1.0.0
	 */
	public function verification_test() {

		$conn = $this->authenticate();

		$payload = array();

		if ( $conn ) {
			$body = json_decode( $conn['body'] );
			$payload = array(
				'error'		=> false,
				'data'		=> array(
					'http_code'		=> $conn['response']['code'],
					'http_message'	=> $conn['response']['message'],
				),
			);
			
			if ( $conn['response']['code'] === 200 ) {
				$payload['data']['message'] = esc_html__( 'Token received from Shelfless API' , 'bring-3pl-shelfless-fulfillment-for-woocommerce' );
				$payload['data']['token_expiry'] =  $body->expires_in;
			}
		}
		else {
			$payload = array( 'error' => true );
		}

		unset( $conn );

		return json_encode( $payload );
		
	}

	/**
	 * Get and pull article service ID and return article service response
	 *
	 * @since    	1.0.0
	 * @access		public
	 * @param		int			$article_id		Unique ID to pass for the transient field data
	 */
	public function get_article( $article_id = false, $cache = false ) { 

		Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( __CLASS__ . '->' . __FUNCTION__ );
		
		$token = $this->get_token();
		if ( ! $token ) return false;

		if ( ! $cache ) 
			delete_transient( $this->plugin_name . '_articles_inventory_' . get_current_blog_id() );

		$next_page = true;
		$next_page_meta = '';
		$data = array();

		$response = get_transient( $this->plugin_name . '_articles_inventory_' . get_current_blog_id() );
		
		// Let's pull from endpoint when nothing is returned (no transient or it has expired). -> Harvey
		if ( ! $response ) { 

			$headers = array(
				'Authorization' => 'Bearer ' . $token['token'],
				'Connection'	=> 'keep-alive',
				'Accept'		=> 'application/json'
			);
			
			while( $next_page == true ) {
				// Will be removed in the future when /article is ready
				$endpoint = $this->service_uri . $this->article_service_path . ( ! empty( $article_id ) ? '?sku='. $article_id : '?total=true' . ( $next_page === true ? '&next-page=' . $next_page_meta : '' ) );
				
				Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( $endpoint );

				$response = wp_remote_get( 
					$endpoint, 
					array(
						'headers'	=> $headers,
						'body'		=> array(),
					)
				);

				if ( $this->is_error( $response ) ) {
					Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( $response, 'error' );
					return false;
				}
				
				if ( ! $response || $response['response']['code'] !== 200 ) {
					Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( $response, 'error' );
					return false;
				}

				if ( $response['response']['code'] === 200 ) { 

					$decoded = json_decode( $response['body'] );

					if ( ! empty( $decoded->meta->next_page ) ) { 
						$next_page = true;
						$next_page_meta = $decoded->meta->next_page;
					} else {
						$next_page = false;
					}

					$data[]['data'] = $decoded->data;

				}

				Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( $response['body'] );
				
			}

			$response = json_encode( $data );
			
			set_transient( $this->plugin_name . '_articles_inventory_' . get_current_blog_id(), $response, HOUR_IN_SECONDS );

		}
		
		return json_decode( $response);

	} 

	/**
	 * Create fulfillment request for order.
	 *
	 * @since    	1.0.0
	 * @access		public
	 * @param		int			$orderid		Unique order ID.
	 * @param		int			$order			Order data.
	 */
	public function create_fulfillment_request( $orderid, $order ) { 

		Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( __CLASS__ . '->' . __FUNCTION__ );

		$token = $this->get_token();

		if ( ! $token ) return false;

		$headers = array(
			'Authorization' => 'Bearer ' . $token['token'],
			'Connection'	=> 'keep-alive',
			'Accept'		=> 'application/json'
		);

		$response = wp_remote_post( 
			$this->service_uri . $this->order_service_path,
			array(
				'headers'	=> $headers,
				'body'		=> $order,
			)
		);

		if ( $this->is_error( $response ) ) {
			Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( array( 'POST', $this->service_uri . $this->order_service_path, $order, $response ), 'error' );
			return false;
		}

		Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( array( $this->service_uri . $this->order_service_path, $order, $response ), 'info' );

		// We need to catch 400 series and 200 HTTP codes only. Why 400 series? It contains many codes that describe different errors.
		if ( ! $response || ( $response['response']['code'] !== 200 && ( $response['response']['code'] < 400 && $response['response']['code'] > 499 ) ) ) {
			Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( array( 'POST', $this->service_uri . $this->order_service_path, $order ), 'error' );
			Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( $response, 'error' );
			return false;
		}
		else {
			return $response;
		}

	}

	/**
	 * Update fulfillment request for order.
	 *
	 * @since    	1.0.0
	 * @access		public
	 * @param		int			$orderid		Unique order ID.
	 * @param		int			$order			Order data
	 */
	public function update_fulfillment_request( $orderid, $order ) {

		Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( __CLASS__ . '->' . __FUNCTION__ );

		$is_updatable = false;

		$order_statuses = $this->get_fulfillment_updates( $orderid );

		if ( $order_statuses ) {

			$status = last_shelfless_order_status_sorted( $order_statuses );
			
			// What if there are no statuses received, e.g.,  "statuses":null? Well it could mean that
			// the order has not been acknowledged yet by or not created yet at the warehouse, OCS
			// has a copy of it already. In this case, we would
			// send a PUT operation still. -> Harvey

			// Backorders are not sent to warehouse, so we can still cancel here. -> Harvey

			if ( ! $status || in_array( $status->status_code, array( 1, 7 ) ) )
				$is_updatable = true;
			
		}
		
		if ( ! $is_updatable ) return false;

		$token = $this->get_token();

		if ( ! $token ) return false;

		$headers = array(
			'Authorization' => 'Bearer ' . $token['token'],
			'Connection'	=> 'keep-alive',
			'Accept'		=> 'application/json'
		);

		$response = wp_remote_request( 
			$this->service_uri . $this->order_service_path,
			array(
				'method'	=> 'PUT',
				'headers'	=> $headers,
				'body'		=> $order,
			)
		);

		if ( $this->is_error( $response ) )
			return false;

		Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( array( $this->service_uri . $this->order_service_path, $order, $response ), 'info' );

		// We need to catch 400 series and 200 HTTP codes only. Why 400 series? It contains many codes that describe different errors.
		if ( ! $response || ( $response['response']['code'] !== 200 && ( $response['response']['code'] < 400 && $response['response']['code'] > 499 ) ) ) {
			Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( array( 'PUT', $this->service_uri . $this->order_service_path, $order ), 'error' );
			Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( $response, 'error' );
			return false;
		}
		else {
			return $response;
		}

	}

	/**
	 * Cancel fulfillment request for order.
	 *
	 * @since    	1.0.0
	 * @access		public
	 * @param		int			$orderid		Unique order ID.
	 */
	public function cancel_fulfillment_request( $orderid ) {

		Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( __CLASS__ . '->' . __FUNCTION__ );

		$is_cancellable = false;

		$order_statuses = $this->get_fulfillment_updates( $orderid );

		if ( $order_statuses ) {

			$status = last_shelfless_order_status_sorted( $order_statuses );
			
			// What if there are no statuses received, e.g.,  "statuses":null? Well it could mean that
			// the order has not been acknowledged yet by or not created yet at the warehouse, OCS
			// has a copy of it already. In this case, we would
			// send a DELETE operation still. -> Harvey

			// Backorders are not sent to warehouse, so we can still cancel here. -> Harvey

			if ( ! $status || in_array( $status->status_code, array( 1, 7 ) ) )
				$is_cancellable = true;
			
		}
		
		if ( ! $is_cancellable ) return false;

		$token = $this->get_token();

		if ( ! $token ) return false;

		$headers = array(
			'Authorization' => 'Bearer ' . $token['token'],
			'Connection'	=> 'keep-alive',
			'Accept'		=> 'application/json'
		);

		$response = wp_remote_request( 
			$this->service_uri . $this->order_service_path . '/' . $orderid,
			array(
				'method'	=> 'DELETE',
				'headers'	=> $headers,
			)
		);

		if ( $this->is_error( $response ) )
			return false;
		
		Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( array( $this->service_uri . $this->order_service_path, $orderid, $response ), 'info' );

		// We need to catch 400 series and 200 HTTP codes only. Why 400 series? It contains many codes that describe different errors.
		if ( ! $response || ( $response['response']['code'] !== 200 && ( $response['response']['code'] < 400 && $response['response']['code'] > 499 ) ) ) {
			Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( array( 'DELETE', $this->service_uri . $this->order_service_path . '/' . $orderid ), 'error' );
			Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( $response, 'error' );
			return false;
		}
		else {
			return $response;
		}

	}

	/**
	 * Get and pull fulfillment request for order update.
	 *
	 * @since    	1.0.0
	 * @access		public
	 * @param		int			$orderid		Unique order ID.
	 */
	public function get_fulfillment_updates( $orderid ) {

		Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( __CLASS__ . '->' . __FUNCTION__ );

		$token = $this->get_token();

		if ( ! $token ) return false;

		$headers = array(
			'Authorization' => 'Bearer ' . $token['token'],
			'Connection'	=> 'keep-alive',
			'Accept'		=> 'application/json'
		);

		$response = wp_remote_get( 
			$this->service_uri . $this->order_service_path . '/' . $orderid,
			array(
				'headers'	=> $headers,
			)
		);

		if ( $this->is_error( $response ) )
			return false;
		
		Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( array( $this->service_uri . $this->order_service_path, $orderid, $response ), 'info' );

		if ( ! $response || $response['response']['code'] !== 200 ) {
			Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( array( 'GET', $this->service_uri . $this->order_service_path . '/' . $orderid ), 'error' );
			Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( $response, 'error' );
			return false;
		}
		else {
			return $response['body'];
		}

	}

	/**
	 * Logs error
	 *
	 * @since    	1.0.0
	 * @access		private
	 * @param		array			$obj		List of log details
	 */
	private function is_error( $obj ) {

		if ( is_wp_error( $obj ) ) {
			error_log( print_r( $obj, 1) );
			return true;
		}

		return false;

	}

	/**
	 * Get and pull stock adjustments.
	 *
	 * @since    	1.0.0
	 * @access		public
	 * @param		string			$sku		An SKU to use as parameter.
	 */
	public function get_stock_adjustments( $sku = false, $cache = false ) { 

		Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( __CLASS__ . '->' . __FUNCTION__ );
		
		$token = $this->get_token();

		if ( ! $token ) return false;

		$transient_label = '_stock_adjustment_report_inventory';

		if ( ! $cache )
			delete_transient( $this->plugin_name . $transient_label);

		$response = get_transient( $this->plugin_name . $transient_label );

		// Let's pull from endpoint when nothing is returned (no transient or it has expired). -> Wendell
		if ( ! $response ) {

			$headers = array(
				'Authorization' => 'Bearer ' . $token['token'],
				'Connection'	=> 'keep-alive',
				'Accept'		=> 'application/json'
			);
			
			$response = wp_remote_get( 
				$this->service_uri . $this->stock_adjustment_path . ( $sku ? '?sku=' . $sku : '' ),
				array(
					'headers'	=> $headers,
					'body'		=> array(),
				) 
			);

			if ( $this->is_error( $response ) )
				return false;
			
			if ( ! $response || $response['response']['code'] !== 200 ) {
				Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( $response, 'error' );
				return false;
			}

			Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( $response, 'info' );
			
			set_transient( $this->plugin_name . $transient_label, $response, HOUR_IN_SECONDS );

		}

		if ( $response['response']['code'] === 200 ) {
			return json_decode( $response['body'] );
		}
		
		return false;

	}

	/**
	 * Method to create and update article sync.
	 * 
	 * @since		1.0.0
	 * @access		public
	 * @param		int			$product_id		Product ID
	 * @param		string		$status			Either POST to create an article or PATCH for update.
	 */
	public function get_article_sync_create_update( $product_id, $status ) { 

		Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( __CLASS__ . '->' . __FUNCTION__ );
		$admin_instance = new Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin( $this->plugin_name, $this->version );
		$token = $this->get_token();

		if ( ! $token ) return false;

		$headers = array(
			'Authorization' => 'Bearer ' . $token['token'], 
			'Connection'	=> 'keep-alive',
			'Accept'		=> 'application/json'
		);

		// update article
		$method = ( ( $status == 'create' ) ? 'POST' : 'PATCH' );
		if ( $status === 'create' ) {
			$method = 'POST';
			$article = $admin_instance->shelfless_article_sync_create_data_format( $product_id );
		}
		else {
			$method = 'PATCH';
			$article = $admin_instance->shelfless_article_sync_update_data_format( $product_id );
		}
		
		$response = wp_remote_request( 
			$this->service_uri . $this->article_master_data_path, 
			array(
				'method'	=> $method,
				'headers'	=> $headers,
				'body'		=> $article,
			)
		);

		if ( $this->is_error( $response ) )
			return false;

		if ( $response['response']['code'] !== 200 ) {

			if ( $status === 'update' && in_array( $response['response']['code'], array( 409, 422 ) ) ) { 
				$this->get_article_sync_create_update( $product_id, 'create' );
			}
			else {
				Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( array( $this->service_uri . $this->article_master_data_path, $article, $response['body'], $response['response'] ), 'info' );
			}
			
		}

		Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( array( $this->service_uri . $this->article_master_data_path, $article, $response['body'], $response['response'] ), 'info' );

		return $response['response'];
		
	}

	/**
	 * Get rates from Bring Shipping API
	 * 
	 * @since		1.2.5
	 * @access		public
	 * @param		array		$package	The cart information.
	 */
	public function get_bring_rates( $payload ) {

		$headers = array(
			'X-Mybring-API-Uid'		=> sanitize_text_field( get_option( $this->plugin_name . '_mybring_api_email_address' ) ),
			'X-Mybring-API-Key'		=> sanitize_text_field( get_option( $this->plugin_name . '_mybring_api_key' ) ),
			'X-Bring-Client-URL'	=> sanitize_url( get_site_url() ),
			'Content-Type'			=> 'application/json',
		);

		Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( array(
			'headers'	=> $headers,
			'body'		=> json_encode( $payload ),
		), 'ship' );

		$response = wp_remote_post( 
			$this->bring_shipping_uri, 
			array(
				'headers'	=> $headers,
				'body'		=> json_encode( $payload ),
			)
		);

		if ( $this->is_error( $response ) || $response['response']['code'] !== 200 ) {

			Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( array( $this->bring_shipping_uri, $headers, $response ), 'error' );
			return false;
		}
		
		return json_decode( $response['body'] );

	}

	/**
	 * Get pickup points from Bring Shipping API
	 * 
	 * @since		1.2.5
	 * @access		public
	 * @param		array		$destination	A shipping address as an array
	 * @param		boolean		$default		Set to true if only the default pickup point is to fetched.
	 */
	public function get_bring_pickup_points( $destination, $default = false ) {

		$headers = array(
			'X-Mybring-API-Uid'		=> sanitize_text_field( get_option( $this->plugin_name . '_mybring_api_email_address' ) ),
			'X-Mybring-API-Key'		=> sanitize_text_field( get_option( $this->plugin_name . '_mybring_api_key' ) ),
			'X-Bring-Client-URL'	=> sanitize_url( get_site_url() ),
			'Content-Type'			=> 'application/json',
		);

		$uri = $this->bring_pickup_point_uri . '/' . sanitize_text_field( $destination['country'] ) . '/postalCode/' . sanitize_text_field( $destination['postcode'] );
		$uri = ( $default ? $uri . '/default.json' : $uri . '.json?street=' . sanitize_text_field( $destination['address'] ) );
		$uri = sanitize_url( $uri );

		Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( $uri, 'ship' );
		
		$response = wp_remote_get( 
			$uri,
			array(
				'headers'	=> $headers,
			)
		);

		if ( $this->is_error( $response ) || $response['response']['code'] !== 200 ) {

			Bring_3pl_Shelfless_Fulfillment_For_Woocommerce_Admin::shelfless_log( array( $uri, $headers, $response ), 'error' );
			return false;
		}
		
		return json_decode( $response['body'] );

	}

}
