<?php //get_header();
//
//
//$category = get_category( get_query_var( 'cat' ) );
//$cat_id = $category->cat_ID;	?>
<!---->
<!---->
<!--<header class="headerSection">-->
<!--  <div class="container">-->
<!--    <h1>--><?php //echo get_the_category_by_ID( $cat_id ); ?><!--</h1>-->
<!--    <p class="large text-muted">--><?php //the_archive_description(); ?><!--</p>-->
<!--  </div>-->
<!--</header>-->
<!---->
<!---->
<?php //if ($cat_id == 19) {
//
//get_template_part( 'template-parts/acf/category_work_section', 'none');
//
//} else { ?>
<!---->
<!--<!--- Start --->-->
<!---->
<!---->
<?php
//$term = get_queried_object();
//$children = get_categories([
//  'child_of' => 5,
//  'hide_empty' => false,
//]);
//?>
<!---->
<!--<header class="archive-header container">-->
<!---->
<!--  --><?php //if ($term && !empty($term->description)) : ?>
<!--    <p class="archive-description">--><?php //echo esc_html($term->description); ?><!--</p>-->
<!--  --><?php //endif; ?>
<!---->
<!--  --><?php //if ($children) : ?>
<!--    <div class="archive-filters">-->
<!--      <a href="/resources/"-->
<!--          class="filter-pill --><?php //if (is_category(5)) echo 'active'; ?><!--">-->
<!--          All-->
<!--        </a>-->
<!--      --><?php //foreach ($children as $child) : ?>
<!--        <a href="--><?php //echo esc_url(get_category_link($child->term_id)); ?><!--"-->
<!--          class="filter-pill --><?php //if (is_category($child->term_id)) echo 'active'; ?><!--">-->
<!--          --><?php //echo esc_html($child->name); ?>
<!--        </a>-->
<!--      --><?php //endforeach; ?>
<!--    </div>-->
<!--  --><?php //endif; ?>
<!--</header>-->
<!---->
<!--<section class="archive-content">-->
<!---->
<!--  <!-- Row 1: Horizontal scroll -->-->
<!--  <section class="featured-section">-->
<!--    <div class="featured-wrapper">-->
<!--      <div class="featured-row">-->
<!--        --><?php
//        $featured_query = new WP_Query([
//          'posts_per_page' => 5,
//          'cat' => get_queried_object_id(),
//          'paged' => 1
//        ]);
//
//        if ($featured_query->have_posts()) :
//          while ($featured_query->have_posts()) : $featured_query->the_post(); ?>
<!--            <article class="featured-card fade-on-scroll">-->
<!--              <a href="--><?php //the_permalink(); ?><!--" class="featured-inner">-->
<!--                --><?php //if (has_post_thumbnail()) : ?>
<!--                  <div class="featured-image">-->
<!--                    --><?php //the_post_thumbnail('large'); ?>
<!--                  </div>-->
<!--                --><?php //endif; ?>
<!--                <div class="featured-content">-->
<!--                  <h2>--><?php //the_title(); ?><!--</h2>-->
<!--                  <p>--><?php //echo wp_trim_words(get_the_excerpt(), 20); ?><!--</p>-->
<!--                </div>-->
<!--              </a>-->
<!--            </article>-->
<!--        --><?php //endwhile; wp_reset_postdata(); endif; ?>
<!--      </div>-->
<!--    </div>-->
<!--  </section>-->
<!---->
<!---->
<!--  <!-- Row 2 onwards: Grid layout -->-->
<!--  <div id="archive-grid" class="archive-grid">-->
<!--    --><?php
//    if (have_posts()) :
//      while (have_posts()) : the_post(); ?>
<!--        <article --><?php //post_class('grid-card'); ?><!-->-->
<!--          <a href="--><?php //the_permalink(); ?><!--">-->
<!--            --><?php //if (has_post_thumbnail()) : ?>
<!--              <div class="grid-image">--><?php //the_post_thumbnail('medium_large'); ?><!--</div>-->
<!--            --><?php //endif; ?>
<!--            <div class="grid-content">-->
<!--              <h3>--><?php //the_title(); ?><!--</h3>-->
<!--            </div>-->
<!--          </a>-->
<!--        </article>-->
<!--    --><?php //endwhile; endif; ?>
<!--  </div>-->
<!--</section>-->
<!---->
<!---->
<!---->
<!--<script>-->
<!---->
<!--document.addEventListener("DOMContentLoaded", () => {-->
<!--  let page = 2;-->
<!--  const grid = document.querySelector("#archive-grid");-->
<!--  let loading = false;-->
<!---->
<!--  window.addEventListener("scroll", () => {-->
<!--    if (loading) return;-->
<!--    const scrollY = window.scrollY + window.innerHeight;-->
<!--    const trigger = document.body.offsetHeight - 500;-->
<!---->
<!--    if (scrollY >= trigger) {-->
<!--      loading = true;-->
<!--      fetch(`${window.location.pathname}?paged=${page}`)-->
<!--        .then(res => res.text())-->
<!--        .then(data => {-->
<!--          const parser = new DOMParser();-->
<!--          const html = parser.parseFromString(data, "text/html");-->
<!--          const newPosts = html.querySelectorAll(".grid-card");-->
<!--          if (newPosts.length) {-->
<!--            newPosts.forEach(post => grid.appendChild(post));-->
<!--            page++;-->
<!--            loading = false;-->
<!--          }-->
<!--        });-->
<!--    }-->
<!--  });-->
<!--});-->
<!---->
<!--document.addEventListener("DOMContentLoaded", () => {-->
<!--  const section = document.querySelector(".featured-section");-->
<!--  const row = document.querySelector(".featured-row");-->
<!--  if (!section || !row) return;-->
<!---->
<!--  const totalScrollDistance = row.scrollWidth - window.innerWidth + window.innerHeight;-->
<!--  section.style.height = `${totalScrollDistance}px`;-->
<!---->
<!--  let targetScroll = 0;-->
<!--  let currentScroll = 0;-->
<!---->
<!--  const smoothScroll = () => {-->
<!--    currentScroll += (targetScroll - currentScroll) * 0.08;-->
<!--    row.style.transform = `translateX(-${currentScroll}px)`;-->
<!--    requestAnimationFrame(smoothScroll);-->
<!--  };-->
<!---->
<!--  smoothScroll();-->
<!---->
<!--  window.addEventListener("scroll", () => {-->
<!--    const rect = section.getBoundingClientRect();-->
<!--    const start = rect.top;-->
<!--    const end = rect.bottom - window.innerHeight;-->
<!---->
<!--    if (start <= 0 && end >= 0) {-->
<!--      targetScroll = (window.scrollY - section.offsetTop);-->
<!--    }-->
<!--  });-->
<!--});-->
<!---->
<!---->
<!---->
<!--</script>-->
<!---->
<!---->
<!--<!--- END --->-->
<!---->
<?php //}
//
//?>
<!---->
<!---->
<?php //get_footer(); ?>


