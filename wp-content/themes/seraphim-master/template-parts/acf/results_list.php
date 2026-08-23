<?php
/**
 * Results List ACF Component
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/*TODO
 * fix result title font
 * add length to video tags
 * fix month capitalisation
 * fix dropdown style
 */

$selected_type = get_sub_field( 'result_type' );

$args = array(
    'post_type'      => 'result',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
);

if ( $selected_type && $selected_type !== 'all' ) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'result-type',
            'field'    => 'slug',
            'terms'    => $selected_type,
        ),
    );
}

$query = new WP_Query( $args );

if ( $query->have_posts() ) :
    // Group by Financial Year
    $grouped_results = array();
    while ( $query->have_posts() ) {
        $query->the_post();
        
        // Determine Financial Year
        // Logic: If month is April (4) or later, FY is current_year / next_year
        // If month is March (3) or earlier, FY is previous_year / current_year
        $post_date = get_the_date('Y-m-d');
        $year = (int)get_the_date('Y');
        $month = (int)get_the_date('n');
        
        if ($month >= 4) {
            $fy = $year . '/' . substr($year + 1, 2);
        } else {
            $fy = ($year - 1) . '/' . substr($year, 2);
        }
        
        $grouped_results[$fy][] = array(
            'id' => get_the_ID(),
            'title' => get_the_title(),
            'date' => get_the_date('d M Y'),
            'timestamp' => get_the_time('U'),
            'type' => '',
            'format' => '',
            'url' => '',
            'is_video' => false,
            'video_duration' => '',
            'file_size' => '',
        );
        
        // Get Term (Result Type)
        $terms = get_the_terms(get_the_ID(), 'result-type');
        if (!is_wp_error($terms) && !empty($terms)) {
            $grouped_results[$fy][count($grouped_results[$fy])-1]['type'] = $terms[0]->name;
        }
        
        // Get Format & URL
        $media_location = get_field( 'media_location' );
        $media_link = get_field( 'media_link' );
        $document = get_field( 'document' ) ?: get_field( 'result_file' );
        $video_duration = get_field( 'video_duration' );

        $document_url = '';
        if ( $media_location === '0' || $media_location === 0 ) {
            $document_url = $media_link;
        } else {
            $document_url = is_array( $document ) ? $document['url'] : ( is_numeric( $document ) ? wp_get_attachment_url( $document ) : $document );
        }

        $file_extension = strtoupper( pathinfo( $document_url, PATHINFO_EXTENSION ) );
        $video_extensions = array( 'MP4', 'WEBM', 'OGV', 'MOV', 'AVI', 'WMV' );
        $is_video = in_array( $file_extension, $video_extensions ) || ( ( $media_location === '0' || $media_location === 0 ) && strpos( $document_url, 'vimeo.com' ) !== false ) || ( ( $media_location === '0' || $media_location === 0 ) && strpos( $document_url, 'youtube.com' ) !== false ) || ( ( $media_location === '0' || $media_location === 0 ) && strpos( $document_url, 'youtu.be' ) !== false );

        $grouped_results[$fy][count($grouped_results[$fy])-1]['url'] = $document_url;
        
        if ( $is_video ) {
            $grouped_results[$fy][count($grouped_results[$fy])-1]['format'] = 'VIDEO';
            $grouped_results[$fy][count($grouped_results[$fy])-1]['is_video'] = true;
            $grouped_results[$fy][count($grouped_results[$fy])-1]['video_duration'] = $video_duration;
        } else {
            $grouped_results[$fy][count($grouped_results[$fy])-1]['format'] = $file_extension ?: 'PDF';
            $grouped_results[$fy][count($grouped_results[$fy])-1]['is_video'] = false;
            
            // Try to get file size if it's an array from ACF
            if (is_array($document) && isset($document['filesize'])) {
                $grouped_results[$fy][count($grouped_results[$fy])-1]['file_size'] = size_format($document['filesize']);
            }
        }
    }
    wp_reset_postdata();
    ?>

    <style>
        body {
            background-color: #030a0d;
            color: #fff;
            padding-top: 50px;
            padding-bottom: 100px;
        }

        /* Replicating the specific design from the image */
        .results-filter {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 20px 30px;
            margin-bottom: 50px;
            display: flex;
            gap: 40px;
            align-items: center;
        }

        .results-filter__item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .results-filter__select {
            background: transparent;
            border: none;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            appearance: none;
            padding-right: 20px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'%3E%3Cpath d='M1 1l4 4 4-4' fill='none' stroke='white' stroke-width='1'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right center;
        }

        .results-filter__select:focus {
            outline: none;
        }

        .results-year-title {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 25px;
            margin-top: 50px;
            border: none;
            padding: 0;
        }

        .result-item {
            background: rgba(255, 255, 255, 0.02);
            border: none;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 0;
            padding: 25px 0;
            min-height: auto;
            backdrop-filter: none;
            display: flex;
            align-items: center;
        }

        .result-item:last-child {
            border-bottom: none;
        }

        .result-item__title {
            flex: 1;
            padding-right: 20px;
        }

        .result-item__title h3 {
            font-size: 18px;
            font-weight: 400;
            color: #fff;
            margin: 0;
        }

        .result-item__meta {
            display: flex;
            align-items: center;
            flex-shrink: 0;
            width: 70%; /* Total width for meta columns */
            gap: 0;
        }

        @media (max-width: 991.98px) {
            .result-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 20px;
            }
            .result-item__meta {
                width: 100%;
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            .result-item__type, .result-item__date, .result-item__filetype, .result-item__download-col {
                width: 100%;
                justify-content: flex-start;
            }
            .result-item__title {
                padding-right: 0;
            }
        }

        .result-item__type {
            width: 20%;
            color: rgba(255, 255, 255, 0.5);
            font-size: 13px;
            font-weight: 400;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .result-item__type::before {
            content: '[';
        }
        .result-item__type::after {
            content: ']';
        }

        .result-item__date {
            width: 25%;
            color: rgba(255, 255, 255, 0.5);
            font-size: 14px;
        }

        .result-item__filetype {
            width: 25%;
            background: transparent;
            color: rgba(255, 255, 255, 0.5);
            font-size: 13px;
            padding: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .result-item__filetype::before {
            content: '[';
        }
        .result-item__filetype::after {
            content: ']';
        }

        .result-item__download-col {
            width: 30%;
            display: flex;
            justify-content: flex-end;
        }

        .result-item__download {
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 8px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 400;
            transition: all 0.3s ease;
            text-decoration: none;
            color: #fff;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .result-item__download:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.4);
            color: #fff;
        }

        /* Video specific meta */
        .result-item--video .result-item__filetype span {
            margin-left: 5px;
        }

        .page-bg-gradient {
            position: fixed;
            top: 0;
            right: 0;
            width: 50%;
            height: 50%;
            background: radial-gradient(circle at top right, rgba(0, 255, 128, 0.08), transparent 70%);
            pointer-events: none;
            z-index: -1;
        }
    </style>

    <div class="container">
        <div class="results-filter">
            <div class="results-filter__item">
                <select id="filter-year" class="results-filter__select">
                    <option value="all">Financial year</option>
                    <?php foreach (array_keys($grouped_results) as $fy_option) : ?>
                        <option value="<?php echo esc_attr($fy_option); ?>">FY <?php echo esc_html($fy_option); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="width: 1px; height: 20px; background: rgba(255,255,255,0.2);"></div>
            <div class="results-filter__item">
                <select id="filter-format" class="results-filter__select">
                    <option value="all">Format</option>
                    <option value="PDF">PDF</option>
                    <option value="VIDEO">Video</option>
                </select>
            </div>
        </div>

        <div class="results-list-container">
            <?php foreach ($grouped_results as $fy => $results) : ?>
                <div class="results-year-group" data-year="<?php echo esc_attr($fy); ?>">
                    <h2 class="results-year-title">FY <?php echo esc_html($fy); ?></h2>
                    <div class="results-list">
                        <?php foreach ($results as $result) : ?>
                            <div class="result-item <?php echo $result['is_video'] ? 'result-item--video' : ''; ?>" data-format="<?php echo esc_attr($result['format']); ?>">
                                <div class="result-item__title">
                                    <h3><?php echo esc_html($result['title']); ?></h3>
                                </div>
                                
                                <div class="result-item__meta">
                                    <div class="result-item__type"><?php echo esc_html($result['type']); ?></div>
                                    <div class="result-item__date">Updated <?php echo esc_html(strtolower($result['date'])); ?></div>
                                    <div class="result-item__filetype"><?php echo esc_html($result['format']); ?><?php if ($result['is_video'] && $result['video_duration']) : ?> <span>&middot; <?php echo esc_html($result['video_duration']); ?></span><?php elseif (!$result['is_video'] && $result['file_size']) : ?> <span>&middot; <?php echo esc_html($result['file_size']); ?></span><?php endif; ?></div>
                                    <div class="result-item__download-col">
                                        <a href="<?php echo esc_url($result['url']); ?>" class="result-item__download" <?php echo $result['is_video'] ? 'target="_blank"' : 'download'; ?>>
                                            <?php if ($result['is_video']) : ?>
                                                Watch video <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polygon points="10 8 16 12 10 16 10 8"></polygon></svg>
                                            <?php else: ?>
                                                Open document <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                            <?php endif; ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        function filterResults() {
            var year = $('#filter-year').val();
            var format = $('#filter-format').val();

            $('.results-year-group').each(function() {
                var $group = $(this);
                var groupYear = $group.data('year');
                var groupVisible = false;

                $group.find('.result-item').each(function() {
                    var $item = $(this);
                    var itemFormat = $item.data('format');
                    var showItem = true;

                    if (year !== 'all' && groupYear !== year) {
                        showItem = false;
                    }
                    if (format !== 'all' && itemFormat !== format) {
                        showItem = false;
                    }

                    if (showItem) {
                        $item.show();
                        groupVisible = true;
                    } else {
                        $item.hide();
                    }
                });

                if (groupVisible) {
                    $group.show();
                } else {
                    $group.hide();
                }
            });
        }

        $('#filter-year, #filter-format').on('change', filterResults);
    });
    </script>
<?php endif; ?>