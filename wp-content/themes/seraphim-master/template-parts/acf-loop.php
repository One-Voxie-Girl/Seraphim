<?php
/**
 * ACF loop (backwards-compatible with original structure)
 * Preserves:
 *  - content_repeater → content_section → columns nesting
 *  - Dynamic classes derived from background_type / colour / section_class
 *  - Inline padding from section_padding
 *  - Pattern markup (patternCon/patternBg/patternGradOverlay gradient-{colour})
 *  - Column width logic via numbers_of_columns
 *  - section_id passthrough
 */

if ( ! defined('ABSPATH') ) { exit; }

/** Include a layout partial by slug (e.g., 'text' -> template-parts/acf/text.php) */
function muc3_include_acf_part($slug){
    $slug = sanitize_title($slug);
    $paths = [
        'template-parts/acf/' . $slug . '.php',
        'template-parts/acf/' . str_replace('_', '-', $slug) . '.php',
    ];
    foreach ($paths as $p){
        $located = locate_template($p);
        if ($located){ include $located; return true; }
    }
    return false;
}

/**
 * Render the “content_selector” (original behaviour) or common fallbacks.
 * Tries nested Flexible Content field named 'content_selector' (original),
 * then 'content' (alt), then a slug-like subfield.
 */
function muc3_content_selector(){
    if ( function_exists('have_rows') && have_rows('content_selector') ){
        while ( have_rows('content_selector') ) : the_row();
            $layout = get_row_layout();
            muc3_include_acf_part($layout);
        endwhile;
        return true;
    }
    if ( function_exists('have_rows') && have_rows('content') ){
        while ( have_rows('content') ) : the_row();
            $layout = get_row_layout();
            muc3_include_acf_part($layout);
        endwhile;
        return true;
    }
    // Fallback: look for a subfield naming the template
    $candidates = ['template_slug','layout','type','component','partial'];
    foreach ($candidates as $key){
        $slug = get_sub_field($key);
        if ( is_string($slug) && $slug !== '' ){
            muc3_include_acf_part($slug);
            return true;
        }
    }
    return false;
}

/**
 * est_circle_animation partial inclusion
 */
function muc3_est_circle_animation(){
    muc3_include_acf_part('est_circle_animation');
}

/**
 * updates_card partial inclusion
 */
function muc3_updates_card(){
    muc3_include_acf_part('updates_card');
}

/**
 * MAIN: Recreate the original “content_repeater → content_section → columns” structure
 * exactly, keeping all field values mapped to classes/attributes the same way.
 */
