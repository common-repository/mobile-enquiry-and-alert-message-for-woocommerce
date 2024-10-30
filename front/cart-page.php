<?php 
/**
 * Defines if cart page
 */
function mmwea_is_cart_page() {

    $general_settings_options   = get_option('mmwea_general_settings_options');
    $cart_page_options        = get_option('mmwea_product_cart_page_options');
    $whatsapp_number = $hide_btn_des = $new_tab = $display_btn = $button_text = $btn_position_hook = $msg_body = $message_body = $hide_checkout_btn = $body_header = $body_footer = $enable_user_role = $user_role_option = $login_user_role = "";
    $user_role_wise = 1;

    $currency_symbol = get_woocommerce_currency_symbol();
    
    if(isset($general_settings_options) && !empty($general_settings_options)){
        if (isset($general_settings_options['whatsapp_number']))		$whatsapp_number    = $general_settings_options['whatsapp_number'];
        if (isset($general_settings_options['enable_user_role']))		$enable_user_role   = $general_settings_options['enable_user_role'];
        if (isset($general_settings_options['user_role_option']))		$user_role_option   = $general_settings_options['user_role_option'];
        if (isset($general_settings_options['login_user_role']))		$login_user_role    = $general_settings_options['login_user_role'];
    }

    if(isset($cart_page_options) && !empty($cart_page_options)){
        if (isset($cart_page_options['display_on_cart_page']))		$display_btn    	    = $cart_page_options['display_on_cart_page'];
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

    if($display_btn == "on" && $user_role_wise == 0 && !empty($whatsapp_number)){
        return true;
    }
    
    return false;
}

/**
 * Shortcode for cart page button
 */
add_shortcode('mmwea_cart_wh_btn','mmwea_cart_shortcode',10);
function mmwea_cart_shortcode() {
    if( mmwea_is_cart_page() ) {
        $general_settings_options   = get_option('mmwea_general_settings_options');
        $cart_page_options        = get_option('mmwea_product_cart_page_options');
        $whatsapp_number = $hide_btn_des = $new_tab = $display_btn = $button_text = $btn_position_hook = $msg_body = $message_body = $hide_checkout_btn = $body_header = $body_footer = $enable_user_role = $user_role_option = $login_user_role = "";
        $user_role_wise = 1;

        $currency_symbol = get_woocommerce_currency_symbol();
        
        if(isset($general_settings_options) && !empty($general_settings_options)){
            if (isset($general_settings_options['whatsapp_number']))		$whatsapp_number    = $general_settings_options['whatsapp_number'];
            if (isset($general_settings_options['hide_wa_btn_desktop']))    $hide_btn_des    	= $general_settings_options['hide_wa_btn_desktop'];
            if (isset($general_settings_options['open_link_new_tab']))		$new_tab    	    = $general_settings_options['open_link_new_tab'];
            if (isset($general_settings_options['enable_user_role']))		$enable_user_role   = $general_settings_options['enable_user_role'];
            if (isset($general_settings_options['user_role_option']))		$user_role_option   = $general_settings_options['user_role_option'];
            if (isset($general_settings_options['login_user_role']))		$login_user_role    = $general_settings_options['login_user_role'];
        }

        if(isset($cart_page_options) && !empty($cart_page_options)){
            if (isset($cart_page_options['display_on_cart_page']))		$display_btn    	    = $cart_page_options['display_on_cart_page'];
            if (isset($cart_page_options['enquiry_btn_text']))		    $button_text    	    = $cart_page_options['enquiry_btn_text'];
            if (isset($cart_page_options['hide_checkout_btn']))		    $hide_checkout_btn    	= $cart_page_options['hide_checkout_btn'];
            if (isset($cart_page_options['btn_position_hook']))		    $btn_position_hook    	= $cart_page_options['btn_position_hook'];
            if (isset($cart_page_options['body_header']))		        $body_header    	    = $cart_page_options['body_header'];
            if (isset($cart_page_options['message_body']))		        $message_body    	    = $cart_page_options['message_body'];
            if (isset($cart_page_options['body_footer']))		        $body_footer    	    = $cart_page_options['body_footer'];
        }

        $btn_class  = $hide_btn_des == 'on' ? 'mmwea-for-mob' : '';
        $btn_target = $new_tab == 'on' ? 'target="_blank"' : '';

        $msg_data = array();

        global $woocommerce;
        $items = $woocommerce->cart->get_cart();

        $currency_entity = urlencode($currency_symbol);
        $entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D', $currency_entity);
        $replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", ",", "/", "?", "%", "#", "[", "]", $currency_symbol);

        if(!empty($items) && isset($items)){

            foreach ($items as $item => $values) {
                $_product =  wc_get_product($values['data']->get_id());

                $product_name = $_product->get_title();
                $product_price = get_post_meta($values['product_id'], '_price', true);
                $product_quantity   =  $values['quantity'];
                $product_url    = get_permalink($values['product_id']);

                $old_val =  array("{{product_name}}", "{{product_price}}", "{{product_quantity}}", "{{product_url}}");
                $new_val =  array($product_name, $currency_symbol . $product_price, $product_quantity, $product_url);
                $updated_val = str_replace($old_val, $new_val, $message_body);

                $msg_data[] = str_replace($entities, $replacements, urlencode($updated_val) . "%0A----------------------%0A");
            }
        }

        $msg_data = implode("<br />", $msg_data);
        
        $body_header    = preg_replace('#<br\s*/?>#i', "%0A", nl2br($body_header));
        $body_footer    = preg_replace('#<br\s*/?>#i', "%0A", nl2br($body_footer));
        $msg_body       = preg_replace('#<br\s*/?>#i', "%0A", nl2br($msg_data));

        $msg_body = $body_header."%0A%0A".$msg_body."%0A%0A".$body_footer;
        $wh_image_url    = MMWEA_PLUGIN_URL . '/assets/image/whatsapp_phone_icon.svg';
        $button_url = "https://wa.me/".$whatsapp_number."/?text=".$msg_body;

        $wa_btn_html = sprintf('<div class="mmwea-button-box"><a href="%s" id="wa-order-button-click" class="mmwea-wa-button button btn %s" %s ><img src="%s" width="50" height="50" ><span>%s</span></a></div>', $button_url, $btn_class, $btn_target, $wh_image_url, __($button_text));

        return $wa_btn_html;
    }
}

/**
 * Add button to cart page
 */
add_action('wp','mmwea_cart_page_front_setting',10);
function mmwea_cart_page_front_setting() {
    if( mmwea_is_cart_page() && is_cart() ) {

        $btn_position_hook = $hide_checkout_btn = "";
        $cart_page_options  = get_option('mmwea_product_cart_page_options');

        if(isset($cart_page_options) && !empty($cart_page_options)){
            if (isset($cart_page_options['btn_position_hook']))		    $btn_position_hook    	= $cart_page_options['btn_position_hook'];
            if (isset($cart_page_options['hide_checkout_btn']))		    $hide_checkout_btn    	= $cart_page_options['hide_checkout_btn'];
        }

        if($hide_checkout_btn == "on")    remove_action( 'woocommerce_proceed_to_checkout','woocommerce_button_proceed_to_checkout', 20);

        if(isset($btn_position_hook) && !empty($btn_position_hook)) {
            add_action($btn_position_hook, function() {
                _e(do_shortcode('[mmwea_cart_wh_btn]'));
            });
        }
    }
}