<?php
/**
 * Bring_3pl_Shelfless_Fulfillment_For_Woocommerce Helper Functions
 *
 * Contains helper functions that can be reused by all classes of Shelfless by Bring.
 *
 * @link       https://www.bring.no/
 * @since      1.0.0
 *
 * @package    Bring_3pl_Shelfless_Fulfillment_For_Woocommerce
 * @subpackage Bring_3pl_Shelfless_Fulfillment_For_Woocommerce/includes
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Returns the app name
 *
 * @since    1.0.0
 */
function get_bring_shelfless_app_name() {
    return Bring_3pl_Shelfless_Fulfillment_For_Woocommerce::get_app_name();
}

/**
 * Returns the plugin name
 *
 * @since    1.0.0
 */
function get_bring_shelfless_plugin_name() {
    return Bring_3pl_Shelfless_Fulfillment_For_Woocommerce::get_plugin_name();
}

/**
 * Validates and sanitize the text fields rendered in form
 *
 * @since    1.0.0
 */
function validate_bring_shelfless_text_field( $text ) {

    $text = is_null ( $text ) ? '' : $text;
    return wp_kses( stripslashes( $text ), 'strip' );

}

/**
 * Generates Shelfless fields.
 *
 * @since    1.0.0
 */
function get_bring_shelfless_fields( $args ) {

    $value = get_option( $args['fid'] );
    
    $vas_codes_value = '';
    if ( $args['fid'] == get_bring_shelfless_plugin_name() . '_order_value_added_services_codes' ) { 
        
        $vas_codes = '';
        if ( ! empty( $value ) ) { 
            if ( is_array( $value ) ) {
                $ctr = 1;
                foreach( $value as $idx => $code ) { 
                    if ( $ctr > 1 ) { 
                        $custom_name = get_bring_shelfless_plugin_name() . '_order_settings_value_added_services_codes';
                        $vas_codes .= '<p><label for="row_vas_code"><input type="text" id="'. $args['fid'] .'_'. $idx .'" name="'. $custom_name .'[]" value="'. $code .'" placeholder="1091" class="vas_codes_wrapper"/></label> <a href="javascript:;" class="remove-vas-code"><span class="bring-shelfless-remove dashicons dashicons-no"></a></p>';
                    }
                    $ctr++;
                } 
                // set the value of the first input text with zero(0) index
                $value = $value[0];
            }
        }

    }

    if ( isset( $value ) && $value === false ) {
        $value = $args['default'];
    }

    switch( $args['type'] ) { 
        case 'text':
            $args['class'] = ( ! empty( $args['class'] ) ? $args['class'] : '' );
            $icon = ( ! empty( $args['icon'] ) ? '<a id="'. $args['icon'] .'" href="javascript:;"><span class="bring-shelfless-add-new dashicons dashicons-plus"></a></a>' : '' );
            $container = ( ! empty( $args['container'] ) ? '<div id="'. $args['container'] .'" data-codes="'. $vas_codes_value .'">'. $vas_codes .'</div>' : '' );

            $class = ( !empty( $args['class'] ) ? 'class="'. $args['class'] .'"' : '' );
            $disabled = ( !empty( $args['options'][0]['disabled'] ) ? $args['options'][0]['disabled'] : '' );
            $description = ( !empty( $args['options'][0]['text'] ) ? '<p>' . $args['options'][0]['text'] . '</p>' : '' );

            // this will always keep the value localized when displayed in the frontend
            // displaying actual value from DB
            $default_dimensions = array( get_bring_shelfless_plugin_name() . '_sd_use_default_dimensions_length', get_bring_shelfless_plugin_name() . '_sd_use_default_dimensions_width', get_bring_shelfless_plugin_name() . '_sd_use_default_dimensions_height', get_bring_shelfless_plugin_name() . '_sd_use_default_dimensions_weight', get_bring_shelfless_plugin_name() . '_sd_use_default_dimensions_maximum_weight', get_bring_shelfless_plugin_name() . '_sd_use_default_dimensions_maximum_items_in_cart' );

            if ( in_array( $args['fid'], $default_dimensions ) ) { 
                $value = ( $value == '' ? $args['default'] : get_option( $args['fid'] ) );
            }

            printf( 
                '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" '. $class .' %6$s/>' . $icon . ( ! empty( $args['tooltip'] ) ? '<span class="bring-shelfless-tooltip dashicons dashicons-editor-help" title="%5$s" data-toggle="tooltip"' . ( ! empty( $args['ttip_w_html'] ) ? ' data-html="true"' : '' ) . '></span>' . $container . $description : '' ),
                $args['fid'], $args['type'], $args['placeholder'], $value, $args['tooltip'], $disabled 
            );
            break;
        case 'password':
                printf( 
                    '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />' . ( ! empty( $args['tooltip'] ) ? '<span class="bring-shelfless-tooltip dashicons dashicons-editor-help" title="%5$s" data-toggle="tooltip"' . ( ! empty( $args['ttip_w_html'] ) ? ' data-html="true"' : '' ) . '></span>' : '' ),
                    $args['fid'], $args['type'], $args['placeholder'], $value, $args['tooltip']
                );
                break;
        case 'checkbox':
            echo '<fieldset>';
            if ( count( $args['options'] ) === 1 ) { 
                
                // this will always keep the checkbox on/checked - Use default dimensions when missing
                if ( $args['fid'] == get_bring_shelfless_plugin_name() . '_sd_use_default_dimensions' ) { 
                    $value = '1';
                } else {
                    $value = get_option( $args['fid'] );
                }
                $is_setup = get_option( get_bring_shelfless_plugin_name() . '_shelfless_api_setup_is_complete' );
                if ( empty( $value ) && empty( $is_setup ) ) $value = $args['options'][0]['default'];
                $disabled = ( !empty( $args['options'][0]['disabled'] ) ? $args['options'][0]['disabled'] : '' );
                printf(
                    '<label for="%1$s">', $args['fid']
                );
                $class = ( !empty( $args['class'] ) ? 'class="_'. $args['class'] .'"' : '' );
                printf( 
                    '<input name="%1$s" id="%1$s" type="%2$s" value="%3$s" %4$s %5$s %7$s/><span class="checkbox-text">%6$s</span>',
                    $args['fid'], $args['type'], $args['options'][0]['value'], checked( $value, '1', false ), $disabled, $args['options'][0]['text'], $class,
                );
                printf(
                    ( ! empty( $args['tooltip'] ) ? '<span class="bring-shelfless-tooltip dashicons dashicons-editor-help" title="%1$s" data-toggle="tooltip"' . ( ! empty( $args['ttip_w_html'] ) ? ' data-html="true"' : '' ) . '></span>' : '' ),
                    $args['tooltip']
                );
                echo '</label>';
            }
            else {
                foreach( $args['options'] as $key => $option ) {
                    $value = get_option( $args['fid'] . '_' . $key );
                    if ( ! $value ) $value = $option['default'];
                    printf(
                        '<label for="%1$s">', $args['fid'] . '_' . $key
                    );
                    printf( 
                        '<input name="%1$s" id="%1$s" type="%2$s" value="%3$s" %4$s /><span class="checkbox-text">%5$s</span>',
                        $args['fid'] . '_' . $key, $args['type'], $option['value'], checked( $value, '1', false ), $option['text']
                    );
                    printf(
                        ( ! empty( $option['tooltip'] ) ? '<span class="bring-shelfless-tooltip dashicons dashicons-editor-help" title="%1$s" data-toggle="tooltip"' . ( ! empty( $option['ttip_w_html'] ) ? ' data-html="true"' : '' ) . '></span>' : '' ),
                        $args['tooltip']
                    );
                    echo '</label><br />';
                }
            }
            echo '</fieldset>';
            break;
        case 'radio':
            echo '<fieldset>';
            foreach( $args['options'] as $key => $option ) {
                echo '<label>';
                printf( 
                    '<input name="%1$s" id="%1$s" type="%2$s" value="%3$s" %4$s /><span class="checkbox-text">%5$s</span>',
                    $args['fid'], $args['type'], $option['value'], checked( $value, '1', false ), $option['text']
                );
                printf(
                    ( ! empty( $option['tooltip'] ) ? '<span class="bring-shelfless-tooltip dashicons dashicons-editor-help" title="%1$s" data-toggle="tooltip"' . ( ! empty( $option['ttip_w_html'] ) ? ' data-html="true"' : '' ) . '></span>' : '' ),
                    $args['tooltip']
                );
                echo '</label><br />';
            }
            echo '</fieldset>';
            break;
        case 'textarea':
            printf(
                '<textarea name="%1$s" id="%1$s" placeholder="%2$s" rows="5" cols="50">%3$s</textarea>' . ( ! empty( $args['tooltip'] ) ? '<span class="bring-shelfless-tooltip dashicons dashicons-editor-help" title="%4$s" data-toggle="tooltip"' . ( ! empty( $args['ttip_w_html'] ) ? ' data-html="true"' : '' ) . '></span>' : '' ),
                $args['uid'], $args['placeholder'], $value, $args['tooltip']
            );
            break;
        case 'select': 
            if ( ! empty ( $args['options'] ) && is_array( $args['options'] ) ) { 
                $description = ( !empty( $args['field_text'] ) ? '<p>' . $args['field_text'] . '</p>' : '' );
                $options_markup = '';
                $data_grp = array();
                foreach( $args['options'] as $key => $label ) { 
                    if ( is_array( $label ) ) { 
                        // reformat select values to new array
                        $data_grp[$label['group']][] = array( $key, $label['name'] );
                    } else {
                        $options_markup .= sprintf( '<option data-key="'. $key .'" data-value="'. $key .'"  value="%s" %s>%s</option>', $key, selected( $value, $key, false ), $label );
                    }
                }
                
                /** start - Shipping | Shipping Mappings | implements optgroup */
                $count = count( $args['options'] );

                $current_opt_grp = '';
                $prev_opt_grp = '';
                if ( ! empty( $data_grp ) ) {
                    $ctr = 0;
                    foreach( $data_grp as $grp => $services ) { 
                        foreach( $services as $idx => $data ) {
                            $ctr++;
                            if ( empty( $current_opt_grp ) ) {
                                $current_opt_grp = $grp;
                                $options_markup .= sprintf( '<optgroup label="'. $grp .'"><option data-key="'. $data[0] .'" data-value="'. $data[0] .'"  value="%s" %s>%s</option>', $data[0], selected( $value, $data[0], false ), $data[1] );
                            } else {
                                if ( $current_opt_grp != $grp ) { 
                                    $options_markup .= '</optgroup>';
                                    $prev_opt_grp = $current_opt_grp;
                                    $current_opt_grp = $grp;

                                    if ( $prev_opt_grp != $current_opt_grp ) {
                                        $options_markup .= sprintf( '<optgroup label="'. $grp .'"><option data-key="'. $data[0] .'" data-value="'. $data[0] .'"  value="%s" %s>%s</option>', $data[0], selected( $value, $data[0], false ), $data[1] );
                                    } else {
                                        $options_markup .= sprintf( '<option data-key="'. $data[0] .'" data-value="'. $data[0] .'"  value="%s" %s>%s</option>', $data[0], selected( $value, $data[0], false ), $data[1] );
                                    }
                                } else {
                                    $options_markup .= sprintf( '<option data-key="'. $data[0] .'" data-value="'. $data[0] .'"  value="%s" %s>%s</option>', $data[0], selected( $value, $data[0], false ), $data[1] );
                                }
                            }

                            if ( $count == $ctr ) {
                                $options_markup .= '</optgroup>';
                            }
                        }
                        
                    }
                }
                /** end - Shipping | Shipping Mappings */

                printf( '<select name="%1$s" id="%1$s">%2$s</select>' . ( ! empty( $args['tooltip'] ) ? '<span class="bring-shelfless-tooltip dashicons dashicons-editor-help" title="%3$s" data-toggle="tooltip"' . ( ! empty( $args['ttip_w_html'] ) ? ' data-html="true"' : '' ) . '></span>%4$s' : '' ) . '', $args['fid'], $options_markup, $args['tooltip'], $description );
            }
            break;
        
        case 'multiselect':
            if ( ! empty ( $args['options'] ) && is_array( $args['options'] ) ) { 
                $options_markup = '';
                foreach( $args['options'] as $key => $label ) {
                    $val = false;
                    if ( ! empty( $args['default'] ) ) { 
                        $val = ( in_array( $key, $args['default'] ) ? $key : false );
                    }
                    $options_markup .= sprintf( '<option value="%s" %s>%s</option>', $key, selected( $val, $key, false ), $label );
                }
                printf( '<select name="%1$s[]" id="%1$s" multiple="multiple">%2$s</select>' . ( ! empty( $args['tooltip'] ) ? '<span class="bring-shelfless-tooltip dashicons dashicons-editor-help" title="%3$s" data-toggle="tooltip"' . ( ! empty( $args['ttip_w_html'] ) ? ' data-html="true"' : '' ) . '></span>' : '' ) . '', $args['fid'], $options_markup, $args['tooltip'] );
            }
            break;
        case 'separator':
            printf( 
                '<h6>%1$s</h6><p>%2$s</p>',
                $args['options']['heading'], $args['options']['text']
            );
            break;
    }

}

