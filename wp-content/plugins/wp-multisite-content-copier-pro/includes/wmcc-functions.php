<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit( 'restricted access' );
}

/*
 * This is a function that show copier section.
 * Show multisite relationships.
 */
if ( ! function_exists( 'wmcc_display_content_copier' ) ) {
    add_action( 'wp_ajax_display_content_copier', 'wmcc_display_content_copier' );
    function wmcc_display_content_copier() {
        
        global $wpdb;
        
        $current_blog_id = get_current_blog_id();
        $current_item_id = (int) $_POST['item_id'];
        $type = sanitize_text_field( $_POST['type'] );
        $type_name = sanitize_text_field( $_POST['type_name'] );
        
        $sites = $wpdb->get_results( "SELECT * FROM ".$wpdb->base_prefix."blogs" );
        if ( $sites != null ) {
            $wmcc_blogs = get_site_option( 'wmcc_blogs' );
            if ( ! $wmcc_blogs ) {
                $wmcc_blogs = array();
            } 
            
            if ( $wmcc_blogs != null ) {                
                ?>
                <p><strong><?php esc_html_e( 'Select destination sites you want to copy or update.', 'wp-multisite-content-copier-pro' ); ?></strong></p>
                <div id="wmcc-message"></div>
                <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="wmcc-detail-check-uncheck" type="checkbox" /><?php esc_html_e( 'All', 'wp-multisite-content-copier-pro' ); ?></label>
                <p class="description">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php esc_html_e( 'Select/Deselect all sites.', 'wp-multisite-content-copier-pro' ); ?></p>
                <script type="text/javascript">
                    jQuery( document ).ready( function() {
                        jQuery( '.wmcc-detail-check-uncheck' ).on( 'change', function() {                            
                            var checked = jQuery( this ).prop( 'checked' );
                            jQuery( '#wmcc-sites input[type="checkbox"]' ).each( function() {
                                if ( checked ) {
                                    jQuery( this ).prop( 'checked', true );
                                } else {
                                    jQuery( this ).prop( 'checked', false );
                                }
                            });                   
                        }); 
                    });
                </script>
                <div id="wmcc-sites">                    
                    <?php
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
                        
                        foreach ( $sites as $key => $value ) {
                            if ( in_array( $value->blog_id, $wmcc_blogs ) && $value->blog_id != get_current_blog_id() ) {
                                $checked = '';
                                $post_modified = '';
                                if ( isset( $blog_relationships[$value->blog_id] ) ) {
                                    $checked = ' checked="checked"';
                                    
                                    switch_to_blog( $value->blog_id );
                                        $blog_post = get_post( $blog_relationships[$value->blog_id] );
                                        if ( $blog_post != null ) {                                            
                                            $date_format = get_option('date_format');
                                            $time_format = get_option('time_format');                                            
                                            $post_modified = '( '.date( $date_format.' @ '.$time_format, strtotime( $blog_post->post_modified ) ).' )';                                           
                                        }
                                    restore_current_blog();
                                }
                                $blog_details = get_blog_details( $value->blog_id );
                                ?>
                                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label><input type="checkbox" value="<?php echo esc_attr( $value->blog_id ); ?>"<?php echo esc_attr( $checked ); ?>><?php echo esc_html( $value->domain ); echo esc_html( $value->path ); echo ' ('.esc_html( $blog_details->blogname ).')'; ?> <strong><?php echo esc_html( $post_modified ); ?></strong></label></p>
                                <?php
                            }
                        }
                    ?>
                </div>
                <p><strong><?php esc_html_e( 'Extra Options', 'wp-multisite-content-copier-pro' ); ?></strong></p>
                <?php
                    if ( $type == 'post_type' ) {
                        ?>
                            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label><input id="is-copy-media" type="checkbox" value="1"><?php esc_html_e( ' Copy or update media (Attachments)', 'wp-multisite-content-copier-pro' ); ?></label></p>
                        <?php
                        $taxonomies = get_object_taxonomies( $type_name, 'objects' );
                        if ( $taxonomies != null ) {
                            $taxonomies_title = array();
                            foreach ( $taxonomies as $taxonomy_key => $taxonomy_value ) {
                                $taxonomies_title[] = $taxonomy_value->label;
                            }
                            ?>
                                <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label><input id="is-copy-terms" type="checkbox" value="1"><?php esc_html_e( ' Copy or update terms (', 'wp-multisite-content-copier-pro' ); echo implode( ', ', $taxonomies_title ); esc_html_e(')', 'wp-multisite-content-copier-pro' ); ?></label></p>
                            <?php
                        }
                    }
                ?>                
                <button type="button" id="wmcc-copy" class="button-primary"><?php esc_html_e( 'Copy/Update', 'wp-multisite-content-copier-pro' ); ?></button>            
                <?php      
            } else {
                ?>
                <div id="wmcc-notice">
                    <div class="wmcc-notice-warning">
                        <p><?php esc_html_e( 'Sites is not selected. Please go to Network Admin->WMCC and select sites.', 'wp-multisite-content-copier-pro' ); ?></p>
                    </div>
                </div>
                <?php
            }           
        }
        
        wp_die();
    }
}

