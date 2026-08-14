<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'restricted access' );
}

/*
 * This is a function that create meta box for posts, pages and custom post types.
 * Access user role wise which is set in settings
 */
if ( ! function_exists( 'wmcc_add_meta_boxes' ) ) {
    add_action( 'add_meta_boxes', 'wmcc_add_meta_boxes' );
    function wmcc_add_meta_boxes() {
        
        $wmcc_post_types = get_site_option( 'wmcc_post_types' );
        if ( $wmcc_post_types ) {
            $new_wmcc_post_types = array( 'none' );
            foreach( $wmcc_post_types as $wmcc_post_type ) {
                if ( $wmcc_post_type == get_post_type() ) {
                    $new_wmcc_post_types = $wmcc_post_type;
                }
            }
            
            $current_user = wp_get_current_user();  
            if ( $current_user != null ) {
                $current_user_role = reset( $current_user->roles );
            }
            
            $wmcc_user_roles = get_site_option( 'wmcc_user_roles' );
            if ( ! $wmcc_user_roles ) {
                $wmcc_user_roles = array();
            }
            
            if ( is_super_admin() || ( in_array( $current_user_role, $wmcc_user_roles ) ) ) {
                $current_blog_id = get_current_blog_id();
                $wmcc_blogs = get_site_option( 'wmcc_blogs' );
                if ( ! $wmcc_blogs ) {
                    $wmcc_blogs = array();
                }

                if ( in_array( $current_blog_id, $wmcc_blogs ) ) {
                    add_meta_box( 'wmcc-content-copier', esc_html__( 'WordPress Multisite Content Copier/Updater: Copy/Update Content', 'wp-multisite-content-copier-pro' ), 'wmcc_content_copier_callback', $new_wmcc_post_types );
                }
            }
        }
    }
}

/*
 * This is a function that call copier meta box.
 * $post variable return current edit post data.
 */
if ( ! function_exists( 'wmcc_content_copier_callback' ) ) {
    function wmcc_content_copier_callback( $post ) {
        
        $post_status = get_post_status( get_the_ID() );
        if ( $post_status == 'publish' || $post_status == 'future' || $post_status == 'private' ) {
            ?>
                <div id="wmcc-content" item-id="<?php echo esc_attr( get_the_ID() ); ?>" type="post_type" type-name="<?php echo esc_attr( get_post_type() ); ?>"></div>
            <?php
        } else {
            ?><p><?php esc_html_e( 'If you want to copy/update, first published it. Once published, just refresh it.', 'wp-multisite-content-copier-pro' ); ?></p><?php
        }
    }
}

/*
 * This is a function that show user copier section.
 */
if ( ! function_exists( 'wmcc_user_content_copier' ) ) {
    add_action( 'show_user_profile', 'wmcc_user_content_copier' );
    add_action( 'edit_user_profile', 'wmcc_user_content_copier' );
    function wmcc_user_content_copier() {
        
        global $wpdb;
        
        $wmcc_users = get_site_option( 'wmcc_users' );
        if ( $wmcc_users ) {
            $current_user = wp_get_current_user();  
            if ( $current_user != null ) {
                $current_user_role = reset( $current_user->roles );
            } 

            $wmcc_user_roles = get_site_option( 'wmcc_user_roles' );
            if ( ! $wmcc_user_roles ) {
                $wmcc_user_roles = array();
            }

            if ( is_super_admin() || ( in_array( $current_user_role, $wmcc_user_roles ) ) ) {
                $current_blog_id = get_current_blog_id();
                $wmcc_blogs = get_site_option( 'wmcc_blogs' );
                if ( ! $wmcc_blogs ) {
                    $wmcc_blogs = array();
                }
                
                if ( in_array( $current_blog_id, $wmcc_blogs ) ) {
                    ?>
                        <h2><?php esc_html_e( 'WordPress Multisite Content Copier/Updater: Copy/Update Content', 'wp-multisite-content-copier-pro' ); ?></h2>
                        <p><strong><?php esc_html_e( 'Select destination sites you want to copy or update.', 'wp-multisite-content-copier-pro' ); ?></strong></p>
                        <table class="form-table">
                            <tbody>
                                <tr>
                                    <th><label><?php esc_html_e( 'Sites', 'wp-multisite-content-copier-pro' ); ?></label></th>
                                    <td>
                                        <label><input class="wmcc-check-uncheck" type="checkbox" /><?php esc_html_e( 'All', 'wp-multisite-content-copier-pro' ); ?></label>
                                        <p class="description"><?php esc_html_e( 'Select/Deselect all sites.', 'wp-multisite-content-copier-pro' ); ?></p>
                                        <br>
                                        <fieldset class="wmcc-sites">
                                            <?php
                                                $sites = $wpdb->get_results( "SELECT * FROM ".$wpdb->base_prefix."blogs" );                                       
                                                if ( $sites != null ) {
                                                    $user_id = ( isset( $_REQUEST['user_id'] ) ? (int) $_REQUEST['user_id'] : 0 );
                                                    foreach ( $sites as $key => $value ) {
                                                        if ( in_array( $value->blog_id, $wmcc_blogs ) ) {
                                                            $checked = '';
                                                            if ( is_user_member_of_blog( $user_id, $value->blog_id ) ) {
                                                                $checked = ' checked="checked"';
                                                            }
                                                            
                                                            $blog_details = get_blog_details( $value->blog_id );
                                                            if ( $value->blog_id != get_current_blog_id() ) {
                                                                ?>
                                                                    <label><input name="wmcc_blogs[]" type="checkbox" value="<?php echo esc_attr( $value->blog_id ); ?>"<?php echo esc_attr( $checked ); ?>><?php echo esc_html( $value->domain ); echo esc_html( $value->path ); echo ' ('.esc_html( $blog_details->blogname ).')'; ?></label><br>
                                                                <?php
                                                            }
                                                        }
                                                    }
                                                }
                                            ?>
                                        </fieldset>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    <?php
                }
            }
        }
    }
}

/*
 * This is a function that call user copier functionality.
 * $user variable return current edit user data.
 */
if ( ! function_exists( 'wmcc_user_content_copier_update' ) ) {
    add_action( 'edit_user_profile_update', 'wmcc_user_content_copier_update' );
    function wmcc_user_content_copier_update( $user ) {
        
        $wmcc_users = get_site_option( 'wmcc_users' );
        if ( $wmcc_users ) {
            if ( is_super_admin() ) {
                $wmcc_blogs = ( isset( $_POST['wmcc_blogs'] ) ? $_POST['wmcc_blogs'] : array() );
                if ( $wmcc_blogs != null ) {
                    $user_info = get_userdata( $user );            
                    $user_id = $user;
                    $role = reset( $user_info->roles );       
                    if ( $role == null ) {
                        $role = 'subscriber';
                    }

                    if ( isset( $_POST['role'] ) && $_POST['role'] != null ) {
                        $role = sanitize_text_field( $_POST['role'] );
                    }

                    foreach ( $wmcc_blogs as $wmcc_blog ) {
                        $blog_id = (int) $wmcc_blog;
                        add_user_to_blog( $blog_id, $user_id, $role );
                    }
                }
            }
        }
    }
}
