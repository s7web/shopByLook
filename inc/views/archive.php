<?php
get_header();
global $woocommerce;
while ( have_posts() ) : the_post();
	?>
	<div class="shop_by_look">
		<div class="left_column">
			<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			<?php the_post_thumbnail( array( 400, 400 ) ); ?>
			<?php the_content(); ?>
		</div>
		<div class="right_column">
			<?php $ids = get_post_meta( $post->ID, 'products', TRUE ); ?>
			<?php $products = new WP_Query( array( 'post_type' => 'product', 'post__in' => $ids ) ); ?>
			<h2><?php _e( 'Products', 'shop_by_look' ); ?></h2>

			<div class="links">
				<a data-shop-all href="#" class="buy-all button"><?php _e( 'Buy all', 'shop_by_look' ); ?></a>
				<a class="cart"
				   href="<?php echo $woocommerce->cart->get_cart_url(); ?>"><?php _e( 'Cart', 'shop_by_look' ); ?></a>
			</div>
			<ul>
				<?php while ( $products->have_posts() ): ?>
					<li>
						<?php $products->the_post(); ?>
						<?php $p = new WC_Product( get_the_ID() );
						echo $p->get_image( array( 30, 30 ) ); ?>
						<a href="<?php echo $p->add_to_cart_url(); ?>"><?php the_title(); ?></a>
						<span><?php echo $p->get_price_html(); ?></span>
						<a data-shop="<?php the_ID(); ?>" class="button add-to-cart" href="#" title="Add to cart">+</a>
					</li>
				<?php endwhile;
				wp_reset_postdata(); ?>
			</ul>
		</div>
	</div>
<?php
endwhile;
get_footer();
?>