/*
 * This is a function that send content for copy or update.
 * AJAX callback function.
 */
if ( ! function_exists( 'wmcc_send_content_copier' ) ) {
    add_action( 'wp_ajax_send_content_copier', 'wmcc_send_content_copier' );
    function wmcc_send_content_copier() {
        
        global $wpdb;
        
        $current_user = wp_get_current_user();

        $exclude_meta_data = array();
        $wmcc_exclude_meta_data = get_site_option( 'wmcc_exclude_meta_data' );
        if ( $wmcc_exclude_meta_data ) {
            $exclude_meta_data = explode( ',', $wmcc_exclude_meta_data );
        }
        
        $blogs = $_POST['sites'];
        $source_blog_id = get_current_blog_id();
        $source_item_id = (int) $_POST['item_id'];
        $type = sanitize_text_field( $_POST['type'] );
        $type_name = sanitize_text_field( $_POST['type_name'] );
        $copy_media = (int) $_POST['copy_media'];
        $copy_terms = (int) $_POST['copy_terms'];

        if ( $type == 'post_type' ) {
            if ( $blogs != null ) {                
                $taxonomies = array();
                $taxonomy_objects = get_object_taxonomies( $type_name );
                if ( $taxonomy_objects != null && $copy_terms ) {
                    foreach ( $taxonomy_objects as $taxonomy_object ) {
                        if ( ! in_array( $taxonomy_object, $exclude_meta_data ) ) {
                            $post_terms = wp_get_post_terms( $source_item_id, $taxonomy_object );
                            $taxonomies[$taxonomy_object] = $post_terms;
                        }
                    }
                }
                
                foreach ( $blogs as $blog ) {
                    $destination_blog_id = (int) $blog;
                    $destination_post_id = wmcc_copy_post( $source_item_id, $source_blog_id, $type, $type_name, $destination_blog_id, $copy_media );
                    
                    if ( $taxonomies != null && $destination_post_id ) {
                        foreach ( $taxonomies as $taxonomy => $terms ) {
                            $destination_terms = array();
                            if ( $terms != null ) {
                                foreach ( $terms as $term ) {
                                    $destination_term_id = wmcc_copy_term( $term, $source_blog_id, 'taxonomy', $taxonomy, $destination_blog_id );
                                    if ( $destination_term_id ) {
                                        $destination_terms[] = (int) $destination_term_id;
                                    }
                                }
                            }
                            
                            wmcc_set_destination_post_terms( $destination_post_id, $destination_terms, $taxonomy, $destination_blog_id );
                        }
                    }
                }
            }                       
        }
        
        wp_die();
    }
}

/*
 * This is a function that copy or update post.
 * $source_item_id variable return source blog item id.
 * $source_blog_id variable return source blog id.
 * $type variable return conetnt post_type.
 * $type_name variable return content type name like posts, pages, custom post types, etc...
 * $destination_blog_id variable return destination blog id.
 * $copy_media variable return copy media or not.
 */
