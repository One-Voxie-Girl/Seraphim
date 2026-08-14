<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}	
	
// Vars
$attachment_id = get_sub_field('image');
$size = "original";
$image = wp_get_attachment_image_src( $attachment_id, $size );

$image_name = get_sub_field('image_name');

$image_link = get_sub_field('image_link');

		if( $image_link ){
			$link_url = $image_link['url'];
			$link_title = $image_link['title'];
			$link_target = $image_link['target'] ? $image_link['target'] : '_self';
			?>
		<a href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>" name="<?= $link_title; ?>">
	<?php } ?>
		
		<div>
			<img src="<?= $image[0]; ?>" alt="<?= $image_name;?>" width="100%" height="100%" />     
		</div>
          
	<?php if ($image_link) { ?>
		</a>
	<?php } ?>