/**
 * Generates Shelfless buttons for settings and wizards.
 *
 * @since    1.0.0
 */
function generate_bring_shelfless_btn_classes( $type = 'primary') {
    $screen = get_current_screen();
    $btn_classes = array();

    if ( $screen->id === 'toplevel_page_bring_3pl_shelfless_fulfillment_for_woocommerce_setup' ) {
        $btn_classes[] = 'btn';
        $btn_classes[] = 'btn-' . $type;
        $btn_classes[] = 'btn-setup-creds';
    }
    else {
        $btn_classes[] = 'button';
        $btn_classes[] = 'button-' . $type;
    }

    return implode( ' ', $btn_classes );
}

/**
 * Generates Shelfless buttons for settings and wizards.
 *
 * @since       1.0.0
 * @param       string      $page       Page slug/url
 */
function redirect_bring_shelfless_page( $page ) {

    $admin_dash = self_admin_url( '', 'admin' );

    $url = esc_url(
        add_query_arg(
            'page',
            $page,
            $admin_dash . 'admin.php'
        )
    );
     
    exit( wp_safe_redirect( urldecode( $url ) ) );
}

/**
 * Displays notice messages
 *
 * @since       1.0.0
 * @param       string      $notice         Notice message
 * @param       string      $type           Notice type
 * @param       boolean     $dismissible    Dismiss the notice message, true/false
 */
