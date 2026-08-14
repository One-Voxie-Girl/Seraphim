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
                    <div class="col-12 col-md-6 col-lg-4 insight-card-wrapper">
                        <div class="insight-card h-100 border rounded shadow-sm overflow-hidden d-flex flex-column">
                            <?php if ( $thumbnail_url ) : ?>
                                <div class="insight-card__image" style="height: 200px; background-image: url('<?php echo esc_url( $thumbnail_url ); ?>'); background-size: cover; background-position: center;"></div>
                            <?php endif; ?>
                            <div class="insight-card__content p-4 d-flex flex-column flex-grow-1">
                                <div class="insight-card__meta mb-2 text-muted small">
                                    <?php if ( !empty($type_names) ) : ?>
                                        <span class="badge bg-secondary me-2"><?php echo esc_html( implode(', ', $type_names) ); ?></span>
                                    <?php endif; ?>
                                    <span><?php echo get_the_date( 'd M Y' ); ?></span>
                                </div>
                                <h3 class="h5 insight-card__title mb-3">
                                    <a href="<?php the_permalink(); ?>" class="text-decoration-none text-dark">
                                        <?php the_title(); ?>
                                    </a>
                                </h3>
                                <div class="insight-card__excerpt mb-4">
                                    <?php echo wp_trim_words( get_the_excerpt(), 20 ); ?>
                                </div>
                                <div class="mt-auto">
                                    <a href="<?php the_permalink(); ?>" class="btn btn-outline-primary btn-sm">Read More</a>
                                </div>
                            </div>
                        </div>
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
