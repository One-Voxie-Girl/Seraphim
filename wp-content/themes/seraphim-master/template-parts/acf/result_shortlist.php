<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/*TODO
 * fix button hover animation
*/

$title = get_sub_field( 'title' );
$link  = get_sub_field( 'link' );
$all_results_link = get_sub_field('all_results_link');

$orientation = get_sub_field('orientation'); //0 : Vertical  1 : Horizontal
$result_count = get_sub_field('result_count');

$args = array(
    'post_type'      => 'result',
    'posts_per_page' => $result_count ?: 4,
    'orderby'        => 'date',
    'order'          => 'DESC',
);

$query = new WP_Query( $args );
?>

<style>
    .resultsDownloadCon--horizontal .resultsDownloadList {
        grid-template-columns: repeat(3, 1fr);
    }

    @media (max-width: 991.98px) {
        .resultsDownloadCon--horizontal .resultsDownloadList {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 767.98px) {
        .resultsDownloadCon--horizontal .resultsDownloadList {
            grid-template-columns: 1fr;
        }
    }

    .resultsDownloadCon--horizontal .resultsDownloadItem {
        flex-direction: column;
        align-items: flex-start;
        justify-content: space-between;
        min-height: 240px;
        gap: 20px;
    }

    .resultsDownloadCon--horizontal .resultsDownloadItem__content h4 {
        margin-bottom: 12px;
    }

    .resultsDownloadCon--horizontal .resultsDownloadItem__content .caption {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .resultsDownloadCon--horizontal .resultsDownloadItem__content .caption .format {
        display: inline-flex;
        align-items: center;
    }

    .resultsDownloadCon--horizontal .resultsDownloadItem__content .caption .format::before {
        content: "";
        display: inline-block;
        width: 1px;
        height: 12px;
        background: rgba(255, 255, 255, 0.3);
        margin-right: 10px;
    }
</style>

<div class="resultsDownloadCon <?php echo $orientation == 1 ? 'resultsDownloadCon--horizontal' : ''; ?>">
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
                $media_location = get_field( 'media_location' );
                $media_link = get_field( 'media_link' );
                $document = get_field( 'document' ) ?: get_field( 'result_file' );

                if ( $media_location === '0' || $media_location === 0 ) {
                    $document_url = $media_link;
                } else {
                    $document_url = is_array( $document ) ? $document['url'] : ( is_numeric( $document ) ? wp_get_attachment_url( $document ) : $document );
                }

                if ( ! $document_url ) {
                    $document_url = get_the_permalink();
                }

                $file_extension = strtoupper( pathinfo( $document_url, PATHINFO_EXTENSION ) );
                $video_extensions = array( 'MP4', 'WEBM', 'OGV', 'MOV', 'AVI', 'WMV' );
                $is_video = in_array( $file_extension, $video_extensions ) || ( ( $media_location === '0' || $media_location === 0 ) && strpos( $document_url, 'vimeo.com' ) !== false ) || ( ( $media_location === '0' || $media_location === 0 ) && strpos( $document_url, 'youtube.com' ) !== false ) || ( ( $media_location === '0' || $media_location === 0 ) && strpos( $document_url, 'youtu.be' ) !== false );
                ?>
                <div class="resultsDownloadItem">
                    <div class="resultsDownloadItem__content">
                        <h4><?php the_title(); ?></h4>
                        <span class="caption">
                            <?php echo get_the_date( 'd M Y' ); ?>
                            <?php if ( $orientation == 1 ) : ?>
                                <span class="format"><?php echo $is_video ? 'VIDEO' : ( $file_extension ?: 'PDF' ); ?></span>
                            <?php endif; ?>
                        </span>
                    </div>

                    <?php if ( $is_video ) : ?>
                        <a href="<?php echo esc_url( $document_url ); ?>" class="button secondary resultsDownloadItem__button" target="_blank">
                            Watch video <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polygon points="10 8 16 12 10 16 10 8"></polygon></svg>
                        </a>
                    <?php else : ?>
                        <a href="<?php echo esc_url( $document_url ); ?>" class="button secondary resultsDownloadItem__button" <?php echo ! empty( $document ) ? 'download' : ''; ?>>
                            Open document <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    <?php endif; ?>
</div>
