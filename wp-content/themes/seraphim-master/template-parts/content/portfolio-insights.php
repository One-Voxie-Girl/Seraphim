<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$portfolio_id = get_the_ID();

$args = array(
    'post_type'      => 'insight',
    'posts_per_page' => 5,
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
    <div class="thirdPartyResearchCon portfolio-insights">
        <div class="container">
            <div class="thirdPartyResearchHeader">
                <h2>Insights on <?php echo get_the_title( $portfolio_id ); ?></h2>
            </div>
            <div class="thirdPartyResearchList">
                <?php while ( $query->have_posts() ) : $query->the_post();
                    $insight_types = get_the_terms( get_the_ID(), 'insight-type' );
                    $insight_type = '';
                    if ( ! is_wp_error( $insight_types ) && ! empty( $insight_types ) ) {
                        $insight_type = $insight_types[0]->name;
                    }
                    $thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'large' ) ?: '';
                    ?>
                    <a href="<?php the_permalink(); ?>" class="thirdPartyResearchItem">
                        <?php if ( $thumbnail_url ) : ?>
                            <div class="thirdPartyResearchItem__image" style="background-image: url('<?php echo esc_url( $thumbnail_url ); ?>');"></div>
                        <?php endif; ?>
                        <div class="thirdPartyResearchItem__content">
                            <div class="thirdPartyResearchItem__meta">
                                <?php if ( $insight_type ) : ?>
                                    <span class="caption"><?php echo esc_html( $insight_type ); ?></span>
                                <?php endif; ?>
                                <span class="caption"><?php echo get_the_date( 'd M Y' ); ?></span>
                            </div>
                            <h4><?php the_title(); ?></h4>
                        </div>
                    </a>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </div>
    </div>
<?php endif; ?>
