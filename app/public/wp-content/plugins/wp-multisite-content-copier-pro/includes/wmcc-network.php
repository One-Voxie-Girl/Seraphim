<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'restricted access' );
}

/*
 * This is a function that add network admin menu.
 */
if ( ! function_exists( 'wmcc_add_network_admin_menu' ) ) {
    add_action( 'network_admin_menu', 'wmcc_add_network_admin_menu' );
    function wmcc_add_network_admin_menu() {
        
        add_menu_page( esc_html__( 'WordPress Multisite Content Copier/Updater', 'wp-multisite-content-copier-pro' ), esc_html__( 'Content Copier', 'wp-multisite-content-copier-pro' ), 'manage_options', 'wordpress-multisite-content-copier', 'wmcc_wordpress_multisite_content_copier' );
        add_submenu_page( 'wordpress-multisite-content-copier', esc_html__( 'Content Copier: Bulk Copy/Update', 'wp-multisite-content-copier-pro' ), esc_html__( 'Bulk Copy/Update', 'wp-multisite-content-copier-pro' ), 'manage_options', 'wordpress-multisite-content-copier', 'wmcc_wordpress_multisite_content_copier' );
        add_submenu_page( 'wordpress-multisite-content-copier', esc_html__( 'Content Copier: Settings', 'wp-multisite-content-copier-pro' ), esc_html__( 'Settings', 'wp-multisite-content-copier-pro' ), 'manage_options', 'wordpress-multisite-content-copier-settings', 'wmcc_wordpress_multisite_content_copier_settings' );
        add_submenu_page( 'wordpress-multisite-content-copier', esc_html__( 'Licence Verification', 'wp-multisite-content-copier-pro' ), esc_html__( 'Licence Verification', 'wp-multisite-content-copier-pro' ), 'manage_options', 'wmcc_licence_verification', 'wmcc_licence_verification' );
    }
}

/*
 * This is a function that add network page.
 * Also call bulk copy/update functionality.
 * Copy/update bulk posts, pages, custom post types, categories, tags, custom taxonomies and users.
 */
