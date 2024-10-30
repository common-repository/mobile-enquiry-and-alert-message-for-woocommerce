<?php
if (!class_exists('mmwea_general_settings')) {
    $mmwea_general_settings_options = get_option('mmwea_general_settings_options');
    class mmwea_general_settings{

        public function __construct(){       
            add_action('admin_init', array($this, 'general_setting_register_init'));
        }

        function general_setting_customize_callback(){          
            ?>
            <form action="options.php" class="mmwea-general-setting" method="post">
                <?php  
                settings_fields('mmwea-general-setting-options');   
                ?>

                <div class="mmwea-section">
                    <?php 
                    do_settings_sections('mmwea_basic_setting_input'); 
                    ?>
                </div>              

                <div class="mmwea-section">
                    <?php 
                    do_settings_sections('mmwea_user_role_setting_input'); 
                    ?>
                </div>

                <?php
                submit_button('Save Settings');
                ?>
            </form>
            <?php
        }

        public function general_setting_register_init(){
            
            register_setting('mmwea-general-setting-options', 'mmwea_general_settings_options', array($this, 'sanitize_settings'));

            /** Add to cart button section start */
            add_settings_section(
                'mmwea_basic_setting',
                __('Basic Setting', 'mobile-message-for-woocommerce-enquiries-and-alerts'),
                array(),
                'mmwea_basic_setting_input'
            );
            add_settings_field(
                'whatsapp_number',
                __('Whatsapp Number', 'mobile-message-for-woocommerce-enquiries-and-alerts'),
                array($this, 'basic_setting_text_field'),
                'mmwea_basic_setting_input',
                'mmwea_basic_setting',
                [
                    'label_for'     => 'whatsapp_number',
                    'description'   => 'Add WhatsApp number with country code here like the example given below.',
                    'class'         => 'mmwea-number-validation'
                ]
            );
            add_settings_field(
                'whatsapp_button_desktop',
                __('Hide Button on Desktop', 'mobile-message-for-woocommerce-enquiries-and-alerts'),
                array($this, 'basic_setting_checkbox_field'),
                'mmwea_basic_setting_input',
                'mmwea_basic_setting',
                [
                    'label_for'     => 'hide_wa_btn_desktop',
                    'description'   => 'This will hide WhatsApp button on desktop.'
                ]
            );
            add_settings_field(
                'open_new_tab',
                __('Open in New Tab', 'mobile-message-for-woocommerce-enquiries-and-alerts'),
                array($this, 'basic_setting_checkbox_field'),
                'mmwea_basic_setting_input',
                'mmwea_basic_setting',
                [
                    'label_for'     => 'open_link_new_tab',
                    'description'   => 'Open in New Tab'
                ]
            );

            /** User Role Wise Start */
            add_settings_section(
                'mmwea_user_role_setting',
                __('User Role Setting', 'mobile-message-for-woocommerce-enquiries-and-alerts'),
                array(),
                'mmwea_user_role_setting_input'
            );
            add_settings_field(
                'enable_disable_userrole',
                __('Enable/Disable User Role', 'mobile-message-for-woocommerce-enquiries-and-alerts'),
                array($this, 'basic_setting_checkbox_field'),
                'mmwea_user_role_setting_input',
                'mmwea_user_role_setting',
                [
                    'label_for'     => 'enable_user_role',
                    'description'   => 'Enable to show WhatsApp button user wise.'
                ]
            );
            add_settings_field(
                'user_role_select',
                __('Select User Role', 'mobile-message-for-woocommerce-enquiries-and-alerts'),
                array($this, 'basic_setting_radio_field'),
                'mmwea_user_role_setting_input',
                'mmwea_user_role_setting',
                [
                    'label_for'     => 'user_role_option',
                    'class'     => 'mmwea_user_role_option',
                    'description'   => 'Show WhatsApp button for guests and login user.'
                ]
            );
            global $mmwea_general_settings_options;
            $user_role_value = isset($mmwea_general_settings_options['user_role_option']) ? $mmwea_general_settings_options['user_role_option'] : '';
            $class = "";
            if($user_role_value == "logged-in"){
                $class  = "mmwea-user-role-show";
            }else{
                $class  = "mmwea-user-role-hide";
            }
            add_settings_field(
                'loggin_user_role_select',
                __('Select Logged in User Role', 'mobile-message-for-woocommerce-enquiries-and-alerts'),
                array($this, 'basic_setting_select_field'),
                'mmwea_user_role_setting_input',
                'mmwea_user_role_setting',
                [
                    'label_for'     => 'login_user_role',
                    'description'   => 'Show WhatsApp Button for selectes user roles.',
                    'class'         => $class
                ]
            );
            /** User Role Wise End */
        }

        public function basic_setting_text_field($args){
            global $mmwea_general_settings_options;
            $value = isset($mmwea_general_settings_options[$args['label_for']]) ? $mmwea_general_settings_options[$args['label_for']] : '';
            ?>
            <input type="text" name="mmwea_general_settings_options[<?php esc_attr_e( $args['label_for'] ); ?>]" id="<?php esc_attr_e( $args['label_for'] ); ?>" value="<?php esc_attr_e($value); ?>">
            <p class="mmwea-input-note"><?php _e($args['description'],'mobile-message-for-woocommerce-enquiries-and-alerts') ?></p>
            <span><b><?php _e('Use','mobile-message-for-woocommerce-enquiries-and-alerts'); ?> :</b> <?php _e('911234567891','mobile-message-for-woocommerce-enquiries-and-alerts'); ?></span>
            <br>
            <span><b><?php _e('Do not Use','mobile-message-for-woocommerce-enquiries-and-alerts'); ?> :</b> <?php _e('+911234567891','mobile-message-for-woocommerce-enquiries-and-alerts'); ?></span>
            <?php
        }

        public function basic_setting_checkbox_field($args){
            global $mmwea_general_settings_options;
            $value = isset($mmwea_general_settings_options[$args['label_for']]) ? $mmwea_general_settings_options[$args['label_for']] : '';
            ?>
            <label class="mmwea-switch">
				<input type="checkbox" class="mmwea-checkbox" name="mmwea_general_settings_options[<?php esc_attr_e( $args['label_for'] ); ?>]" id="<?php esc_attr_e( $args['label_for'] ); ?>" value="on" <?php if($value == "on"){ esc_attr_e('checked'); } ?>>
				<span class="mmwea-slider mmwea-round"></span>
			</label>
            <p class="mmwea-input-note"><?php _e($args['description'],'mobile-message-for-woocommerce-enquiries-and-alerts') ?></p>
            <?php
        }

        public function basic_setting_radio_field($args){
            global $mmwea_general_settings_options;
            $value = isset($mmwea_general_settings_options[$args['label_for']]) ? $mmwea_general_settings_options[$args['label_for']] : '';
            ?>
            <label><input type="radio" class="mmwea-radio mmwea-user-radio" name="mmwea_general_settings_options[<?php esc_attr_e( $args['label_for'] ); ?>]" <?php if($value == "non-logged"){ esc_attr_e('checked'); } ?> value="non-logged"><?php esc_attr_e('Non Logged') ?></label>
            <label><input type="radio" class="mmwea-radio mmwea-user-radio" name="mmwea_general_settings_options[<?php esc_attr_e( $args['label_for'] ); ?>]" <?php if($value == "logged-in"){ esc_attr_e('checked'); } ?> value="logged-in"><?php esc_attr_e('Logged In') ?></label>
            <p class="mmwea-input-note"><?php _e($args['description'],'mobile-message-for-woocommerce-enquiries-and-alerts') ?></p>
            <?php
        }

        public function basic_setting_select_field($args){
            global $mmwea_general_settings_options;
            global $wp_roles;

            $all_roles = $wp_roles->roles;         
            $select_value = array();
            if(isset($mmwea_general_settings_options[$args['label_for']]) && !empty($mmwea_general_settings_options[$args['label_for']])){
                $select_value = explode(",",$mmwea_general_settings_options[$args['label_for']] );
            }
            ?>
            <select name="mmwea_general_settings_options[<?php esc_attr_e( $args['label_for'] ); ?>][]" id="<?php esc_attr_e( $args['label_for'] ); ?>" class="mmwea-select2-multi" multiple="multiple">
                <?php 
                if(!empty($all_roles) && isset($all_roles)){
                    foreach ($all_roles as $key => $value) {    
                        $role = $value['name'];
                        $key = strtolower(str_replace(" ","-",$role)); ?>
                            <option value="<?php esc_attr_e($key); ?>" <?php if(in_array($key, $select_value)){ esc_attr_e('selected'); }  ?>  ><?php esc_attr_e($role); ?></option>
                        <?php
                    }
                } ?>
            </select>
            <p class="mmwea-input-note"><?php _e($args['description'],'mobile-message-for-woocommerce-enquiries-and-alerts-pro') ?></p>            
            <?php
        }

        public function sanitize_settings($input){
            $new_input = array();
            if (isset($input['whatsapp_number']) && !empty($input['whatsapp_number'])) {
                $new_input['whatsapp_number'] = sanitize_text_field($input['whatsapp_number']);
            }

            if (isset($input['hide_wa_btn_desktop']) && !empty($input['hide_wa_btn_desktop'])) {
                $new_input['hide_wa_btn_desktop'] = sanitize_text_field($input['hide_wa_btn_desktop']);
            }

            if (isset($input['open_link_new_tab']) && !empty($input['open_link_new_tab'])) {
                $new_input['open_link_new_tab'] = sanitize_text_field($input['open_link_new_tab']);
            }            

            if (isset($input['enable_user_role']) && !empty($input['enable_user_role'])) {
                $new_input['enable_user_role'] = sanitize_text_field($input['enable_user_role']);
            }

            if (isset($input['user_role_option']) && !empty($input['user_role_option'])) {
                $new_input['user_role_option'] = sanitize_text_field($input['user_role_option']);
            }

            if (isset($input['login_user_role']) && !empty($input['login_user_role'])) {
                $user_role_list = implode(",",$input['login_user_role']);
                $new_input['login_user_role'] = sanitize_text_field($user_role_list);
            }

            return $new_input;
        }
    }
}