function add_bring_shelfless_notice( $notice = '', $type = "info", $dismissible = true, $code = '' ) {

    if ( ! is_admin() || ( is_admin() && ! current_user_can( 'manage_options' ) ) ) {
        return false;
    }

    $notices = get_transient( 'shelfless_notices_' . get_current_blog_id() . '_' . get_current_user_id(), array() );

    $dismiss_text = $dismissible ? ' is-dismissible ' : '';

    $notices[] = array(
        'code'		    => $code,
        'notice'		=> $notice,
        'type'			=> $type,
        'dismissible'	=> $dismiss_text,
    );

    set_transient( 'shelfless_notices_' . get_current_blog_id() . '_' .  get_current_user_id(), $notices );
}

/**
 * Display admin notice when deselecting product that exists in an ongoing order via ajax, this apply to product level inventory
 * 
 * @since       1.0.0
 * @param       string      $txt_products   Generic label, default products
 * @param       string      $is_are         Displays is or are usage
 * @param       string      $txt_orders     Generic label, default orders
 */
function show_notice_deselect_product_existing_in_order( $products, $txt_orders ) {

    $cnt = count( $products );
    $is_are = ( $cnt > 1 ? 'are' : 'is' );
    $txt_products = ( $cnt > 1 ? 'products' : 'product' );

    if ( $products ) {
        $links = array();
        foreach( $products as $k => $product ) {
            $links[] = '<a href="'. esc_url( get_admin_url() . 'post.php?post='. $product->id .'&action=edit' ) .'"><strong>'. $product->name .'</strong></a>';
        }
        $link = implode( ', ', $links );
    }

    $html = '<div class="notice notice-warning is-dismissible clearfix fade show" role="alert">
        <div class="float-left helper">
            <img class="bring-error-icon" src="'. urldecode( esc_url( plugin_dir_url( dirname( __FILE__ ) ) .'admin/assets/bring_icon.png' ) ) . '" />
        </div>
        <div>
            <p>The '. $txt_products .' you indicated for fulfillment deselection '. $is_are .' currently included in '. $txt_orders .' processed for fulfillment and shipment. 
            <br>The '. $txt_products .' cannot be deselected until the orders have been fulfilled. '. $link .'</p>
        </div>
    </div>';
    
    return $html;
}

