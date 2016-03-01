<?php
/**
 * Template: Meta section in admin of Shop by Look
 * Description: Used to display input fields for products on Shop By Look CPT create and edit post interface
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
if ( $all_products ) :
	?>
	<select name="products[]" multiple>
		<?php foreach ( $all_products as $product ): ?>
			<option
				value="<?php echo $product->ID; ?>"
				<?php if( in_array( $product->ID, $post_products ) ): ?>selected<?php endif; ?>
			>
				<?php echo $product->post_title; ?>
			</option>
		<?php endforeach; ?>
	</select>
<?php else : ?>
	<?php _e( 'No products' ); ?>
<?php endif; ?>
<script>jQuery('select[name="products[]"]').select2();</script>