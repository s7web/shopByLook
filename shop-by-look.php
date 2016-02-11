<?php
/**
 * Plugin Name: Shop by Look
 * Description: Shop by Look is a WooCommerce-dependent WordPress plugin that allows the user to bulk-buy grouped products and view product groups. Its perfect for you to enable your customers to buy group of articles in desired amounts at once.
 * Author:      S7Design
 * Author URI:  http://www.s7designcreative.com
 * Version:     1.0-BETA
 * Text Domain: shop-by-look
 * Domain Path: /languages
 * Licence: GNU
 * Network: true
 */
namespace S7D\ShopByLook;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

spl_autoload_register( __NAMESPACE__ . '\autoload' );

/**
 * Autoload function
 *
 * @param  string $class
 *
 * @return void
 */
function autoload( $class ) {

	if ( strpos($class, __NAMESPACE__) !== 0 ) {
		return;
	}

	$class = str_replace( __NAMESPACE__ . '\\', '', $class );
	$class = str_replace( '\\', DIRECTORY_SEPARATOR, $class );
	$lower = lcfirst( $class );
	$class = $class === $lower ? $class : 'class-' . $lower;

	require_once( 'includes/' . $class . '.php' );
}

add_action( 'plugins_loaded', __NAMESPACE__ . '\loaded' );

/**
 * Instantiate and run the plugin
 *
 * @return void
 */
function loaded() {

	check_is_woocommerce_is_active();
	$plugin = new Plugin();
	$plugin->run();
}

register_activation_hook( __FILE__, __NAMESPACE__ . '\flush_rewrites' );

/**
 * Plugin activation callback function.
 *
 * @return void
 */
function flush_rewrites() {

	check_is_woocommerce_is_active();
	$plugin = new Plugin();
	$plugin->register_post_type();
	flush_rewrite_rules();
}

/**
 * Check is Woocommerce plugin is active
 * 
 * If Woocommerce is not active deactivate plugin and show message.
 *
 * @return void
 */
function check_is_woocommerce_is_active() {

	if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		deactivate_plugins( plugin_basename( __FILE__ ), true );
    	add_action( 'admin_notices', __NAMESPACE__ . '\error_notice' ); 
	}
}

/**
 * Show admin notice
 *
 * @return void
 *
 * @wp-hook admin_notices
 */
function error_notice() {

	$message = __( 'Woocommerce must be active in order to activate Shop by Look plugin. Currently Shop by Look plugin is deactivated.', 'shop-by-look' );
    echo"<div class='error updated notice is-dismissible'> <p>$message <button type='button' class='notice-dismiss'><span class='screen-reader-text'>Dismiss this notice.</span></button></p></div>"; 
}
