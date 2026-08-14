<?php
/*
Plugin Name: WordPress Multisite Content Copier/Updater (PRO)
Description: WordPress Multisite Content Copier/Updater plugin is the best solution for copy/update posts, pages, custom post type posts and users from one site (blog) to the other sites (blogs) in your WordPress Multisite Network.
Version:     2.3.1
Author:      Obtain Infotech
Author URI:  https://www.obtaininfotech.com/
License:     GPL2
Text Domain: wp-multisite-content-copier-pro
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit( 'restricted access' );
}

/*
 * This is a constant variable for plugin path.
 */
define( 'WMCC_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

/*
 * This is a funtion that run when plugin activating.
 * It's deactivate free version when PRO version activate.
 */
if ( ! function_exists( 'wmcc_deactivate_free_version' ) ) {
    register_activation_hook( __FILE__, 'wmcc_deactivate_free_version' );
    function wmcc_deactivate_free_version() {
        
        deactivate_plugins( '/wp-multisite-content-copier/wp-multisite-content-copier.php' );
    }
}

/*
 * This is a function that create database tables.
 * 'wmcc_relationships' table use for multisite relationships.
 * 'wmcc_cf' table use for store special custom fields like image, file etc...
 */ 
if ( ! function_exists( 'wmcc_plugin_activation' ) ) {
    register_activation_hook( __FILE__, 'wmcc_plugin_activation' );
    function wmcc_plugin_activation() {
        
        global $wpdb;
        
        $table_wmcc_relationships = $wpdb->base_prefix . 'wmcc_relationships';
        $table_wmcc_cf = $wpdb->base_prefix . 'wmcc_cf';
        $charset_collate = $wpdb->get_charset_collate();
        
        $wmcc_relationships_sql = "CREATE TABLE $table_wmcc_relationships (
            `id` bigint(20) NOT NULL AUTO_INCREMENT,
            `source_item_id` bigint(20) NOT NULL,
            `source_blog_id` tinyint(4) NOT NULL,
            `destination_item_id` bigint(20) NOT NULL,
            `destination_blog_id` tinyint(4) NOT NULL,
            `relationship_id` varchar(200) NOT NULL,
            `type` varchar(20) NOT NULL,
            `type_name` varchar(200) NOT NULL,
            PRIMARY KEY (`id`)
        ) $charset_collate;";
        
        $wmcc_cf_sql = "CREATE TABLE $table_wmcc_cf (
            `id` bigint(20) NOT NULL AUTO_INCREMENT,
            `filed_key` text NOT NULL,
            `field_type` varchar(150) NOT NULL,
            `field_data` text NOT NULL,
            PRIMARY KEY (`id`)
        ) $charset_collate;";
        
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $wmcc_relationships_sql );
        dbDelta( $wmcc_cf_sql );

        update_site_option( 'wmcc_old', 1 );
    }
}

/*
 * This is a function that call admin side css and js.
 */
if ( ! function_exists( 'wmcc_admin_include_css_and_js' ) ) {
    add_action( 'admin_enqueue_scripts', 'wmcc_admin_include_css_and_js' );
    function wmcc_admin_include_css_and_js() {
        
        /* admin style */
        wp_register_style( 'wmcc-style', plugin_dir_url( __FILE__ ) . 'assets/css/wmcc-style.css', false, '1.0.0' );
        wp_enqueue_style( 'wmcc-style' );
        
        /* admin script */
        wp_register_script( 'wmcc-script', plugin_dir_url( __FILE__ ) . 'assets/js/wmcc-script.js', array( 'jquery' ), '1.0.0', false );
        wp_localize_script( 'wmcc-script', 'wmcc_ajaxurl', array( 'ajaxurl' => admin_url( '/admin-ajax.php' ) ) );
        wp_enqueue_script( 'wmcc-script' );
    }
}

/*
 * This is a function file for network settings.
 * Add network admin menu
 * Add network pages
 */
require  WMCC_PLUGIN_PATH . 'includes/wmcc-network.php';

/*
 * This is a file for plugin core functions.
 */
require  WMCC_PLUGIN_PATH . 'includes/wmcc-functions.php';

/*
 * This is a file for copier content functions.
 */
require  WMCC_PLUGIN_PATH . 'includes/content-copier.php';

/*
 * This is a file for extra plugins support like ACF.
 */
require  WMCC_PLUGIN_PATH . 'includes/extra.php';