if ( ! function_exists( 'wmcc_wordpress_multisite_content_copier' ) ) {
    function wmcc_wordpress_multisite_content_copier() {
        
        global $wpdb;
        
        $current_blog_id = get_current_blog_id();
        $wmcc_blogs = get_site_option( 'wmcc_blogs' );
        if ( ! $wmcc_blogs ) {
            $wmcc_blogs = array();
        }
        
        $wmcc_users = get_site_option( 'wmcc_users' );
        $wmcc_post_types = get_site_option( 'wmcc_post_types' );
        $page_url = network_admin_url( '/admin.php?page=wordpress-multisite-content-copier' );        
        $wmcc_content_type = ( isset( $_REQUEST['wmcc_content_type'] ) ? sanitize_text_field( $_REQUEST['wmcc_content_type'] ) : '' );
        $wmcc_source_blog = ( isset( $_REQUEST['wmcc_source_blog'] ) ? (int) $_REQUEST['wmcc_source_blog'] : 0 );
        $wmcc_record_per_page = ( isset( $_REQUEST['wmcc_record_per_page'] ) ? (int) $_REQUEST['wmcc_record_per_page'] : 10 );        
        $wmcc_records = ( isset( $_REQUEST['wmcc_records'] ) ? $_REQUEST['wmcc_records'] : array() );
        $wmcc_destination_blogs = ( isset( $_REQUEST['wmcc_destination_blogs'] ) ? $_REQUEST['wmcc_destination_blogs'] : array() );
        $copy_media = ( isset( $_REQUEST['copy_media'] ) ? (int) $_REQUEST['copy_media'] : 0 );
        $copy_terms = ( isset( $_REQUEST['copy_terms'] ) ? (int) $_REQUEST['copy_terms'] : 0 );
        if ( $wmcc_content_type && $wmcc_source_blog && $wmcc_destination_blogs != null && $wmcc_records != null && isset( $_REQUEST['wmcc_submit'] ) ) {
            $blogs = $wmcc_destination_blogs;
            $source_blog_id = (int) $wmcc_source_blog;            
            if ( $wmcc_content_type == 'users' ) {
                if ( $source_blog_id != $current_blog_id ) {                
                    switch_to_blog( $source_blog_id );
                }
                
                foreach ( $wmcc_records as $wmcc_record ) {
                    if ( $blogs != null ) {
                        $wmcc_record = (int) $wmcc_record;
                        $user_info = get_userdata( $wmcc_record );            
                        $user_id = $wmcc_record;
                        $role = reset( $user_info->roles ); 
                        foreach ( $blogs as $blog ) {
                            $blog_id = $blog;
                            add_user_to_blog( $blog, $user_id, $role );
                        }
                    }
                }
                
                if ( $source_blog_id != $current_blog_id ) {                
                    restore_current_blog();
                }
            } else {                            
                $type = 'post_type';
                $type_name = $wmcc_content_type;

                foreach ( $wmcc_records as $wmcc_record ) {
                    $source_item_id = (int) $wmcc_record;
                    if ( $type == 'post_type' ) {
                        if ( $blogs != null ) {
                            $taxonomies = array();
                            if ( $copy_terms ) {
                                if ( $source_blog_id != $current_blog_id ) {                
                                    switch_to_blog( $source_blog_id );
                                }
                                
                                $taxonomy_objects = get_object_taxonomies( $type_name );                                
                                if ( $taxonomy_objects != null ) {
                                    foreach ( $taxonomy_objects as $taxonomy_object ) {
                                        $post_terms = wp_get_post_terms( $source_item_id, $taxonomy_object );
                                        if ( $post_terms ) {
                                            $taxonomies[$taxonomy_object] = $post_terms;
                                        }
                                    }                    
                                }

                                if ( $source_blog_id != $current_blog_id ) {                
                                    restore_current_blog();
                                }
                            }

                            foreach ( $blogs as $blog ) {
                                $destination_blog_id = (int) $blog;
                                $destination_post_id = wmcc_copy_post( $source_item_id, $source_blog_id, $type, $type_name, $destination_blog_id, $copy_media );

                                if ( $taxonomies != null && $destination_post_id ) {
                                    foreach ( $taxonomies as $taxonomy => $terms ) {
                                        if ( $terms != null ) {
                                            $destination_terms = array();
                                            foreach ( $terms as $term ) {
                                                $destination_term_id = wmcc_copy_term( $term, $source_blog_id, 'taxonomy', $taxonomy, $destination_blog_id );
                                                if ( $destination_term_id ) {
                                                    $destination_terms[] = (int) $destination_term_id;
                                                }
                                            }

                                            if ( $destination_terms != null ) {
                                                wmcc_set_destination_post_terms( $destination_post_id, $destination_terms, $taxonomy, $destination_blog_id );
                                            }
                                        }
                                    }
                                }
                            }
                        }                       
                    }
                }
            }
            
            ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php esc_html_e( 'Records successfully copy/update.', 'wp-multisite-content-copier-pro' ); ?></p>
                </div>
            <?php
        }
        ?>
        <div class="wrap">
            <h2><?php esc_html_e( 'Bulk Copy/Update', 'wp-multisite-content-copier-pro' ); ?></h2>
            <hr>
            <?php
                $wmcc_licence = get_site_option( 'wmcc_licence' );
                if ( $wmcc_licence ) {
                    ?>
                        <form method="post" action="<?php echo esc_url( $page_url ); ?>">                
                            <table class="form-table">
                                <tbody>
                                    <tr>
                                        <th scope="row"><?php esc_html_e( 'Content Type', 'wp-multisite-content-copier-pro' ); ?></th>
                                        <td>                     
                                            <select name="wmcc_content_type" required="required">
                                                <option value=""><?php esc_html_e( 'Select content type', 'wp-multisite-content-copier-pro' ); ?></option>
                                            <?php                                
                                                $post_types = get_post_types();                                    
                                                if ( $wmcc_post_types ) {
                                                    ?><optgroup label="<?php esc_html_e( 'Post Types', 'wp-multisite-content-copier-pro' ); ?>"><?php
                                                    foreach ( $wmcc_post_types as $key ) {
                                                        $post_type_label = get_post_type_object($key)->label;
                                                        $selected = '';
                                                        if ( $wmcc_content_type == $key ) {
                                                            $selected = ' selected="$selected"';
                                                        }
                                                        ?>
                                                            <option value="<?php echo esc_attr( $key ); ?>"<?php echo esc_attr( $selected ); ?>><?php echo esc_html( $post_type_label ); ?></option>                                                
                                                        <?php
                                                    }
                                                    ?></optgroup><?php
                                                }

                                                if ( $wmcc_users ) {
                                                    ?>
                                                        <optgroup label="<?php esc_html_e( 'Users', 'wp-multisite-content-copier-pro' ); ?>">
                                                            <option value="users"<?php echo ( $wmcc_content_type == 'users' ? ' selected="$selected"' : '' ); ?>><?php esc_html_e( 'Users', 'wp-multisite-content-copier-pro' ); ?></option>
                                                        </optgroup>
                                                    <?php
                                                }
                                            ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><?php esc_html_e( 'Source Site', 'wp-multisite-content-copier-pro' ); ?></th>
                                        <td>     
                                            <select name="wmcc_source_blog" required="required">
                                            <?php
                                                $sites = $wpdb->get_results( "SELECT * FROM ".$wpdb->base_prefix."blogs" );
                                                $blog_list = array();
                                                if ( $sites != null ) {
                                                    ?><option value=""><?php esc_html_e( 'Select source site', 'wp-multisite-content-copier-pro' ); ?></option><?php
                                                    foreach ( $sites as $key => $value ) {
                                                        $blog_list[$value->blog_id] = $value->domain;
                                                        if ( in_array( $value->blog_id, $wmcc_blogs ) ) {
                                                            $selected = '';
                                                            if ( $wmcc_source_blog == $value->blog_id ) {
                                                                $selected = ' selected="$selected"';
                                                            }

                                                            $blog_details = get_blog_details( $value->blog_id );                                            
                                                            ?>
                                                                <option value="<?php echo esc_attr( $value->blog_id ); ?>"<?php echo esc_attr( $selected ); ?>><?php echo esc_html( $value->domain ); echo esc_html( $value->path ); echo ' ('.esc_html( $blog_details->blogname ).')'; ?></option>                                                
                                                            <?php
                                                        }
                                                    }
                                                }
                                            ?> 
                                            </select>
                                        </td>
                                    </tr>    
                                    <tr>
                                        <th scope="row"><?php esc_html_e( 'Number of records per page', 'wp-multisite-content-copier-pro' ); ?></th>
                                        <td>
                                            <input type="number" name="wmcc_record_per_page" min="1" value="<?php echo esc_attr( $wmcc_record_per_page ); ?>" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <p class="submit">
                                <input name="submit" class="button button-secondary" value="<?php esc_html_e( 'Filter', 'wp-multisite-content-copier-pro' ); ?>" type="submit">
                                &nbsp;&nbsp;&nbsp;&nbsp;<a class="button button-secondary" href="<?php echo esc_url( $page_url ); ?>"><?php esc_html_e( 'Clear', 'wp-multisite-content-copier-pro' ); ?></a>
                            </p>
                        </form>
                    <?php
                } else {
                    ?>
                        <div class="notice notice-error is-dismissible">
                            <p><?php esc_html_e( 'Please verify purchase code.', 'wp-multisite-content-copier-pro' ); ?></p>
                        </div>
                    <?php
                }

                if ( $wmcc_content_type && $wmcc_source_blog ) {
                    if ( $wmcc_source_blog != get_current_blog_id() ) {
                        $wmcc_source_blog = (int) $wmcc_source_blog;
                        switch_to_blog( $wmcc_source_blog );
                    }
                    
                    $paged = ( isset( $_REQUEST['paged'] ) ) ? (int) $_REQUEST['paged'] : 1;
                    $args = array(
                        'post_type'         => $wmcc_content_type,
                        'posts_per_page'    => $wmcc_record_per_page,
                        'paged'             => $paged,
                    );
                    
                    $add_args = array(
                        'wmcc_content_type'     => $wmcc_content_type,
                        'wmcc_source_blog'      => $wmcc_source_blog,
                        'wmcc_record_per_page'  => $wmcc_record_per_page,
                    );
                    
                    if ( isset( $_REQUEST['s'] ) ) {
                        $args['s'] = sanitize_text_field( $_REQUEST['s'] );
                        $add_args['s'] = sanitize_text_field( $_REQUEST['s'] );
                    }
                    
                    $records = new WP_Query( $args );
                    ?>
                    <form method="post">
                        <p class="search-box wmcc-search-box">
                            <label class="screen-reader-text" for="post-search-input"><?php esc_html_e( 'Search Records:', 'wp-multisite-content-copier-pro' ); ?></label>
                            <input id="post-search-input" name="s" value="<?php echo ( isset( $_REQUEST['s'] ) ? esc_attr( $_REQUEST['s'] ) : ''  ); ?>" type="search">
                            <input id="search-submit" class="button" value="<?php esc_html_e( 'Search Records', 'wp-multisite-content-copier-pro' ); ?>" type="submit">
                        </p>                       
                        <table class="wp-list-table widefat fixed striped">
                            <thead>
                                <tr>
                                    <td class="manage-column column-cb check-column"><input type="checkbox"></td>
                                    <th><?php esc_html_e( 'Title', 'wp-multisite-content-copier-pro' ); ?></th>                                  
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <td class="manage-column column-cb check-column"><input type="checkbox"></td>
                                    <th><?php esc_html_e( 'Title', 'wp-multisite-content-copier-pro' ); ?></th>                                   
                                </tr>
                            </tfoot>
                            <tbody>
                            <?php
                            if ( $wmcc_content_type == 'users' ) {
                                $args = array(
                                    'number'    => $wmcc_record_per_page,                                    
                                    'paged'     => $paged,
                                );
                                
                                if ( isset( $_REQUEST['s'] ) ) {
                                    $args['search'] = sanitize_text_field( $_REQUEST['s'] );
                                    $args['search_columns'] = array(
                                        'ID',
                                        'user_login',
                                        'user_nicename',
                                        'user_email',
                                        'user_url',
                                    );
                                    $add_args['s'] = sanitize_text_field( $_REQUEST['s'] );
                                }
                                
                                $user_query = new WP_User_Query( $args );
                                $records = $user_query->get_results(); 
                                if ( $records != null ) {
                                    $sites = $wpdb->get_results( "SELECT * FROM ".$wpdb->base_prefix."blogs" );
                                    foreach ( $records as $record ) {
                                        ?>
                                            <tr>
                                                <th class="check-column"><input type="checkbox" name="wmcc_records[]" value="<?php echo esc_attr( $record->ID ); ?>"></th>
                                                <td class="title column-title page-title">
                                                    <strong><a href="<?php echo esc_url( get_edit_user_link( $record->ID ) ); ?>"><?php echo esc_html( $record->data->display_name ); ?></a></strong>                                                    
                                                    <?php
                                                        if ( $sites != null ) {
                                                            $user_synced = array();
                                                            foreach ( $sites as $user_site ) {                                                                
                                                                if ( is_user_member_of_blog( $record->ID, $user_site->blog_id ) && $wmcc_source_blog != $user_site->blog_id ) {
                                                                    $user_synced[] = $user_site->blog_id;
                                                                }
                                                            }
                                                            
                                                            if ( $user_synced != null ) {                                                                
                                                                echo '<b>'; esc_html_e( 'Synced: ', 'wp-multisite-content-copier-pro' ); echo '</b>';
                                                                $count_blog_list = count( $user_synced );
                                                                $count_blog = 0;
                                                                foreach ( $user_synced as $user_synced_value ) {
                                                                    $blog_details = get_blog_details( $user_synced_value );
                                                                    echo esc_html( $blog_list[$user_synced_value] ); echo esc_html( $blog_details->path ); echo ' ('.esc_html( $blog_details->blogname ).')';
                                                                    if ( $count_blog != ( $count_blog_list - 1) ) {
                                                                        echo ', ';
                                                                    }
                                                                    $count_blog ++;
                                                                }
                                                            }
                                                        }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php
                                    }
                                }                                
                            } else if ( $records->have_posts() && $wmcc_content_type != 'users' ) {
                                while ( $records->have_posts() ) {
                                    $records->the_post();
                                    ?>
                                    <tr>
                                        <th class="check-column"><input type="checkbox" name="wmcc_records[]" value="<?php echo esc_attr( get_the_ID() ); ?>"></th>
                                        <td class="title column-title page-title">
                                            <strong><a href="<?php echo esc_url( get_permalink() ); ?>"><?php echo esc_html( get_the_title() ); ?></a></strong>
                                            <?php
                                                $type = 'post_type';
                                                $type_name = get_post_type();
                                                $current_blog_id = get_current_blog_id();
                                                $current_item_id = get_the_ID();
                                                $current_relationship = $wpdb->get_row( "SELECT * FROM ".$wpdb->base_prefix."wmcc_relationships WHERE type='$type' AND type_name='$type_name' AND ((source_item_id='$current_item_id' AND source_blog_id='$current_blog_id') || (destination_item_id='$current_item_id' AND destination_blog_id='$current_blog_id'))" );
                                                $blog_relationships = array();
                                                if ( $current_relationship != null ) {
                                                    $relationship_id = $current_relationship->relationship_id;
                                                    $relationships = $wpdb->get_results( "SELECT * FROM ".$wpdb->base_prefix."wmcc_relationships WHERE relationship_id='$relationship_id'");
                                                    if ( $relationships != null ) {
                                                        foreach ( $relationships as $relationship ) {
                                                            if ( $current_blog_id == $relationship->source_blog_id && $current_item_id == $relationship->source_item_id ) {
                                                                $blog_relationships[$relationship->destination_blog_id] = $relationship->destination_item_id;
                                                            } else if ( $current_blog_id == $relationship->destination_blog_id && $current_item_id == $relationship->destination_item_id ) {
                                                                $blog_relationships[$relationship->source_blog_id] = $relationship->source_item_id;
                                                            } else {
                                                                if ( ! isset( $blog_relationships[$relationship->source_blog_id] ) ) {
                                                                    $blog_relationships[$relationship->source_blog_id] = $relationship->source_item_id;
                                                                }

                                                                if ( ! isset( $blog_relationships[$relationship->destination_blog_id] ) ) {
                                                                    $blog_relationships[$relationship->destination_blog_id] = $relationship->destination_item_id;
                                                                }
                                                            }
                                                        }
                                                    }                            
                                                }
                                                if ( $blog_relationships != null ) {
                                                    echo '<b>'; esc_html_e( 'Synced: ', 'wp-multisite-content-copier-pro' ); echo '</b>';
                                                    $count_blog_list = count( $blog_relationships );
                                                    $count_blog = 0;
                                                    foreach ( $blog_relationships as $key => $value ) {
                                                        $blog_details = get_blog_details( $key );
                                                        echo esc_html( $blog_list[$key] ); echo esc_html( $blog_details->path ); echo ' ('.esc_html( $blog_details->blogname ).')';
                                                        if ( $count_blog != ( $count_blog_list - 1) ) {
                                                            echo ', ';
                                                        }
                                                        $count_blog ++;
                                                    }
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                ?>
                                    <tr class="no-items">                                       
                                        <td class="colspanchange" colspan="2"><?php esc_html_e( 'No records found.', 'wp-multisite-content-copier-pro' ); ?></td>
                                    </tr>
                                <?php
                            }
                            $big = 999999999;                            
                            ?>
                            </tbody>
                        </table>
                        <div class="wmcc-pagination">
                            <span class="pagination-links">
                                <?php
                                if ( $wmcc_content_type == 'users' ) { 
                                    $total = ceil( $user_query->get_total() / $wmcc_record_per_page );
                                } else {
                                    $total = $records->max_num_pages;
                                }
                                
                                $paginate_url = network_admin_url( '/admin.php?page=wordpress-multisite-content-copier&paged=%#%' );
                                echo paginate_links( array(
                                    'base'      => str_replace( $big, '%#%', $paginate_url ),
                                    'format'    => '?paged=%#%',
                                    'current'   => max( 1, $paged ),
                                    'total'     => $total,
                                    'add_args'  => $add_args,    
                                    'prev_text' => '&laquo;',
                                    'next_text' => '&raquo;',
                                ) );
                                ?>
                            </span>
                        </div>
                        <br class="clear">
                        <input type="hidden" name="wmcc_content_type" value="<?php echo esc_html( $wmcc_content_type ); ?>">
                        <input type="hidden" name="wmcc_source_blog" value="<?php echo esc_attr( $wmcc_source_blog ); ?>">
                        <input type="hidden" name="wmcc_record_per_page" value="<?php echo esc_attr( $wmcc_record_per_page ); ?>">
                        <?php wp_reset_postdata(); ?>
                        <table class="form-table">
                            <tbody>
                                <tr>
                                    <th scope="row"><?php esc_html_e( 'Destination Sites', 'wp-multisite-content-copier-pro' ); ?></th>
                                    <td>
                                        <label><input class="wmcc-check-uncheck" type="checkbox" /><?php esc_html_e( 'All', 'wp-multisite-content-copier-pro' ); ?></label>
                                        <p class="description"><?php esc_html_e( 'Select/Deselect all sites.', 'wp-multisite-content-copier-pro' ); ?></p>
                                        <br>
                                        <fieldset class="wmcc-sites">                                           
                                            <?php                                                                                       
                                                if ( $sites != null ) {
                                                    foreach ( $sites as $key => $value ) { 
                                                        if ( $wmcc_source_blog != $value->blog_id ) {
                                                            if ( in_array( $value->blog_id, $wmcc_blogs ) ) {
                                                                $blog_details = get_blog_details( $value->blog_id );
                                                                ?>
                                                                    <label><input name="wmcc_destination_blogs[]" type="checkbox" value="<?php echo esc_attr( $value->blog_id ); ?>"><?php echo esc_html( $value->domain ); echo esc_html( $value->path ); echo ' ('.esc_html( $blog_details->blogname ).')'; ?></label><br>
                                                                <?php
                                                            }
                                                        }
                                                    }
                                                }
                                            ?>                                                                          				
                                        </fieldset>
                                    </td>
                                </tr>
                                <?php if ( $wmcc_content_type != 'users' ) { ?>
                                <tr>
                                    <th scope="row"><?php esc_html_e( 'Extra Options', 'wp-multisite-content-copier-pro' ); ?></th>
                                    <td>
                                        <fieldset>
                                            <label><input value="1" type="checkbox" name="copy_media"> <?php esc_html_e( 'Copy or update media ( Attachments )', 'wp-multisite-content-copier-pro' ); ?></label><br>
                                            <label><input value="1" type="checkbox" name="copy_terms"> <?php esc_html_e( 'Copy or update terms ( Categories & Tags )', 'wp-multisite-content-copier-pro' ); ?></label>
                                        </fieldset>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <p class="submit"><input name="wmcc_submit" class="button button-primary" value="<?php esc_html_e( 'Copy/Update', 'wp-multisite-content-copier-pro' ); ?>" type="submit"></p>
                    </form>
                    <?php                    
                    if ( $wmcc_source_blog != get_current_blog_id() ) {
                        restore_current_blog();
                    }
                }
            ?>
        </div>
        <?php
    }
}

/*
 * This is a function that call plugin settings.
 * Post Types: Set copier for specific post types.
 * User Roles: Set specific user role to access copier.
 * Sites: Set specif site to manage copier.
 */
if ( ! function_exists( 'wmcc_wordpress_multisite_content_copier_settings' ) ) {
    function wmcc_wordpress_multisite_content_copier_settings() {
        
        global $wpdb;
        
        $current_blog_id = get_current_blog_id();
        $sites = $wpdb->get_results( "SELECT * FROM ".$wpdb->base_prefix."blogs" );
        if ( isset( $_POST['submit'] ) ) {
            if ( isset( $_POST['wmcc_user_roles'] ) ) {
                if ( $_POST['wmcc_user_roles'] != null ) {
                    foreach ( $_POST['wmcc_user_roles'] as $key => $value ) {
                        $_POST['wmcc_user_roles'][$key] = sanitize_text_field( $value );
                    }
                }

                update_site_option( 'wmcc_user_roles', $_POST['wmcc_user_roles'] );
            } else {
                update_site_option( 'wmcc_user_roles', 0 );
            }
            
            if ( isset( $_POST['wmcc_post_types'] ) ) {
                if ( is_array( $_POST['wmcc_post_types'] ) && $_POST['wmcc_post_types'] != null ) {
                    foreach ( $_POST['wmcc_post_types'] as $key => $value ) {
                        $_POST['wmcc_post_types'][$key] = sanitize_text_field( $value );
                    }
                }

                update_site_option( 'wmcc_post_types', $_POST['wmcc_post_types'] );
            }
            
            if ( isset( $_POST['wmcc_blogs'] ) ) {
                if ( is_array( $_POST['wmcc_blogs'] ) && $_POST['wmcc_blogs'] != null ) {
                    foreach ( $_POST['wmcc_blogs'] as $key => $value ) {
                        $_POST['wmcc_blogs'][$key] = (int) $value;
                    }
                }

                update_site_option( 'wmcc_blogs', $_POST['wmcc_blogs'] );
            }
            
            if ( isset( $_POST['wmcc_old'] ) ) {
                update_site_option( 'wmcc_old', (int) $_POST['wmcc_old'] );
            }
            
            if ( isset( $_POST['wmcc_users'] ) ) {
                update_site_option( 'wmcc_users', (int) $_POST['wmcc_users'] );
            }
            
            if ( isset( $_POST['wmcc_exclude_meta_data'] ) ) {
                update_site_option( 'wmcc_exclude_meta_data', sanitize_text_field( $_POST['wmcc_exclude_meta_data'] ) );
            }
            
            if ( $sites != null ) {
                foreach ( $sites as $key => $value ) {
                    $blog_id = $value->blog_id;
                    if ( $blog_id != $current_blog_id ) {
                        switch_to_blog( $blog_id );
                    }
                    
                    global $wpdb;
                    $args = array(
                        'post_type'         => 'acf-field-group',
                        'post_status'       => 'publish',
                        'posts_per_page'    => -1,
                    );
                    $posts = get_posts( $args );
                    if ( $posts != null ) {
                        foreach ( $posts as $post ) {
                            $post_id = $post->ID;
                            $fields = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."posts WHERE post_parent=".$post_id );            
                            if ( $fields != null ) {
                                $types = array(
                                    'image',
                                    'file',
                                    'page_link',
                                    'post_object',
                                    'relationship',
                                    'taxonomy',
                                    'gallery',
                                );
                                
                                foreach ( $fields as $field ) {
                                    $filed_key = $field->post_excerpt;
                                    $field_data = unserialize( $field->post_content );
                                    $cf = $wpdb->get_row( "SELECT * FROM ".$wpdb->base_prefix."wmcc_cf WHERE filed_key='$filed_key'" );                
                                    if ( in_array( $field_data['type'], $types ) ) {
                                        if ( $cf != null ) {
                                            $wpdb->update(
                                                $wpdb->base_prefix . 'wmcc_cf',
                                                array( 
                                                    'filed_key'     => $field->post_excerpt,
                                                    'field_type'    => $field_data['type'],
                                                    'field_data'    => $field->post_content,                            
                                                ),
                                                array( 
                                                    'id' => $cf->id, 
                                                )
                                            );
                                        } else {                    
                                            $wpdb->insert(
                                                $wpdb->base_prefix . 'wmcc_cf',
                                                array( 
                                                    'filed_key'     => $field->post_excerpt,
                                                    'field_type'    => $field_data['type'],
                                                    'field_data'    => $field->post_content,                             
                                                )
                                            ); 
                                        }
                                    } else {
                                        if ( $cf != null ) {
                                            $wpdb->delete( 
                                                $wpdb->base_prefix . 'wmcc_cf',
                                                array( 
                                                    'id' => $cf->id, 
                                                )
                                            );
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if ( $blog_id != $current_blog_id ) {
                        restore_current_blog();
                    }
                }
            }

            ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php esc_html_e( 'Settings saved.', 'wp-multisite-content-copier-pro' ); ?></p>
                </div>
            <?php
        }
        
        $wmcc_post_types = get_site_option( 'wmcc_post_types' );
        $wmcc_blogs = get_site_option( 'wmcc_blogs' );
        if ( ! $wmcc_blogs ) {
            $wmcc_blogs = array();
        }
        
        $wmcc_user_roles = get_site_option( 'wmcc_user_roles' );
        $wmcc_old = get_site_option( 'wmcc_old' );
        $wmcc_users = get_site_option( 'wmcc_users' );
        $exclude_meta_data = get_site_option( 'wmcc_exclude_meta_data' );
        $wmcc_licence = get_site_option( 'wmcc_licence' );
        ?>
        <div class="wrap">      
            <h2><?php esc_html_e( 'Settings', 'wp-multisite-content-copier-pro' ); ?></h2>
            <hr>
            <?php
                if ( $wmcc_licence ) {
                    ?>
                    <form method="post">                
                        <table class="form-table">
                            <tbody>
                                <tr>
                                    <th scope="row"><?php esc_html_e( 'Post Types', 'wp-multisite-content-copier-pro' ); ?></th>
                                    <td>
                                        <fieldset>
                                            <?php
                                                $checked = '';
                                                if ( ! $wmcc_post_types ) {
                                                    $checked = ' checked="checked"';
                                                }
                                            ?>
                                            <label><input name="wmcc_post_types" type="checkbox" value="0"<?php echo esc_attr( $checked ); ?>><?php esc_html_e( 'None', 'wp-multisite-content-copier-pro' ); ?></label><br>
                                            <?php
                                                $post_types = get_post_types();
                                                if ( $post_types != null ) {
                                                    $not_in_post_types = array(
                                                        'nav_menu_item',
                                                        'attachment',
                                                        'revision',
                                                        'acf',
                                                        'custom_css',
                                                        'customize_changeset',
                                                        'oembed_cache',
                                                        'user_request',
                                                        'wp_block',
                                                        'wp_template',
                                                        'wp_template_part',
                                                        'wp_global_styles',
                                                        'wp_navigation',
                                                        'acf-field-group',
                                                        'acf-field',
                                                        'wp_font_family',
                                                        'wp_font_face',
                                                    );
                                                    foreach ( $post_types as $key => $value ) {
                                                        if ( ! in_array( $key, $not_in_post_types ) ) {
                                                            $post_type_label = get_post_type_object($key)->label;
                                                            $checked = '';
                                                            if ( $wmcc_post_types && in_array( $key, $wmcc_post_types ) ) {
                                                                $checked = ' checked="checked"';
                                                            }
                                                            ?>
                                                                <label><input name="wmcc_post_types[]" type="checkbox" value="<?php echo esc_attr( $key ); ?>"<?php echo esc_attr( $checked ); ?>><?php echo ( $post_type_label != null ? esc_html( $post_type_label ) : esc_html( $value ) ); ?></label><br>
                                                            <?php
                                                        }
                                                    }
                                                }
                                            ?>                                                                          				
                                        </fieldset>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php esc_html_e( 'Users', 'wp-multisite-content-copier-pro' ); ?></th>
                                    <td>
                                        <input name="wmcc_users" type="hidden" value="0" />
                                        <input name="wmcc_users" type="checkbox" value="1"<?php echo ( $wmcc_users ? ' checked="checked"' : '' ); ?> />
                                    </td>
                                </tr>	
                                <tr>
                                    <th scope="row"><?php esc_html_e( 'Sites', 'wp-multisite-content-copier-pro' ); ?></th>
                                    <td>
                                        <fieldset>
                                            <?php
                                                $checked = '';
                                                if ( ! $wmcc_blogs ) {
                                                    $checked = ' checked="checked"';
                                                }
                                            ?>
                                            <label><input name="wmcc_blogs" type="checkbox" value="0"<?php echo esc_attr( $checked ); ?>><?php esc_html_e( 'None', 'wp-multisite-content-copier-pro' ); ?></label><br>
                                            <?php
                                                if ( $sites != null ) {
                                                    foreach ( $sites as $key => $value ) {
                                                        $checked = '';
                                                        if ( $wmcc_blogs && in_array( $value->blog_id, $wmcc_blogs ) ) {
                                                            $checked = ' checked="checked"';
                                                        }

                                                        $blog_details = get_blog_details( $value->blog_id );
                                                        ?>
                                                            <label><input name="wmcc_blogs[]" type="checkbox" value="<?php echo esc_attr( $value->blog_id ); ?>"<?php echo esc_attr( $checked ); ?>><?php echo esc_html( $value->domain ); echo esc_html( $value->path ); echo ' ('.esc_html( $blog_details->blogname ).')'; ?></label><br>
                                                        <?php
                                                    }
                                                }
                                            ?>                                                                          				
                                        </fieldset>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php esc_html_e( 'Old posts, pages and custom post type posts check?', 'wp-multisite-content-copier-pro' ); ?></th>
                                    <td>
                                        <input type="hidden" name="wmcc_old" value="0" />
                                        <input type="checkbox" name="wmcc_old" value="1"<?php echo ( $wmcc_old ? ' checked="checked"' : '' ); ?> />
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php esc_html_e( 'Exclude Meta Data (posts, pages and custom post type posts)', 'wp-multisite-content-copier-pro' ); ?></th>
                                    <td>
                                        <input type="text" name="wmcc_exclude_meta_data" value="<?php echo esc_html( $exclude_meta_data ); ?>" class="regular-text" />
                                        <p class="description"><?php esc_html_e( 'Add meta key by comma separated.', 'wp-multisite-content-copier-pro' ); ?></p>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><?php esc_html_e( 'User Roles', 'wp-multisite-content-copier-pro' ); ?></th>
                                    <td>
                                        <label><input name="wmcc_user_roles" type="checkbox" value="0" checked="checked" disabled><?php esc_html_e( 'Super Admin', 'wp-multisite-content-copier-pro' ); ?></label><br>
                                        <fieldset>
                                            <?php
                                                $roles = get_editable_roles();
                                                if ( $roles != null ) {
                                                    foreach ( $roles as $key => $value ) {
                                                        if ( $key != 'subscriber' ) {
                                                            $checked = '';
                                                            if ( $wmcc_user_roles && in_array( $key, $wmcc_user_roles ) ) {
                                                                $checked = ' checked="checked"';
                                                            }
                                                            ?>
                                                                <label><input name="wmcc_user_roles[]" type="checkbox" value="<?php echo esc_attr( $key ); ?>"<?php echo esc_attr( $checked ); ?>><?php echo esc_html( $value['name'] ); ?></label><br>
                                                            <?php
                                                        }
                                                    }
                                                }
                                            ?>                                                                          				
                                        </fieldset>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <input type='submit' name="submit" class='button-primary' value="<?php esc_html_e( 'Save Changes', 'wp-multisite-content-copier-pro' ); ?>" />
                                    </th>
                                </tr>                        
                            </tbody>
                        </table>
                    </form>
                    <script type="text/javascript">
                    jQuery( document ).ready( function( $ ) {
                        $( 'input[type="checkbox"]' ).on( 'change', function() {
                            if ( $( this ).val() != 0 ) {
                                var fieldset = $( this ).closest( 'fieldset' );
                                $( 'input[type="checkbox"]', fieldset ).each( function() {
                                    if ( $( this ).val() == 0 ) {
                                        $( this ).prop('checked', false);
                                    }
                                });
                            } else {
                                var fieldset = $( this ).closest( 'fieldset' );
                                $( 'input[type="checkbox"]', fieldset ).each( function() {
                                    if ( $( this ).val() != 0 ) {
                                        $( this ).prop('checked', false);
                                    }
                                });
                            }                        
                        });
                    });
                </script>
                    <?php
                } else {
                    ?>
                        <div class="notice notice-error is-dismissible">
                            <p><?php esc_html_e( 'Please verify purchase code.', 'wp-multisite-content-copier-pro' ); ?></p>
                        </div>
                    <?php
                }
            ?>
        </div>
        <?php
    }
}

/*
 * This is a function that verify product licence.
 */
if ( ! function_exists( 'wmcc_licence_verification' ) ) {
    function wmcc_licence_verification() {
        
        if ( isset( $_POST['verify'] ) ) {
            if ( isset( $_POST['wmcc_purchase_code'] ) ) {
                update_site_option( 'wmcc_purchase_code', sanitize_text_field( $_POST['wmcc_purchase_code'] ) );
                
                $data = array(
                    'sku'           => '19166406',
                    'purchase_code' => $_POST['wmcc_purchase_code'],
                    'domain'        => site_url(),
                    'status'        => 'verify',
                    'type'          => 'oi',
                );

                $ch = curl_init();
                curl_setopt( $ch, CURLOPT_URL, 'https://www.obtaininfotech.com/extension/' );
                curl_setopt( $ch, CURLOPT_POST, 1 );
                curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $data ) );
                curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
                curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
                $json_response = curl_exec( $ch );
                curl_close ($ch);
                
                $response = json_decode( $json_response );
                if ( isset( $response->success ) ) {
                    if ( $response->success ) {
                        update_site_option( 'wmcc_licence', 1 );
                    }
                }
            }
        } else if ( isset( $_POST['unverify'] ) ) {
            if ( isset( $_POST['wmcc_purchase_code'] ) ) {
                $data = array(
                    'sku'           => '19166406',
                    'purchase_code' => $_POST['wmcc_purchase_code'],
                    'domain'        => site_url(),
                    'status'        => 'unverify',
                    'type'          => 'oi',
                );

                $ch = curl_init();
                curl_setopt( $ch, CURLOPT_URL, 'https://www.obtaininfotech.com/extension/' );
                curl_setopt( $ch, CURLOPT_POST, 1 );
                curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $data ) );
                curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
                curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
                $json_response = curl_exec( $ch );
                curl_close ($ch);

                $response = json_decode( $json_response );
                if ( isset( $response->success ) ) {
                    if ( $response->success ) {
                        update_site_option( 'wmcc_purchase_code', '' );
                        update_site_option( 'wmcc_licence', 0 );
                    }
                }
            }
        }    
        
        $wmcc_purchase_code = get_site_option( 'wmcc_purchase_code' );
        ?>
            <div class="wrap">      
                <h2><?php esc_html_e( 'Licence Verification', 'wp-multisite-content-copier-pro' ); ?></h2>
                <hr>
                <?php
                    if ( isset( $response->success ) ) {
                        if ( $response->success ) {                            
                             ?>
                                <div class="notice notice-success is-dismissible">
                                    <p><?php echo esc_html( $response->message ); ?></p>
                                </div>
                            <?php
                        } else {
                            update_site_option( 'wmcc_licence', 0 );
                            ?>
                                <div class="notice notice-error is-dismissible">
                                    <p><?php echo esc_html( $response->message ); ?></p>
                                </div>
                            <?php
                        }
                    }
                ?>
                <form method="post">
                    <table class="form-table">                    
                        <tbody>
                            <tr>
                                <th scope="row"><?php esc_html_e( 'Purchase Code', 'wp-multisite-content-copier-pro' ); ?></th>
                                <td>
                                    <input name="wmcc_purchase_code" type="text" class="regular-text" value="<?php echo esc_html( $wmcc_purchase_code ); ?>" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <p>
                        <input type='submit' class='button-primary' name="verify" value="<?php esc_html_e( 'Verify', 'wp-multisite-content-copier-pro' ); ?>" />
                        <input type='submit' class='button-primary' name="unverify" value="<?php esc_html_e( 'Unverify', 'wp-multisite-content-copier-pro' ); ?>" />
                    </p>
                </form>   
            </div>
        <?php
    }
}
