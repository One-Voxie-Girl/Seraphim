<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}


$video_id = get_sub_field('video_id');
$portrait_version = get_sub_field('portrait_version');

$attachment_id = get_sub_field('placeholder_image');
$size = "full size";
$image = wp_get_attachment_image_src( $attachment_id, $size );

$full_width = get_sub_field('full_width');

$overlay_pattern = get_sub_field('overlay_pattern');

//player settings
$controls = get_sub_field('controls');
$autoplay = get_sub_field('autoplay');
$loop = get_sub_field('loop');
$volume = get_sub_field('volume');

// Add aspect ratio or custom height if needed
$min_height = get_sub_field('min_height') ?: '400px';

$text_overlay = get_sub_field('text_overlay');
$headline = get_sub_field('headline');
$cta_text = get_sub_field('cta_text');
$cta_link = get_sub_field('cta_link');

$image_url = ($image && isset($image[0])) ? $image[0] : '';



?>

<?php if ($controls != "1"){
    $controls = '0';
} ?>

<?php if ($full_width == "yes"){
    echo '</div></div></div>';
} ?>

<div class="videoCon <?php if ($full_width == "yes"){ echo 'fullWidthVideo';}?>" style="<?php if ($image_url): ?>background-image: url(<?= $image_url; ?>);<?php endif; ?> min-height: <?= $min_height; ?>;">

    <div class="videoPlayerCon">
        <?php if($image_url) { ?>
            <img src="<?php echo $image_url; ?>" style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; z-index: -1;" />
        <?php } ?>



        <video
                class="video"
                preload="auto"

                <?php if ($autoplay): ?>autoplay<?php endif; ?>

                <?php if ($controls || !$autoplay): ?>controls<?php endif; ?>
                <?php if ($loop): ?>loop<?php endif; ?>
                <?php if (!$volume): ?>muted<?php endif; ?>
                style="width: 100%; height: 100%; object-fit: cover; border-radius: 20px;"
        >

            <?php if ($video_id): ?>
                <source
                        src="<?php echo esc_url($video_id); ?>"
                        type="video/mp4"
                >
            <?php endif; ?>

            <!-- Fallback text -->

        </video>
    </div>
    <?php if ($overlay_pattern != "none"){ ?>
        <div class="videoPatternOverlay <?= $overlay_pattern; ?>" ></div>
    <?php }  ?>

    <?php if ($text_overlay == "yes") { ?>

        <div class="videoOverlay">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-lg-10">
                        <div class="videoHeadline">
                            <?= $headline; ?>
                        </div>
                        <?php if ($cta_link): ?>
                            <div class="button-logo">
                                <a href="<?= $cta_link; ?>" rel="bookmark" class="button secondary right">
                                    <span><?= $cta_text; ?></span>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    <?php } ?>

</div>



<?php if ($full_width == "yes"){
    echo '<div class="container"><div class="row"><div class="col-12">';
} ?>













