<?php 
if (!class_exists('mmwea_single_page_settings')) {
    $mmwea_product_single_page_options = array();
    $mmwea_product_single_page_options = get_option('mmwea_product_single_page_options');

    class mmwea_single_page_settings{
        function general_setting_customize_callback() { ?>
            <form action="options.php?tab=mmwea-product-single-page" class="mmwea-product-single-setting" method="post">
                <?php
                settings_fields('mmwea-single-page-option-group');   
                ?>

                <div class="mmwea-section">
                    <?php 
                    do_settings_sections('single-page-setting-page'); 
                    ?>
                </div>
                <div class="mmwea-section">
                    <?php 
                    do_settings_sections('mmwea_product_setting_input'); 
                    ?>
                </div>

                <?php submit_button('Save Settings'); ?>
            </form>
            <?php
        }

        public function single_page_setting_register_init(){

            register_setting('mmwea-single-page-option-group', 'mmwea_product_single_page_options', array($this, 'sanitize_settings'));

            add_settings_section(
                'single-page-setting-section',
                __('Single Page Setting', 'mobile-message-for-woocommerce-enquiries-and-alerts'),
                array(),
                'single-page-setting-page'
            );

            add_settings_field(
                'enable-on-product-single-page',
                __('Enable on Product Single Page', 'mobile-message-for-woocommerce-enquiries-and-alerts'),
                array($this, 'single_page_checkbox_field'),
                'single-page-setting-page',
                'single-page-setting-section',
                [
                    'label_for'     => 'display_on_single_page',
                    'description'   => 'Enable to show "Connect WhatsApp" button in product details page.'
                ]
            );

            add_settings_field(
                'hide-add-cart-btn-single-page',
                __('Hide Add to Cart Button', 'mobile-message-for-woocommerce-enquiries-and-alerts'),
                array($this, 'single_page_checkbox_field'),
                'single-page-setting-page',
                'single-page-setting-section',
                [
                    'label_for'     => 'hide_cart_btn',
                    'description'   => 'Enable to hide add to cart button in product details page.<br><strong>Note</strong> : If you are hide <strong>Add to Cart</strong> button, then <strong>Add to Cart</strong> button releted position not work.'
                ]
            );

            add_settings_field(
                'btn-text-for-single-page',
                __('Button Text', 'mobile-message-for-woocommerce-enquiries-and-alerts'),
                array($this, 'basic_setting_text_field'),
                'single-page-setting-page',
                'single-page-setting-section',
                [
                    'label_for'     => 'enquiry_btn_text',
                    'description'   => 'Change "Connect WhatsApp" button text.'
                ]
            );

            add_settings_field(
                'btn-position-single-page',
                __('Button Position', 'mobile-message-for-woocommerce-enquiries-and-alerts'),
                array($this, 'basic_setting_btn_position_field'),
                'single-page-setting-page',
                'single-page-setting-section',
                [
                    'label_for'     => 'btn_position_hook',
                    'description'   => 'Select position where to show button.'
                ]
            );

            add_settings_field(
                'whatsapp-message-body-single-page',
                __('Message', 'mobile-message-for-woocommerce-enquiries-and-alerts'),
                array($this, 'message_body_field'),
                'single-page-setting-page',
                'single-page-setting-section',
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
                'btn-shortcode-for-single-page',
                __('Shortcode (Optional)', 'mobile-message-for-woocommerce-enquiries-and-alerts'),
                array($this, 'basic_setting_shortcode_field'),
                'single-page-setting-page',
                'single-page-setting-section',
                [
                    'label_for'     => 'enquiry_btn_shortcode',
                    'description'   => 'Use this shortcode to display whatsapp button for single product. You can avoid product_id if using in single product page.'
                ]
            );

            /** Product and Product Category Wise Sorting Start */
            add_settings_section(
                'mmwea_product_wise_setting',
                __('Product Setting', 'mobile-message-for-woocommerce-enquiries-and-alerts'),
                array(),
                'mmwea_product_setting_input'
            );
            
            add_settings_field(
                'enable_disable_product',
                __('Enable for Specific Products', 'mobile-message-for-woocommerce-enquiries-and-alerts'),
                array($this, 'single_page_checkbox_field'),
                'mmwea_product_setting_input',
                'mmwea_product_wise_setting',
                [
                    'label_for'     => 'enable_product_wise',
                    'description'   => 'Enable to show "Connect WhatsApp" button on specific product.'
                ]
            );
            add_settings_field(
                'product_list',
                __('Select Products', 'mobile-message-for-woocommerce-enquiries-and-alerts'),
                array($this, 'basic_setting_product_list_field'),
                'mmwea_product_setting_input',
                'mmwea_product_wise_setting',
                [
                    'label_for'     => 'select_product_list',
                    'description'   => 'Select products on which you want to show "Connect WhatsApp" button.'
                ]
            );
            add_settings_field(
                'enable_disable_category',
                __('Enable for Specific Categories', 'mobile-message-for-woocommerce-enquiries-and-alerts'),
                array($this, 'single_page_checkbox_field'),
                'mmwea_product_setting_input',
                'mmwea_product_wise_setting',
                [
                    'label_for'     => 'enable_category_wise',
                    'description'   => 'Enable to show "Connect WhatsApp" button on specific category.'
                ]
            );
            add_settings_field(
                'product_category_list',
                __('Select Categories', 'mobile-message-for-woocommerce-enquiries-and-alerts'),
                array($this, 'product_category_list_field'),
                'mmwea_product_setting_input',
                'mmwea_product_wise_setting',
                [
                    'label_for'     => 'select_pro_category_list',
                    'description'   => 'Select category on which you want to show "Connect WhatsApp" button.'
                ]
            );
            /** Product and Product Category Wise Sorting End */
        }

        public function basic_setting_text_field($args) {
            global $mmwea_product_single_page_options;
            $value = isset($mmwea_product_single_page_options[$args['label_for']]) ? $mmwea_product_single_page_options[$args['label_for']] : 'WhatsApp Me';
            ?>
            <input type="text" name="mmwea_product_single_page_options[<?php esc_attr_e( $args['label_for'] ); ?>]" id="<?php esc_attr_e( $args['label_for'] ); ?>" value="<?php esc_attr_e($value); ?>">
            <p class="mmwea-input-note"><?php _e($args['description'],'mobile-message-for-woocommerce-enquiries-and-alerts') ?></p>
            <?php
        }

        public function basic_setting_shortcode_field($args) {
            $value = '[mmwea_single_product_wh_btn product_id="123"]'; ?>
            <input type="text" onfocus="this.select();" readonly="readonly" value="<?php esc_attr_e($value); ?>" class="code mmwea-shortcode">
            <p class="mmwea-input-note"><?php _e($args['description'],'mobile-message-for-woocommerce-enquiries-and-alerts') ?></p>
            <?php
        }


        public function single_page_checkbox_field($args) {
            global $mmwea_product_single_page_options;
            $value = isset($mmwea_product_single_page_options[$args['label_for']]) ? $mmwea_product_single_page_options[$args['label_for']] : '';
            ?>
            <label class="mmwea-switch">
				<input type="checkbox" class="mmwea-checkbox" name="mmwea_product_single_page_options[<?php esc_attr_e( $args['label_for'] ); ?>]" id="<?php esc_attr_e( $args['label_for'] ); ?>" value="on" <?php if($value == "on"){ esc_attr_e('checked'); } ?>>
				<span class="mmwea-slider mmwea-round"></span>
			</label>
            <p class="mmwea-input-note"><?php _e($args['description'],'mobile-message-for-woocommerce-enquiries-and-alerts') ?></p>
            <?php
        }

        public function basic_setting_btn_position_field($args){
            global $mmwea_product_single_page_options;
            $value = isset($mmwea_product_single_page_options[$args['label_for']]) ? $mmwea_product_single_page_options[$args['label_for']] : 'woocommerce_product_meta_start';
            $hook_list=array(
                'woocommerce_before_single_product',
                'woocommerce_before_single_product_summary',
                'woocommerce_single_product_summary',
                'woocommerce_after_product_title',
                'woocommerce_after_product_price',
                'woocommerce_before_add_to_cart_form',
                'woocommerce_before_variations_form',
                'woocommerce_before_single_variation',
                'woocommerce_single_variation',
                'woocommerce_before_add_to_cart_button',
                'woocommerce_after_add_to_cart_button',
                'woocommerce_after_single_variation',
                'woocommerce_after_variations_form',
                'woocommerce_after_add_to_cart_form',
                'woocommerce_product_meta_start',
                'woocommerce_product_meta_end',
                'woocommerce_share',
                'woocommerce_product_thumbnails',
                'woocommerce_after_single_product_summary',
                'woocommerce_after_single_product',
            ); ?>

            <select class="mmwea-select2-multi" name="mmwea_product_single_page_options[<?php esc_attr_e( $args['label_for'] ); ?>]">
                <?php
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
            global $mmwea_product_single_page_options;
            $value = "Hello there, I visit your store. I Like this product and want to buy. Please share more information regarding this product.

*Product Title* :- {{product_name}} 
                        
*Product Price* :- {{product_price}}
                        
*Product SKU* :- {{product_sku}}
                        
*Product Type* :- {{product_type}}
                        
*Product Variations* :- {{product_variations}}

*Product URL* :- {{product_url}}

Thank you for giving us your valuable time.";
            $value = isset($mmwea_product_single_page_options[$args['label_for']]) ? $mmwea_product_single_page_options[$args['label_for']] : $value;
            ?>
            <textarea name="mmwea_product_single_page_options[<?php esc_attr_e( $args['label_for'] ); ?>]"  id="" cols="80" rows="15"><?php esc_attr_e($value); ?></textarea>
                <p class="mmwea-input-note"><?php _e($args['description'],'mobile-message-for-woocommerce-enquiries-and-alerts') ?></p>
                <h4>Product Variables</h4>
                <p>Product Title :- {{product_name}}<br>Product Price :- {{product_price}}<br>Product SKU :- {{product_sku}}<br>Product Type :- {{product_type}}<br>Product Variations :- {{product_variations}}<br>Product URL :- {{product_url}}</p>
            <?php
        }

        public function basic_setting_product_list_field($args){ 
            global $mmwea_product_single_page_options;
            $select_product_ids = array();
            if(isset($mmwea_product_single_page_options[$args['label_for']]) && !empty($mmwea_product_single_page_options[$args['label_for']])){
                $select_product_ids = explode(",",$mmwea_product_single_page_options[$args['label_for']] );
            }
            ?>
            <select class="mmwea-select2-multi-product" multiple="multiple" name="mmwea_product_single_page_options[<?php esc_attr_e( $args['label_for'] ); ?>][]">
                <?php 
                if(!empty($select_product_ids) && isset($select_product_ids)){
                    foreach ($select_product_ids as $key => $product_id) { ?>
                        <option selected="selected" value="<?php echo esc_attr($product_id) ?>"><?php echo esc_html(get_the_title($product_id)); ?></option>
                        <?php
                    }
                }
                ?>        
            </select>
            <p class="mmwea-input-note"><?php _e($args['description'],'mobile-message-for-woocommerce-enquiries-and-alerts') ?></p>
          <?php
        }

        public function product_category_list_field($args){
            global $mmwea_product_single_page_options;
            $select_category_id = array();

            if(isset($mmwea_product_single_page_options[$args['label_for']]) && !empty($mmwea_product_single_page_options[$args['label_for']])){
                $select_category_id = explode(",",$mmwea_product_single_page_options[$args['label_for']] );
            }

            $taxonomies= get_terms( array(
                'taxonomy' => 'product_cat',
                'hide_empty' => false,
                'parent'   => 0
            ) );

            if ( $taxonomies ) { ?>
                <select class="mmwea-select2-multi" multiple="multiple" name="mmwea_product_single_page_options[<?php esc_attr_e( $args['label_for'] ); ?>][]"> 
                    <?php
                    foreach ($taxonomies as $taxonomy) {
                        if (in_array($taxonomy->term_id, $select_category_id)) { ?>
                                <option value="<?php echo esc_attr($taxonomy->term_id); ?>" selected="selected" ><?php echo esc_html($taxonomy->name); ?></option>
                            <?php
                        } else { ?>
                                <option value="<?php echo esc_attr($taxonomy->term_id); ?>"><?php echo esc_html($taxonomy->name); ?></option>
                            <?php
                        }
                        mmwea_get_product_category($taxonomy->term_id, $select_category_id);
                    } ?>
                </select>
                <?php
            } ?>
            <p class="mmwea-input-note"><?php _e($args['description'],'mobile-message-for-woocommerce-enquiries-and-alerts') ?></p>
            <?php
        }

        public function sanitize_settings($input){
            $new_input = array();

            if (isset($input['display_on_single_page']) && !empty($input['display_on_single_page'])) {
                $new_input['display_on_single_page'] = sanitize_text_field($input['display_on_single_page']);
            }

            if (isset($input['hide_cart_btn']) && !empty($input['hide_cart_btn'])) {
                $new_input['hide_cart_btn'] = sanitize_text_field($input['hide_cart_btn']);
            }

            if (isset($input['enquiry_btn_text']) && !empty($input['enquiry_btn_text'])) {
                $new_input['enquiry_btn_text'] = sanitize_text_field($input['enquiry_btn_text']);
            }

            if (isset($input['btn_position_hook']) && !empty($input['btn_position_hook'])) {
                $new_input['btn_position_hook'] = sanitize_text_field($input['btn_position_hook']);
            }

            if (isset($input['message_body']) && !empty($input['message_body'])) {
                $new_input['message_body'] = sanitize_textarea_field($input['message_body']);
            }

            if (isset($input['enable_product_wise']) && !empty($input['enable_product_wise'])) {
                $new_input['enable_product_wise'] = sanitize_text_field($input['enable_product_wise']);
            }

            if (isset($input['select_product_list']) && !empty($input['select_product_list'])) {
                $product_list = implode(",",$input['select_product_list']);
                $new_input['select_product_list'] = sanitize_text_field($product_list);
            }

            if (isset($input['enable_category_wise']) && !empty($input['enable_category_wise'])) {
                $new_input['enable_category_wise'] = sanitize_text_field($input['enable_category_wise']);
            }

            if (isset($input['select_pro_category_list']) && !empty($input['select_pro_category_list'])) {
                $category_list = implode(",",$input['select_pro_category_list']);
                $new_input['select_pro_category_list'] = sanitize_text_field($category_list);
            }
                
            return $new_input;
        }
    }
}