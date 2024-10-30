<?php 
/**
 * Defines if single product page
 */
function mmwea_is_single_product_page() {
    
    $general_settings_options   = get_option('mmwea_general_settings_options');
    $single_page_options        = get_option('mmwea_product_single_page_options');

    $whatsapp_number = $hide_btn_des = $new_tab = $button_html = $display_btn = $hide_cart_button = $button_text = $button_position = $enable_product_wise = $select_pro_category_list = $enable_category_wise = $product_variations = $enable_user_role = $user_role_option = $login_user_role = "";
    
    $select_pro_category_list = $select_product_list = array();
    $user_role_wise = 1;

    $currency_symbol = get_woocommerce_currency_symbol();

    if(isset($general_settings_options) && !empty($general_settings_options)){    
        if (isset($general_settings_options['whatsapp_number']))		$whatsapp_number    = $general_settings_options['whatsapp_number'];
        if (isset($general_settings_options['enable_user_role']))		$enable_user_role   = $general_settings_options['enable_user_role'];
        if (isset($general_settings_options['user_role_option']))		$user_role_option   = $general_settings_options['user_role_option'];
        if (isset($general_settings_options['login_user_role']))		$login_user_role    = $general_settings_options['login_user_role'];
    }

    if(isset($single_page_options) && !empty($single_page_options)){    
        if (isset($single_page_options['display_on_single_page']))		$display_btn    	    = $single_page_options['display_on_single_page'];
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
 * Shortcode for Single Product Page button
 */
add_shortcode('mmwea_single_product_wh_btn','mmwea_single_product_shortcode',10);
function mmwea_single_product_shortcode($atts) {
    $atts = shortcode_atts( array( 'product_id' => '', ), $atts );

    if( mmwea_is_single_product_page() ) {
        $general_settings_options   = get_option('mmwea_general_settings_options');
        $single_page_options        = get_option('mmwea_product_single_page_options');
    
        $whatsapp_number = $hide_btn_des = $new_tab = $button_html = $display_btn = $hide_cart_button = $button_text = $button_position = $enable_product_wise = $select_pro_category_list = $enable_category_wise = $product_variations = $enable_user_role = $user_role_option = $login_user_role = "";
        
        $select_pro_category_list = $select_product_list = array();
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
    
        if(isset($single_page_options) && !empty($single_page_options)){
    
            if (isset($single_page_options['display_on_single_page']))		$display_btn    	    = $single_page_options['display_on_single_page'];
            if (isset($single_page_options['hide_cart_btn']))		        $hide_cart_button    	= $single_page_options['hide_cart_btn'];
            if (isset($single_page_options['enquiry_btn_text']))		    $button_text    	    = $single_page_options['enquiry_btn_text'];
            if (isset($single_page_options['btn_position_hook']))		    $button_position    	= $single_page_options['btn_position_hook'];
            if (isset($single_page_options['message_body']))		        $send_data    	        = $single_page_options['message_body'];

            if (isset($single_page_options['enable_product_wise']))		    $enable_product_wise        = $single_page_options['enable_product_wise'];
            if (isset($single_page_options['select_product_list']))		    $select_product_list        = explode(",",$single_page_options['select_product_list']);
            if (isset($single_page_options['enable_category_wise']))		$enable_category_wise       = $single_page_options['enable_category_wise'];
            if (isset($single_page_options['select_pro_category_list']))    $select_pro_category_list   = explode(",",$single_page_options['select_pro_category_list']);
        }
        
        
        $product_id = (isset($atts['product_id']) && !empty($atts['product_id'])) ? $atts['product_id'] : get_the_ID();
        if(isset($product_id) && !empty($product_id)) {
            $product    = wc_get_product( $product_id );
            $curr_pro_cat = get_the_terms ( $product_id, 'product_cat' );
            
            if($enable_product_wise == "on" ){

                if (!in_array($product_id, $select_product_list) && isset($select_product_list) && !empty($select_product_list)){
                    return false;
                }
            }
            
            $j = 0;
            if($enable_category_wise == "on" ){
                if(isset($curr_pro_cat) && !empty($curr_pro_cat) && !empty($select_pro_category_list) && isset($select_pro_category_list) ){
                    foreach ( $curr_pro_cat as $category ) {
                        $cat_id = $category->term_id;   
                        if (in_array($cat_id, $select_pro_category_list)){
                        $j = 1;
                        }
                    }

                    if($j == 0){
                        return false;
                    }
                } 
            }               

            $btn_class  = $hide_btn_des == 'on' ? 'mmwea-for-mob' : '';
            $btn_target = $new_tab == 'on' ? 'target="_blank"' : '';    

            $product_name   = $product->get_name();
            $product_type   = $product->get_type();
            $product_price  = $product->get_price();
            $product_sku    = $product->get_sku();
            $product_url    = get_permalink($product_id);

            $cart_btn_classs = "single_add_to_cart_button button alt product_type_".$product_type;

            if($product_type == "variable"){
                $product_variations = "{{product_variations}}";
            }

            $msg_data = explode('<br />', nl2br($send_data));

            $i = 0;
            $replce_val = array("{{product_name}}", "{{product_price}}", "{{product_url}}", "{{product_sku}}", "{{product_type}}", "{{product_variations}}");
            

            foreach ($msg_data as $key => $msg_value) {

                foreach ($replce_val as $key => $value) {

                    if (strpos($msg_value, $value)) {

                        if ($value == "{{product_name}}" && isset($product_name)  && !empty($product_name)) {

                            $msg_data[$i] = str_replace($value, $product_name, $msg_value);
                        } elseif ($value == "{{product_price}}" && isset($product_price)  && !empty($product_price)) {
                            
                            $msg_data[$i] = str_replace($value, $currency_symbol . $product_price, $msg_value);
                        } elseif ($value == "{{product_url}}" && isset($product_url)  && !empty($product_url)) {

                            $msg_data[$i] = str_replace($value, $product_url, $msg_value);
                        } elseif ($value == "{{product_sku}}" && isset($product_sku) && !empty($product_sku)) {

                            $msg_data[$i] = str_replace($value, $product_sku, $msg_value);
                        } elseif ($value == "{{product_variations}}" && isset($product_variations) && !empty($product_variations) ) {

                            $msg_data[$i] = str_replace($value, $product_variations, $msg_value);
                        } elseif ($value == "{{product_type}}" && isset($product_type) && !empty($product_type)) {

                            $msg_data[$i] = str_replace($value, $product_type, $msg_value);
                        } else {
                            unset($msg_data[$i]);
                            unset($msg_data[$i + 1]);
                        }
                    }
                }
                $i++;
            }

            $msg_body =  implode("", $msg_data);

            $currency_entity = urlencode($currency_symbol);
            $entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D', $currency_entity);
            $replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", ",", "/", "?", "%", "#", "[", "]", $currency_symbol);

            $endocde_msg_body = str_replace( $entities, $replacements, urlencode($msg_body) );

            $button_url = "https://wa.me/".$whatsapp_number."/?text=".$endocde_msg_body;

            $wh_image_url = MMWEA_PLUGIN_URL . '/assets/image/whatsapp_phone_icon.svg';            
            $wa_btn_html = sprintf('<div class="mmwea-button-box"><a href="%s" id="wa-order-button-click" class="mmwea-wa-button %s %s" %s ><img src="%s" width="50" height="50" ><span>%s</span></a></div>', $button_url, $btn_class, $cart_btn_classs, $btn_target, $wh_image_url, __($button_text));

            return $wa_btn_html;
        }
    }
    return false;
}

/**
 * Add button to single product page
 */
add_action('wp','mmwea_single_product_page_front',10);
function mmwea_single_product_page_front() {

    if( mmwea_is_single_product_page() && is_singular('product') ) {

        $button_position = $hide_cart_button = "";
        $single_page_options  = get_option('mmwea_product_single_page_options');

        if(isset($single_page_options) && !empty($single_page_options)) {
            if (isset($single_page_options['btn_position_hook']))		    $button_position    	= $single_page_options['btn_position_hook'];
            if (isset($single_page_options['hide_cart_btn']))		        $hide_cart_button    	= $single_page_options['hide_cart_btn'];
        }

        if($hide_cart_button == "on"){

            function mmwea_filter_woocommerce_post_class( $classes, $product ) {
                if ( ! is_product() ) return $classes;
                
                $classes[] = 'mmwea-hide-cart-btn';
                
                return $classes;
            }
            add_filter( 'woocommerce_post_class', 'mmwea_filter_woocommerce_post_class', 10, 2 );
        }

        if($button_position == 'woocommerce_after_single_product_summary'){
            add_action( $button_position, function(){
                _e(do_shortcode('[mmwea_single_product_wh_btn]'));
            },8);

        }elseif($button_position == 'woocommerce_single_product_summary'){
            add_action( $button_position, function(){
                _e(do_shortcode('[mmwea_single_product_wh_btn]'));
            },4);

        }elseif($button_position == 'woocommerce_after_product_title'){

            add_action( 'woocommerce_single_product_summary',function(){
                _e(do_shortcode('[mmwea_single_product_wh_btn]'));
            },5);

        }elseif($button_position == 'woocommerce_after_product_price'){

            add_action( 'woocommerce_single_product_summary' , function(){
                _e(do_shortcode('[mmwea_single_product_wh_btn]'));
            },10);

        }elseif($button_position == 'woocommerce_product_thumbnails'){

            add_action( 'woocommerce_after_single_product_summary',function() {
                _e(do_shortcode('[mmwea_single_product_wh_btn]'));
            },5);
            
        }else{
            add_action($button_position, function() {
                _e(do_shortcode('[mmwea_single_product_wh_btn]'));
            },10);
        }
    }
}