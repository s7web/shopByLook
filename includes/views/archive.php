<?php
/**
 * Template: Shop By Look archive template
 * Description: Displays list of posts for Shop By Look CPT
 * 
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

include( 'header.php' );
while ( have_posts() ) : the_post();
	if ( has_post_thumbnail() ) :
		$size = array( 300, 300, true );
		$attr = array(
			'alt'   => get_the_title(),
			'title' => get_the_title(),
		);
		?>
		<a href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail( $size, $attr ); ?>
		</a>
	<?php
	endif;
endwhile;
include( 'footer.php' );
?>
