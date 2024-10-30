<?php
include( MMWEA_PLUGIN_DIR_PATH .'admin/setting/general-setting.php'); 
include( MMWEA_PLUGIN_DIR_PATH .'admin/setting/single-page-setting.php'); 
include( MMWEA_PLUGIN_DIR_PATH .'admin/setting/cart-page-setting.php'); 
include( MMWEA_PLUGIN_DIR_PATH .'admin/setting/checkout-page-setting.php'); 
include( MMWEA_PLUGIN_DIR_PATH .'admin/setting/thankyou-page-setting.php'); 
include( MMWEA_PLUGIN_DIR_PATH .'admin/setting/account-page-setting.php'); 
include( MMWEA_PLUGIN_DIR_PATH .'admin/setting/design-elements-setting.php'); 

$default_tab = null;
$tab = "";
$tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : $default_tab;

if (!class_exists('mmwea_general_option_settings')) {

    if ($tab == null) { 
        $loop  = new mmwea_general_settings();    
        add_action('admin_init', array($loop, 'general_setting_register_init'));
    }
    if ($tab == "mmwea-product-single-page") { 
        $loop  = new mmwea_single_page_settings();    
        add_action('admin_init', array($loop, 'single_page_setting_register_init'));
    }
    if ($tab == "mmwea-cart-page-setting") { 
        $loop  = new mmwea_cart_page_settings();    
        add_action('admin_init', array($loop, 'cart_page_setting_register_init'));
    }
    if ($tab == "mmwea-checkout-page-setting") { 
        $loop  = new mmwea_checkout_page_settings();   
        add_action('admin_init', array($loop, 'checkout_page_setting_register_init'));
    }
    if ($tab == "mmwea-thankyou-page-setting") { 
        $loop  = new mmwea_thankyou_page_settings();   
        add_action('admin_init', array($loop, 'thankyou_page_setting_register_init'));
    }
    if ($tab == "mmwea-account-page-setting") { 
        $loop  = new mmwea_account_page_settings();   
        add_action('admin_init', array($loop, 'account_page_setting_register_init'));
    }
    if ($tab == "mmwea-desgin-elements-setting") { 
        $loop  = new mmwea_design_elements_settings();   
        add_action('admin_init', array($loop, 'design_elements_register_settings_init'));
    }

    class mmwea_general_option_settings{
        public function __construct(){
            add_action('admin_menu',  array($this, 'mmwea_admin_menu_donation_setting_page'));            
        }        

        function mmwea_admin_menu_donation_setting_page(){            
            add_submenu_page('woocommerce', 'Mobile Enquiry and Alert Message for Woocommerce', 'Mobile Enquiry and Alert Message for Woocommerce', 'manage_options', 'mmwea-option-page', array($this, 'basic_setting_callback'));
        }

        function basic_setting_callback(){
       
            $default_tab = null;
            $tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : $default_tab;
            ?>

            <div class="mmwea-main-box">
                <div class="mmwea-container">
                    <div class="mmwea-header">
                        <h1 class="mmwea-h1"> <?php _e('Mobile Enquiry and Alert Message for Woocommerce', 'mobile-message-for-woocommerce-enquiries-and-alerts'); ?></h1>
                    </div>
                    <div class="mmwea-option-section">

                        <div class="mmwea-tabbing-box">
                            <ul class="mmwea-tab-list">

                                <li><a href="?page=mmwea-option-page" class="nav-tab <?php if ($tab === null) : ?>nav-tab-active<?php endif; ?>"><?php _e('General Settings', 'mobile-message-for-woocommerce-enquiries-and-alerts'); ?></a></li>
                                <li><a href="?page=mmwea-option-page&tab=mmwea-product-single-page" class="nav-tab <?php if ($tab === 'mmwea-product-single-page') : ?>nav-tab-active<?php endif; ?>"><?php _e('Product Single Page', 'mobile-message-for-woocommerce-enquiries-and-alerts'); ?></a></li>
                                <li><a href="?page=mmwea-option-page&tab=mmwea-cart-page-setting" class="nav-tab <?php if ($tab === 'mmwea-cart-page-setting') : ?>nav-tab-active<?php endif; ?>"><?php _e('Cart', 'mobile-message-for-woocommerce-enquiries-and-alerts'); ?></a></li>
                                <li><a href="?page=mmwea-option-page&tab=mmwea-checkout-page-setting" class="nav-tab <?php if ($tab === 'mmwea-checkout-page-setting') : ?>nav-tab-active<?php endif; ?>"><?php _e('Checkout', 'mobile-message-for-woocommerce-enquiries-and-alerts'); ?></a></li>
                                <li><a href="?page=mmwea-option-page&tab=mmwea-thankyou-page-setting" class="nav-tab <?php if ($tab === 'mmwea-thankyou-page-setting') : ?>nav-tab-active<?php endif; ?>"><?php _e('Order Page', 'mobile-message-for-woocommerce-enquiries-and-alerts'); ?></a></li>
                                <li><a href="?page=mmwea-option-page&tab=mmwea-account-page-setting" class="nav-tab <?php if ($tab === 'mmwea-account-page-setting') : ?>nav-tab-active<?php endif; ?>"><?php _e('My Account Page', 'mobile-message-for-woocommerce-enquiries-and-alerts'); ?></a></li>
                                <li><a href="?page=mmwea-option-page&tab=mmwea-desgin-elements-setting" class="nav-tab <?php if ($tab === 'mmwea-desgin-elements-setting') : ?>nav-tab-active<?php endif; ?>"><?php _e('Design Elements', 'mobile-message-for-woocommerce-enquiries-and-alerts'); ?></a></li>
   
                            </ul>
                        </div>

                        <div class="mmwea-tabing-option">
                           
                            <?php 
                            if ($tab == null) { 
                                $loop  = new mmwea_general_settings();    
                                $loop->general_setting_customize_callback();

                            }
                            
                            if ($tab == "mmwea-product-single-page") {
                                $shop_page_class  = new mmwea_single_page_settings();                                
                                $shop_page_class->general_setting_customize_callback();

                            }
                            
                            if ($tab == "mmwea-cart-page-setting") {
                                $design_class  = new mmwea_cart_page_settings();
                                $design_class->general_setting_customize_callback();

                            }
                            
                            
                            if ($tab == "mmwea-checkout-page-setting") {
                                $pro_des_class  = new mmwea_checkout_page_settings();
                                $pro_des_class->general_setting_customize_callback();

                            }

                            if ($tab == "mmwea-thankyou-page-setting") {
                                $thankyou_class  = new mmwea_thankyou_page_settings();
                                $thankyou_class->general_setting_customize_callback();

                            }

                            if ($tab == "mmwea-account-page-setting") {
                                $account_class  = new mmwea_account_page_settings();
                                $account_class->general_setting_customize_callback();

                            }

                            if ($tab == "mmwea-desgin-elements-setting") {
                                $account_class  = new mmwea_design_elements_settings();
                                $account_class->general_setting_customize_callback();

                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }

    new mmwea_general_option_settings();
}