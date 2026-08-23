<?php
/**
 * Theme functions for Seraphim
 */

if (!defined('seraphim_2')) {
    define('seraphim_2', '2.0.0');
}

add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form','comment-form','comment-list','gallery','caption','style','script']);
    register_nav_menus([
        'primary' => __('Primary Menu', 'seraphim-2'),
        'footer'  => __('Footer Menu', 'seraphim-2'),
    ]);
});

/**
 * Enqueue styles & scripts
 */
add_action('wp_enqueue_scripts', function () {
    // Adobe Fonts: Unbounded
    wp_enqueue_style(
        'muc3-fonts',
        'https://use.typekit.net/vit0ewn.css',
        [],
        null
    );

    // Bootstrap 5 CSS (latest 5.x via CDN)
    wp_enqueue_style(
        'bootstrap-css',
        'https://cdn.jsdelivr.net/npm/bootstrap@5/dist/css/bootstrap.min.css',
        [],
        null
    );

    // Theme CSS
    $theme_css = get_template_directory() . '/assets/scss/style.css?v=1';
    wp_enqueue_style(
        'seraphim-style',
        get_template_directory_uri() . '/assets/scss/main.css',
        ['bootstrap-css','muc3-fonts'],
        file_exists($theme_css) ? filemtime($theme_css) : seraphim_2
    );

    // jQuery & jQuery UI (bundled with WP)
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-core');

    // Bootstrap 5 Bundle JS (includes Popper)
    wp_enqueue_script(
        'bootstrap-js',
        'https://cdn.jsdelivr.net/npm/bootstrap@5/dist/js/bootstrap.bundle.min.js',
        ['jquery'],
        null,
        true
    );

    // Theme JS (placeholder)
    wp_enqueue_script(
        'seraphim-master',
        get_template_directory_uri() . '/assets/js/theme.js',
        ['jquery'],
        seraphim_2,
        true
    );
});


add_action('widgets_init', function () {
    register_sidebar([
        'name'          => __('Primary Sidebar', 'seraphim-2'),
        'id'            => 'primary-sidebar',
        'before_widget' => '<section id="%1$s" class="widget %2$s mb-4">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title h5">',
        'after_title'   => '</h3>',
    ]);
});

/**
 * Populate platforms_checklist from investment_platform repeater in options page
 */
add_filter('acf/load_field/name=platforms_checklist', function($field) {
    $field['choices'] = [];
    $platforms = get_field('investment_platform', 'option');

    if ($platforms) {
        foreach ($platforms as $platform) {

            $name = is_array($platform) ? ($platform['platform_name'] ?? $platform['name'] ?? '') : $platform;

            if ($name) {
                $field['choices'][$name] = $name;
            }
        }
    }

    return $field;
});

/**
 * Populate insight_type radio button from insight-type taxonomy
 */
add_filter('acf/load_field/name=insight_type', function($field) {
    $field['choices'] = [
        'all' => 'All'
    ];

    $terms = get_terms([
        'taxonomy' => 'insight-type',
        'hide_empty' => false,
    ]);

    if (!is_wp_error($terms) && !empty($terms)) {
        foreach ($terms as $term) {
            $field['choices'][$term->slug] = $term->name;
        }
    }

    return $field;
});

/**
 * Populate associated_companies checkbox with all companies from portfolio post type
 */
add_filter('acf/load_field/name=associated_companies', function($field) {
    $field['choices'] = [];

    $companies = get_posts([
        'post_type'      => 'portfolio',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'title',
        'order'          => 'ASC',
    ]);

    if ($companies) {
        foreach ($companies as $company) {
            $field['choices'][$company->ID] = $company->post_title;
        }
    }

    return $field;
});

/**
 * Populate team_tags checkbox from team-tag taxonomy
 */
add_filter('acf/load_field/name=team_tags', function($field) {
    $field['choices'] = [];

    $terms = get_terms([
        'taxonomy' => 'team-tag',
        'hide_empty' => false,
    ]);

    if (!is_wp_error($terms) && !empty($terms)) {
        foreach ($terms as $term) {
            $field['choices'][$term->term_id] = $term->name;
        }
    }

    return $field;
});

/**
 * Populate team_member select field with all team members
 */
