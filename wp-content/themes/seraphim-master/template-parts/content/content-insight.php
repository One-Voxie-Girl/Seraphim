<?php

/**
 * Template part for displaying portfolio items
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 */


$title                = get_the_title();
$tagline              = get_field( 'tagline' );
$insight_file         = get_field( 'insight_file' );
$insight_file_type    = get_field( 'insight_file_type' );
$insight_external     = get_field( 'insight_external_file' );
$associated_companies = get_field( 'associated_companies' );
$insight_type         = get_the_terms( get_the_ID(), 'insight-type' );

$download_link = '';
if ( $insight_file_type == 1 && ! empty( $insight_file ) ) {
    $download_link = $insight_file['url'];
} elseif ( $insight_file_type == 2 && ! empty( $insight_external ) ) {
    $download_link = $insight_external;
}

$embed_content = '';
if ( $insight_file_type == 1 && ! empty( $insight_file ) ) {
    $mime = $insight_file['mime_type'];
    if ( str_contains( $mime, 'video' ) ) {
        // Ensure WordPress media functions are available
        if ( ! function_exists( 'wp_video_shortcode' ) ) {
            require_once ABSPATH . WPINC . '/media.php';
        }



        if ( function_exists( 'wp_video_shortcode' ) ) {


            $embed_content = wp_video_shortcode( array( 'src' => $insight_file['url'] ) );
        } else {
            $embed_content = '<video controls src="' . esc_url( $insight_file['url'] ) . '" style="width:100%; border-radius:10px;"></video>';
        }
    } elseif ( $mime === 'application/pdf' ) {
        $embed_content = '<iframe src="' . esc_url( $insight_file['url'] ) . '" width="100%" height="100%" style="border: none; border-radius: 10px; margin-bottom: 30px;"></iframe>';
    }
} elseif ( $insight_file_type == 2 && ! empty( $insight_external ) ) {
    if ( str_contains( $insight_external, 'spotify.com' ) ) {
        $spotify_url = str_replace( 'spotify.com/', 'spotify.com/embed/', $insight_external );
        $spotify_url = strtok( $spotify_url, '?' );
        $embed_content = '<iframe style="border-radius:12px; margin-bottom: 30px;" src="' . esc_url( $spotify_url ) . '" width="100%" height="352" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>';
    } elseif ( str_contains( $insight_external, 'youtube.com' ) || str_contains( $insight_external, 'youtu.be' ) ) {
        $embed_content = wp_oembed_get( $insight_external );
    } else {
        // Generic oEmbed fallback for other links
        $embed_content = wp_oembed_get( $insight_external );
    }
}

?>

<!-- ARTICLE HERO -->
<header class="singleArticleHero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-lg-6">
                <div class="contentCard__meta">
                    <?php if ( ! empty( $insight_type ) && ! is_wp_error( $insight_type ) ) : ?>
                        <span class="caption"><?php echo esc_html( $insight_type[0]->name ); ?></span>
                    <?php endif; ?>
                    <time class="caption" datetime="<?php echo get_the_date( 'c' ); ?>"><?php echo get_the_date( 'd M Y' ); ?></time>
                </div>

                <h1><?php echo esc_html( $title ); ?></h1>
            </div>

        </div>
    </div>
</header>
<!-- ARTICLE HERO END -->


<!-- ARTICLE CONTENT -->
<main class="singleArticleCon">
    <div class="container">
        <div class="singleArticleDivider"></div>

        <div class="row">
            <div class="col-12 col-lg-12">
                <article class="singleArticleBody">
                    <div class="activeCorners gradientCorners">
                        <div class="top"></div>
                        <div class="bottom"></div>
                    </div>

                    <?php if ( $embed_content ) : ?>
                        <div class="singleArticleEmbed">
                            <?php echo $embed_content; ?>
                        </div>
                    <?php endif; ?>

                    <?php the_content(); ?>


                </article>
            </div>

<!--            <div class="col-12 col-lg-3">-->
<!--                <aside class="singleArticleShare">-->
<!--                    <span class="caption">Share article</span>-->
<!--                    <div class="footerSocials">-->
<!--                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=--><?php //echo urlencode( get_permalink() ); ?><!--" aria-label="Share on LinkedIn" target="_blank"><img src="--><?php //echo get_template_directory_uri(); ?><!--/assets/img/linkedin.svg" alt=""></a>-->
<!--                        <a href="https://twitter.com/intent/tweet?url=--><?php //echo urlencode( get_permalink() ); ?><!--&text=--><?php //echo urlencode( $title ); ?><!--" aria-label="Share on X" target="_blank"><img src="--><?php //echo get_template_directory_uri(); ?><!--/assets/img/x.svg" alt=""></a>-->
<!--                        <a href="#" aria-label="Copy link" class="copy-link"><i class="ci-External_Link"></i></a>-->
<!--                    </div>-->
<!--                </aside>-->
<!--            </div>-->
        </div>
    </div>
</main>
<!-- ARTICLE CONTENT END -->



