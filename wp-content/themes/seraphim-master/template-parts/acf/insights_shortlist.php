<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$title = get_sub_field( 'title' ) ?: 'Insights';
$all_insights_link = get_sub_field('all_insights_link');
$featured_insight = get_sub_field('featured_insight');

$args = array(
    'post_type'      => 'insight',
    'posts_per_page' => 5,
    'orderby'        => 'date',
    'order'          => 'DESC',
);

if ( $featured_insight ) {
    $args['post__not_in'] = array( $featured_insight->ID );
    $args['posts_per_page'] = 4;
}

$query = new WP_Query( $args );
?>

<div class="shortInsightsCon">
    <div class="shortInsightsHeader">
        <h2><?php echo $title ? esc_html( $title ) : 'Third party research'; ?></h2>
        <?php if ( $all_insights_link ) : ?>
            <a href="<?php echo esc_url( $all_insights_link['url'] ); ?>" target="<?php echo esc_attr( $all_insights_link['target'] ?: '_self' ); ?>">
                View All <?php echo esc_html( $all_insights_link['title'] ); ?>
            </a>
        <?php endif; ?>
    </div>

    <?php if ( $featured_insight || $query->have_posts() ) : ?>
        <div class="insightsShortList">
            <?php
            // Featured Insight
            if ( $featured_insight ) :
                $post = $featured_insight;
                setup_postdata( $post );
                $insight_types = get_the_terms( get_the_ID(), 'insight-type' );
                $insight_type = '';
                if ( ! is_wp_error( $insight_types ) && ! empty( $insight_types ) ) {
                    foreach ( $insight_types as $term ) {
                            $insight_type = $term->name;
                            break;
                    }
                }
                $thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'large' ) ?: 'src/img/card-background-ssit-v1.jpg';
                ?>
                <div class="insightFeatured">
                    <a href="<?php the_permalink(); ?>" class="insightItem featured">
                        <div class="insightItem__image" style="background-image: url('<?php echo esc_url( $thumbnail_url ); ?>');"></div>
                        <div class="insightItem__content">
                            <div class="insightItem__meta">
                                <?php if ( $insight_type ) : ?>
                                    <span class="caption"><?php echo esc_html( $insight_type ); ?></span>
                                <?php endif; ?>
                                <span class="caption"><?php echo get_the_date( 'd M Y' ); ?></span>
                            </div>
                            <h3><?php the_title(); ?></h3>
                            <div class="insightItem__excerpt">
                                <?php echo wp_trim_words( get_the_excerpt(), 25 ); ?>
                            </div>
                            <span class="readMore">Read More</span>
                        </div>
                    </a>
                </div>
                <?php wp_reset_postdata(); ?>
            <?php elseif ( $query->have_posts() ) :
                $query->the_post();
                $insight_types = get_the_terms( get_the_ID(), 'insight-type' );
                $insight_type = '';
                if ( ! is_wp_error( $insight_types ) && ! empty( $insight_types ) ) {
                    foreach ( $insight_types as $term ) {
                            $insight_type = $term->name;
                            break;
                    }
                }
                $thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'large' ) ?: 'src/img/card-background-ssit-v1.jpg';
                ?>
                <div class="insightFeatured">
                    <a href="<?php the_permalink(); ?>" class="insightItem featured">
                        <div class="insightItem__image" style="background-image: url('<?php echo esc_url( $thumbnail_url ); ?>');"></div>
                        <div class="insightItem__content">
                            <div class="insightItem__meta">
                                <?php if ( $insight_type ) : ?>
                                    <span class="caption"><?php echo esc_html( $insight_type ); ?></span>
                                <?php endif; ?>
                                <span class="caption"><?php echo get_the_date( 'd M Y' ); ?></span>
                            </div>
                            <h3><?php the_title(); ?></h3>
                            <div class="insightItem__excerpt">
                                <?php echo wp_trim_words( get_the_excerpt(), 25 ); ?>
                            </div>
                            <span class="readMore">Read More</span>
                        </div>
                    </a>
                </div>
            <?php endif; ?>

            <div class="insightList">
                <?php while ( $query->have_posts() ) : $query->the_post();
                    $insight_types = get_the_terms( get_the_ID(), 'insight-type' );
                    $insight_type = '';
                    if ( ! is_wp_error( $insight_types ) && ! empty( $insight_types ) ) {
                        foreach ( $insight_types as $term ) {
                                $insight_type = $term->name;
                                break;
                        }
                    }
                    ?>
                    <a href="<?php the_permalink(); ?>" class="insightItem">
                        <div class="insightItem__image" style="background-image: url('<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'medium' ) ?: 'src/img/card-background-ssit-v1.jpg' ); ?>');"></div>
                        <div class="insightItem__content">
                            <div class="insightItem__meta">
                                <?php if ( $insight_type ) : ?>
                                    <span class="caption"><?php echo esc_html( $insight_type ); ?></span>
                                <?php endif; ?>
                                <span class="caption"><?php echo get_the_date( 'd M Y' ); ?></span>
                            </div>
                            <h4><?php the_title(); ?></h4>
                        </div>
                    </a>
                <?php endwhile; wp_reset_postdata(); ?>
            </div> <!-- End insightList -->
        </div>
    <?php endif; ?>
</div>
