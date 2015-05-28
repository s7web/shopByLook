<?php
include( 'header.php' );
while ( have_posts() ) : the_post();
	?>
	<article class="shop_by_look archive">
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
			<table>
				<?php
				$total = 0;
				while ( $products->have_posts() ):
					?>
					<tr>
						<?php
						$products->the_post();
						$p = new WC_Product( get_the_ID() );
						?>
						<?php  ?>
						<td><a href="<?php echo $p->add_to_cart_url(); ?>">
							<?php
							echo $p->get_image( array( 30, 30 ) ) . ' ';
							the_title();
							?>
						</a></td>
						<td><?php echo $p->get_price_html(); ?></td>
					</tr>
				<?php endwhile; ?>
				<tr>
					<td><b>Total:</b></td>
					<td></td>
				</tr>
				<?php wp_reset_postdata(); ?>
			</table>
		</div>
	</article>
<?php
endwhile;
include( 'footer.php' );
?>