if ( ! function_exists( 'wmcc_copy_post' ) ) {
    function wmcc_copy_post( $source_item_id = 0, $source_blog_id = 0, $type = '', $type_name = '', $destination_blog_id = 0, $copy_media = 0 ) {
        
        if ( $source_item_id && $source_blog_id && $type && $type_name && $destination_blog_id ) {
            $current_blog_id = get_current_blog_id();
            if ( $source_blog_id != $current_blog_id ) {                
                switch_to_blog( $source_blog_id );
            }
            
            global $wpdb;
            $item = get_post( $source_item_id );
            if ( $item != null ) {
                $current_user = wp_get_current_user();
                $post_name = $item->post_name;
                $post_data = array(
                    'post_author'           => $item->post_author,
                    'post_date'             => $item->post_date,
                    'post_content'          => $item->post_content,
                    'post_title'            => wp_strip_all_tags( $item->post_title ),
                    'post_excerpt'          => $item->post_excerpt,            
                    'post_status'           => $item->post_status,
                    'comment_status'        => $item->comment_status,
                    'ping_status'           => $item->ping_status,
                    'post_password'         => $item->post_password,
                    'to_ping'               => $item->to_ping,
                    'pinged'                => $item->pinged,
                    'post_content_filtered' => $item->post_content_filtered,                    
                    'menu_order'            => $item->menu_order,
                    'post_type'             => $item->post_type,
                    'post_mime_type'        => $item->post_mime_type,                    
                );
                
                $custom_fields = $wpdb->get_results( "SELECT * FROM ".$wpdb->base_prefix."wmcc_cf" );
                $special_custom_fields = array();
                if ( $custom_fields != null ) {
                    foreach ( $custom_fields as $custom_field ) {
                        $special_custom_fields[$custom_field->filed_key] = array(
                            'type'  => $custom_field->field_type,
                            'data'  => $custom_field->field_data,
                        );
                    }
                }
                
                $postmeta_fields = array();                
                $postmetas = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."postmeta WHERE post_id=$source_item_id" );
                if ( $postmetas != null ) {
                    foreach ( $postmetas as $postmeta ) {
                        if ( isset( $special_custom_fields[$postmeta->meta_key] ) ) {
                            $special_custom_field = $special_custom_fields[$postmeta->meta_key];                            
                            if ( $copy_media ) {
                                if ( $special_custom_field['type'] == 'image' || $special_custom_field['type'] == 'file' ) {
                                    $cf_attachment_id = get_post_meta( $source_item_id, $postmeta->meta_key, true );
                                    if ( $cf_attachment_id ) {
                                        $cf_attachment = get_post( $cf_attachment_id );
                                        $cf_attachment_path = get_attached_file( $cf_attachment_id );                                
                                        if ( $cf_attachment_path != null ) {
                                            $postmeta_fields[$postmeta->meta_key] = array(
                                                'post_title'                => wp_strip_all_tags( $cf_attachment->post_title ),
                                                'post_content'              => $cf_attachment->post_content,                            
                                                'post_excerpt'              => $cf_attachment->post_excerpt,
                                                'wp_attachment_image_alt'   => get_post_meta( $cf_attachment_id, '_wp_attachment_image_alt', true ),
                                                'post_name'                 => $cf_attachment->post_name, 
                                                'path'                      => $cf_attachment_path,
                                            );
                                        }                    
                                    } else {
                                        $postmeta_fields[$postmeta->meta_key] = '';
                                    }
                                }
                            }                            
                        } else {
                            $postmeta_fields[$postmeta->meta_key] = get_post_meta( $source_item_id, $postmeta->meta_key, true );
                        }
                    }
                }              
                
                $thumbnail_id = get_post_meta( $source_item_id, '_thumbnail_id', true );
                if ( $thumbnail_id && $copy_media ) {
                    $thumbnail = get_post( $thumbnail_id );
                    $thumbnail_path = get_attached_file( $thumbnail_id );                                
                    if ( $thumbnail_path != null ) {
                        $postmeta_fields['_thumbnail_id'] = array(
                            'post_title'                => wp_strip_all_tags( $thumbnail->post_title ),
                            'post_content'              => $thumbnail->post_content,                            
                            'post_excerpt'              => $thumbnail->post_excerpt,
                            'wp_attachment_image_alt'   => get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true ),
                            'post_name'                 => $thumbnail->post_name, 
                            'path'                      => $thumbnail_path,
                        );
                    }                    
                }
                
                $wmcc_exclude_meta_data = get_site_option( 'wmcc_exclude_meta_data' );
                if ( $wmcc_exclude_meta_data ) {
                    $exclude_meta_data = explode( ',', $wmcc_exclude_meta_data );
                    if ( is_array( $exclude_meta_data ) && $postmeta_fields != null ) {
                        foreach ( $postmeta_fields as $post_meta_key => $post_meta_value ) {
                            if ( in_array( $post_meta_key, $exclude_meta_data ) ) {
                                unset( $postmeta_fields[$post_meta_key] );
                            }
                        }
                    }
                    
                    if ( is_array( $exclude_meta_data ) && $post_data != null ) {
                        foreach ( $post_data as $post_data_key => $post_data_value ) {
                            if ( in_array( $post_data_key, $exclude_meta_data ) ) {
                                unset( $post_data[$post_data_key] );
                            }
                        }
                    }
                }
                
                $blog_relationships = array();
                $current_relationship = $wpdb->get_row( "SELECT * FROM ".$wpdb->base_prefix."wmcc_relationships WHERE type='$type' AND type_name='$type_name' AND ((source_item_id='$source_item_id' AND source_blog_id='$source_blog_id') || (destination_item_id='$source_item_id' AND destination_blog_id='$source_blog_id'))" );  
                if ( $current_relationship != null ) {
                    $relationship_id = $current_relationship->relationship_id;
                    $relationships = $wpdb->get_results( "SELECT * FROM ".$wpdb->base_prefix."wmcc_relationships WHERE relationship_id='$relationship_id'");
                    if ( $relationships != null ) {
                        foreach ( $relationships as $relationship ) {
                            if ( $source_blog_id == $relationship->source_blog_id && $source_item_id == $relationship->source_item_id ) {
                                $blog_relationships[$relationship->destination_blog_id] = $relationship->destination_item_id;
                            } else if ( $source_blog_id == $relationship->destination_blog_id && $source_item_id == $relationship->destination_item_id ) {
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
                
                if ( $blog_relationships == null ) {
                    $relationship_id = uniqid();
                }
                
                $post_data['post_parent'] = 0;
                if ( $item->post_parent ) {
                    $item_parent_id = wmcc_copy_post( $item->post_parent, $source_blog_id, $type, $type_name, $destination_blog_id );
                    if ( $item_parent_id ) {
                        $post_data['post_parent'] = $item_parent_id;
                    }
                }
                
                if ( $source_blog_id != $current_blog_id ) {                
                    restore_current_blog();
                }
                
                switch_to_blog( $destination_blog_id );
                    $post_data['post_content'] = wp_slash( $post_data['post_content'] );
                    $post_data['post_excerpt'] = wp_slash( $post_data['post_excerpt'] );
                    
                    if ( isset( $blog_relationships[$destination_blog_id] ) && get_post( $blog_relationships[$destination_blog_id] ) != null ) {
                        $destination_item_id = $blog_relationships[$destination_blog_id];
                        $post_data['ID'] = $destination_item_id;
                        wp_update_post( $post_data );
                    } else {
                        $destination_item_not_exists = 1;
                        $wmcc_old = get_site_option( 'wmcc_old' );
                        if ( $wmcc_old ) {                            
                            $check_destination_item_args = array(
                                'name'              => $post_name,
                                'post_type'         => $post_data['post_type'],
                                'posts_per_page'    => 1,
                            );
                            $check_destination_item = get_posts( $check_destination_item_args );
                            if ( $check_destination_item != null && $check_destination_item[0]->post_name == $post_name ) {
                                $destination_item_id = $check_destination_item[0]->ID;
                                if( $destination_item_id ) {
                                    $post_data['ID'] = $destination_item_id;
                                    wp_update_post( $post_data );
                                    $wpdb->insert(
                                        $wpdb->base_prefix . 'wmcc_relationships',
                                        array( 
                                            'source_item_id'        => $source_item_id,
                                            'source_blog_id'        => $source_blog_id,
                                            'destination_item_id'   => $destination_item_id,
                                            'destination_blog_id'   => $destination_blog_id,
                                            'relationship_id'       => $relationship_id,
                                            'type'                  => $type,
                                            'type_name'             => $type_name,
                                        )
                                    ); 
                                    
                                    $destination_item_not_exists = 0;
                                }
                            }
                        }
                        
                        if ( $destination_item_not_exists ) {
                            $destination_item_id = wp_insert_post( $post_data ); 
                            if( $destination_item_id ) {
                                $wpdb->insert(
                                    $wpdb->base_prefix . 'wmcc_relationships',
                                    array( 
                                        'source_item_id'        => $source_item_id,
                                        'source_blog_id'        => $source_blog_id,
                                        'destination_item_id'   => $destination_item_id,
                                        'destination_blog_id'   => $destination_blog_id,
                                        'relationship_id'       => $relationship_id,
                                        'type'                  => $type,
                                        'type_name'             => $type_name,
                                    )
                                );                       
                            }
                        }
                    }
                    
                    if ( $destination_item_id && $postmeta_fields ) {
                        if ( $postmeta_fields != null ) {
                            foreach ( $postmeta_fields as $field_key => $field_value ) {
                                if ( isset( $special_custom_fields[$field_key] ) ) {
                                    $special_custom_field = $special_custom_fields[$field_key];
                                    if ( $special_custom_field['type'] == 'image' || $special_custom_field['type'] == 'file' ) {
                                        $postmeta_field_value = $postmeta_fields[$field_key];
                                        if ( isset( $postmeta_field_value['path'] ) && $postmeta_field_value != null ) { 
                                            $check_attachment_args = array(
                                                'name'              => $postmeta_field_value['post_name'],
                                                'post_type'         => 'attachment',
                                                'post_status'       => 'inherit',
                                                'posts_per_page'    => 1,
                                            );
                                            $check_attachment = get_posts( $check_attachment_args );
                                            if ( $check_attachment != null && $check_attachment[0]->post_name == $postmeta_field_value['post_name'] ) {       
                                                $attach_id = $check_attachment[0]->ID;
                                                update_post_meta( $destination_item_id, $field_key, $attach_id );
                                                $update_attachment = array(
                                                    'ID'            => $attach_id,
                                                    'post_title'    => $postmeta_field_value['post_title'],
                                                    'post_content'  => $postmeta_field_value['post_content'],
                                                    'post_excerpt'  => $postmeta_field_value['post_excerpt'],
                                                );                                            
                                                wp_update_post( $update_attachment );
                                                update_post_meta( $attach_id, '_wp_attachment_image_alt', $postmeta_field_value['wp_attachment_image_alt'] );
                                            } else {                                        
                                                $file = $postmeta_field_value['path'];
                                                $upload_file = wp_upload_bits( basename( $file ), null, file_get_contents( $file ) );
                                                if ( ! $upload_file['error'] ) {
                                                    $filetype = wp_check_filetype( basename( $file ), null );
                                                    $attachment = array(         
                                                        'post_mime_type' => $filetype['type'],
                                                        'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $file ) ),                                            
                                                        'post_status'    => 'inherit'
                                                    );
                                                    $attach_id = wp_insert_attachment( $attachment, $upload_file['file'] );
                                                    if ( $attach_id ) {
                                                        update_post_meta( $destination_item_id, $field_key, $attach_id );
                                                        require_once( ABSPATH . 'wp-admin/includes/media.php' );
                                                        require_once( ABSPATH . 'wp-admin/includes/image.php' );
                                                        require_once( ABSPATH . 'wp-admin/includes/file.php' );
                                                        $attach_data = wp_generate_attachment_metadata( $attach_id, $upload_file['file'] );
                                                        wp_update_attachment_metadata( $attach_id, $attach_data ); 

                                                        $update_attachment = array(
                                                            'ID'            => $attach_id,
                                                            'post_title'    => $postmeta_field_value['post_title'],
                                                            'post_content'  => $postmeta_field_value['post_content'],
                                                            'post_excerpt'  => $postmeta_field_value['post_excerpt'],
                                                        );                                            
                                                        wp_update_post( $update_attachment );
                                                        update_post_meta( $attach_id, '_wp_attachment_image_alt', $postmeta_field_value['wp_attachment_image_alt'] );
                                                    } 
                                                } 
                                            }
                                        } else {
                                            delete_post_meta( $destination_item_id, $field_key );
                                        }
                                    }
                                } else {
                                    update_post_meta( $destination_item_id, $field_key, wp_slash( $field_value ) );
                                }
                            }
                        }
                        
                        if ( $copy_media ) {
                            if ( isset( $postmeta_fields['_thumbnail_id'] ) ) {
                                $postmeta_field_value = $postmeta_fields['_thumbnail_id'];
                                if ( isset( $postmeta_field_value['path'] ) && $postmeta_field_value != null ) { 
                                    $check_attachment_args = array(
                                        'name'              => $postmeta_field_value['post_name'],
                                        'post_type'         => 'attachment',
                                        'post_status'       => 'inherit',
                                        'posts_per_page'    => 1,
                                    );
                                    $check_attachment = get_posts( $check_attachment_args );
                                    if ( $check_attachment != null && $check_attachment[0]->post_name == $postmeta_field_value['post_name'] ) {       
                                        $attach_id = $check_attachment[0]->ID;
                                        update_post_meta( $destination_item_id, '_thumbnail_id', $attach_id );
                                        $update_attachment = array(
                                            'ID'            => $attach_id,
                                            'post_title'    => $postmeta_field_value['post_title'],
                                            'post_content'  => $postmeta_field_value['post_content'],
                                            'post_excerpt'  => $postmeta_field_value['post_excerpt'],
                                        );                                            
                                        wp_update_post( $update_attachment );
                                        update_post_meta( $attach_id, '_wp_attachment_image_alt', $postmeta_field_value['wp_attachment_image_alt'] );
                                    } else {                                        
                                        $file = $postmeta_field_value['path'];
                                        $upload_file = wp_upload_bits( basename( $file ), null, file_get_contents( $file ) );
                                        if ( ! $upload_file['error'] ) {
                                            $filetype = wp_check_filetype( basename( $file ), null );
                                            $attachment = array(         
                                                'post_mime_type' => $filetype['type'],
                                                'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $file ) ),                                            
                                                'post_status'    => 'inherit'
                                            );
                                            $attach_id = wp_insert_attachment( $attachment, $upload_file['file'] );
                                            if ( $attach_id ) {
                                                update_post_meta( $destination_item_id, '_thumbnail_id', $attach_id );
                                                require_once( ABSPATH . 'wp-admin/includes/media.php' );
                                                require_once( ABSPATH . 'wp-admin/includes/image.php' );
                                                require_once( ABSPATH . 'wp-admin/includes/file.php' );
                                                $attach_data = wp_generate_attachment_metadata( $attach_id, $upload_file['file'] );
                                                wp_update_attachment_metadata( $attach_id, $attach_data ); 

                                                $update_attachment = array(
                                                    'ID'            => $attach_id,
                                                    'post_title'    => $postmeta_field_value['post_title'],
                                                    'post_content'  => $postmeta_field_value['post_content'],
                                                    'post_excerpt'  => $postmeta_field_value['post_excerpt'],
                                                );                                            
                                                wp_update_post( $update_attachment );
                                                update_post_meta( $attach_id, '_wp_attachment_image_alt', $postmeta_field_value['wp_attachment_image_alt'] );
                                            } 
                                        } 
                                    }
                                }
                            } else {
                                delete_post_meta( $destination_item_id, '_thumbnail_id' );
                            }
                        }
                    }
                restore_current_blog();
                
                return $destination_item_id;
            }
        }
    }
}

/*
 * This is a function that copy or update term.
 * $term variable return source blog term data.
 * $source_blog_id variable return source blog id.
 * $type variable return conetnt taxonomy.
 * $type_name variable return content type name like category, tag etc...
 * $destination_blog_id variable return destination blog id.
 */
if ( ! function_exists( 'wmcc_copy_term' ) ) {
    function wmcc_copy_term( $term = null, $source_blog_id = 0, $type = '', $type_name = '', $destination_blog_id = 0 ) {
        
        if ( $term != null && $source_blog_id && $type && $type_name && $destination_blog_id ) {
            $current_blog_id = get_current_blog_id();
            if ( $source_blog_id != $current_blog_id ) {                
                switch_to_blog( $source_blog_id );
            }
            
            global $wpdb;
            $source_item_id = $term->term_id;
            $blog_relationships = array();
            $current_relationship = $wpdb->get_row( "SELECT * FROM ".$wpdb->base_prefix."wmcc_relationships WHERE type='$type' AND type_name='$type_name' AND ((source_item_id='$source_item_id' AND source_blog_id='$source_blog_id') || (destination_item_id='$source_item_id' AND destination_blog_id='$source_blog_id'))" );  
            if ( $current_relationship != null ) {
                $relationship_id = $current_relationship->relationship_id;
                $relationships = $wpdb->get_results( "SELECT * FROM ".$wpdb->base_prefix."wmcc_relationships WHERE relationship_id='$relationship_id'");
                if ( $relationships != null ) {
                    foreach ( $relationships as $relationship ) {
                        if ( $source_blog_id == $relationship->source_blog_id && $source_item_id == $relationship->source_item_id ) {
                            $blog_relationships[$relationship->destination_blog_id] = $relationship->destination_item_id;
                        } else if ( $source_blog_id == $relationship->destination_blog_id && $source_item_id == $relationship->destination_item_id ) {
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

            if ( $blog_relationships == null ) {
                $relationship_id = uniqid();
            }
            
            $item_parent_id = 0;
            if ( $term->parent ) {
                $term_parent = get_term( $term->parent, $term->taxonomy );
                if ( $term_parent != null ) {
                    $item_parent_id = wmcc_copy_term( $term_parent, $source_blog_id, $type, $type_name, $destination_blog_id );
                }
            }
            
            if ( $source_blog_id != $current_blog_id ) {                
                restore_current_blog();
            }
            
            switch_to_blog( $destination_blog_id );
                if ( isset( $blog_relationships[$destination_blog_id] ) ) {
                    $destination_item_id = $blog_relationships[$destination_blog_id];    
                    wp_update_term(
                        $destination_item_id,                            
                        $term->taxonomy,
                        array(
                            'name'          => $term->name,
                            'description'   => $term->description,
                            'parent'        => $item_parent_id,
                        )
                    ); 
                } else {
                    $insert_term = wp_insert_term(
                        $term->name,
                        $term->taxonomy,
                        array(
                            'description'   => $term->description,
                            'parent'        => $item_parent_id,
                        )
                    );
                    
                    if ( is_wp_error( $insert_term ) ) {  
                        if ( isset( $insert_term->error_data ) ) {
                            $error_data = $insert_term->error_data;
                            if ( isset( $error_data['term_exists'] ) ) {
                                $destination_item_id = $error_data['term_exists'];                               
                            }
                        }
                    } else {
                        if ( isset( $insert_term['term_id'] ) ) {
                            $destination_item_id = $insert_term['term_id'];
                        }
                    }
                    
                    if( $destination_item_id ) {
                        $wpdb->insert(
                            $wpdb->base_prefix . 'wmcc_relationships',
                            array( 
                                'source_item_id'        => $source_item_id,
                                'source_blog_id'        => $source_blog_id,
                                'destination_item_id'   => $destination_item_id,
                                'destination_blog_id'   => $destination_blog_id,
                                'relationship_id'       => $relationship_id,
                                'type'                  => $type,
                                'type_name'             => $type_name,
                            )
                        );                       
                    }                               
                }
            restore_current_blog();
           
            return $destination_item_id;
        }
    }
}

/*
 * This is a function that set destination site post terms.
 * $destination_post_id variable return destination post id.
 * $destination_terms variable return terms data.
 * $taxonomy variable return taxonomy type.
 * $destination_blog_id variable return destination blog id.
 */
if ( ! function_exists( 'wmcc_set_destination_post_terms' ) ) {
    function wmcc_set_destination_post_terms( $destination_post_id = 0, $destination_terms = array(), $taxonomy = '', $destination_blog_id = 0 ) {
        
        if ( $destination_post_id && $taxonomy && $destination_blog_id ) {
            switch_to_blog( $destination_blog_id );
                
                wp_set_post_terms( $destination_post_id, $destination_terms, $taxonomy ); 
                if ( $destination_terms != null ) {
                    wp_update_term_count_now( $destination_terms, $taxonomy );
                }

            restore_current_blog();
        }
    }
}
