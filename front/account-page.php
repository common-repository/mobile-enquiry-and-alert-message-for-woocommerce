<?php
/**
 * Defines if my account page
 */
function mmwea_is_my_account_page() {

    $general_settings_options   = get_option('mmwea_general_settings_options');
    $account_page_options        = get_option('mmwea_product_account_page_options');
    $whatsapp_number = $hide_btn_des = $new_tab = $display_btn = $button_text = $message_body = $enable_user_role = $user_role_option = $login_user_role = "";
    $user_role_wise = 1;
    
    if(isset($general_settings_options) && !empty($general_settings_options)){

        if (isset($general_settings_options['whatsapp_number']))		$whatsapp_number    = $general_settings_options['whatsapp_number'];
        if (isset($general_settings_options['hide_wa_btn_desktop']))    $hide_btn_des    	= $general_settings_options['hide_wa_btn_desktop'];
        if (isset($general_settings_options['open_link_new_tab']))		$new_tab    	    = $general_settings_options['open_link_new_tab'];
        if (isset($general_settings_options['enable_user_role']))		$enable_user_role   = $general_settings_options['enable_user_role'];
        if (isset($general_settings_options['user_role_option']))		$user_role_option   = $general_settings_options['user_role_option'];
        if (isset($general_settings_options['login_user_role']))		$login_user_role    = $general_settings_options['login_user_role'];
    }

    if(isset($account_page_options) && !empty($account_page_options)){
        if (isset($account_page_options['display_on_account_page']))		$display_btn    	    = $account_page_options['display_on_account_page'];
        if (isset($account_page_options['enquiry_btn_text']))		        $button_text    	    = $account_page_options['enquiry_btn_text'];
        if (isset($account_page_options['message_body']))		            $message_body    	    = $account_page_options['message_body'];
    }

    if($enable_user_role == "on"){
        if($user_role_option == "logged-in" && is_user_logged_in()){               
            if(isset($login_user_role) && !empty($login_user_role)){
                $allow_user_role = explode(",",$login_user_role);
                $user_id = get_current_user_id();
                $user_meta = get_userdata($user_id);
                $user_roles = $user_meta->roles; 

                if(!empty($user_roles) && isset($user_roles)){
                    foreach ($user_roles as $key => $role) {                 
                        if(in_array($role, $allow_user_role)){
                            $user_role_wise = 0;
                        }
                    }
                }
            }else{
                $user_role_wise = 0;
            }
        }elseif($user_role_option == "non-logged" && !is_user_logged_in()){
            $user_role_wise = 0;
        }
    }else{
        $user_role_wise = 0;
    }

    if($display_btn == "on" && $user_role_wise == 0){
        return true;
    }
    
    return false;
}

/**
 * Shortcode for my account page button
 */
