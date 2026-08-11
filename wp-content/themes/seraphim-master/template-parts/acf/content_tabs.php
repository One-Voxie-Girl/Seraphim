<?php
/**
 * Content Tabs Layout
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$tab_group_id = 'tabs-' . uniqid();
$tabs = [];

if ( have_rows( 'content_tab' ) ) :
    while ( have_rows( 'content_tab' ) ) : the_row();
        $tabs[] = [
            'title'   => get_sub_field( 'tab_title' ),
            'rows'    => get_field( 'content_row' ) // This gets the raw array of layouts
        ];
    endwhile;
endif;

if ( ! empty( $tabs ) ) : ?>
    <div class="content-tabs-wrapper" id="<?php echo esc_attr( $tab_group_id ); ?>">
        <div class="content-tabs-nav">
            <?php foreach ( $tabs as $index => $tab ) : ?>
                <button class="content-tab-link<?php echo $index === 0 ? ' active' : ''; ?>" 
                        data-tab="<?php echo esc_attr( $tab_group_id . '-' . $index ); ?>">
                    <?php echo esc_html( $tab['title'] ?: 'Tab ' . ($index + 1) ); ?>
                </button>
            <?php endforeach; ?>
        </div>
        
        <div class="content-tabs-content">
            <?php 
            // We need to re-run the loop for the nested flexible content to use the_row() properly
            if ( have_rows( 'content_tab' ) ) :
                $index = 0;
                while ( have_rows( 'content_tab' ) ) : the_row();
                ?>
                <div class="content-tab-pane<?php echo $index === 0 ? ' active' : ''; ?>" 
                     id="<?php echo esc_attr( $tab_group_id . '-' . $index ); ?>"
                     <?php echo $index !== 0 ? 'style="display:none;"' : ''; ?>>
                    
                    <?php 
                    if ( have_rows( 'content_row' ) ) :
                        while ( have_rows( 'content_row' ) ) : the_row();
                            // content_row layout in Content Tab
                            $layout = get_row_layout();
                            
                            if ( $layout === 'content_row' ) :
                                $col_width = get_sub_field('numbers_of_columns') ?: 12;
                                ?>
                                <div class="row">
                                    <?php
                                    if ( have_rows('columns') ) :
                                        while ( have_rows('columns') ) : the_row();
                                            echo '<div class="col-12 col-lg-' . intval($col_width) . '">';
                                                if ( function_exists( 'muc3_content_selector' ) ) {
                                                    muc3_content_selector();
                                                }
                                            echo '</div>';
                                        endwhile;
                                    endif;
                                    ?>
                                </div>
                                <?php
                            else :
                                // Fallback for other potential layouts inside the tab's flexible content
                                if ( function_exists( 'muc3_include_acf_part' ) ) {
                                    muc3_include_acf_part( $layout );
                                }
                            endif;
                        endwhile;
                    endif;
                    ?>
                </div>
                <?php 
                $index++;
                endwhile;
            endif;
            ?>
        </div>

        <script>
            (function() {
                const wrapper = document.getElementById('<?php echo $tab_group_id; ?>');
                if (!wrapper) return;
                
                const links = wrapper.querySelectorAll('.content-tab-link');
                const panes = wrapper.querySelectorAll('.content-tab-pane');
                
                links.forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const targetId = this.getAttribute('data-tab');
                        
                        links.forEach(l => l.classList.remove('active'));
                        panes.forEach(p => {
                            p.classList.remove('active');
                            p.style.display = 'none';
                        });
                        
                        this.classList.add('active');
                        const activePane = document.getElementById(targetId);
                        if (activePane) {
                            activePane.classList.add('active');
                            activePane.style.display = 'block';
                        }
                    });
                });
            })();
        </script>
    </div>
<?php endif; ?>
