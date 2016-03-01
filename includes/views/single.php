<?php
/**
 * Template: Single post view
 * Description: Displays single post of shop by look CPT. 
 * It shows thumbnail image of grouped products along with list of Woocommerce articles on that picture.
 * provides option to buy all at once, or just selected. 
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

include( 'header.php' );
?>
<article class="shop_by_look">
	<div>
		<h1 class="entry_title"><?php the_title(); ?></h1>
		<?php the_post_thumbnail( 'full' ); ?>
	</div>
	<table>
		<tr>
			<td>
				<?php
				$content = $post->post_content;
				$content = apply_filters( 'the_content', $content );
				$content = str_replace( ']]>', ']]&gt;', $content );

				echo $content;
				?>
			</td>
			<td>
				<?php $ids = get_post_meta( $post->ID, 'products', TRUE ); ?>
				<?php $products = new WP_Query( array( 'post_type' => 'product', 'post__in' => $ids ) ); ?>
				<h4><?php _e( 'Products', 'shop_by_look' ); ?></h4>
				<table>
					<tr>
						<th><button data-shop-all class="buy-all button"><?php _e( 'Buy all', 'shop_by_look' ); ?></button></th>
						<th colspan="2">
							<a class="cart"
							   href="<?php echo $woocommerce->cart->get_cart_url(); ?>">
								<?php _e( 'View cart', 'shop_by_look' ); ?>
							</a>
						</th>
					</tr>
					<?php while ( $products->have_posts() ): ?>
						<tr>
							<?php $products->the_post(); ?>
							<?php $p = new WC_Product( get_the_ID() ); ?>
							<td><div>
								<a href="<?php echo $p->add_to_cart_url(); ?>">
									<?php
									echo $p->get_image( array( 30, 30 ) ) . ' ';
									the_title();
									?>
								</a>
							</div></td>
							<td><div><?php echo $p->get_price_html(); ?></div></td>
							<td class="add"><a data-shop="<?php the_ID(); ?>" class="button add-to-cart" href="#" title="Add to cart">+</a></td>
						</tr>
					<?php endwhile;
					wp_reset_postdata(); ?>
				</table>
			</td>
		</tr>
	</table>
	<div class="right_column">

	</div>
</article>
<?php include( 'footer.php' ); ?>