add_filter('acf/load_field/name=team_member', function($field) {
    $field['choices'] = [];

    $members = get_posts([
        'post_type'      => 'team-members',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'title',
        'order'          => 'ASC',
    ]);

    if ($members) {
        foreach ($members as $member) {
            $field['choices'][$member->ID] = $member->post_title;
        }
    }

    return $field;
});

/**
 * Filter team-members query to respect menu_order if needed,
 * though get_posts by default might not. 
 * But for the spotlight we probably just want alphabetical or recent.
 * The load_field above uses 'title' ASC.
 */

/**
 * Populate document_type select field from document-type taxonomy
 */
add_filter('acf/load_field/name=document_type', function($field) {
    $field['choices'] = [
        'all' => 'All'
    ];

    $terms = get_terms([
        'taxonomy' => 'document-type',
        'hide_empty' => false,
    ]);

    if (!is_wp_error($terms) && !empty($terms)) {
        foreach ($terms as $term) {
            $field['choices'][$term->slug] = $term->name;
        }
    }

    return $field;
});

/**
 * Populate result_type select field from result-type taxonomy
 */
add_filter('acf/load_field/name=result_type', function($field) {
    $field['choices'] = [
        'all' => 'All'
    ];

    $terms = get_terms([
        'taxonomy' => 'result-type',
        'hide_empty' => false,
    ]);

    if (!is_wp_error($terms) && !empty($terms)) {
        foreach ($terms as $term) {
            $field['choices'][$term->slug] = $term->name;
        }
    }

    return $field;
});

add_action('wp_enqueue_scripts', function() {
  wp_dequeue_script('bootstrap');
  wp_deregister_script('bootstrap');
}, 100);





/**
 * AJAX Insights Search
 */
add_action('wp_ajax_search_insights', 'seraphim_search_insights');
add_action('wp_ajax_nopriv_search_insights', 'seraphim_search_insights');
function seraphim_search_insights() {
    $s = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
    $filter = isset($_GET['filter']) ? sanitize_text_field($_GET['filter']) : '';
    
    $args = array(
        'post_type'      => 'insight',
        'posts_per_page' => -1,
        's'              => $s,
        'orderby'        => 'relevance'
    );

    if ( ! empty( $filter ) ) {
        $term = get_term_by('name', $filter, 'insight-type');
        if ($term) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'insight-type',
                    'field'    => 'term_id',
                    'terms'    => $term->term_id,
                ),
            );
        } else {
            // Fallback to searching for the term by slug if name match fails
            $term = get_term_by('slug', sanitize_title($filter), 'insight-type');
            if ($term) {
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'insight-type',
                        'field'    => 'term_id',
                        'terms'    => $term->term_id,
                    ),
                );
            } else {
                // Last resort: exact name match in query
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'insight-type',
                        'field'    => 'name',
                        'terms'    => $filter,
                    ),
                );
            }
        }
    }
    
    $query = new WP_Query($args);
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            
            $insight_types = get_the_terms(get_the_ID(), 'insight-type');
            $type_names = [];
            if (!is_wp_error($insight_types) && !empty($insight_types)) {
                foreach ($insight_types as $term) {
                    $type_names[] = $term->name;
                }
            }
            $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'large') ?: '';
            ?>
            <div class="col-12 col-md-6 col-lg-4">
                <a href="<?php the_permalink(); ?>" class="contentCard contentCard--grid">
                     <div class="activeCorners gradientCorners">
                        <div class="top"></div>
                        <div class="bottom"></div>
                    </div>
                    <div class="contentCard__image" style="background-image: url('<?php echo esc_url($thumbnail_url); ?>');"></div>
                    <div class="contentCard__meta">
                        <?php if (!empty($type_names)) : ?>
                            <span class="caption"><?php echo esc_html(implode(', ', $type_names)); ?></span>
                        <?php endif; ?>
                        <span class="caption"><?php echo get_the_date('d M Y'); ?></span>
                    </div>
                    <h4><?php the_title(); ?></h4>
                </a>
            </div>
            <?php
        }
        wp_reset_postdata();
    } else {
        echo '<div class="col-12 text-center py-5"><p>No insights found matching your search.</p></div>';
    }
    
    wp_die();
}
