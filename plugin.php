<?php
/**
* Plugin Name: AGZone
* Plugin URI: https://www.techyouup.com/
* Description: A word press plugin in to add agriculteral zones to addresses.
* Version: 0.1
* Author: Hallcrash
* Author URI: https://www.techyouup.com
**/


// Prevent direct access data leaks.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once ABSPATH . 'wp-admin/includes/plugin.php';

if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) && ! is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) {
	deactivate_plugins( plugin_basename( __FILE__ ) );

	/**
	 * Deactivate the plugin if WooCommerce is not active.
	 *
	 * @since    0.1
	 */
	function wc_AGZone_woocommerce_notice_error() {
		$class   = 'notice notice-error';
		$message = __( 'WooCommerce AGZone requires WooCommerce and has been deactivated.', 'AGZone' );

		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_attr( $message ) );
	}
	add_action( 'admin_notices', 'wc_AGZone_woocommerce_notice_error' );
	add_action( 'network_admin_notices', 'wc_AGZone_woocommerce_notice_error' );
} else {
	require plugin_dir_path( __FILE__ ) . 'includes/class_wc_ag_zone.php';
    
	// Adds plugin settings.
	//include_once dirname( __FILE__ ) . '/includes/settings.php';

	// Init Class.
	WC_AGZone::get_instance();
}


 function agzone_add_field( $fields ) {
	$fields[ 'shipping_ag_zone' ]   = array(
		'label'        => 'ag zone',
		'required'     => false,
		'class'        => array( 'form-row-wide', 'my-custom-class' ),
		'priority'     => 20,
		'placeholder'  => '',
	);
	
	return $fields;
}

 function agzone_admin_add_field( $admin_fields ) {

$admin_fields[ 'billing' ][ 'fields' ][ 'shipping_ag_zone' ] = array(
	'label' => 'ag zone',
	'value' => 'zone here',
	'description' => 'Agricultural Zone',
);
return $admin_fields;

}

function add_ag_zone_column( $columns ) {
	$new_array = [];
	foreach ($columns as $key => $title) {
		if ($key == 'billing_address') {
			$new_array['ag_zone'] = 'Ag Zone';
		}
		$new_array[$key] = $title;
	}
	return $new_array;

//return $columns;
}

/**
 * Show zone number on the invoice
 */
function add_ag_zone_to_packingslip($template_type, $order){
	$shipping_postcode = $order->get_meta( '_shipping_postcode' );
	echo '<div>AG Zone: ' . get_ag_zone($shipping_postcode) . '</div>';
		
}


function display_ag_zone_column( $column ) {
global $post;

if ( 'ag_zone' === $column ) {
	$shipping_postcode = get_post_meta( $post->ID, '_shipping_postcode', true );

	echo get_ag_zone($shipping_postcode);
}

	
}

function get_ag_zone($postal_code){
	if (strpos($postal_code,'-',0) > 0){
	$formatpostal_code = substr($postal_code, 0, strpos($postal_code,'-',0));
	}
	else{
		$formatpostal_code = $postal_code;
	}
    global $wpdb;
	$sql = $wpdb->prepare("SELECT zone FROM hardiness_zones WHERE zip_code = %s;", $formatpostal_code);
	$result = $wpdb->get_var($sql);
	return $result;
		
}

add_filter( 'woocommerce_customer_meta_fields', 'agzone_admin_add_field' );
add_filter( 'manage_edit-shop_order_columns', 'add_ag_zone_column', 10, 1 );
add_action( 'manage_shop_order_posts_custom_column', 'display_ag_zone_column', 10, 1 );
add_action( 'wpo_wcpdf_after_shipping_address', 'add_ag_zone_to_packingslip', 10, 2);
