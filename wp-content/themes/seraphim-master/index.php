<?php get_header(); ?>


    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
      <?php get_template_part('template-parts/acf-loop'); ?>
    <?php endwhile; the_posts_pagination(); else: ?>
      <p><?php _e('No posts found.', 'make-us-care-3-0'); ?></p>
    <?php endif; ?>


<?php get_footer(); ?>
