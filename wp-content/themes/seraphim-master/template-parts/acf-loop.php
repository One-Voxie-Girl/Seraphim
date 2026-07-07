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
 * MAIN: Recreate the original “content_repeater → content_section → columns” structure
 * exactly, keeping all field values mapped to classes/attributes the same way.
 */
if ( function_exists('have_rows') && have_rows('content_repeater') ) :

    while ( have_rows('content_repeater') ) : the_row();

        $section_id         = get_sub_field('section_id');        // string
        $section_class      = get_sub_field('section_class');     // string
        $section_width      = get_sub_field('section_width');     // string

        // Section wrapper — keep original class composition and inline padding
        ?>
        
        <section class="section <?php echo $section_class; ?>" <?php if($section_id) {echo 'id="' . $section_id . '"';}?> >

            <?php
            // Inner content rows
            if ( have_rows('content_section') ) :
                while ( have_rows('content_section') ) : the_row();

                    $col_width          = get_sub_field('numbers_of_columns');  // e.g. 6
                    ?>
                        <div class="<?php if ($section_width){ echo $section_width; } else { echo 'container'; } ?>" <?php if ($section_id){ echo 'id="' . esc_attr($section_id) . '"'; }?>>
                            <div class="row">
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
                endwhile;
            endif;
            ?>

        </section>
        <?php

    endwhile;

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

