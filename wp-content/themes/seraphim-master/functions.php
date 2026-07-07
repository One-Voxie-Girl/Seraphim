<?php
/**
 * Theme functions for Make Us Care 3.0
 */

if (!defined('MUC3_VERSION')) {
    define('MUC3_VERSION', '1.0.0');
}

add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form','comment-form','comment-list','gallery','caption','style','script']);
    register_nav_menus([
        'primary' => __('Primary Menu', 'make-us-care-3-0'),
        'footer'  => __('Footer Menu', 'make-us-care-3-0'),
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
        'muc3-style',
        get_template_directory_uri() . '/assets/scss/style.css',
        ['bootstrap-css','muc3-fonts'],
        file_exists($theme_css) ? filemtime($theme_css) : MUC3_VERSION
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
        'muc3-theme',
        get_template_directory_uri() . '/assets/js/theme.js',
        ['jquery'],
        MUC3_VERSION,
        true
    );
});


add_action('widgets_init', function () {
    register_sidebar([
        'name'          => __('Primary Sidebar', 'make-us-care-3-0'),
        'id'            => 'primary-sidebar',
        'before_widget' => '<section id="%1$s" class="widget %2$s mb-4">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title h5">',
        'after_title'   => '</h3>',
    ]);
});

add_action('wp_enqueue_scripts', function() {
  wp_dequeue_script('bootstrap');
  wp_deregister_script('bootstrap');
}, 100);

