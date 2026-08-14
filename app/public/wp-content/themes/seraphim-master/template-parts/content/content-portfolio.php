<?php

/**
 * Template part for displaying portfolio items
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 */

$company_name      = get_the_title();
$company_logo      = get_field( 'company_logo' );
$background_image  = get_field( 'background_image' );
$preview_text      = get_field( 'preview_text' );
$top_holding       = get_field( 'top_holding' );
$seraphim_brand_attribution = get_field( 'seraphim_brand_attribution' );
$website_link_only = get_field( 'website_link_only' );
$website_link      = get_field( 'website_link' );
$company_country   = get_field( 'country' );
$podcast_link      = get_field( 'podcast_link' );


$sectors    = get_the_terms( get_the_ID(), 'company-sector' );
$categories = get_the_terms( get_the_ID(), 'company-category' );
$countries  = get_the_terms( get_the_ID(), 'country' );

$sector_name   = ( ! is_wp_error( $sectors ) && ! empty( $sectors ) ) ? $sectors[0]->name : '';
$sector_slug   = ( ! is_wp_error( $sectors ) && ! empty( $sectors ) ) ? $sectors[0]->slug : '';
$category_name = ( ! is_wp_error( $categories ) && ! empty( $categories ) ) ? $categories[0]->name : '';
$category_slug = ( ! is_wp_error( $categories ) && ! empty( $categories ) ) ? $categories[0]->slug : '';

$country_name = '';
$country_slug = '';
if ( ! is_wp_error( $countries ) && ! empty( $countries ) ) {
    $country_term = $countries[0];
    $country_name = $country_term->name;
    $country_slug = $country_term->slug;


}




?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'portfolio-item' ); ?>>
    <?php if ( ! empty( $background_image['url'] ) ) : ?>
        <style>
            body {
                background-image: url('<?php echo esc_url( $background_image['url'] ); ?>');
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
                background-repeat: no-repeat;
            }
            body::before {
                content: "";
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.4);
                z-index: -1;
            }
        </style>
    <?php endif; ?>
    <div class="portfolio-hero">
        <div class="container h-100">
            <div class="row h-100">
                <div class="col-12 text-white portfolio-hero__content">
                    <h2 class="portfolio-hero__title"><?php echo esc_html( $company_name ); ?></h2>
                    <div class="portfolio-hero__preview-container">
                        <?php if ( $preview_text ) : ?>
                            <div class="portfolio-hero__preview">
                                <p><?php echo esc_html( $preview_text ); ?></p>
                            </div>
                        <?php endif; ?>
                        <?php if ( $website_link ) : ?>
                            <div class="portfolio-hero__website">
                                <a href="<?php echo esc_url( $website_link ); ?>" class="button tertiary" target="_blank" rel="noopener">Visit Website</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="textRowCon">
                <div class="container container-text-row">
                    <div class="row text-columns-row">
                        <div class="col-3">
                            <div class="column-content">
                                <div class="subtext">Space Segment</div>
                                <h3> <?= $sector_name; ?></h3>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="column-content">
                                <div class="subtext">Valuation</div>
                                <h3> $1.4B</h3>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="column-content">
                                <div class="subtext">Status</div>
                                <h3> Public</h3>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="column-content">
                                <div class="subtext">Location</div>
                                <h3> <?php echo $country_name; ?></h3>
                            </div>
                        </div>

                    </div>
                </div>
                <?php
                get_template_part('template-parts/acf/call_to_action', 'none');
                ?>
            </div>

            <?php get_template_part('template-parts/content/portfolio-insights'); ?>
            
            <?php if ( $podcast_link ) : ?>
                <div class="portfolio-podcast">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="podcast-embed">
                                    <?php
                                    $embed_url = str_replace( 'spotify.com/', 'spotify.com/embed/', $podcast_link );
                                    $embed_url = strtok( $embed_url, '?' ); // Clean up query params if any
                                    ?>
                                    <iframe style="border-radius:12px" src="<?php echo esc_url( $embed_url ); ?>" width="100%" height="352" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</article>


