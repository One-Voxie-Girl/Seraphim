<?php get_header(); 

global $post;
$postcat = get_the_category( $post->ID );
$catID = $postcat[0]->cat_ID;

?>

<?php if ( $catID == 19 ) : ?>

  <?php get_template_part( 'template-parts/acf-loop' ); ?>

<?php else : ?>

  <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

  <div class="container">
    <article <?php post_class('mb-5'); ?>>
      <div class="headerSection">
        <h1 class="entry-title"><?php the_title(); ?></h1>
      </div>
      <div class="entry-content">
        <?php the_content(); ?>
      </div>
      <?php wp_link_pages(); ?>
    </article>
  </div>

  <?php endwhile; endif; ?>

<?php endif; ?>

<?php get_footer(); ?>
