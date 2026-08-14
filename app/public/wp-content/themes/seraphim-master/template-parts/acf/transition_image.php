<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}	
	
// Vars
$attachment_id = get_sub_field('image');
$size = "large";
$image = wp_get_attachment_image_src( $attachment_id, $size );

$top_colour = get_sub_field('top_colour');
$bottom_colour = get_sub_field('bottom_colour');

?>

</div></div></div>

	<div>
		<div>
			<div></div>
		</div>
		<div>
			<div></div>
		</div>
	</div>
          
<div class="container"><div class="row"><div class="col-12">

