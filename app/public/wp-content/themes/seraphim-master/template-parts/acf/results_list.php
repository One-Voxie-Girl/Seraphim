<?php
/**
 * Holdings List ACF Component
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$args = array(
    'post_type'      => 'result',
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'DESC',
);

$query = new WP_Query( $args );

if ( $query->have_posts() ) : 
    $grouped_results = array();
    
    while ( $query->have_posts() ) : $query->the_post();
        $post_date = get_the_date('Y-m-d');
        $date_obj = new DateTime($post_date);
        $year = (int)$date_obj->format('Y');
        $month = (int)$date_obj->format('m');
        
        // Financial Year starts April 1st
        if ($month >= 4) {
            $fy_start = $year;
            $fy_end = $year + 1;
        } else {
            $fy_start = $year - 1;
            $fy_end = $year;
        }
        $fy_label = $fy_start . '/' . substr((string)$fy_end, -2);
        
        if (!isset($grouped_results[$fy_label])) {
            $grouped_results[$fy_label] = array();
        }
        
        $title = get_the_title();
        
        $media_location = get_field( 'media_location' );
        $media_link = get_field( 'media_link' );
        $result_file = get_field( 'result_file' ) ?: get_field( 'document' );
        
        $result_type_terms = get_the_terms( get_the_ID(), 'result-type' );
        $result_type = ! empty( $result_type_terms ) && ! is_wp_error( $result_type_terms ) ? $result_type_terms[0]->name : '';
        
        $date_updated = get_the_modified_date( 'd M Y' );
        
        $file_url = '';
        $file_type = '';
        $is_video = false;
        $video_extensions = array( 'MP4', 'WEBM', 'OGV', 'MOV', 'AVI', 'WMV' );
        
        if ( $media_location === '0' || $media_location === 0 ) {
            $file_url = $media_link;
            $file_type = strtoupper( pathinfo( $file_url, PATHINFO_EXTENSION ) );
        } else {
            if ( is_array( $result_file ) ) {
                $file_url = $result_file['url'];
                $file_type = strtoupper( pathinfo( $file_url, PATHINFO_EXTENSION ) );
            } elseif ( is_numeric( $result_file ) ) {
                $file_url = wp_get_attachment_url( $result_file );
                $file_type = strtoupper( pathinfo( $file_url, PATHINFO_EXTENSION ) );
            } elseif ( is_string( $result_file ) ) {
                $file_url = $result_file;
                $file_type = strtoupper( pathinfo( $file_url, PATHINFO_EXTENSION ) );
            }
        }
        
        if ( in_array( $file_type, $video_extensions ) ) {
            $is_video = true;
        }

        $grouped_results[$fy_label][] = array(
            'title' => $title,
            'result_type' => $result_type,
            'date_updated' => $date_updated,
            'file_type' => $file_type,
            'file_url' => $file_url,
            'is_video' => $is_video,
            'media_location' => $media_location,
            'result_type_slug' => ! empty( $result_type_terms ) && ! is_wp_error( $result_type_terms ) ? $result_type_terms[0]->slug : '',
        );
    endwhile; wp_reset_postdata();

    // Determine current financial year
    $now = new DateTime();
    $now_year = (int)$now->format('Y');
    $now_month = (int)$now->format('m');
    if ($now_month >= 4) {
        $current_fy_start = $now_year;
        $current_fy_end = $now_year + 1;
    } else {
        $current_fy_start = $now_year - 1;
        $current_fy_end = $now_year;
    }
    $current_fy_label = $current_fy_start . '/' . substr((string)$current_fy_end, -2);

    // Sort keys descending
    krsort($grouped_results);

    // Collect all formats for the filter
    $all_formats = array();
    foreach ($grouped_results as $items) {
        foreach ($items as $item) {
            if ($item['file_type']) {
                $all_formats[] = $item['file_type'];
            }
        }
    }
    $all_formats = array_unique($all_formats);
    sort($all_formats);
    ?>
    <div class="container">
        <div class="results-tabs">
            <button class="results-tab active" data-type="all">All</button>
            <button class="results-tab" data-type="Factsheets">Factsheets</button>
            <button class="results-tab" data-type="Annual">Annual</button>
            <button class="results-tab" data-type="Interim">Interim</button>
            <button class="results-tab" data-type="Quaterly">Quaterly</button>
            <button class="results-tab" data-type="AGM">AGM</button>
        </div>

        <div class="results-filter">
            <div class="results-filter__item">
                <label for="filter-year">Financial Year</label>
                <select id="filter-year" class="results-filter__select">
                    <option value="all">All Years</option>
                    <?php foreach (array_keys($grouped_results) as $fy) : ?>
                        <option value="<?php echo esc_attr($fy); ?>" <?php echo $fy === $current_fy_label ? 'selected' : ''; ?>><?php echo esc_html($fy); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="results-filter__item">
                <label for="filter-format">Format</label>
                <select id="filter-format" class="results-filter__select">
                    <option value="all">All Formats</option>
                    <?php foreach ($all_formats as $format) : ?>
                        <option value="<?php echo esc_attr($format); ?>"><?php echo esc_html($format); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="results-list-container">
            <?php 
            foreach ($grouped_results as $fy => $items) : 
                $is_current = ($fy === $current_fy_label);
                ?>
                <div class="results-year-group" data-year="<?php echo esc_attr($fy); ?>" <?php echo ! $is_current ? 'style="display:none;"' : ''; ?>>
                    <h2 class="results-year-title">FY <?php echo esc_html($fy); ?></h2>
                    <div class="results-list">
                        <?php foreach ($items as $item) : ?>
                            <div class="result-item" data-format="<?php echo esc_attr($item['file_type']); ?>" data-type="<?php echo esc_attr($item['result_type']); ?>">
                                <div class="result-item__title">
                                    <h3><?php echo esc_html( $item['title'] ); ?></h3>
                                </div>
                                
                                <div class="result-item__meta">
                                    <?php if ( $item['result_type'] ) : ?>
                                        <div class="result-item__type">
                                            <?php echo esc_html( $item['result_type'] ); ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="result-item__date">
                                        <?php echo esc_html( $item['date_updated'] ); ?>
                                    </div>

                                    <?php if ( $item['file_type'] ) : ?>
                                        <div class="result-item__filetype">
                                            <?php echo esc_html( $item['file_type'] ); ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ( $item['file_url'] ) : ?>
                                        <a href="<?php echo esc_url( $item['file_url'] ); ?>" class="result-item__download <?php echo $item['is_video'] ? 'video-popup' : ''; ?>" <?php echo ! $item['is_video'] ? 'target="_blank"' : ''; ?> <?php echo ( ! $item['is_video'] && ( $item['media_location'] !== '0' && $item['media_location'] !== 0 ) ) ? 'download' : ''; ?>>
                                            <?php if ( $item['is_video'] ) : ?>
                                                Watch video <i class="ci-Play_Fill"></i>
                                            <?php else : ?>
                                                Open document <i class="ci-Download"></i>
                                            <?php endif; ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (count($grouped_results) > 1 || (count($grouped_results) == 1 && !isset($grouped_results[$current_fy_label]))) : ?>
                <div class="results-list-load-more text-center mt-5">
                    <button id="load-more-results" class="button button--secondary">Load Previous Year</button>
                </div>
            <?php endif; ?>
            
            <script>
                (function() {
                    const yearFilter = document.getElementById('filter-year');
                    const formatFilter = document.getElementById('filter-format');
                    const tabs = document.querySelectorAll('.results-tab');
                    const loadMoreBtn = document.getElementById('load-more-results');
                    const yearGroups = document.querySelectorAll('.results-year-group');
                    const resultItems = document.querySelectorAll('.result-item');
                    const loadMoreContainer = document.querySelector('.results-list-load-more');

                    function filterResults() {
                        const selectedYear = yearFilter.value;
                        const selectedFormat = formatFilter.value;
                        const activeTab = document.querySelector('.results-tab.active');
                        const selectedType = activeTab ? activeTab.getAttribute('data-type') : 'all';

                        // Hide load more when filtering
                        if (selectedYear !== 'all' || selectedFormat !== 'all' || selectedType !== 'all') {
                            if (loadMoreContainer) loadMoreContainer.style.display = 'none';
                        } else {
                            // If reset to "all", show load more if we haven't clicked it yet
                            if (loadMoreContainer && !loadMoreBtn.hasAttribute('data-clicked')) {
                                loadMoreContainer.style.display = '';
                            }
                        }

                        yearGroups.forEach(group => {
                            const groupYear = group.getAttribute('data-year');
                            const items = group.querySelectorAll('.result-item');
                            let hasVisibleItems = false;

                            items.forEach(item => {
                                const itemFormat = item.getAttribute('data-format');
                                const itemType = item.getAttribute('data-type');
                                const matchesYear = (selectedYear === 'all' || selectedYear === groupYear);
                                const matchesFormat = (selectedFormat === 'all' || selectedFormat === itemFormat);
                                const matchesType = (selectedType === 'all' || selectedType.toLowerCase() === (itemType || '').toLowerCase());

                                if (matchesYear && matchesFormat && matchesType) {
                                    item.style.display = '';
                                    hasVisibleItems = true;
                                } else {
                                    item.style.display = 'none';
                                }
                            });

                            if (hasVisibleItems) {
                                group.style.display = '';
                            } else {
                                group.style.display = 'none';
                            }
                        });
                    }

                    if (yearFilter) yearFilter.addEventListener('change', filterResults);
                    if (formatFilter) formatFilter.addEventListener('change', filterResults);
                    
                    tabs.forEach(tab => {
                        tab.addEventListener('click', function() {
                            tabs.forEach(t => t.classList.remove('active'));
                            this.classList.add('active');
                            filterResults();
                        });
                    });

                    if (loadMoreBtn) {
                        loadMoreBtn.addEventListener('click', function() {
                            this.setAttribute('data-clicked', 'true');
                            
                            // Reset filters to "all" when loading more
                            yearFilter.value = 'all';
                            formatFilter.value = 'all';
                            tabs.forEach(t => t.classList.remove('active'));
                            tabs[0].classList.add('active');

                            yearGroups.forEach(group => {
                                group.style.display = '';
                                const items = group.querySelectorAll('.result-item');
                                items.forEach(item => item.style.display = '');
                            });
                            this.closest('.results-list-load-more').style.display = 'none';
                        });
                    }
                })();
            </script>
        </div>
    </div>
<?php endif; ?>