/**
 * Display admin notice when deselecting product that exists in an ongoing order via ajax, this apply to product level inventory
 * 
 * @since       1.0.0
 * @param       string      $msg        General message to display for users
 * @param       string      $type       Product type
 * @param       string      $class      class attribute to add in the element
 */
function show_notice_via_ajax( $msg, $type='', $class='' ) {

    if ( ! empty( $type ) ) {
        switch ( $type ) { 
            case 'composite' : 
                $msg .= 'components.';
                break;

            case 'grouped' :
                $msg .= 'linked products.';
                break;

            case 'variable' : 
                $msg .= 'variations.';
                break;

            default : 
                break;
        }
    }

    $html = '<div class="notice notice-success is-dismissible clearfix fade show '. $class .'" role="alert">
        <div class="float-left helper">
            <img class="bring-success-icon" src="'. urldecode( esc_url( plugin_dir_url( dirname( __FILE__ ) ) .'admin/assets/bring_icon.png' ) ) . '" />
        </div>
        <div>
            <p>'. $msg .'</p>
        </div>
        <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
    </div>';
    
    return $html;
}

/**
 * Sorts order statuses fetched from Shelfless endpoint and returns the latest status based on timestamp.
 * 
 * @since       1.1.3
 * @param       string      $order_statuses        A JSON string to be converted to an array

 */
