<div class="thirdPartyResearchCon">
    <div class="thirdPartyResearchHeader">
        <h2><?php echo esc_html( $title ); ?></h2>
        <?php if ( $all_insights_link ) : ?>
            <a href="<?php echo esc_url( $all_insights_link['url'] ); ?>" target="<?php echo esc_attr( $all_insights_link['target'] ?: '_self' ); ?>">
                View All <?php echo esc_html( $all_insights_link['title'] ); ?>
            </a>
        <?php endif; ?>
    </div>

    <?php if ( $query->have_posts() ) : ?>
        <div class="InsightsShortList insightsShortlist">
            <?php
            $count = 0;
            while ( $query->have_posts() ) : $query->the_post();
                $count++;
                $insight_types = get_the_terms( get_the_ID(), 'insight-type' );
                $insight_tag = '';
                if ( ! is_wp_error( $insight_types ) && ! empty( $insight_types ) ) {
                    $insight_tag = $insight_types[0]->name;
                }

                $thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'large' ) ?: 'src/img/card-background-ssit-v1.jpg';

                // Get video runtime if available
                $runtime = '';
                $insight_file = get_field('insight_file');
                if ($insight_file && isset($insight_file['mime_type']) && str_contains($insight_file['mime_type'], 'video')) {
//                  $runtime = get_field('duration') ?: get_field('video_runtime') ?: ($insight_file['duration'] ?? '');
                    $runtime = wp_read_video_metadata($insight_file['url']);
                }
                debug_to_console($runtime);
                ?>



                <a href="<?php the_permalink(); ?>" class="thirdPartyResearchItem<?php echo $count === 1 ? ' featured' : ''; ?>">
                    <div class="thirdPartyResearchItem__image" style="background-image: url('<?php echo esc_url( $thumbnail_url ); ?>');"></div>
                    <div class="thirdPartyResearchItem__content">
                        <div class="thirdPartyResearchItem__meta">
                            <?php if ( $insight_tag ) : ?>
                                <span class="caption"><?php echo esc_html( $insight_tag ); ?></span>
                            <?php endif; ?>
                            <p><?php echo esc_html( $runtime ); ?></p>
                            <?php if ( $runtime ) : ?>
                                <span class="caption runtime"><?php echo esc_html( $runtime ); ?></span>
                            <?php endif; ?>
                            <span class="caption"><?php echo get_the_date( 'd M Y' ); ?></span>
                        </div>
                        <h4><?php the_title(); ?></h4>
                    </div>
                </a>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    <?php endif; ?>
</div>






// Generate a unique ID for the chart
$chart_id = 'line-chart-' . uniqid();
?>

<div class="line-graph-section<?php if ($background_colour) echo ' bg' . strtolower($background_colour); ?>" style="padding: 60px 0;">
    <div class="container">
        <?php if ($tagline || $headline) : ?>
            <div class="row mb-4">
                <div class="col-12 text-center">
                    <?php if ($tagline) : ?><h2 class="title prefix h5"><?= $tagline; ?></h2><?php endif; ?>
                    <?php if ($headline) : ?><h1 class="h2"><?= $headline; ?></h1><?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="line-graph-wrapper" style="border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 20px; padding: 40px; overflow: hidden; background: rgba(2, 7, 20, 0.5);">

            <div class="timescale-buttons mb-4 d-flex flex-wrap justify-content-center" style="gap: 10px;">
                <button class="btn btn-outline-light timescale-btn" data-days="30">30 Days</button>
                <button class="btn btn-outline-light timescale-btn" data-days="182">6 Months</button>
                <button class="btn btn-outline-light timescale-btn" data-days="365">1 Year</button>
                <button class="btn btn-outline-light timescale-btn" data-days="1095">3 Years</button>
                <button class="btn btn-outline-light timescale-btn active" data-days="all">All Time</button>
            </div>

            <div class="line-graph-container" style="position: relative; height:400px; width:100%;">
                <canvas id="<?= $chart_id; ?>"></canvas>
            </div>
        </div>
    </div>
</div>

<style>
    .timescale-btn {
        border-radius: 30px;
        padding: 5px 20px;
        font-family: 'F37 Neuro', sans-serif;
        font-size: 14px;
        transition: all 0.3s ease;
        background: transparent;
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    .timescale-btn:hover, .timescale-btn.active {
        background: #70D0D9;
        color: #020714;
        border-color: #70D0D9;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('<?= $chart_id; ?>').getContext('2d');
        const rawData = <?= json_encode($raw_data); ?>;

        let chart;

        function updateChart(days) {
            let filteredData = rawData;
            if (days !== 'all') {
                const cutoff = Date.now() - (days * 24 * 60 * 60 * 1000);
                filteredData = rawData.filter(d => d.t >= cutoff);
            }

            if (chart) {
                chart.data.datasets[0].data = filteredData;
                chart.update();
                return;
            }

            chart = new Chart(ctx, {
                type: 'line',
                data: {
                    datasets: [{
                        label: '<?= $line_label; ?>',
                        data: filteredData,
                        borderColor: '#70D0D9',
                        backgroundColor: 'rgba(112, 208, 217, 0.1)',
                        borderWidth: 3,
                        pointRadius: 4,
                        pointBackgroundColor: '#70D0D9',
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.parsed.y;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                tooltipFormat: 'PP'
                            },
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#FFFFFF',
                                maxTicksLimit: 6, // Ensures around 6 labels
                                autoSkip: false, // Changed to false to force closer to 6 if possible
                                source: 'auto'
                            }
                        },
                        y: {
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#FFFFFF'
                            }
                        }
                    }
                }
            });
        }

        // Initial load
        updateChart('all');

        // Button clicks
        document.querySelectorAll('.timescale-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.timescale-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                updateChart(this.dataset.days);
            });
        });
    });
</script>


