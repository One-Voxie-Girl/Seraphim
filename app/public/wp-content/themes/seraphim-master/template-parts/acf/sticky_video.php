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

$controls = get_sub_field('controls');
$autoplay = get_sub_field('autoplay');
$loop = get_sub_field('loop');
$volume = get_sub_field('volume');

$text_overlay = get_sub_field('text_overlay');
$headline = get_sub_field('headline');
$cta_text = get_sub_field('cta_text');
$cta_link = get_sub_field('cta_link');




?>


              
	
</section>

<div class="video-scroll-section">
  <div class="video-scroll-sticky">
      <!--div class="muse-video-player"
           data-video="<?= $video_id; ?>"
           data-autoplay="<?= $autoplay; ?>"
           data-loop="<?= $loop; ?>"
           data-volume="0"
           data-sizing="fill"
           data-style="<?= $controls; ?>"></div-->


<?php /*video src="<?= $video_id; ?>" autoplay muted playsinline loop class="video-scroll-scale"></video */?>

<?php if($image != "") { ?>
  <img src="<?php echo $image[0]; ?>" />
<?php } ?>

<video
    class="video-scroll-scale"
    autoplay muted playsinline loop
    preload="auto"
    style="width: 100%; height: 100%; object-fit: cover; transform: scale(0.5); border-radius: 100px;"
  >
  <?php if ($portrait_version): ?>
    <source
      src="<?php echo esc_url($portrait_version); ?>"
      type="video/mp4"
      media="(max-width: 767px)"
    >
  <?php endif; ?>

  <?php if ($video_id): ?>
    <source
      src="<?php echo esc_url($video_id); ?>"
      type="video/mp4"
    >
  <?php endif; ?>

  <!-- Fallback text -->
  
</video>

  </div>
</div>




<script src="https://muse.ai/static/js/embed-player.min.js"></script>


<script>

document.addEventListener("scroll", () => {
  const section = document.querySelector(".video-scroll-section");
  const scaleTarget = document.querySelector(".video-scroll-scale");
  if (!section || !scaleTarget) return;

  const rect = section.getBoundingClientRect();
  const progress = Math.min(Math.max((window.innerHeight - rect.top) / rect.height, 0), 1);

  // Map scroll progress (0 → 1) to scale (1 → full width)
  const minScale = 0.5; // start at 60% of viewport width
  const maxScale = 1.0; // end at 100%
  const scale = minScale + (maxScale - minScale) * progress;

  scaleTarget.style.transform = `scale(${scale})`;

  // --- Border radius (100px → 0px only in last 10% of scroll) ---
  let radius = 100;
  if (progress > 0.9) {
    const localProgress = (progress - 0.9) / 0.1; // map 0.9–1.0 → 0–1
    radius = 100 - (100 * localProgress);
  }
  scaleTarget.style.borderRadius = radius + "px";

});

</script>

<section class="section">