function last_shelfless_order_status_sorted( $order_statuses ) {

    $status = false;
    
    if ( $order_statuses ) {

        $updates = json_decode( $order_statuses );

        $updates = $updates->statuses;
        $ocs_statuses = array();

        if ( $updates ) {

            foreach( $updates as $status_update ) {
                $ocs_statuses[strtotime( $status_update->updated_at )] = $status_update;
            }

            ksort( $ocs_statuses, SORT_NUMERIC );
            $status = array_pop( $ocs_statuses );

        }

    }

    return $status;

}

/**
 * Display MyBring Link and Shelfless Status
 * 
 * @since       1.2.1
 * @param       int         $customer_id                Customer ID
 * @param       string      $order_number               Unique order number
 * @param       string      $last_shelfless_status      Previous Shelfless status
 * @return      string      $html

 */
function print_mybring_link( $customer_id, $order_number, $last_shelfless_status ) {

    $url = sanitize_url( 'https://www.mybring.com/warehousing/customer/' . $customer_id . '/salesorder/' . $order_number );

    echo '
        <h3 class="shelfless-mybring-information-header">' . esc_html__( 'Mybring Information:', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) . '</h3>
        <p class="form-field form-field-wide shelfless-mybring-link">
            <span>' . esc_html__( 'Sales Order ID:', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) . '<strong> #<a href="' . esc_url( $url ) . '" target="_blank">' . esc_html( $order_number ) . '</a></strong></span><br/>
            <span>' . esc_html__( 'Last Shelfless Status:', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) . '<span><strong> ' . esc_html( $last_shelfless_status ) . '</strong></span>
        </p>
    ';
}

/**
 * Display MyBring Link and Shelfless Status
 * 
 * @since       1.2.1
 * @param       int         $customer_id                Customer ID
 * @param       string      $order_number               Unique order number
 * @param       string      $last_shelfless_status      Previous Shelfless status
 * @return      string      $html

 */
function print_mybring_link_preview( $customer_id, $order_number, $last_shelfless_status ) {

    $url = sanitize_url( 'https://www.mybring.com/warehousing/customer/' . $customer_id . '/salesorder/' . $order_number );

    $html = '
        <div class="wc-order-preview-addresses">
            <div class="wc-order-preview-address">
                <h2>' . esc_html__( 'Mybring Information:', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) . '</h2>
                <p>
                    <strong>' . esc_html__( 'Sales Order ID:', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) . '</strong> 
                    <span><a href="' . esc_url( $url ) . '" target="_blank">' . $order_number . '</a></span></br>
                    <strong>' . esc_html__( 'Shelfless Status:', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) . '</strong> 
                    <span>' . esc_html( $last_shelfless_status ) . '</span></br>
                </p>
            </div>
        </div>
    ';

    return $html;
}

