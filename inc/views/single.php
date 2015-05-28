<?php include( 'header.php' ); ?>
<article class="shop_by_look">
	<div class="left_column">
		<h1><?php the_title(); ?></h1>
		<?php the_post_thumbnail( 'small' ); ?>
		<?php the_content(); ?>
	</div>
	<div class="right_column">
		<?php $ids = get_post_meta( $post->ID, 'products', TRUE ); ?>
		<?php $products = new WP_Query( array( 'post_type' => 'product', 'post__in' => $ids ) ); ?>
		<h3><?php _e( 'Products', 'shop_by_look' ); ?></h3>
		<table>
			<tr>
				<td><button data-shop-all class="buy-all button"><?php _e( 'Buy all', 'shop_by_look' ); ?></button></td>
				<td colspan="2">
					<a class="cart"
					   href="<?php echo $woocommerce->cart->get_cart_url(); ?>">
						<?php _e( 'View cart', 'shop_by_look' ); ?>
					</a>
				</td>
			</tr>
			<?php while ( $products->have_posts() ): ?>
				<tr>
					<?php $products->the_post(); ?>
					<?php $p = new WC_Product( get_the_ID() ); ?>
					<td>
						<a href="<?php echo $p->add_to_cart_url(); ?>">
							<?php
							echo $p->get_image( array( 30, 30 ) ) . ' ';
							the_title();
							?>
						</a>
					</td>
					<td><?php echo $p->get_price_html(); ?></td>
					<td><a data-shop="<?php the_ID(); ?>" class="button add-to-cart" href="#" title="Add to cart">+</a></td>
				</tr>
			<?php endwhile;
			wp_reset_postdata(); ?>
		</table>
	</div>
</article>
<?php include( 'footer.php' ); ?>