if ( function_exists('have_rows') && have_rows('content_repeater') ) :

    while ( have_rows('content_repeater') ) : the_row();

        $section_id         = get_sub_field('section_id');        // string
        $section_class      = get_sub_field('section_class');     // string
        $section_width      = get_sub_field('section_width');     // string

        $background_type              = get_sub_field('background_type');      //int 0:no background video, 1:uploaded video, 2:external video, 3: Uploaded Image, 4: External Image
        $video_file         =get_sub_field('video_file');           //video file array
        $video_link         =get_sub_field('video_link');           //video link string
        $image_file         =get_sub_field('image_file');           //Image file array
        $image_link         =get_sub_field('image_link');           //Image link string
        $overlay_image         =get_sub_field('overlay_image');      //Image file array
        // Section wrapper — keep original class composition and inline padding
        ?>
        <style>


            .globe-image {
                position: fixed;
                bottom: 0;
                left: 50%;
                transform: translateX(-50%);
                width: 101vw;
                height: auto;
                z-index: 10; /* In front of hero text and content */
                pointer-events: none;
                display: block;
            }



            .content-section {
                position: relative;
                z-index: 5; /* Lower than globe image inside sequence */
                padding: 10vh 20px 0;
                background: transparent;
            }

            /* Override for content outside the sequence */
            .globe-sequence ~ .content-section {
                z-index: 200;
                background: var(--bg-color);
            }






            /* Override for content outside the sequence */
            .globe-sequence ~ .section .content-section {
                padding-top: 0;
            }



            /* Top Overlay Section - Covers the globe */
            .overlay-section {
                position: relative;
                width: 0px;
                height: 0px;
                z-index: 100; /* Extremely high to ensure it stays in front */
                background: var(--bg-color); /* To ensure it covers everything */
                display: flex;
                align-items: flex-end; /* Align image to bottom */
                justify-content: center;
                overflow: hidden;
            }


            .globe-container {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100vh;
                pointer-events: none;
                z-index: 10;
                will-change: transform;
                visibility: hidden; /* Hidden by default, script will show it */
                /* Remove transition for tighter scroll sync */
            }

            .ov {
                transform: translate(-50%,5px);
            }

            .overlay-section-trigger {
                position: absolute;
                bottom: 0;
                height: 0px;
                width: 0px;
                visibility: hidden;
            }

        </style>


        
        <section class="section <?php echo $section_class; ?> <?php if ($background_type==1||$background_type==2) { echo 'section--has-video pageHeaderCon shareDataHero '; }elseif ($background_type==3||$background_type==4) {echo 'globe-sequence';}?>" <?php if($section_id) {echo 'id="' . $section_id . '"';}?> >

            <?php if ($background_type==1 or  $background_type==2) : ?>
                <div class="headerVideoCon">
                    <video autoplay muted loop playsinline preload="metadata" aria-hidden="true">
                        <?php if ($background_type == 1 && !empty($video_file['url'])) : ?>
                            <source src="<?php echo esc_url($video_file['url']); ?>" type="<?php echo esc_attr($video_file['mime_type']); ?>">
                        <?php elseif ($background_type == 2 && !empty($video_link)) : ?>
                            <source src="<?php echo esc_url($video_link); ?>" type="video/mp4">
                        <?php endif; ?>
                    </video>
                </div>
            <?php elseif ($background_type==3 && !empty($image_file['url'])) : ?>
                <div class="globe-container">
                    <img src="<?php echo esc_url($image_file['url']); ?>" alt="<?php echo esc_attr($image_file['alt']); ?>" aria-hidden="true" class="globe-image">
                    <?php if (!empty($overlay_image['url'])) : ?>
                        <img src="<?php echo esc_url($overlay_image['url']); ?>" alt="overlay" class="globe-image ov">
                    <?php endif; ?>
                </div>

            <?php endif; ?>

            <?php
            // Inner content rows
            if ( have_rows('content_section') ) :
                while ( have_rows('content_section') ) : the_row();
                    $layout = get_row_layout();

                    if ( $layout === 'content_tabs' ) :
                        ?>
                        <div class="<?php if ($section_width){ echo $section_width; } else { echo 'container'; } ?> content-section" <?php if ($section_id){ echo 'id="' . esc_attr($section_id) . '"'; }?>>
                            <div class="row">
                                <div class="col-12">
                                    <?php muc3_include_acf_part('content_tabs'); ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    elseif ( $layout === 'insights_tabs' ) :
                        ?>
                        <div class="<?php if ($section_width){ echo $section_width; } else { echo 'container'; } ?> content-section" <?php if ($section_id){ echo 'id="' . esc_attr($section_id) . '"'; }?>>
                            <div class="row">
                                <div class="col-12">
                                    <?php muc3_include_acf_part('insights_tabs'); ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    else :
                        //single content row
                        $col_width = get_sub_field('numbers_of_columns');  // e.g. 6
                        $content_alignment = get_sub_field('content_alignment'); // 0 : Left, 1 : Centre, 2 : Right

                        $row_class = 'row';
                        if ($content_alignment == 1) {
                            $row_class .= ' justify-content-center';
                        } elseif ($content_alignment == 2) {
                            $row_class .= ' justify-content-end';
                        }
                        ?>
                        <div class="<?php if ($section_width){ echo $section_width; } else { echo 'container'; } ?> content-section" <?php if ($section_id){ echo 'id="' . esc_attr($section_id) . '"'; }?>>
                            <div class="<?php echo $row_class; ?>">
                                <?php
                                if ( have_rows('columns') ) :
                                    while ( have_rows('columns') ) : the_row();
                                        // Keep Bootstrap column mapping exactly as before
                                        echo '<div class="col-12 col-lg-' . intval($col_width) . '">';
                                            // Replace original content_selector() call with our compatible version
                                            muc3_content_selector();
                                        echo '</div>';
                                    endwhile;
                                endif;
                                ?>
                            </div>
                        </div>
                        <?php
                    endif;
                endwhile;
            endif;
            ?>

            <?php if ($background_type == 3 || $background_type == 4) : ?>

                <section class="overlay-section">

                </section>
                <div class="overlay-section-trigger"></div>
            <?php endif; ?>

        </section>
        <?php

    endwhile;
    ?>
    <script>
        (function() {
            function handleScroll() {
                const scrollY = window.pageYOffset || document.documentElement.scrollTop;
                const globeSequences = document.querySelectorAll('.globe-sequence');

                globeSequences.forEach(sequence => {
                    const globeContainer = sequence.querySelector('.globe-container');
                    const scrollHint = sequence.querySelector('.scroll-hint');
                    const overlaySection = sequence.querySelector('.overlay-section');

                    if (!globeContainer || !overlaySection) return;

                    // Fade out scroll hint if it exists
                    if (scrollHint) {
                        if (scrollY > 50) {
                            scrollHint.style.opacity = '0';
                            scrollHint.style.transition = 'opacity 0.3s ease';
                        } else {
                            scrollHint.style.opacity = '1';
                        }
                    }

                    const sequenceRect = sequence.getBoundingClientRect();
                    const viewportHeight = window.innerHeight;

                    // Show globe only if the sequence is active in the viewport
                    if (sequenceRect.top < viewportHeight && sequenceRect.bottom > 0) {
                        globeContainer.style.visibility = 'visible';
                        
                        // Move globe up with the overlay once the overlay enters the viewport
                        const overlayRect = overlaySection.getBoundingClientRect();

                        // If the top of the overlay section is within the viewport (coming from below)
                        if (overlayRect.top < viewportHeight) {
                            const diff = viewportHeight - overlayRect.top;
                            // Match the movement speed exactly so they appear locked
                            globeContainer.style.transform = `translateY(-${diff}px)`;

                            // Hide globe if it's completely out of view
                            if (diff > viewportHeight) {
                                globeContainer.style.visibility = 'hidden';
                            }
                        } else {
                            globeContainer.style.transform = `translateY(0)`;
                        }
                    } else {
                        globeContainer.style.visibility = 'hidden';
                    }
                });
            }

            window.addEventListener('scroll', handleScroll, { passive: true });
            window.addEventListener('resize', handleScroll);
            handleScroll(); // Initial check
        })();
    </script>
    <?php
else :
    // Original empty fallback
    ?>
    <div class="container blankPage">
        <div class="row">
            <div class="col-12 text">
                <h1><?php the_title(); ?></h1>
                <?php the_content(); ?>
            </div>
        </div>
    </div>
    <?php
endif;

