<?php
get_header();
global $woocommerce;
while ( have_posts() ) : the_post();
	?>
	<div class="shop_by_look archive">
		<div class="left_column">
			<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			<?php
			the_post_thumbnail( array( 500, 300 ) );

			$length  = 300;
			$content = get_the_content();
			$more	 = FALSE;
			if ( strlen( $content ) > $length ) {
				$more = TRUE;
			}
			$content = substr( $content, 0, $length );
			$content = apply_filters( 'the_content', $content );
			$content = str_replace( ']]>', ']]&gt;', $content );

			echo $content;
			if ( $more ) :
			?>
			<p>[ <a href="<?php the_permalink(); ?>"><?php _e( 'Read more...', 'shop_by_look' ); ?></a> ]</p>
			<?php endif; ?>
		</div>
		<div class="right_column">
			<?php $ids = get_post_meta( $post->ID, 'products', TRUE ); ?>
			<?php $products = new WP_Query( array( 'post_type' => 'product', 'post__in' => $ids ) ); ?>
			<h3><?php _e( 'Products', 'shop_by_look' ); ?></h3>
			<ul>
				<?php while ( $products->have_posts() ): ?>
					<li>
						<?php $products->the_post(); ?>
						<?php $p = new WC_Product( get_the_ID() );
						echo $p->get_image( array( 30, 30 ) ); ?>
						<a href="<?php echo $p->add_to_cart_url(); ?>"><?php the_title(); ?></a>
						<span><?php echo $p->get_price_html(); ?></span>
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
