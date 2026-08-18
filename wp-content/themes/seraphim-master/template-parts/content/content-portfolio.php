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
$valuation         = get_field( 'valuation' );
$status            = get_field( 'status' );


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

$impact_overview = get_field( 'impact_overview' ); // text area field
$sdgs = get_field( 'sustainability_cards' );  // checkbox returning int value


?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'portfolio-item' ); ?>>

    <section class="pageHeaderCon" <?php if ( ! empty( $background_image['url'] ) ) : ?> style="background-image: url('<?php echo esc_url( $background_image['url'] ); ?>');" <?php endif; ?>>
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-7">
                    <h1><?php echo esc_html( $company_name ); ?></h1>
                    <?php if ( $preview_text ) : ?>
                        <p><?php echo esc_html( $preview_text ); ?></p>
                    <?php endif; ?>
                </div>

                <div class="col-12 col-lg-5 tickerCol">
                    <?php if ( $website_link ) : ?>
                        <div class="buttonsCon rightAligned">
                            <a href="<?php echo esc_url( $website_link ); ?>" class="button" target="_blank" rel="noopener">
                                Visit website
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row statsRowCon smallstats">
                <div class="activeCorners">
                    <div class="top"></div>
                    <div class="bottom"></div>
                </div>

                <div class="col-12 col-md-3">
                    <span class="caption">Space segment</span>
                    <h4 class="title small"><?php echo esc_html( $sector_name ); ?></h4>
                </div>
                <div class="col-12 col-md-3">
                    <span class="caption">Valuation</span>
                    <h4 class="title small"><?php echo esc_html( $valuation ); ?></h4>
                </div>
                <div class="col-12 col-md-3">
                    <span class="caption">Status</span>
                    <h4 class="title small"><?php echo esc_html( $status ); ?></h4>
                </div>
                <div class="col-12 col-md-3">
                    <span class="caption">Location</span>
                    <h4 class="title small"><?php echo esc_html( $country_name ); ?></h4>
                </div>
            </div>
        </div>
    </section>

    <!-- NEWS FEED -->
    <?php get_template_part('template-parts/content/portfolio-insights'); ?>
    <!-- NEWS FEED END -->

    <!-- SPOTIFY EMBED -->
    <?php if ( $podcast_link ) : ?>
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <?php
                        $embed_url = str_replace( 'spotify.com/', 'spotify.com/embed/', $podcast_link );
                        $embed_url = strtok( $embed_url, '?' ); // Clean up query params if any
                        ?>
                        <iframe style="border-radius:12px" src="<?php echo esc_url( $embed_url ); ?>" width="100%" height="352" frameBorder="0" allowfullscreen="" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>
    <!-- SPOTIFY EMBED END -->

    <!-- IMPACT EMBED -->
    <?php if ( $impact_overview || ! empty( $sdgs ) ) : ?>
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="impactCon">
                            <div class="row">
                                <div class="col-12">
                                    <h3>Impact</h3>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-lg-6 impactOverview">
                                    <?php echo wp_kses_post( $impact_overview ); ?>
                                </div>
                                <div class="col-12 col-lg-6 sdgCon">
                                    <?php if ( ! empty( $sdgs ) ) : ?>
                                        <h4>Sustainability Development Goals:</h4>
                                        <div class="row">
                                            <?php foreach ( $sdgs as $sdg_card ) : 
                                                // Assuming $sdg_card is the number/slug of the SDG
                                                $sdg_image_url = 'https://seraphim.vc/wp-content/themes/seraphimvc/src/images/sdg/' . $sdg_card . '.svg';
                                                ?>
                                                <div class="col-6 col-md-3 sdg">
                                                    <img src="<?php echo esc_url( $sdg_image_url ); ?>" alt="SDG <?php echo esc_attr( $sdg_card ); ?>" />
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>
    <!-- IMPACT EMBED END -->

</article>


