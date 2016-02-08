<?php
/**
 * Plugin Name: Shop by Look
 * Description: Sell bulk products by single image.
 * Author:      S7Design
 * Author URI:  http://www.s7designcreative.com
 * Version:     1.0
 * Text Domain: shop-by-look
 * Domain Path: /languages
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

	$plugin = new Plugin();
	$plugin->register_post_type();
	flush_rewrite_rules();
}