/**
 * Process the article name to strictly implement UTF-8
 * 
 * @since       1.2.1
 * @param       string      $name               Article Name
 */
function process_article_name( $name ) {

    // Ensuring that the variable is in string
    $name = (string) $name;

    // Strip HTML tags
    $name = strip_tags( $name );

    // trimp extra spaces
    $name = trim( $name );

    $name_length = strlen( $name );
    $name = mb_strimwidth( $name, 0, $name_length, '...', 'UTF-8' );

    return $name;

}

/**
 * Process the article description to strictly implement UTF-8
 * 
 * @since       1.2.1
 * @param       string      $desc               Article description
 */
function process_article_description( $desc ) { 

    $max_length = BRING_3PL_SHELFLESS_FULFILLMENT_FOR_WOOCOMMERCE_ARTICLE_DESC_MAX_LENGTH;
    
    // Ensuring that the variable is in string
    $desc = (string) $desc;

    // Strip HTML tags
    $desc = strip_tags( $desc );

    // Replace line breaks with one space
    // and trim extra spaces
    $desc = preg_replace('#\s+#', ' ', $desc);
    $desc = trim( $desc );

    // cut off extra characters that exceeded the max length and then add ellipsis
    $desc_length = strlen( $desc );
    if ( $desc_length > $max_length ) { 
        $desc_length = $max_length;
    }

    $desc = mb_strimwidth( $desc, 0, $desc_length, '...', 'UTF-8' );

    return $desc;
}

/**
 * Print the pickup point dropbox.
 * @param array $pickup_points
 * @param int $selected_pickup_point_id
 *
 * @since    1.2.5
 */
function shelfless_delivery_pickup_points( $pickup_points, $selected_pickup_point_id ) {

    if ( is_cart() ) {
        echo '<form name="shelfless_delivery_pickup_points" class="shelfless_delivery_pickup_points">';
    }

    echo  '<div class="shelfless-delivery">';
    $nonce = wp_create_nonce( 'nonce_' . get_bring_shelfless_plugin_name() . 'shelfless_delivery' );
    echo '<input type="hidden" id="shelfless-delivery-pickup-nonce" name="shelfless-delivery-pickup-nonce" value="' . esc_attr( $nonce ) . '" />';
    echo '<input type="hidden" id="shelfless-delivery-pickup-location" name="shelfless-delivery-pickup-location" value="' . esc_attr( WC()->session->get( 'shelfless-delivery-pickup-location' ) ) . '" />';
    $field = array(
        'type'		    => 'select',
        'class'		    => array('form-row-wide woocommerce-shipping-totals shelfless-delivery-bring-pickup-point-id shipping'),
        'required'	    => true,
        'autocomplete'  => 'off',
        'options'	    => $pickup_points,
        'default'	    => $selected_pickup_point_id
    );
    woocommerce_form_field( 'shelfless-delivery-bring-pickup-point-id', $field , $selected_pickup_point_id );
    echo  '</div>';

    if ( is_cart() ) {
        echo '</form>';
    }

}

/**
 * Form to show available addons
 * @param array $available_addons
 *
 * @since    1.2.6
 */
function shelfless_delivery_addons( $available_addons ) {

    echo '<tr>';
    echo '<th>' . esc_html__( 'Addons', 'bring-3pl-shelfless-fulfillment-for-woocommerce' ) . '</th>';
    echo '<td>';
    $nonce = wp_create_nonce( 'nonce_' . get_bring_shelfless_plugin_name() . 'shelfless_delivery_addons' );
    echo '<input type="hidden" id="shelfless-delivery-addons-nonce" name="shelfless-delivery-addons-nonce" value="' . esc_attr( $nonce ) . '" />';

    foreach ( $available_addons as $addon_code => $addon ) {
        
        $field = array(
            'id'		=> 'shelfless-delivery-addon-' . $addon_code,
            'type'		=> 'checkbox',
            'name'		=> 'shelfless-delivery-addon',
            'value'		=> $addon_code,
            'required'	=> false,
            'class'		=> array('form-row-wide woocommerce-shipping-totals shelfless-delivery-bring-addons shipping'),
            'input_class'   => array('shelfless-delivery-bring-addon shelfless-delivery-bring-addon-'. $addon_code ),
            'label'		=> esc_html__( $addon, 'bring-3pl-shelfless-fulfillment-for-woocommerce' ),
            'default'	=> ''
        );
        woocommerce_form_field( 'shelfless-delivery-addon[' . $addon_code . ']', $field, '' );

    }

    echo  '</td>';
    echo '</tr>';

}

