<?php
/**
 * Storefront engine room
 *
 * @package storefront
 */

/**
 * Assign the Storefront version to a var
 */
$theme              = wp_get_theme( 'storefront' );
$storefront_version = $theme['Version'];

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 980; /* pixels */
}

$storefront = (object) array(
	'version'    => $storefront_version,

	/**
	 * Initialize all the things.
	 */
	'main'       => require 'inc/class-storefront.php',
	'customizer' => require 'inc/customizer/class-storefront-customizer.php',
);

require 'inc/storefront-functions.php';
require 'inc/storefront-template-hooks.php';
require 'inc/storefront-template-functions.php';
require 'inc/wordpress-shims.php';

if ( class_exists( 'Jetpack' ) ) {
	$storefront->jetpack = require 'inc/jetpack/class-storefront-jetpack.php';
}

if ( storefront_is_woocommerce_activated() ) {
	$storefront->woocommerce            = require 'inc/woocommerce/class-storefront-woocommerce.php';
	$storefront->woocommerce_customizer = require 'inc/woocommerce/class-storefront-woocommerce-customizer.php';

	require 'inc/woocommerce/class-storefront-woocommerce-adjacent-products.php';

	require 'inc/woocommerce/storefront-woocommerce-template-hooks.php';
	require 'inc/woocommerce/storefront-woocommerce-template-functions.php';
	require 'inc/woocommerce/storefront-woocommerce-functions.php';
}

if ( is_admin() ) {
	$storefront->admin = require 'inc/admin/class-storefront-admin.php';

	require 'inc/admin/class-storefront-plugin-install.php';
}

/**
 * NUX
 * Only load if wp version is 4.7.3 or above because of this issue;
 * https://core.trac.wordpress.org/ticket/39610?cversion=1&cnum_hist=2
 */
if ( version_compare( get_bloginfo( 'version' ), '4.7.3', '>=' ) && ( is_admin() || is_customize_preview() ) ) {
	require 'inc/nux/class-storefront-nux-admin.php';
	require 'inc/nux/class-storefront-nux-guided-tour.php';
	require 'inc/nux/class-storefront-nux-starter-content.php';
}

/**
 * Note: Do not add any custom code here. Please use a custom plugin so that your customizations aren't lost during updates.
 * https://github.com/woocommerce/theme-customisations
 */


// require_once get_stylesheet_directory() . '/best-selling.php';

/**
 * Auto assingn to the sale category while publish and updated the product 
 */

add_action('wp_insert_post', 'matat_add_product_to_sale_categroy', 10, 3);
function matat_add_product_to_sale_categroy($post_id, $post, $update){
   	if( $post->post_type == 'product' && $post->post_status == 'publish' ){
		$product = wc_get_product($post_id);
  	  	$sale_price = $product->get_sale_price();
		$sale_price = apply_filters('advanced_woo_discount_rules_get_product_discount_price', $sale_price, $product);

  	  	$discount = apply_filters('advanced_woo_discount_rules_get_product_discount_price_from_custom_price', $price, $product, 1, 0, 'all', true, false);
  	  	if( $sale_price || $discount || $product->is_on_sale()   ){
  	  		wp_set_object_terms($post_id, 'sale-category', 'product_cat', true);
  	  	}else{		  	  		  	
  	  		wp_remove_object_terms($post_id, 'sale-category', 'product_cat', true);
  	  	}
   	} 
}


/**
 * Cron schedule for the above sale category assign
 */
add_action('init', 'matat_schedule_my_cron');
function matat_schedule_my_cron() {    
    if (!wp_next_scheduled('matat_auto_add_sale_category')) {
        wp_schedule_event(time(), 'daily', 'matat_auto_add_sale_category');
    }
}

add_action('matat_auto_add_sale_category', 'matat_auto_add_sale_category_cb');
function matat_auto_add_sale_category_cb() {
	$args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'post_status' => 'publish'
    );
    $result = new WP_Query( $args );
    if( $result->have_posts() ){
    	while($result->have_posts()){    		
    		$result->the_post();
    		$post_id = get_the_ID();
    		$product = wc_get_product($post_id);
    	  	$sale_price = $product->get_sale_price();
			$sale_price = apply_filters('advanced_woo_discount_rules_get_product_discount_price', $sale_price, $product);			
	  	  	$discount = apply_filters('advanced_woo_discount_rules_get_product_discount_price_from_custom_price', $price, $product, 1, 0, 'all', true, false);
	  	  	if( $sale_price || $discount || $product->is_on_sale()   ){
	  	  		wp_set_object_terms($post_id, 'sale-category', 'product_cat', true);
	  	  	}else{		  	  		  	
	  	  		wp_remove_object_terms($post_id, 'sale-category', 'product_cat', true);
	  	  	}		
    	}
    	wp_reset_postdata();
    }
}


// add_action( 'wp_enqueue_scripts', 'sapporo_scripts' );

// function sapporo_scripts(){
// 	wp_enqueue_script('customJs', get_template_directory_uri().'/js/custom.js',array('jquery'),'1.0.0', true );
// }

require 'inc/matat-custom/matat-functions.php';
require 'inc/matat-custom/matat-custom-post-type.php';
require 'inc/matat-custom/options.php';
require 'inc/matat-custom/acf-practice.php';