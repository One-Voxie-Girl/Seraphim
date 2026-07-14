<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$title = get_sub_field( 'title' );
$link  = get_sub_field( 'link' );

$args = array(
    'post_type'      => 'insight',
    'posts_per_page' => 3,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'tax_query'      => array(
        array(
            'taxonomy' => 'insight_type',
            'field'    => 'slug',
            'terms'    => 'research',
        ),
    ),
);

$query = new WP_Query( $args );
?>

<section class="research-shortlist py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4 border-bottom pb-3">

            <h2 class="mb-0">Third Party Research</h2>

            <?php if ( $link ) : ?>
                <a href="<?php echo esc_url( $link['url'] ); ?>" class="text-decoration-none" target="<?php echo esc_attr( $link['target'] ?: '_self' ); ?>">
                    <?php echo esc_html( $link['title'] ); ?>
                </a>
            <?php endif; ?>
        </div>

        <?php if ( $query->have_posts() ) : ?>
            <div class="research-posts">
                <?php while ( $query->have_posts() ) : $query->the_post(); 
                    $insight_types = get_the_terms( get_the_ID(), 'insight_type' );
                    $research_tag = '';
                    if ( ! is_wp_error( $insight_types ) && ! empty( $insight_types ) ) {
                        foreach ( $insight_types as $term ) {
                            if ( $term->slug === 'research' ) {
                                $research_tag = $term->name;
                                break;
                            }
                        }
                        // Fallback to first term if 'research' slug match not found (unlikely due to query)
                        if ( empty( $research_tag ) ) {
                            $research_tag = $insight_types[0]->name;
                        }
                    }
                    ?>
                    <div class="research-post-row row mb-5 align-items-center">
                        <div class="col-md-3">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="post-thumbnail overflow-hidden" style="border-radius: 4px;">
                                    <?php the_post_thumbnail( 'medium', array( 'class' => 'img-fluid w-100 h-100 object-fit-cover' ) ); ?>
                                </div>
                            <?php else : ?>
                                <div class="post-thumbnail-placeholder bg-light d-flex align-items-center justify-content-center" style="height: 150px; border-radius: 4px;">
                                    <span class="text-muted small">No Image</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-9 mt-3 mt-md-0">
                            <div class="post-meta mb-2 d-flex align-items-center gap-2">
                                <?php if ( $research_tag ) : ?>
                                    <span class="research-tag text-uppercase small" style="letter-spacing: 0.1em; font-weight: 600;"><?php echo esc_html( $research_tag ); ?></span>
                                <?php endif; ?>
                                <span class="publish-date text-muted small"><?php echo get_the_date( 'd.m.Y' ); ?></span>
                            </div>
                            <h3 class="post-title h4 mb-0">
                                <a href="<?php the_permalink(); ?>" class="text-dark text-decoration-none" style="font-family: Osiris, sans-serif;">
                                    <?php the_title(); ?>
                                </a>
                            </h3>
                        </div>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        <?php endif; ?>
    </div>
</section>

    




    
<?php
// End of file
