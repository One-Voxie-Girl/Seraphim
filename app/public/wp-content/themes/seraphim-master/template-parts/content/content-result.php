<?php
/**
 * Template part for displaying result items in a list
 */

$title = get_the_title();

$media_location = get_field( 'media_location' );
$media_link = get_field( 'media_link' );
$result_file = get_field( 'result_file' );
if (!$result_file) {
    $result_file = get_field('document');
}

$result_type_terms = get_the_terms( get_the_ID(), 'result-type' );
$result_type = ! empty( $result_type_terms ) && ! is_wp_error( $result_type_terms ) ? $result_type_terms[0]->name : '';

$date_updated = get_the_modified_date( 'd M Y' );

$file_url = '';
$file_type = '';
$is_video = false;
$video_extensions = array( 'MP4', 'WEBM', 'OGV', 'MOV', 'AVI', 'WMV' );

if ( $media_location === '0' || $media_location === 0 ) {
    $file_url = $media_link;
    $file_type = strtoupper( pathinfo( $file_url, PATHINFO_EXTENSION ) );
} else {
    if ( is_array( $result_file ) ) {
        $file_url = $result_file['url'];
        $file_type = strtoupper( pathinfo( $file_url, PATHINFO_EXTENSION ) );
    } elseif ( is_numeric( $result_file ) ) {
        $file_url = wp_get_attachment_url( $result_file );
        $file_type = strtoupper( pathinfo( $file_url, PATHINFO_EXTENSION ) );
    } elseif ( is_string( $result_file ) ) {
        $file_url = $result_file;
        $file_type = strtoupper( pathinfo( $file_url, PATHINFO_EXTENSION ) );
    }
}

if ( in_array( $file_type, $video_extensions ) ) {
    $is_video = true;
}
?>

<div class="result-item">
    <div class="result-item__title">
        <h3><?php echo esc_html( $title ); ?></h3>
    </div>
    
    <?php if ( $is_video && $file_url ) : ?>
        <div class="result-item__video">
            <?php get_template_part( 'template-parts/acf/video', null, array(
                'video_id' => $file_url,
                'controls' => true,
            ) ); ?>
        </div>
    <?php endif; ?>

    <div class="result-item__meta">
        <?php if ( $result_type ) : ?>
            <span class="result-item__type"><?php echo esc_html( $result_type ); ?></span>
        <?php endif; ?>
        
        <span class="result-item__date"><?php echo esc_html( $date_updated ); ?></span>
        
        <?php if ( $file_type ) : ?>
            <span class="result-item__filetype"><?php echo esc_html( $file_type ); ?></span>
        <?php endif; ?>

        <?php if ( $file_url ) : ?>
            <a href="<?php echo esc_url( $file_url ); ?>" class="result-item__download" <?php echo ( $media_location !== '0' && $media_location !== 0 ) ? 'download' : ''; ?>>
                Download <i class="ci-Download"></i>
            </a>
        <?php endif; ?>
    </div>
</div>