/**
 * Get all allowed and supported Shelfless Delivery services. 
 *  @since    1.2.6
 */
function shelfless_delivery_services() {
    
    $services = array();

    $carriers = get_option( get_bring_shelfless_plugin_name() . '_sd_services_bring', array() );

    foreach ( $carriers as $carrier => $carrier_products ) {

        if ( 'bring' !== $carrier ) { break; }

        foreach ( $carrier_products as $code => $method ) {
            
            if ( '1' == $method['enabled']  ) {
                $services[$code] = $method;
            }
        }

    }

    return $services;

}

/**
 * Get all allowed and supported Shelfless Delivery addons. 
 *
 * @since    1.2.6
 */
function shelfless_delivery_services_addons() {
    $addons = array(
        '1000' => 'Cash on delivery',
        '1091' => 'eVarsling (eAdvising)',
        '1133' => 'ID verification',
        '1134' => 'Individual verification',
        '2086' => 'Notification by letter',
        '1082' => 'Social control',
        '2012' => 'Same day delivery',
        '0041' => 'Flex delivery',
        '1081' => 'Bag on door',
        '0068' => 'Cargo insurance',
        '1288' => 'Label free',
        '0003' => 'Limited quantities',
        '0073' => 'Nordic special goods',
        '0015' => 'Evening delivery',
        '1280' => 'Signature required',
        '1127' => 'TV installation',
        '1141' => 'Mechanical installation',
        '1128' => 'Electronic installation',
        '1140' => 'Carry service oversize',
        '0039' => 'Delivery indoors', 
        '2084' => 'eAdvising'
    );

    return $addons;
}

/**
 * Process price for wp_price
 * @param string $price
 * @return string
 * @since    1.2.5
 */
function shelfless_process_sds_price( $price ) { 

    $price = shelfless_clean_price( $price );

    $price = number_format( $price, 2, '.', '' );
    
    return $price;

}

/**
 * This function is compatible for numbers with dots or commas as decimals
 * This works for all kind of inputs (American or European style)
 * @param string $val
 * @return float
 * @since 1.2.5
 */
function shelfless_clean_price( $val ) { 

    if ( preg_match( '/^\d{1,3}(?:[\.]*\d{3})*(?:[,]{1}\d{2})?$/', $val ) ) { 
        $val = str_replace('.', '', $val);
        $val = str_replace(',', '.', $val);
    }
    
    if ( preg_match( '/^\d{1,3}(?:[,]*\d{3})*(?:[\.]{1}\d{2})?$/', $val ) ) {
        $val = str_replace(',', '', $val);
    }

    return floatval( $val );

}

/**
 * Return WC decimal separator
 */

function shelfless_decimal_separator() {
    
    $decimal_sep = wp_specialchars_decode( stripslashes( get_option( 'woocommerce_price_decimal_sep' ) ), ENT_QUOTES );

    return $decimal_sep;
}

/**
 * Return WC thousand separator
 */
function shelfless_thousand_separator() {

    $thousands_sep = wp_specialchars_decode( stripslashes( get_option( 'woocommerce_price_thousand_sep' ) ), ENT_QUOTES );

    return $thousands_sep;
}

/**
 * Format the price display in the frontend
 * having it localized
 * @param string $price
 * @return string
 * @since 1.2.5
 */
function shelfless_process_display_local_price_settings( $price ) {
    
    $price = trim( $price );

    $num_decimals    = absint( get_option( 'woocommerce_price_num_decimals' ) );
    $num_decimals    = ( empty( $num_decimals ) ? 2 : $num_decimals );
    $decimal_sep     = shelfless_decimal_separator();
    $thousands_sep   = shelfless_thousand_separator();

    $price = number_format( $price, $num_decimals, $decimal_sep, $thousands_sep );

    return $price;

}