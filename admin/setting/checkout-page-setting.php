<?php 
if (!class_exists('mmwea_checkout_page_settings')) {

    $mmwea_product_checkout_page_options = array();
    $mmwea_product_checkout_page_options = get_option('mmwea_product_checkout_page_options');
    class mmwea_checkout_page_settings{

        function general_setting_customize_callback(){          
            ?>
            <form action="options.php?tab=mmwea-checkout-page-setting" method="post" class="mmwea-checkout-setting">
                <?php  
                settings_fields('mmwea-checkout-page-option-group');   
                ?>

                <div class="mmwea-section">
                    <?php 
                    do_settings_sections('checkout-page-setting-page'); 
                    ?>
                </div>

                <?php               
                submit_button('Save Settings');
                ?>
            </form>
            <?php
        }

        public function checkout_page_setting_register_init() {
            register_setting('mmwea-checkout-page-option-group', 'mmwea_product_checkout_page_options', array($this, 'sanitize_settings'));

            add_settings_section(
                'checkout-page-setting-section',
                __('Checkout Page Setting', 'mobile-message-for-woocommerce-enquiries-and-alerts'),
                array(),
                'checkout-page-setting-page'
            );

            add_settings_field(
                'enable-on-product-checkout-page',
                __('Enable on Checkout Page', 'mobile-message-for-woocommerce-enquiries-and-alerts'),
                array($this, 'checkout_page_checkbox_field'),
                'checkout-page-setting-page',
                'checkout-page-setting-section',
                [
                    'label_for'     => 'display_checkout_page',
                    'description'   => 'Enable to show "Connect WhatsApp" button in checkout page.'
                ]
            );

            add_settings_field(
                'btn-text-for-checkout-page',
                __('Button Text', 'mobile-message-for-woocommerce-enquiries-and-alerts'),
                array($this, 'basic_setting_text_field'),
                'checkout-page-setting-page',
                'checkout-page-setting-section',
                [
                    'label_for'     => 'enquiry_btn_text',
                    'description'   => 'Change "Connect WhatsApp" button text.'
                ]
            );

            add_settings_field(
                'btn-position-checkout-page',
                __('Button Position', 'mobile-message-for-woocommerce-enquiries-and-alerts'),
                array($this, 'basic_setting_btn_position_field'),
                'checkout-page-setting-page',
                'checkout-page-setting-section',
                [
                    'label_for'     => 'btn_position_hook',
                    'description'   => 'Select position where to show button.'
                ]
            );

            add_settings_field(
                'whatsapp-message-body-checkout-page',
                __('Message', 'mobile-message-for-woocommerce-enquiries-and-alerts'),
                array($this, 'message_body_field'),
                'checkout-page-setting-page',
                'checkout-page-setting-section',
                [
                    'label_for'     => 'message_body',
                    'description'   => '
                    <h4>Decorative Variables</h4>
                    Bold : *{{product_name}}*
                    <br>
                    Italic : _{{product_name}}_
                    <br>                    
                    Strikethrough :  ~{{product_name}}~'
                ]
            );

            add_settings_field(
                'btn-shortcode-for-checkout-page',
                __('Shortcode (Optional)', 'mobile-message-for-woocommerce-enquiries-and-alerts'),
                array($this, 'basic_setting_shortcode_field'),
                'checkout-page-setting-page',
                'checkout-page-setting-section',
                [
                    'label_for'     => 'enquiry_btn_shortcode',
                    'description'   => 'Use this shortcode to display whatsapp button for checkout.'
                ]
            );

        }

        public function basic_setting_text_field($args){

            global $mmwea_product_checkout_page_options;
            $value = isset($mmwea_product_checkout_page_options[$args['label_for']]) ? $mmwea_product_checkout_page_options[$args['label_for']] : 'WhatsApp Me';
            ?>
            <input type="text" name="mmwea_product_checkout_page_options[<?php esc_attr_e( $args['label_for'] ); ?>]" id="<?php esc_attr_e( $args['label_for'] ); ?>" value="<?php esc_attr_e($value); ?>">
            <p class="mmwea-input-note"><?php _e($args['description'],'mobile-message-for-woocommerce-enquiries-and-alerts') ?></p>
            <?php
        }

        public function basic_setting_shortcode_field($args){
            $value = '[mmwea_checkout_wh_btn]'; ?>
            <input type="text" onfocus="this.select();" readonly="readonly" value="<?php esc_attr_e($value); ?>" class="code mmwea-shortcode">
            <p class="mmwea-input-note"><?php _e($args['description'],'mobile-message-for-woocommerce-enquiries-and-alerts') ?></p>
            <?php
        }

        public function checkout_page_checkbox_field($args){
            global $mmwea_product_checkout_page_options;
            $value = isset($mmwea_product_checkout_page_options[$args['label_for']]) ? $mmwea_product_checkout_page_options[$args['label_for']] : '';
            ?>
            <label class="mmwea-switch">
				<input type="checkbox" class="mmwea-checkbox" name="mmwea_product_checkout_page_options[<?php esc_attr_e( $args['label_for'] ); ?>]" id="<?php esc_attr_e( $args['label_for'] ); ?>" value="on" <?php if($value == "on"){ esc_attr_e('checked'); } ?>>
				<span class="mmwea-slider mmwea-round"></span>
			</label>
            <p class="mmwea-input-note"><?php _e($args['description'],'mobile-message-for-woocommerce-enquiries-and-alerts') ?></p>
            <?php
        }

        public function basic_setting_btn_position_field($args){
            global $mmwea_product_checkout_page_options;
            $value = isset($mmwea_product_checkout_page_options[$args['label_for']]) ? $mmwea_product_checkout_page_options[$args['label_for']] : 'woocommerce_after_checkout_form';
            $hook_list = array(
                'woocommerce_before_checkout_form',
                'woocommerce_checkout_before_customer_details',
                'woocommerce_before_checkout_billing_form',
                'woocommerce_after_checkout_billing_form',
                'woocommerce_before_checkout_shipping_form',
                'woocommerce_after_checkout_shipping_form',
                'woocommerce_before_order_notes',
                'woocommerce_after_order_notes',
                'woocommerce_checkout_after_customer_details',
                'woocommerce_checkout_before_order_review',
                'woocommerce_review_order_before_payment',
                'woocommerce_review_order_before_submit',
                'woocommerce_review_order_after_submit',
                'woocommerce_review_order_after_payment',
                'woocommerce_checkout_after_order_review',
                'woocommerce_after_checkout_form'
            );

            ?>
            <select class="mmwea-select2-multi" name="mmwea_product_checkout_page_options[<?php esc_attr_e( $args['label_for'] ); ?>]"><?php

            $i =1;
            foreach ($hook_list as $key => $hooks) {
                $no_hook = $i.". ".str_replace("_"," ",$hooks);
                ?>
                    <option <?php if($value == $hooks){ esc_attr_e('selected'); } ?> value="<?php esc_attr_e($hooks) ?>"><?php esc_html_e($no_hook) ?></option>
                <?php
                $i++;
            }
            ?>
            </select>
            <p class="mmwea-input-note"><?php _e($args['description'],'mobile-message-for-woocommerce-enquiries-and-alerts') ?></p>
            <?php
        }

        public function message_body_field($args){
            global $mmwea_product_checkout_page_options;
            $value = isset($mmwea_product_checkout_page_options[$args['label_for']]) ? $mmwea_product_checkout_page_options[$args['label_for']] : '*Product Title* :- {{product_name}} 

*Product Price* :- {{product_price}}
            
*Product Quantity* :- {{product_quantity}}
            
*Product URL* :- {{product_url}}';

            $body_header = isset($mmwea_product_checkout_page_options['body_header']) ? $mmwea_product_checkout_page_options['body_header'] : 'Hello there, I place this order but before buying need some information.';
            $body_footer = isset($mmwea_product_checkout_page_options['body_footer']) ? $mmwea_product_checkout_page_options['body_footer'] : 'Thank you for giving us your valuable time.';
            ?>
            <div class="mmwea-full-div">        
                <textarea name="mmwea_product_checkout_page_options[body_header]" cols="80" rows="3"><?php esc_html_e($body_header); ?></textarea>
            </div>
            <div class="mmwea-full-div">
                <textarea name="mmwea_product_checkout_page_options[<?php esc_attr_e( $args['label_for'] ); ?>]"  id="" cols="80" rows="10"><?php esc_html_e($value); ?></textarea>
            </div>
            <div class="mmwea-full-div">        
                <textarea name="mmwea_product_checkout_page_options[body_footer]" cols="80" rows="3"><?php esc_html_e($body_footer); ?></textarea>
            </div>
            </div>
            
            <p class="mmwea-input-note"><?php _e($args['description'],'mobile-message-for-woocommerce-enquiries-and-alerts') ?></p>
            <h4>Product Variables</h4>
            <p>Product Title :- {{product_name}}<br>Product Price :- {{product_price}}<br>Product SKU :- {{product_sku}}<br>Product Type :- {{product_type}}<br>Product Variations :- {{product_variations}}<br>Product URL :- {{product_url}}
            </p>
            <?php

        }


        public function sanitize_settings($input){
            $new_input = array();

                if (isset($input['display_checkout_page']) && !empty($input['display_checkout_page'])) {
                    $new_input['display_checkout_page'] = sanitize_text_field($input['display_checkout_page']);
                }
    
                if (isset($input['enquiry_btn_text']) && !empty($input['enquiry_btn_text'])) {
                    $new_input['enquiry_btn_text'] = sanitize_text_field($input['enquiry_btn_text']);
                }

                if (isset($input['btn_position_hook']) && !empty($input['btn_position_hook'])) {
                    $new_input['btn_position_hook'] = sanitize_text_field($input['btn_position_hook']);
                }

                if (isset($input['body_header']) && !empty($input['body_header'])) {
                    $new_input['body_header'] = sanitize_textarea_field($input['body_header']);
                }

                if (isset($input['message_body']) && !empty($input['message_body'])) {
                    $new_input['message_body'] = sanitize_textarea_field($input['message_body']);
                }

                if (isset($input['body_footer']) && !empty($input['body_footer'])) {
                    $new_input['body_footer'] = sanitize_textarea_field($input['body_footer']);
                }
                
            return $new_input;
        }

    }

}