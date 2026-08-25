<?php
/**
 * Insights Tabs Layout
 * Similar to content_tabs but with filter-style navigation.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$tab_group_id = 'insights-tabs-' . uniqid();
$tabs = [];

// We assume index 0 is the "Home" tab (no button)
// Additional tabs will have buttons.
if ( have_rows( 'insights_tab' ) ) :
    while ( have_rows( 'insights_tab' ) ) : the_row();
        $tabs[] = [
            'title'   => get_sub_field( 'tab_title' ),
            'rows'    => get_field( 'content_row' )
        ];
    endwhile;
endif;

if ( ! empty( $tabs ) ) : ?>
    <div class="insights-tabs-wrapper" id="<?php echo esc_attr( $tab_group_id ); ?>">
        <div class="container">
            <div class="InsightsFilterCon">
                <div class="filter">
                    <span class="filterTitle"><?php the_sub_field('filter_label') ?: _e('Filter:', 'seraphim'); ?></span>
                    <?php 
                    // Skip the first tab as it's the "home" tab
                    foreach ( $tabs as $index => $tab ) : 
                        if ($index === 0) continue;
                    ?>
                        <span class="insights-tab-link" 
                              data-tab="<?php echo esc_attr( $tab_group_id . '-' . $index ); ?>"
                              data-title="<?php echo esc_attr( $tab['title'] ); ?>"
                              style="cursor: pointer;">
                            <?php echo esc_html( $tab['title'] ?: 'Tab ' . ($index + 1) ); ?>
                        </span>
                    <?php endforeach; ?>
                </div>
                <div class="search">
                    <input type="text" class="insights-search-input" placeholder="<?php esc_attr_e('Search', 'seraphim'); ?>" />
                </div>
            </div>
        </div>
        
        <div class="insights-tabs-content">
            <div class="insights-search-results" style="display:none;">
                <div class="container py-5">
                    <div class="row g-4 insights-search-results-grid">
                        <!-- Search results will be injected here -->
                    </div>
                </div>
            </div>
            <div class="insights-tabs-panes">
                <?php 
                if ( have_rows( 'insights_tab' ) ) :
                    $index = 0;
                    while ( have_rows( 'insights_tab' ) ) : the_row();
                    ?>
                    <div class="insights-tab-pane<?php echo $index === 0 ? ' active' : ''; ?>" 
                         id="<?php echo esc_attr( $tab_group_id . '-' . $index ); ?>"
                         <?php echo $index !== 0 ? 'style="display:none;"' : ''; ?>>
                    
                    <?php 
                    if ( have_rows( 'content_row' ) ) :
                        while ( have_rows( 'content_row' ) ) : the_row();
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
                            elseif ( $layout === 'content_tabs' ) :
                                ?>
                                <div class="row">
                                    <div class="col-12">
                                        <?php muc3_include_acf_part('content_tabs'); ?>
                                    </div>
                                </div>
                                <?php
                            elseif ( $layout === 'insights_tabs' ) :
                                ?>
                                <div class="row">
                                    <div class="col-12">
                                        <?php muc3_include_acf_part('insights_tabs'); ?>
                                    </div>
                                </div>
                                <?php
                            else :
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
        </div>

        <script>
            (function() {
                const wrapper = document.getElementById('<?php echo $tab_group_id; ?>');
                if (!wrapper) return;
                
                const links = wrapper.querySelectorAll('.insights-tab-link');
                const panes = wrapper.querySelectorAll('.insights-tab-pane');
                const panesWrapper = wrapper.querySelector('.insights-tabs-panes');
                const searchInput = wrapper.querySelector('.insights-search-input');
                const searchResults = wrapper.querySelector('.insights-search-results');
                const searchResultsGrid = wrapper.querySelector('.insights-search-results-grid');
                const homePaneId = '<?php echo $tab_group_id . "-0"; ?>';
                
                let searchTimeout;

                function showSearchMode(show) {
                    if (show) {
                        panesWrapper.style.display = 'none';
                        searchResults.style.display = 'block';
                        // Deactivate all tab buttons
                        links.forEach(l => l.classList.remove('active'));
                    } else {
                        panesWrapper.style.display = 'block';
                        searchResults.style.display = 'none';
                    }
                }

                if (searchInput) {
                    searchInput.addEventListener('input', function() {
                        clearTimeout(searchTimeout);
                        const query = this.value.trim();
                        
                        if (query.length > 2) {
                            searchTimeout = setTimeout(() => {
                                showSearchMode(true);
                                searchResultsGrid.innerHTML = '<div class="col-12 text-center"><p>Searching...</p></div>';
                                
                                let filter = '';
                                const activeLink = wrapper.querySelector('.insights-tab-link.active');
                                if (activeLink) {
                                    filter = activeLink.getAttribute('data-title') || activeLink.textContent.trim();
                                }
                                
                                fetch('<?php echo admin_url('admin-ajax.php'); ?>?action=search_insights&s=' + encodeURIComponent(query) + '&filter=' + encodeURIComponent(filter))
                                    .then(response => response.text())
                                    .then(html => {
                                        searchResultsGrid.innerHTML = html;
                                    })
                                    .catch(err => {
                                        searchResultsGrid.innerHTML = '<div class="col-12 text-center"><p>Error fetching results.</p></div>';
                                    });
                            }, 300);
                        } else if (query.length === 0) {
                            showSearchMode(false);
                            // Restore active tab
                            const activePane = panesWrapper.querySelector('.insights-tab-pane.active');
                            if (activePane) {
                                const paneId = activePane.id;
                                const activeLink = wrapper.querySelector(`[data-tab="${paneId}"]`);
                                if (activeLink) activeLink.classList.add('active');
                            } else {
                                // Default to home if nothing active
                                const homePane = document.getElementById(homePaneId);
                                if (homePane) {
                                    homePane.classList.add('active');
                                    homePane.style.display = 'block';
                                }
                            }
                        }
                    });
                }

                links.forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        
                        // Clear search
                        if (searchInput) {
                            searchInput.value = '';
                            showSearchMode(false);
                        }

                        const targetId = this.getAttribute('data-tab');
                        const isActive = this.classList.contains('active');
                        
                        // Remove active class from all links
                        links.forEach(l => l.classList.remove('active'));
                        
                        // Hide all panes
                        panes.forEach(p => {
                            p.classList.remove('active');
                            p.style.display = 'none';
                        });
                        
                        if (isActive) {
                            // If clicked the active button, go back to home tab
                            const homePane = document.getElementById(homePaneId);
                            if (homePane) {
                                homePane.classList.add('active');
                                homePane.style.display = 'block';
                            }
                        } else {
                            // If clicked a new button, go to that tab
                            this.classList.add('active');
                            const activePane = document.getElementById(targetId);
                            if (activePane) {
                                activePane.classList.add('active');
                                activePane.style.display = 'block';
                            }
                        }
                    });
                });
            })();
        </script>
    </div>
<?php endif; ?>
