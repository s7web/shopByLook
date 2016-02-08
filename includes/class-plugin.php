<?php
namespace S7D\ShopByLook;

/**
 * Class Plugin
 * @package S7D\ShopByLook
 */
class Plugin {

	/**
	 * Initialize application hooks
	 *
	 * @return void
	 */
	public function run() {

		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'add_meta_boxes_shop_by_look', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post_shop_by_look', array( $this, 'save_meta' ) );
		add_filter( 'template_include', array( $this, 'archive' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'front_scripts' ) );
		add_action( 'transition_post_status',  array( $this, 'check_items' ), 10, 3 );

		$x = load_plugin_textdomain( 'shop-by-look', FALSE, plugin_basename( __DIR__ ) . '/../languages' );
	}

	/**
	 * Register shop_by_look post type
	 *
	 * @return  void
	 *
	 * @wp-hook init
	 */
	public function register_post_type() {

		$labels = array(
			'name'               => _x( 'Shop by Look', 'Post type general name', 'shop-by-look' ),
			'singular_name'      => _x( 'Item', 'Post type singular name', 'shop-by-look' ),
			'menu_name'          => _x( 'Shop by Look', 'Post type menu name', 'shop-by-look' ),
			'name_admin_bar'     => _x( 'Shop', 'Post type admin bar name', 'shop-by-look' ),
			'all_items'          => __( 'All Items', 'shop-by-look' ),
			'add_new'            => _x( 'Add New', 'Add new post', 'shop-by-look' ),
			'add_new_item'       => __( 'Add Item', 'shop-by-look' ),
			'edit_item'          => __( 'Edit Item', 'shop-by-look' ),
			'new_item'           => __( 'New Item', 'shop-by-look' ),
			'view_item'          => __( 'View Item', 'shop-by-look' ),
			'search_items'       => __( 'Search Item', 'shop-by-look' ),
			'not_found'          => __( 'No Item found.', 'shop-by-look' ),
			'not_found_in_trash' => __( 'No Item found in Trash.', 'shop-by-look' ),
			'parent_item_colon'  => __( 'Parent Item:', 'shop-by-look' ),
		);
		$args = array(
			'labels'             => $labels,
			'show_ui'            => TRUE,
			'supports'           => array(
				'title',
				'editor',
				'thumbnail',
			),
			'has_archive'        => TRUE,
			'public'             => TRUE,
		);
		register_post_type( 'shop_by_look', $args );
	}

	/**
	 * Add the meta box
	 *
	 * @return void
	 *
	 * @wp-hook add_meta_boxes_shop_by_look
	 */
	public function add_meta_boxes() {

		add_meta_box(
			'shop_by_look',
			__( 'Products', 'shop-by-look' ),
			array( $this, 'render_meta_box' )
		);
	}

	/**
	 * Render the meta box
	 *
	 * @param  \WP_Post $post
	 *
	 * @return void
	 */
	public function render_meta_box( $post ) {

		$all_products = get_posts( array( 'post_type' => 'product' ) );
		$post_products = get_post_meta( $post->ID, 'products', TRUE );
		$post_products = $post_products ? $post_products : array();
		include 'views/meta.php';
	}

	/**
	 * Update meta for the current shop_by_look post
	 *
	 * @param  int $post_id
	 *
	 * @return void
	 *
	 * @wp-hook save_post_shop_by_look
	 */
	public function save_meta( $post_id ) {

		if ( $_SERVER[ 'REQUEST_METHOD' ] !== 'POST' || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			|| ( defined('DOING_AJAX') && DOING_AJAX ) ) {

			return;
		}
		$products = isset( $_POST[ 'products' ] ) ? array_slice( $_POST[ 'products' ], 0, 3 ) : array();
		update_post_meta( $post_id, 'products', $products );
	}

	/**
	 * Load the back end script and stylesheet
	 *
	 * @return void
	 *
	 * @wp-hook admin_enqueue_scripts
	 */
	public function enqueue_scripts() {

		$assets = plugin_dir_url( __DIR__ ) . 'assets/';
		wp_enqueue_script( 'select2', $assets . 'js/select2.min.js', array( 'jquery' ) );
		wp_enqueue_style( 'select2', $assets . 'css/select2.min.css' );
	}

	/**
	 * Load the front end script and stylesheet
	 *
	 * @return void
	 *
	 * @wp-hook wp_enqueue_scripts
	 */
	public function front_scripts() {

		$assets = plugin_dir_url( __DIR__ ) . 'assets/';
		wp_enqueue_script( 'shop_by_look', $assets . 'js/front.js', array( 'jquery' ) );
		wp_enqueue_style( 'shop_by_look', $assets . 'css/style.css' );
	}

	/**
	 * Change templates for the shop_by_look post type
	 *
	 * @param  string $original_template
	 *
	 * @return string
	 *
	 * @wp-hook template_include
	 */
	function archive( $original_template ) {

		global $post_type;

		if ( 'shop_by_look' === $post_type ) {
			if ( is_single() ) {

				return plugin_dir_path( __FILE__ ) . 'views/single.php';
			}

			return plugin_dir_path( __FILE__ ) . 'views/archive.php';
		}

		return $original_template;
	}

	/**
	 * Check if the number of posts is smaller than maximum before publishing the new one
	 *
	 * @param  string $old
	 * @param  string $new
	 * @param  \WP_Post $post
	 *
	 * @return void
	 *
	 * @wp-hook transition_post_status
	 */
	public function check_items( $old, $new, $post ) {

		if ( 'nav_menu_item' === $post->post_type ) {

			return;
		}

		$counter = get_posts( array( 'post_type' => 'shop_by_look', 'post_status' => 'publish' ) );

		if ( count( $counter ) >= 3 ) {
			wp_die( 'Cannot create more than 3 shop by look items on Free version' );
		}
	}
}
