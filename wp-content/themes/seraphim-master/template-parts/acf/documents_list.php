<?php
/**
 * Documents List ACF Component
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$selected_type = get_sub_field('document_type');

$args = array(
    'post_type'      => 'document',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
);

if ( $selected_type && $selected_type !== 'all' ) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'document-type',
            'field'    => 'slug',
            'terms'    => $selected_type,
        ),
    );
}

$query = new WP_Query( $args );

if ( $query->have_posts() ) : 
    $grouped_documents = array();
    
    while ( $query->have_posts() ) : $query->the_post();
        $item_types = get_the_terms( get_the_ID(), 'document-type' );
        $type_name = 'Uncategorized';
        $type_slug = 'uncategorized';
        
        if ( ! empty( $item_types ) && ! is_wp_error( $item_types ) ) {
            $type_name = $item_types[0]->name;
            $type_slug = $item_types[0]->slug;
        }

        if ( ! isset( $grouped_documents[$type_slug] ) ) {
            $grouped_documents[$type_slug] = array(
                'name'  => $type_name,
                'items' => array()
            );
        }

        $document_file = get_field('document_file');
        $date_updated = get_the_modified_date('d M Y');
        
        $file_url = '';
        if ( is_array( $document_file ) ) {
            $file_url = $document_file['url'];
        } elseif ( is_numeric( $document_file ) ) {
            $file_url = wp_get_attachment_url( $document_file );
        } elseif ( is_string( $document_file ) ) {
            $file_url = $document_file;
        }

        $grouped_documents[$type_slug]['items'][] = array(
            'title'        => get_the_title(),
            'date_updated' => $date_updated,
            'file_url'     => $file_url,
            'type_slugs'   => ! empty( $item_types ) && ! is_wp_error( $item_types ) ? implode( ' ', wp_list_pluck( $item_types, 'slug' ) ) : ''
        );
    endwhile; wp_reset_postdata();
    ?>
    <section class="documents-list-section py-5">
        <div class="container">
            <?php 
            $terms = get_terms([
                'taxonomy' => 'document-type',
                'hide_empty' => true,
            ]);
            
            if ( $selected_type === 'all' && ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
                <div class="results-tabs documents-tabs mb-4">
                    <button class="results-tab active" data-filter="all">All</button>
                    <?php foreach ( $terms as $term ) : ?>
                        <button class="results-tab" data-filter="<?php echo esc_attr( $term->slug ); ?>"><?php echo esc_html( $term->name ); ?></button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="documents-grouped-list accordion" id="documentsAccordion">
                <?php 
                $index = 0;
                foreach ( $grouped_documents as $slug => $group ) : 
                    $index++;
                    $collapse_id = 'collapse-' . $slug;
                    $heading_id = 'heading-' . $slug;
                    ?>
                    <div class="document-group-card mb-4" data-type="<?php echo esc_attr( $slug ); ?>">
                        <div class="document-group-header" id="<?php echo esc_attr( $heading_id ); ?>">
                            <button class="document-group-trigger collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo esc_attr( $collapse_id ); ?>" aria-expanded="false" aria-controls="<?php echo esc_attr( $collapse_id ); ?>">
                                <span class="group-title h4 mb-0"><?php echo esc_html( $group['name'] ); ?></span>
                                <span class="group-icon"><i class="ci-Chevron_Down"></i></span>
                            </button>
                        </div>

                        <div id="<?php echo esc_attr( $collapse_id ); ?>" class="collapse" aria-labelledby="<?php echo esc_attr( $heading_id ); ?>" data-bs-parent="#documentsAccordion">
                            <div class="document-group-content pt-3">
                                <div class="documents-list">
                                    <?php foreach ( $group['items'] as $item ) : ?>
                                        <div class="document-item holdings-list-row row py-4 border-bottom align-items-center d-flex" data-type="<?php echo esc_attr( $item['type_slugs'] ); ?>">
                                            <div class="col-12 col-md-4 mb-2 mb-md-0">
                                                <h4 class="document-item__title h5 mb-0"><?php echo esc_html( $item['title'] ); ?></h4>
                                            </div>
                                            <div class="col-6 col-md-3 mb-2 mb-md-0">
                                                <span class="d-md-none text-muted small d-block">Type:</span>
                                                <div class="document-item__type"><?php echo esc_html( $group['name'] ); ?></div>
                                            </div>
                                            <div class="col-6 col-md-3 mb-2 mb-md-0">
                                                <span class="d-md-none text-muted small d-block">Date:</span>
                                                <div class="document-item__date"><?php echo esc_html( $item['date_updated'] ); ?></div>
                                            </div>
                                            <div class="col-12 col-md-2 text-md-end">
                                                <?php if ( $item['file_url'] ) : ?>
                                                    <a href="<?php echo esc_url( $item['file_url'] ); ?>" class="button button--download tertiary small" target="_blank" download>
                                                        Download <i class="ci-Download"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if ( $selected_type === 'all' ) : ?>
            <script>
                (function() {
                    const tabs = document.querySelectorAll('.documents-tabs .results-tab');
                    const groups = document.querySelectorAll('.document-group-card');

                    tabs.forEach(tab => {
                        tab.addEventListener('click', function() {
                            const filter = this.getAttribute('data-filter');
                            
                            tabs.forEach(t => t.classList.remove('active'));
                            this.classList.add('active');
                            
                            groups.forEach(group => {
                                const groupType = group.getAttribute('data-type');
                                if (filter === 'all' || filter === groupType) {
                                    group.style.display = 'block';
                                } else {
                                    group.style.display = 'none';
                                }
                            });
                        });
                    });
                })();
            </script>
        <?php endif; ?>
    </section>
<?php endif; ?>