add_shortcode('mmwea_my_account_wh_btn','mmwea_my_account_shortcode',10);
function mmwea_my_account_shortcode($atts) {
    $atts = shortcode_atts( array( 'order_id' => '', ), $atts );
    $order_id = (isset($atts['order_id']) && !empty($atts['order_id'])) ? $atts['order_id'] : '';

    if( mmwea_is_my_account_page() ){
        if(isset($order_id) && !empty($order_id)) {
            $order = wc_get_order($order_id);
            $general_settings_options   = get_option('mmwea_general_settings_options');
            $account_page_options        = get_option('mmwea_product_account_page_options');
            $whatsapp_number = $hide_btn_des = $new_tab = $display_btn = $button_text = $message_body = $enable_user_role = $user_role_option = $login_user_role = "";
            $user_role_wise = 1;
            
            if(isset($general_settings_options) && !empty($general_settings_options)){
        
                if (isset($general_settings_options['whatsapp_number']))		$whatsapp_number    = $general_settings_options['whatsapp_number'];
                if (isset($general_settings_options['hide_wa_btn_desktop']))    $hide_btn_des    	= $general_settings_options['hide_wa_btn_desktop'];
                if (isset($general_settings_options['open_link_new_tab']))		$new_tab    	    = $general_settings_options['open_link_new_tab'];
                if (isset($general_settings_options['enable_user_role']))		$enable_user_role   = $general_settings_options['enable_user_role'];
                if (isset($general_settings_options['user_role_option']))		$user_role_option   = $general_settings_options['user_role_option'];
                if (isset($general_settings_options['login_user_role']))		$login_user_role    = $general_settings_options['login_user_role'];
            }

            if(isset($account_page_options) && !empty($account_page_options)){
                if (isset($account_page_options['display_on_account_page']))		$display_btn    	    = $account_page_options['display_on_account_page'];
                if (isset($account_page_options['enquiry_btn_text']))		        $button_text    	    = $account_page_options['enquiry_btn_text'];
                if (isset($account_page_options['message_body']))		            $message_body    	    = $account_page_options['message_body'];
            }

            $btn_class  = $hide_btn_des == 'on' ? 'mmwea-for-mob' : '';
            $btn_target = $new_tab == 'on' ? 'target="_blank"' : '';

            $msg_data = array();

            $button_url = "https://wa.me/".$whatsapp_number."/?text={{msg_body}}";

            $wh_image_url = MMWEA_PLUGIN_URL . '/assets/image/whatsapp_phone_icon.svg';

            $wa_btn_html = sprintf('<div class="mmwea-button-box"><a href="%s" id="mmwea-checkout-btn" class="mmwea-wa-button button btn %s" %s ><img src="%s" width="50" height="50" ><span>%s</span></a></div>', $button_url, $btn_class, $btn_target, $wh_image_url, __($button_text));
            
            $first_name = $last_name = $full_name = $company_name = $cus_address = $cus_email = $cus_phone = $product_name = $product_price = $product_quantity = $product_total = $shipping_first_name = $shipping_last_name = $shipping_full_name = $shipping_company_name = $shipping_address = "";

            $currency_code      = $order->get_currency();
            $currency_symbol    = get_woocommerce_currency_symbol( $currency_code );
            
            $first_name     = $order->get_billing_first_name();
            $last_name      = $order->get_billing_last_name();
            $full_name      = $order->get_formatted_billing_full_name();
            $company_name   = $order->get_billing_company();
            $cus_address    = $order->get_billing_address_1()." ".$order->get_billing_address_2()." ".$order->get_billing_city()." ".$order->get_billing_postcode()." ".$order->get_billing_state()." ".$order->get_billing_country();
            $cus_email      = $order->get_billing_email();
            $cus_phone      = $order->get_billing_phone();

            $cus_address = str_replace('<br/>',' ',$cus_address);

            $bill_old_val =  array("{{bill_first_name}}", "{{bill_last_name}}", "{{bill_full_name}}", "{{bill_company_name}}","{{bill_address}}", "{{bill_email}}", "{{bill_phone}}");
            $bill_new_val =  array($first_name, $last_name, $full_name, $company_name,$cus_address,$cus_email,$cus_phone);
            $message_body = str_replace($bill_old_val, $bill_new_val, $message_body);
        
            $shipping_first_name    = $order->get_shipping_first_name();
            $shipping_last_name     = $order->get_shipping_last_name();
            $shipping_full_name     = $order->get_formatted_shipping_full_name();  
            $shipping_company_name  = $order->get_shipping_company();
            $shipping_address       = $order->get_shipping_address_1()." ".$order->get_shipping_address_2()." ".$order->get_shipping_city()." ".$order->get_shipping_postcode()." ".$order->get_shipping_state()." ".$order->get_shipping_country();

            $msg_data = explode('<br />', nl2br($message_body));

            $i = 0;
            $replce_val = array("{{shipping_first_name}}", "{{shipping_last_name}}", "{{shipping_full_name}}", "{{shipping_company_name}}", "{{shipping_address}}");

            foreach ($msg_data as $key => $msg_value) {
                foreach ($replce_val as $key => $value) {

                    if (strpos($msg_value, $value)) {

                        if ($value == "{{shipping_first_name}}" && isset($shipping_first_name)  && !empty($shipping_first_name)) {
                            $msg_data[$i] = str_replace($value, $shipping_first_name, $msg_value);
                        } elseif ($value == "{{shipping_last_name}}" && isset($shipping_last_name)  && !empty($shipping_last_name)) {
                            $msg_data[$i] = str_replace($value, $shipping_last_name, $msg_value);
                        } elseif ($value == "{{shipping_full_name}}" && isset($shipping_full_name)  && !empty(trim($shipping_full_name))) {
                            $msg_data[$i] = str_replace($value, $shipping_full_name, $msg_value);
                        } elseif ($value == "{{shipping_company_name}}" && isset($shipping_company_name) && !empty($shipping_company_name)) {
                            $msg_data[$i] = str_replace($value, $shipping_company_name, $msg_value);
                        } elseif ($value == "{{shipping_address}}" && isset($shipping_address) && !empty(trim($shipping_address)) ) {
                            $msg_data[$i] = str_replace($value, $shipping_address, $msg_value);
                        }  else {
                            unset($msg_data[$i]);
                            unset($msg_data[$i + 1]);
                        }
                    }
                }
                $i++;
            }

            $message_body =  implode("", $msg_data);

            $product_info = array();
            $pro_msg_data = "*Product Title* :- {{product_name}} <br />*Product Price* :- {{product_price}} <br />*Product Quantity* :- {{product_quantity}} <br />*Total* :- {{product_total}} <br />";
            foreach ( $order->get_items() as $item_id => $item ) { 
                
                
                $product_id         = $item->get_product_id();
                $product            = $item->get_product();

                if(isset($product) && !empty($product)){
                    $product_name       = $item->get_name();
                    $product_price      = $currency_symbol."".$product->get_price();
                    $product_quantity   = $item->get_quantity();              
                    $product_total      = $currency_symbol."".$item->get_total();    
                    
                    $old_val =  array("{{product_name}}", "{{product_price}}", "{{product_quantity}}", "{{product_total}}");
                    $new_val =  array($product_name, $product_price, $product_quantity, $product_total);    
                    $product_info[] = str_replace($old_val, $new_val, $pro_msg_data);
                }
            }
            if(isset($product_info) && !empty($product_info)){

                $product_info_data = implode("<br />", $product_info);
                $order_date = wc_format_datetime($order->get_date_created());
                $order_total = $currency_symbol."".$order->get_total();
                $payment_method = $order->get_payment_method_title();
                $order_status = wc_get_order_status_name($order->get_status());
                
                $order_msg_data = "*Order Number* :- {{order_number}} <br />*Date* :- {{order_date}} <br />*Status* :- {{order_status}} <br />*Total* :- {{order_total}} <br />*Payment Method* :- {{payment_method}} <br />";
                $order_old_val = array("{{order_number}}","{{order_date}}","{{order_status}}","{{order_total}}","{{payment_method}}");
                $order_new_val = array($order_id,$order_date,$order_status,$order_total,$payment_method);
                $order_info_data = str_replace($order_old_val,$order_new_val,$order_msg_data);

                $old_product_val =  array("{{product_info}}","{{order_info}}");
                $new_product_val =  array($product_info_data,$order_info_data);

                $msg_data = str_replace($old_product_val, $new_product_val, $message_body);

                $msg_body = preg_replace('#<br\s*/?>#i', "%0A", $msg_data);

                $currency_entity  = urlencode($currency_symbol);

                $entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D', $currency_entity);
                $replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", ",", "/", "?", "%", "#", "[", "]", $currency_symbol);    
                
                $endocde_msg_body = str_replace( $entities, $replacements, urlencode($msg_body) );

                $wa_btn_html = str_replace('{{msg_body}}',$endocde_msg_body,$wa_btn_html);
                
                return $wa_btn_html;
            }
        }
    }
}

/**
 * Add button to my account page
 */
add_action('wp','mmwea_account_page_front_setting',10);
function mmwea_account_page_front_setting(){
    if(mmwea_is_my_account_page() && is_account_page()) {
        add_filter( 'woocommerce_account_orders_columns', 'mmwea_add_account_orders_column', 10, 1 );
        function mmwea_add_account_orders_column( $columns ) {        
            $columns['order-whatsapp'] = __( 'Whatsapp', 'woocommerce' );
            return $columns;
        }

        add_action('woocommerce_my_account_my_orders_column_order-whatsapp','mmwea_my_account_my_orders_column');
        function mmwea_my_account_my_orders_column($order) {
            if(!$order) {
                return;
            }

            $order_id = $order->get_id();
            _e(do_shortcode('[mmwea_my_account_wh_btn order_id="'.$order_id.'"]'));
        }
    }
}