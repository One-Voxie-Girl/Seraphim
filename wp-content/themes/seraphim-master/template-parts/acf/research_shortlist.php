<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$title = get_sub_field( 'title' );
$link  = get_sub_field( 'link' );
$all_research_link = get_sub_field('all_research_link');

$args = array(
    'post_type'      => 'insight',
    'posts_per_page' => 3,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'tax_query'      => array(
        array(
            'taxonomy' => 'insight-type',
            'field'    => 'slug',
            'terms'    => 'research',
        ),
    ),
);

$query = new WP_Query( $args );
?>

<div class="thirdPartyResearchCon">
    <div class="thirdPartyResearchHeader">
        <h2><?php echo $title ? esc_html( $title ) : 'Third party research'; ?></h2>
        <?php if ( $all_research_link ) : ?>
            <a href="<?php echo esc_url( $all_research_link['url'] ); ?>" target="<?php echo esc_attr( $all_research_link['target'] ?: '_self' ); ?>">
                View All <?php echo esc_html( $all_research_link['title'] ); ?>
            </a>
        <?php endif; ?>
    </div>

    <?php if ( $query->have_posts() ) : ?>
        <div class="InsightsShortList">
            <?php while ( $query->have_posts() ) : $query->the_post();
                $insight_types = get_the_terms( get_the_ID(), 'insight-type' );
                $research_tag = '';
                if ( ! is_wp_error( $insight_types ) && ! empty( $insight_types ) ) {
                    foreach ( $insight_types as $term ) {
                        if ( $term->slug === 'research' ) {
                            $research_tag = $term->name;
                            break;
                        }
                    }
                    if ( empty( $research_tag ) ) {
                        $research_tag = $insight_types[0]->name;
                    }
                }
                $thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'medium' ) ?: 'src/img/card-background-ssit-v1.jpg';
                ?>
                <a href="<?php the_permalink(); ?>" class="thirdPartyResearchItem">
                    <div class="thirdPartyResearchItem__image" style="background-image: url('<?php echo esc_url( $thumbnail_url ); ?>');"></div>
                    <div class="thirdPartyResearchItem__content">
                        <div class="thirdPartyResearchItem__meta">
                            <?php if ( $research_tag ) : ?>
                                <span class="caption"><?php echo esc_html( $research_tag ); ?></span>
                            <?php endif; ?>
                            <span class="caption"><?php echo get_the_date( 'd M Y' ); ?></span>
                        </div>
                        <h4><?php the_title(); ?></h4>
                    </div>
                </a>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    <?php endif; ?>
</div>

<?php
// End of file
