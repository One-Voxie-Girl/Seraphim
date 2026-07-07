<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}	


$video_id = get_sub_field('video_id');

$attachment_id = get_sub_field('placeholder_image');
$size = "large";
$image = wp_get_attachment_image_src( $attachment_id, $size );

$full_width = get_sub_field('full_width');

$overlay_pattern = get_sub_field('overlay_pattern');

$controls = get_sub_field('controls');
$autoplay = get_sub_field('autoplay');
$loop = get_sub_field('loop');
$volume = get_sub_field('volume');

$text_overlay = get_sub_field('text_overlay');
$headline = get_sub_field('headline');
$cta_text = get_sub_field('cta_text');
$cta_link = get_sub_field('cta_link');

?>
<?php if ($full_width == "yes"){
echo '</div></div></div>';
} ?>


              
<div class="videoCon <?php if ($full_width == "yes"){ echo 'fullWidthVideo';}?>" style="background-image: url(<?= $image[0]; ?>);">
	

	<div class="videoPlayerCon <?= $full_width; ?>">
        <div class="muse-video-player" data-video="<?= $video_id; ?>" data-search="0" data-links="0" data-logo="0" data-title="0" data-autoplay="<?= $autoplay; ?>" data-loop="<?= $loop; ?>" data-volume="0" data-width="100%" data-style="<?= $controls; ?>"></div>
    </div>


	<?php if ($overlay_pattern != "none"){ ?>
		<div class="videoPatternOverlay <?= $overlay_pattern; ?>" ></div>
	<?php }  ?>

	<?php if ($text_overlay == "yes") { ?>
		
		<div class="videoOverlay">
		<div class="container">
				<div class="row">
					<div class="col-112">

			<div class="container">
				<div class="row">
					<div class="col-10">
						<div class="videoHeadline">
							<?= $headline; ?>
						</div>
						<div class="button-logo">
							<a href="<?= $cta_link; ?>" rel="bookmark" class="callToAction btn right">
								<span><?= $cta_text; ?></span>
							</a>
						</div>
					</div>
				</div>
			</div>
			</div>
				</div>
			</div>
		</div>

	<?php } ?>
	
</div>



<?php if ($full_width == "yes"){
echo '<div class="container"><div class="row"><div class="col-12">';
} ?>