<?php
/**
 * The template for displaying archive pages
 *
 * Learn more: https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();

$container = get_theme_mod( 'understrap_container_type' );
?>

    <div class="wrapper" id="archive-wrapper">

        <div class="<?php echo esc_attr( $container ); ?>" id="content" tabindex="-1">

            <div class="row">

                <?php
                // Do the left sidebar check and open div#primary.
                get_template_part( 'global-templates/left-sidebar-check' );
                ?>

                <main class="site-main" id="main">

                    <?php
                    if ( have_posts() ) {
                        ?>
                        <header class="page-header">
                            <?php
                            the_archive_title( '<h1 class="page-title">', '</h1>' );
                            the_archive_description( '<div class="taxonomy-description">', '</div>' );
                            ?>
                        </header><!-- .page-header -->
                        <?php
                        // Start the loop.
                        while ( have_posts() ) {
                            the_post();

                            /*
                             * Include the Post-Format-specific template for the content.
                             * If you want to override this in a child theme, then include a file
                             * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                             */
                            get_template_part( 'loop-templates/content', get_post_format() );
                        }
                    } else {
                        get_template_part( 'loop-templates/content', 'none' );
                    }
                    ?>

                </main>

                <?php
                // Display the pagination component.
                understrap_pagination();

                // Do the right sidebar check and close div#primary.
                get_template_part( 'global-templates/right-sidebar-check' );
                ?>

            </div><!-- .row -->

        </div><!-- #content -->

    </div><!-- #archive-wrapper -->

<?php
get_footer();
