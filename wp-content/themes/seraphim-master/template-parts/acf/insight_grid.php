<?php
/**
 * ACF Template Part: Insight Grid
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$selected_type = get_sub_field('insight_type') ?: 'all';

$args = array(
    'post_type'      => 'insight',
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'DESC',
);

if ( $selected_type && $selected_type !== 'all' ) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'insight-type',
            'field'    => 'slug',
            'terms'    => $selected_type,
        ),
    );
}

$query = new WP_Query( $args );
?>

<section class="insight-grid-section py-5">
    <div class="container">
        <?php if ( $query->have_posts() ) : ?>
            <div class="row g-4 insight-grid-container">
                <?php while ( $query->have_posts() ) : $query->the_post();
                    $insight_types = get_the_terms( get_the_ID(), 'insight-type' );
                    $type_names = [];
                    if ( ! is_wp_error( $insight_types ) && ! empty( $insight_types ) ) {
                        foreach ( $insight_types as $term ) {
                            $type_names[] = $term->name;
                        }
                    }
                    $thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'large' ) ?: '';
                    ?>
                    <div class="col-12 col-md-6 col-lg-4">
                        <a href="<?php the_permalink(); ?>" class="contentCard contentCard--grid">
                            <div class="activeCorners gradientCorners">
                                <div class="top"></div>
                                <div class="bottom"></div>
                            </div>
                            <div class="contentCard__image" style="background-image: url('<?php echo esc_url($thumbnail_url); ?>');"></div>
                            <div class="contentCard__meta">
                                <?php if (!empty($type_names)) : ?>
                                    <span class="caption"><?php echo esc_html(implode(', ', $type_names)); ?></span>
                                <?php endif; ?>
                                <span class="caption"><?php echo get_the_date('d M Y'); ?></span>
                            </div>
                            <h4><?php the_title(); ?></h4>
                        </a>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        <?php else : ?>
            <div class="text-center py-5">
                <p>No insights found.</p>
            </div>
        <?php endif; ?>
    </div>
</section>
