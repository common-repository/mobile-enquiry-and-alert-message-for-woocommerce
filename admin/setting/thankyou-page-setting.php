<?php 
if (!class_exists('mmwea_thankyou_page_settings')) {
    $mmwea_product_thankyou_page_options = array();
    $mmwea_product_thankyou_page_options = get_option('mmwea_product_thankyou_page_options');
    class mmwea_thankyou_page_settings{
        function general_setting_customize_callback() { ?>
            <form action="options.php?tab=mmwea-thankyou-page-setting" method="post" class="mmwea-order-setting">
                <?php
                settings_fields('mmwea-thankyou-page-option-group');   
                ?>

                <div class="mmwea-section">
                    <?php 
                    do_settings_sections('thankyou-page-setting-page'); 
                    ?>
                </div>

                <?php               
                submit_button('Save Settings');
                ?>
            </form>
            <?php
        }

        public function thankyou_page_setting_register_init() {
            register_setting('mmwea-thankyou-page-option-group', 'mmwea_product_thankyou_page_options', array($this, 'sanitize_settings'));

            add_settings_section(
                'thankyou-page-setting-section',
                __('Thankyou Page Setting', 'mobile-message-for-woocommerce-enquiries-and-alerts'),
                array(),
                'thankyou-page-setting-page'
            );

            add_settings_field(
                'enable-on-product-thankyou-page',
                __('Enable on Thankyou Page', 'mobile-message-for-woocommerce-enquiries-and-alerts'),
                array($this, 'thankyou_page_checkbox_field'),
                'thankyou-page-setting-page',
                'thankyou-page-setting-section',
                [
                    'label_for'     => 'display_on_thankyou_page',
                    'description'   => 'Enable to show "Connect WhatsApp" button in order thank you page.'
                ]
            );

            add_settings_field(
                'btn-text-for-thankyou-page',
                __('Button Text', 'mobile-message-for-woocommerce-enquiries-and-alerts'),
                array($this, 'basic_setting_text_field'),
                'thankyou-page-setting-page',
                'thankyou-page-setting-section',
                [
                    'label_for'     => 'enquiry_btn_text',
                    'description'   => 'Change "Connect WhatsApp" button text.'
                ]
            );

            add_settings_field(
                'whatsapp-message-body-thankyou-page',
                __('Message', 'mobile-message-for-woocommerce-enquiries-and-alerts'),
                array($this, 'message_body_field'),
                'thankyou-page-setting-page',
                'thankyou-page-setting-section',
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
                'btn-shortcode-for-thankyou-page',
                __('Shortcode (Optional)', 'mobile-message-for-woocommerce-enquiries-and-alerts'),
                array($this, 'basic_setting_shortcode_field'),
                'thankyou-page-setting-page',
                'thankyou-page-setting-section',
                [
                    'label_for'     => 'enquiry_btn_shortcode',
                    'description'   => 'Use this shortcode to display whatsapp button for woocommerce order.'
                ]
            );
        }

        public function basic_setting_text_field($args){
            global $mmwea_product_thankyou_page_options;
            $value = isset($mmwea_product_thankyou_page_options[$args['label_for']]) ? $mmwea_product_thankyou_page_options[$args['label_for']] : 'WhatsApp Me';
            ?>
            <input type="text" name="mmwea_product_thankyou_page_options[<?php esc_attr_e( $args['label_for'] ); ?>]" id="<?php esc_attr_e( $args['label_for'] ); ?>" value="<?php esc_attr_e($value); ?>">
            <p class="mmwea-input-note"><?php _e($args['description'],'mobile-message-for-woocommerce-enquiries-and-alerts') ?></p>
            <?php
        }

        public function basic_setting_shortcode_field($args){
            $value = '[mmwea_order_wh_btn order_id="123"]'; ?>
            <input type="text" onfocus="this.select();" readonly="readonly" value="<?php esc_attr_e($value); ?>" class="code mmwea-shortcode">
            <p class="mmwea-input-note"><?php _e($args['description'],'mobile-message-for-woocommerce-enquiries-and-alerts') ?></p>
            <?php
        }

        public function thankyou_page_checkbox_field($args){
            global $mmwea_product_thankyou_page_options;
            $value = isset($mmwea_product_thankyou_page_options[$args['label_for']]) ? $mmwea_product_thankyou_page_options[$args['label_for']] : '';
            ?>
            <label class="mmwea-switch">
				<input type="checkbox" class="mmwea-checkbox" name="mmwea_product_thankyou_page_options[<?php esc_attr_e( $args['label_for'] ); ?>]" id="<?php esc_attr_e( $args['label_for'] ); ?>" value="on" <?php if($value == "on"){ esc_attr_e('checked'); } ?>>
				<span class="mmwea-slider mmwea-round"></span>
			</label>
            <p class="mmwea-input-note"><?php _e($args['description'],'mobile-message-for-woocommerce-enquiries-and-alerts') ?></p>
            <?php
        }

        public function message_body_field($args){
            global $mmwea_product_thankyou_page_options;
            $value = isset($mmwea_product_thankyou_page_options[$args['label_for']]) ? $mmwea_product_thankyou_page_options[$args['label_for']] : 'Hello there, I place this order how can i get more information regarding shipment and tracking.

*Billing data*
            
*First Name* :- {{bill_first_name}}
            
*Last Name* :- {{bill_last_name}}
            
*Full Name* :- {{bill_full_name}}
            
*Company Name* :- {{bill_company_name}}
            
*Address* :- {{bill_address}}
            
*Email* :- {{bill_email}}
            
*Phone Number* :- {{bill_phone}}
            
*shipping data*
            
*First Name* :- {{shipping_first_name}}
            
*Last Name* :- {{shipping_last_name}}
            
*Full Name* :- {{shipping_full_name}}
            
*Company Name* :- {{shipping_company_name}}
            
*Address* :- {{shipping_address}}
            
*Product Order Details*
            
{{product_info}}
            
*Order Details*
            
{{order_info}}
            
Thank you for giving us your valuable time.';
            ?>
            <textarea name="mmwea_product_thankyou_page_options[<?php esc_attr_e( $args['label_for'] ); ?>]"  id="" cols="80" rows="15"><?php esc_attr_e($value); ?></textarea>
            <p class="mmwea-input-note"><?php _e($args['description'],'mobile-message-for-woocommerce-enquiries-and-alerts') ?></p>
            <h4>Product Variables</h4>
            <p>Product Title :- {{product_name}}<br>Product Price :- {{product_price}}<br>Product SKU :- {{product_sku}}<br>Product Type :- {{product_type}}<br>Product Variations :- {{product_variations}}<br>Product URL :- {{product_url}}
            </p>
            <?php
        }
        
        public function sanitize_settings($input){
            $new_input = array();
            if (isset($input['display_on_thankyou_page']) && !empty($input['display_on_thankyou_page'])) {
                $new_input['display_on_thankyou_page'] = sanitize_text_field($input['display_on_thankyou_page']);
            }

            if (isset($input['enquiry_btn_text']) && !empty($input['enquiry_btn_text'])) {
                $new_input['enquiry_btn_text'] = sanitize_text_field($input['enquiry_btn_text']);
            }

            if (isset($input['message_body']) && !empty($input['message_body'])) {
                $new_input['message_body'] = sanitize_textarea_field($input['message_body']);
            }                
            return $new_input;
        }
    }

}