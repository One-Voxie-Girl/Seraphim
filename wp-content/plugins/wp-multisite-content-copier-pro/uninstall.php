<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'restricted access' );
}

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}

/*
 * Deleted options when plugin uninstall.
 */
delete_site_option( 'wmcc_user_roles' );
delete_site_option( 'wmcc_post_types' );
delete_site_option( 'wmcc_blogs' );
delete_site_option( 'wmcc_old' );
delete_site_option( 'wmcc_exclude_meta_data' );
delete_site_option( 'wmcc_purchase_code' );
