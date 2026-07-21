<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$title = get_sub_field( 'title' );
$link  = get_sub_field( 'link' );
$all_results_link = get_sub_field('all_results_link');

$args = array(
    'post_type'      => 'result',
    'posts_per_page' => 4,
    'orderby'        => 'date',
    'order'          => 'DESC',
);

$query = new WP_Query( $args );
?>

<div class="resultsDownloadCon">
    <div class="resultsDownloadHeader">
        <h2><?php echo $title ? esc_html( $title ) : 'Latest results'; ?></h2>
        <?php if ( $all_results_link ) : ?>
            <a href="<?php echo esc_url( $all_results_link['url'] ); ?>" target="<?php echo esc_attr( $all_results_link['target'] ?: '_self' ); ?>">
                View All <?php echo esc_html( $all_results_link['title'] ); ?>
            </a>
        <?php endif; ?>
    </div>

    <?php if ( $query->have_posts() ) : ?>
        <div class="resultsDownloadList">
            <?php while ( $query->have_posts() ) : $query->the_post();
                $document = get_field( 'document' ) ?: get_field( 'result_file' );
                $document_url = is_array( $document ) ? $document['url'] : ( is_numeric( $document ) ? wp_get_attachment_url( $document ) : $document );
                if ( ! $document_url ) {
                    $document_url = get_the_permalink();
                }
                ?>
                <div class="resultsDownloadItem">
                    <div class="resultsDownloadItem__content">
                        <h4><?php the_title(); ?></h4>
                        <span class="caption"><?php echo get_the_date( 'd M Y' ); ?></span>
                    </div>

                    <a href="<?php echo esc_url( $document_url ); ?>" class="button secondary resultsDownloadItem__button" <?php echo ! empty( $document ) ? 'download' : ''; ?>>
                        Open document <i class="ci-Download"></i>
                    </a>
                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    <?php endif; ?>
</div>
