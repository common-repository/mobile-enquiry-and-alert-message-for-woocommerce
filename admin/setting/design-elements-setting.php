<?php
if (!class_exists('mmwea_design_elements_settings')) {
    $mmwea_des_ele_options = get_option('mmwea_desgin_elements_options');

    class mmwea_design_elements_settings{
        public function __construct(){       
            add_action('admin_init', array($this, 'design_elements_register_settings_init'));
        }

        function general_setting_customize_callback(){ ?>

            <form action="options.php?tab=mmwea-desgin-elements-setting" method="post">
                <?php  settings_fields('mmwea-loop-setting-options');   ?>

                <div class="mmwea-section">
                    <?php do_settings_sections('mmwea_customize_whatsapp_btn_section'); ?>
                </div>

                <?php               
                submit_button('Save Settings');
                ?>
            </form>
            <?php
        }

        /* register setting */
        public function design_elements_register_settings_init()
        {
            register_setting('mmwea-loop-setting-options', 'mmwea_desgin_elements_options', array($this, 'sanitize_settings'));


            /** Customize Add to Cart Button section start */
            add_settings_section(
                'mmwea_shop_page_layout_setting',
                __('Customize Whatsapp Button', 'mobile-message-for-woocommerce-enquiries-and-alerts'),
                array(),
                'mmwea_customize_whatsapp_btn_section'
            );

            add_settings_field(
                'enquiry_button_color',
                __('Button Color', 'mobile-message-for-woocommerce-enquiries-and-alerts'),
                array($this, 'select_color_html'),
                'mmwea_customize_whatsapp_btn_section',
                'mmwea_shop_page_layout_setting',
                [
                    'label_for'     => 'whatsapp_btn_color',
                    'description'   => 'Change "Connect WhatsApp" button background color.'
                ]
            );

            add_settings_field(
                'enquiry_button_font_color',
                __('Button Font Color', 'mobile-message-for-woocommerce-enquiries-and-alerts'),
                array($this, 'select_color_html'),
                'mmwea_customize_whatsapp_btn_section',
                'mmwea_shop_page_layout_setting',
                [
                    'label_for'     => 'whatsapp_btn_font_color',
                    'description'   => 'Change "Connect WhatsApp" button font color.'
                ]
            );

            add_settings_field(
                'enquiry_button_hover_color',
                __('Button Hover Color', 'mobile-message-for-woocommerce-enquiries-and-alerts'),
                array($this, 'select_color_html'),
                'mmwea_customize_whatsapp_btn_section',
                'mmwea_shop_page_layout_setting',
                [
                    'label_for'     => 'whatsapp_btn_hover_color',
                    'description'   => 'Change "Connect WhatsApp" button hover background color.'
                ]
            );

            add_settings_field(
                'enquiry_button_font_hover_color',
                __('Button Font Hover Color', 'mobile-message-for-woocommerce-enquiries-and-alerts'),
                array($this, 'select_color_html'),
                'mmwea_customize_whatsapp_btn_section',
                'mmwea_shop_page_layout_setting',
                [
                    'label_for'     => 'whatsapp_btn_font_hover_color',
                    'description'   => 'Change "Connect WhatsApp" button font hover color.'
                ]
            );

            add_settings_field(
                'enquiry_button_font_size',
                __('Font Size', 'mobile-message-for-woocommerce-enquiries-and-alerts'),
                array($this, 'layout_number_html'),
                'mmwea_customize_whatsapp_btn_section',
                'mmwea_shop_page_layout_setting',
                [
                    'label_for'     => 'whatsapp_btn_font_size',
                    'description'   => 'Change "Connect WhatsApp" button font size.'
                ]
            );

            add_settings_field(
                'enquiry_button_font_weight',
                __('Font Weight', 'mobile-message-for-woocommerce-enquiries-and-alerts'),
                array($this, 'font_weight_html'),
                'mmwea_customize_whatsapp_btn_section',
                'mmwea_shop_page_layout_setting',
                [
                    'label_for'     => 'whatsapp_btn_font_weight',
                    'description'   => 'Change "Connect WhatsApp" button font weight.'
                ]
            );

            add_settings_field(
                'enquiry_button_border_radius',
                __('Border Radius', 'mobile-message-for-woocommerce-enquiries-and-alerts'),
                array($this, 'layout_html'),
                'mmwea_customize_whatsapp_btn_section',
                'mmwea_shop_page_layout_setting',
                [
                    'label_for'     => 'whatsapp_btn_border_radius',
                    'description'   => 'Change "Connect WhatsApp" button border radius.'
                ]
            );

            add_settings_field(
                'enquiry_button_padding',
                __('Padding', 'mobile-message-for-woocommerce-enquiries-and-alerts'),
                array($this, 'layout_html'),
                'mmwea_customize_whatsapp_btn_section',
                'mmwea_shop_page_layout_setting',
                [
                    'label_for'     => 'whatsapp_btn_padding',
                    'description'   => 'Change "Connect WhatsApp" button padding.'
                ]
            );

            /** Customize Add to Cart Button section end */
        }

        public function select_color_html($args){
            global $mmwea_des_ele_options;
            $value = isset($mmwea_des_ele_options[$args['label_for']]) ? $mmwea_des_ele_options[$args['label_for']] : '';
            ?>
            <input type="text" class="mmwea_colorpicker" name="mmwea_desgin_elements_options[<?php esc_attr_e( $args['label_for'] ); ?>]" id="<?php esc_attr_e( $args['label_for'] ); ?>" value="<?php esc_attr_e($value); ?>" >
            <p class="mmwea-input-note"><?php _e($args['description'],'mobile-message-for-woocommerce-enquiries-and-alerts-pro') ?></p>
            <?php
        }

        public function layout_number_html($args){
            global $mmwea_des_ele_options;

            $value = isset($mmwea_des_ele_options[$args['label_for']]) ? $mmwea_des_ele_options[$args['label_for']] : '';


            ?>
            <input type="number" name="mmwea_desgin_elements_options[<?php esc_attr_e( $args['label_for'] ); ?>]" id="<?php esc_attr_e( $args['label_for'] ); ?>" value="<?php esc_attr_e($value); ?>">
            <p class="mmwea-input-note"><?php _e($args['description'],'mobile-message-for-woocommerce-enquiries-and-alerts-pro') ?></p>
            <?php
        }

        public function layout_html($args){
            global $mmwea_des_ele_options;

            $select_style_val = array();
            if(isset($mmwea_des_ele_options[$args['label_for']]) && !empty($mmwea_des_ele_options[$args['label_for']])){
                $select_style_val = explode(",",$mmwea_des_ele_options[$args['label_for']] );
            }

            $top_value = isset($select_style_val[0]) ? $select_style_val[0] : '0';
            $right_value = isset($select_style_val[1]) ? $select_style_val[1] : '0';
            $bottom_value = isset($select_style_val[2]) ? $select_style_val[2] : '0';
            $left_value = isset($select_style_val[3]) ? $select_style_val[3] : '0';


            ?>
            <input type="number" class="mmwea-mini-input" name="mmwea_desgin_elements_options[<?php esc_attr_e( $args['label_for'] ); ?>]"  value="<?php esc_attr_e($top_value); ?>">
            <input type="number" class="mmwea-mini-input" name="mmwea_desgin_elements_options[<?php esc_attr_e( $args['label_for'] ); ?>]"  value="<?php esc_attr_e($right_value); ?>">
            <input type="number" class="mmwea-mini-input" name="mmwea_desgin_elements_options[<?php esc_attr_e( $args['label_for'] ); ?>]"  value="<?php esc_attr_e($bottom_value); ?>">
            <input type="number" class="mmwea-mini-input" name="mmwea_desgin_elements_options[<?php esc_attr_e( $args['label_for'] ); ?>]"  value="<?php esc_attr_e($left_value); ?>">
            <p class="mmwea-input-note"><?php _e($args['description'],'mobile-message-for-woocommerce-enquiries-and-alerts-pro') ?></p>
            <?php
        }

        public function font_weight_html($args){
            global $mmwea_des_ele_options;
            $value = isset($mmwea_des_ele_options[$args['label_for']]) ? $mmwea_des_ele_options[$args['label_for']] : '';
            ?>
            <input type="number" name="mmwea_desgin_elements_options[<?php esc_attr_e( $args['label_for'] ); ?>]" id="<?php esc_attr_e( $args['label_for'] ); ?>" min="100" max="700" step="100" value="<?php esc_attr_e($value); ?>">
            <p class="mmwea-input-note"><?php _e($args['description'],'mobile-message-for-woocommerce-enquiries-and-alerts-pro') ?></p>
            <?php
        }

        public function sanitize_settings($input)
        {
            $new_input = array();

            if (isset($input['whatsapp_btn_color'])) {
                $new_input['whatsapp_btn_color'] = sanitize_text_field($input['whatsapp_btn_color']);
            }

            if (isset($input['whatsapp_btn_font_color'])) {
                $new_input['whatsapp_btn_font_color'] = sanitize_text_field($input['whatsapp_btn_font_color']);
            }

            if (isset($input['whatsapp_btn_hover_color'])) {
                $new_input['whatsapp_btn_hover_color'] = sanitize_text_field($input['whatsapp_btn_hover_color']);
            }
            
            if (isset($input['whatsapp_btn_font_hover_color'])) {                
                $new_input['whatsapp_btn_font_hover_color'] = sanitize_text_field($input['whatsapp_btn_font_hover_color']);
            }

            if (isset($input['whatsapp_btn_font_size']) && !empty($input['whatsapp_btn_font_size'])) {
                $new_input['whatsapp_btn_font_size'] = sanitize_text_field($input['whatsapp_btn_font_size']);
            }

            if (isset($input['whatsapp_btn_font_weight']) && !empty($input['whatsapp_btn_font_weight'])) {
                $new_input['whatsapp_btn_font_weight'] = sanitize_text_field($input['whatsapp_btn_font_weight']);
            }


            if (isset($input['whatsapp_btn_border_radius']) && !empty($input['whatsapp_btn_border_radius'])) {                
                $whatsapp_btn_border_radius = implode(",",$input['whatsapp_btn_border_radius']);
                $new_input['whatsapp_btn_border_radius'] = sanitize_text_field($whatsapp_btn_border_radius);
            }

            if (isset($input['whatsapp_btn_padding']) && !empty($input['whatsapp_btn_padding'])) {
                $whatsapp_btn_padding = implode(",",$input['whatsapp_btn_padding']);
                $new_input['whatsapp_btn_padding'] = sanitize_text_field($whatsapp_btn_padding);
            }
            
            return $new_input;
        }
    }

}