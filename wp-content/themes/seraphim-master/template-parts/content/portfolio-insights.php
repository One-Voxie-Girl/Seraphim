<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$portfolio_id = get_the_ID();

$args = array(
    'post_type'      => 'insight',
    'posts_per_page' => 3,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'meta_query'     => array(
        array(
            'key'     => 'associated_companies',
            'value'   => '"' . $portfolio_id . '"',
            'compare' => 'LIKE',
        ),
    ),
);

$query = new WP_Query( $args );

if ( $query->have_posts() ) : ?>
    <section class="insights-shortlist-section py-5 portfolio-insights">
        <div class="container">
            <div class="row">
                <div class="col-12 insightSectionDivide">
                    <div class="insightTitleLink">
                        <h3>Insights on <?php echo get_the_title( $portfolio_id ); ?></h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php while ( $query->have_posts() ) : $query->the_post();
                    $thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'large' );
                    $terms = get_the_terms( get_the_ID(), 'insight-type' );
                    $term_name = ! empty( $terms ) && ! is_wp_error( $terms ) ? $terms[0]->name : '';
                    $is_video = has_term( 'video', 'insight-type', get_the_ID() );
                    $duration = get_field( 'duration', get_the_ID() );
                    ?>
                    <div class="col-12 col-md-6 col-lg-4 mb-4">
                        <a href="<?php the_permalink(); ?>" class="contentCard contentCard--grid <?php echo $is_video ? 'contentCard--video' : ''; ?>">
                            <div class="activeCorners gradientCorners">
                                <div class="top"></div>
                                <div class="bottom"></div>
                            </div>
                            <div class="contentCard__image" style="background-image: url('<?php echo esc_url( $thumbnail_url ); ?>');">
                                <?php if ( $is_video ) : ?>
                                    <span class="videoPlayButton" aria-hidden="true"></span>
                                <?php endif; ?>
                            </div>
                            <div class="contentCard__meta">
                                <span class="caption"><?php echo esc_html( $term_name ); ?></span>
                                <?php if ( $is_video && $duration ) : ?>
                                    <span class="caption"><?php echo esc_html( $duration ); ?></span>
                                <?php endif; ?>
                                <span class="caption"><?php echo get_the_date( 'd M Y' ); ?></span>
                            </div>
                            <h4><?php the_title(); ?></h4>
                        </a>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </div>
    </section>
<?php endif; ?>
