<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}	
	
// Vars
?>

<div>
<div class="row">
  <div id="archive-grid" class="archive-grid">
<?php

if( have_rows('post_repeater') ):
    while( have_rows('post_repeater') ) : the_row();
      
      $post_id = get_sub_field('post');
      $title_overwrite = get_sub_field('title_overwrite');
      
      $attachment_id = get_sub_field('image_overwrite');
      $size = "large";
      $image = wp_get_attachment_image_src( $attachment_id, $size );

      if ($title_overwrite) {
        $title = $title_overwrite;
      } else {
        $title = get_the_title($post_id);
      }

      if ($attachment_id) {
        $featuredImage = $image[0];
      } else {
        $featuredImage = get_the_post_thumbnail_url($post_id,'medium_large');
      }

      if (get_sub_field('cta_text')) {
        $cta_text = get_sub_field('cta_text');
      } else {
        $cta_text = "Read more";
      }

    ?>

    

        <article <?php post_class('grid-card'); ?>>
          <a href="<?php the_permalink($post_id); ?>">
              <div class="grid-image"><img src="<?php echo $featuredImage; ?>" /></div>
            <div class="grid-content">
              <h3><?= $title; ?></h3>
            </div>
          </a>
        </article>


  <?php
    endwhile;
    wp_reset_postdata();

else :
endif;

?>

</div>
</div>
</div>