<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}	

?>

</section>

<?php

if( have_rows('work_repeater') ):
    while( have_rows('work_repeater') ) : the_row();
      
      $post_id = get_sub_field('post');
      $client_name = get_field('client_name', $post_id);
      $preview_title = get_field('preview_title', $post_id);
      $preview_video_id = get_field('preview_video_id', $post_id);
      $featured_image = get_the_post_thumbnail_url($post_id,'large')
    ?>

      
    <a href="<?= get_permalink($post_id); ?>" rel="bookmark">
             
        <div class="work_preview">
            <!-- Scaling mask contains the video -->
            <div class="work_preview-mask">

                <div class="clientName">
                    <?= $client_name; ?>
                </div>

                <div class="work_call_to_action">
                    <span>View work</span>
                </div>

                <video src="<?= $preview_video_id; ?>" autoplay muted playsinline loop class="video-scroll-scale"></video>

            </div>

        </div>
    </a>



  <?php
    endwhile;
    wp_reset_postdata();

else :
endif;

?>

<script src="https://muse.ai/static/js/embed-player.min.js"></script>
<script>

document.addEventListener("scroll", () => {
  const masks = document.querySelectorAll(".work_preview-mask");
  const vh = window.innerHeight;

  masks.forEach(mask => {
    const rect = mask.getBoundingClientRect();

    // --- Scale (unchanged) ---
    const visibleTop = Math.max(0, 0 - rect.top);
    const visibleBottom = Math.min(vh, vh - rect.top);
    const visible = Math.max(0, Math.min(vh, visibleBottom)) - visibleTop;
    const progress = visible / vh; // how much of viewport this section fills

    const minScale = 0.8;
    const maxScale = 1.0;
    const scale = minScale + (maxScale - minScale) * Math.min(progress, 1);
    mask.style.transform = `translate(-50%, -50%) scale(${scale})`;

    // --- Opacity (now center-point based) ---
    const minOpacity = 0.75;
    const fadeZone = 0.1; // 10% at top and bottom

    // Find element's center relative to viewport
    const centerY = rect.top + rect.height / 2;
    const centerRatio = centerY / vh; // 0 = top, 1 = bottom

    let opacity = 1;

    if (centerRatio < fadeZone) {
      // Top 10%
      const fadeProgress = centerRatio / fadeZone; // 0→1
      opacity = minOpacity + (1 - minOpacity) * fadeProgress;
    } else if (centerRatio > 1 - fadeZone) {
      // Bottom 10%
      const fadeProgress = (1 - centerRatio) / fadeZone; // 0→1
      opacity = minOpacity + (1 - minOpacity) * fadeProgress;
    }

    mask.style.opacity = opacity;
  });
});



</script>


<section class="section">