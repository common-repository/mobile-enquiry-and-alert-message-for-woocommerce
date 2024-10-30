<?php
/*
Plugin Name: Mobile Enquiry and Alert Message for Woocommerce
Description: Mobile Enquiry and Alert Message for Woocommerce is used to get a enquriy from user directly to your whatsapp for product, cart and order detail etc!
Author: Geek Code Lab
Version: 1.6.3
WC tested up to: 8.9.0
Author URI: https://geekcodelab.com/
Text Domain : mobile-enquiry-and-alert-message-for-woocommerce
*/
if (!defined('ABSPATH')) exit;

if (!defined("MMWEA_PLUGIN_DIR_PATH"))

	define("MMWEA_PLUGIN_DIR_PATH", plugin_dir_path(__FILE__));

if (!defined("MMWEA_PLUGIN_URL"))
    
    define("MMWEA_PLUGIN_URL", plugins_url() . '/' . basename(dirname(__FILE__)));
    
define("MMWEA_VERSION", '1.6.3');

/**
 * Trigger an admin notice if WooCommerce is not installed.
 */
if ( ! function_exists( 'mmwea_install_woocommerce_admin_notice' ) ) {
	function mmwea_install_woocommerce_admin_notice() { ?>
		<div class="error">
			<p>
				<?php echo esc_html__( sprintf( '%s is enabled but not effective. It requires WooCommerce in order to work.', 'Mobile Enquiry and Alert Message for Woocommerce' ), 'mobile-enquiry-and-alert-message-for-woocommerce' ); ?>
			</p>
		</div>
		<?php
	}
}

function mmwea_woocommerce_constructor() {
    // Check WooCommerce installation
	if ( ! function_exists( 'WC' ) ) {
		add_action( 'admin_notices', 'mmwea_install_woocommerce_admin_notice' );
		return;
	}
}
add_action( 'plugins_loaded', 'mmwea_woocommerce_constructor' );

require_once( MMWEA_PLUGIN_DIR_PATH .'admin/options.php');

require_once( MMWEA_PLUGIN_DIR_PATH .'front/product-single-page.php');
require_once( MMWEA_PLUGIN_DIR_PATH .'front/cart-page.php');
require_once( MMWEA_PLUGIN_DIR_PATH .'front/checkout-page.php');
require_once( MMWEA_PLUGIN_DIR_PATH .'front/order-page.php');
require_once( MMWEA_PLUGIN_DIR_PATH .'front/account-page.php');

require_once( MMWEA_PLUGIN_DIR_PATH .'/customizer/customizer-library/customizer-library.php');
require_once( MMWEA_PLUGIN_DIR_PATH .'/customizer/styles.php');

function mmwea_plugin_add_settings_link($links){

	$support_link = '<a href="https://geekcodelab.com/contact/"  target="_blank" >' . __('Support','mobile-enquiry-and-alert-message-for-woocommerce') . '</a>';
	array_unshift($links, $support_link);

	$settings_link = '<a href="admin.php?page=mmwea-option-page">' . __('Settings','mobile-enquiry-and-alert-message-for-woocommerce') . '</a>';
	array_unshift($links, $settings_link);
	return $links;
}
$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'mmwea_plugin_add_settings_link');

add_action('admin_print_styles', 'mmwea_admin_style');
function mmwea_admin_style(){
	if (is_admin()) {
		wp_enqueue_style('mmwea-admin-style', MMWEA_PLUGIN_URL . '/assets/css/admin-style.css' , '',MMWEA_VERSION);
		wp_enqueue_style('mmwea-select2-style', MMWEA_PLUGIN_URL . '/assets/css/select2.min.css' , '',MMWEA_VERSION);
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_script('wp-color-picker');
		wp_enqueue_script('mmwea-admin-select2-js',MMWEA_PLUGIN_URL.'/assets/js/select2.min.js' ,array('jquery'),MMWEA_VERSION);
		wp_enqueue_script('mmwea-admin-js',MMWEA_PLUGIN_URL.'/assets/js/admin-script.js' ,array('jquery','mmwea-admin-select2-js'),MMWEA_VERSION);
		wp_localize_script('mmwea-admin-js', 'mmweaObj', [ 'ajaxurl' => admin_url('admin-ajax.php') ]);
	}
}

add_action('wp_enqueue_scripts', 'mmwea_include_front_script');
function mmwea_include_front_script(){
    wp_enqueue_style("mmwea_front_style", MMWEA_PLUGIN_URL . "/assets/css/front-style.css", '',MMWEA_VERSION);
    wp_enqueue_script('mmwea_donation_script', MMWEA_PLUGIN_URL.'/assets/js/front-script.js', array('jquery'), MMWEA_VERSION);
}

function mmwea_get_product_category($term_id,$select_category_id) {
	$select_category_id = $select_category_id;
	$args = array(
		'parent'         => $term_id,
		'hide_empty' => false,
	);
	
	$sub_terms = get_terms('product_cat', $args);
	if (isset($sub_terms) && !empty($sub_terms) ) {
		foreach ( $sub_terms as $sub_taxonomy ) {
			if (in_array($sub_taxonomy->term_id, $select_category_id)){	?>
                    <option value="<?php echo esc_attr($sub_taxonomy->term_id); ?>" selected="selected" ><?php esc_html_e($sub_taxonomy->name) ?></option>
                <?php
			}else{ ?>
                    <option value="<?php echo esc_attr($sub_taxonomy->term_id); ?>" ><?php esc_html_e($sub_taxonomy->name) ?></option>
                <?php
			}
			mmwea_get_product_category($sub_taxonomy->term_id,$select_category_id);
		}
	}
}

/**
 * Added HPOS support for woocommerce
 */
add_action( 'before_woocommerce_init', 'mmwea_before_woocommerce_init' );
function mmwea_before_woocommerce_init() {
    if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
}

/**
 * Ajax to search products for Single page settings
 */
add_action( 'wp_ajax_mmwea_product_select_ajax', 'mmwea_product_select_ajax_callback' );
add_action( 'wp_ajax_nopriv_mmwea_product_select_ajax', 'mmwea_product_select_ajax_callback' );
function mmwea_product_select_ajax_callback() {
	$result = array();
	$search = $_POST['search'];

	$search_product_args = array( 'post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => -1 );

	if(is_numeric($search)) {
		$search_product_args['p'] = (int) $search;
	}else{
		$search_product_args['s'] = $search;
	}
	$mmwea_get_page = get_posts( $search_product_args );

	foreach ($mmwea_get_page as $mmwea_product) {
		$result[] = array(
			'id' => $mmwea_product->ID,
			'title' => $mmwea_product->post_title .  " ( #" . $mmwea_product->ID . " )"
		);
	}
	echo json_encode($result);

	wp_die();
}

/**
 * Pushing sale countdown of product inside `woocommerce_available_variation`
 */
function mmwea_rewrite_wc_available_variation( $default, $class, $variation ) {
	$product_id = $variation->get_id();	
	$variation = new WC_Product_Variation($product_id);
	$variations = $variation->get_variation_attributes();

	$selected = [];
	foreach ($variations as $key => $value) {
		$attribute_name = preg_replace('/^attribute_/', '', $key);
		$selected[] = ucwords(wc_attribute_label($attribute_name)) . ':- ' . ucwords($value);
	}

	// Pushing the initial price [if WC_Product class initialized]
	$default['mmwea_selected_variation'] = json_encode($selected);

	return apply_filters( 'mmwea_woocommerce_available_variation', $default, $class, $variation );
}
add_filter( 'woocommerce_available_variation', 'mmwea_rewrite_wc_available_variation', 99, 3 );
