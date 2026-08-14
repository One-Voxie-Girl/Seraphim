<?php get_header(); 

global $post;
$postcat = get_the_category( $post->ID );
$catID = $postcat[0]->cat_ID;

?>

<?php if ( $catID == 19 ) : ?>

  <?php get_template_part( 'template-parts/acf-loop' ); ?>

<?php else : ?>

  <?php if ( have_posts() ) : while ( have_posts() ) : the_post();
        get_template_part( 'template-parts/content/content', $post->post_type);


  endwhile; endif; ?>

<?php endif; ?>

<?php get_footer(); ?>
