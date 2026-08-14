<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Get ACF sub-fields
$tagline = get_sub_field('tagline');
$headline = get_sub_field('headline');
$graph_data_raw = get_sub_field('graph_data');
$background_colour = get_sub_field('background_colour');
$line_label = get_sub_field('line_label') ?: 'Value';

if (!$graph_data_raw) {
    return;
}

// Parse graph data
// Format: "YYYY-MM-DD:Value" or "Label:Value"
$lines = explode("\n", str_replace("\r", "", trim($graph_data_raw)));
$all_data = [];

foreach ($lines as $line) {
    if (strpos($line, ':') !== false) {
        list($label, $val) = explode(':', $line, 2);
        $label = trim($label);
        $val = (float) trim($val);
        $timestamp = strtotime($label);
        
        $all_data[] = [
            'label' => $label,
            'value' => $val,
            'timestamp' => $timestamp ? $timestamp * 1000 : null // JS works with milliseconds
        ];
    }
}

if (empty($all_data)) {
    return;
}

$chart_id = 'line-chart-' . uniqid();
?>

<div class="line-graph-section<?php if ($background_colour) echo ' bg' . strtolower($background_colour); ?>" style="padding: 60px 0;">
    <div class="container">
        <div class="line-graph-wrapper" style="border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 20px; padding: 40px; background: rgba(2, 7, 20, 0.5);">
            <div class="d-flex flex-wrap justify-content-between align-items-end mb-4">
                <?php if ($tagline || $headline) : ?>
                    <div class="graph-header">
                        <?php if ($tagline) : ?>
                            <h6 class="tagline" style="color: #70D0D9; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px;"><?= $tagline; ?></h6>
                        <?php endif; ?>
                        <?php if ($headline) : ?>
                            <h3 class="headline" style="font-family: Osiris, sans-serif; color: #FFFFFF; margin-bottom: 0;"><?= $headline; ?></h3>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="graph-filters d-flex gap-2 mt-3 mt-md-0" id="filters-<?= $chart_id; ?>">
                    <button class="filter-btn active" data-range="all" style="background: transparent; border: 1px solid #70D0D9; color: #70D0D9; padding: 5px 15px; border-radius: 20px; font-family: 'F37 Neuro', sans-serif; font-size: 14px; cursor: pointer; transition: all 0.3s;">All Time</button>
                    <button class="filter-btn" data-range="3y" style="background: transparent; border: 1px solid rgba(112, 208, 217, 0.3); color: rgba(255, 255, 255, 0.7); padding: 5px 15px; border-radius: 20px; font-family: 'F37 Neuro', sans-serif; font-size: 14px; cursor: pointer; transition: all 0.3s;">3 Years</button>
                    <button class="filter-btn" data-range="1y" style="background: transparent; border: 1px solid rgba(112, 208, 217, 0.3); color: rgba(255, 255, 255, 0.7); padding: 5px 15px; border-radius: 20px; font-family: 'F37 Neuro', sans-serif; font-size: 14px; cursor: pointer; transition: all 0.3s;">1 Year</button>
                    <button class="filter-btn" data-range="6m" style="background: transparent; border: 1px solid rgba(112, 208, 217, 0.3); color: rgba(255, 255, 255, 0.7); padding: 5px 15px; border-radius: 20px; font-family: 'F37 Neuro', sans-serif; font-size: 14px; cursor: pointer; transition: all 0.3s;">6 Months</button>
                    <button class="filter-btn" data-range="30d" style="background: transparent; border: 1px solid rgba(112, 208, 217, 0.3); color: rgba(255, 255, 255, 0.7); padding: 5px 15px; border-radius: 20px; font-family: 'F37 Neuro', sans-serif; font-size: 14px; cursor: pointer; transition: all 0.3s;">30 Days</button>
                </div>
            </div>

            <div class="line-graph-container" style="position: relative; height:400px; width:100%;">
                <canvas id="<?= $chart_id; ?>"></canvas>
            </div>
        </div>
    </div>
</div>

<style>
    .filter-btn.active {
        background: #70D0D9 !important;
        color: #020714 !important;
        border-color: #70D0D9 !important;
    }
    .filter-btn:hover:not(.active) {
        border-color: #70D0D9 !important;
        color: #70D0D9 !important;
    }
    .gap-2 { gap: 0.5rem; }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rawData = <?= json_encode($all_data); ?>;
        const chartId = '<?= $chart_id; ?>';
        const ctx = document.getElementById(chartId).getContext('2d');
        
        let currentChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: rawData.map(d => d.label),
                datasets: [{
                    label: <?= json_encode($line_label); ?>,
                    data: rawData.map(d => d.value),
                    borderColor: '#70D0D9',
                    backgroundColor: 'rgba(112, 208, 217, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#70D0D9',
                    pointBorderColor: '#020714',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(2, 7, 20, 0.9)',
                        titleColor: '#70D0D9',
                        bodyColor: '#FFFFFF',
                        borderColor: 'rgba(255, 255, 255, 0.2)',
                        borderWidth: 1,
                        padding: 10,
                        displayColors: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)',
                            drawBorder: false
                        },
                        ticks: {
                            color: 'rgba(255, 255, 255, 0.7)',
                            maxTicksLimit: 6,
                            font: {
                                family: "'F37 Neuro', sans-serif"
                            }
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)',
                            drawBorder: false
                        },
                        ticks: {
                            color: 'rgba(255, 255, 255, 0.7)',
                            font: {
                                family: "'F37 Neuro', sans-serif"
                            }
                        }
                    }
                }
            }
        });

        const filterButtons = document.querySelectorAll(`#filters-${chartId} .filter-btn`);
        filterButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                // Update active state
                filterButtons.forEach(b => {
                    b.classList.remove('active');
                    b.style.borderColor = 'rgba(112, 208, 217, 0.3)';
                    b.style.color = 'rgba(255, 255, 255, 0.7)';
                    b.style.background = 'transparent';
                });
                this.classList.add('active');
                this.style.borderColor = '#70D0D9';
                this.style.color = '#020714';
                this.style.background = '#70D0D9';

                const range = this.getAttribute('data-range');
                let filteredData = rawData;

                if (range !== 'all') {
                    const now = new Date();
                    let cutoff = new Date();

                    if (range === '30d') cutoff.setDate(now.getDate() - 30);
                    else if (range === '6m') cutoff.setMonth(now.getMonth() - 6);
                    else if (range === '1y') cutoff.setFullYear(now.getFullYear() - 1);
                    else if (range === '3y') cutoff.setFullYear(now.getFullYear() - 3);

                    const cutoffTime = cutoff.getTime();
                    filteredData = rawData.filter(d => d.timestamp && d.timestamp >= cutoffTime);
                }

                currentChart.data.labels = filteredData.map(d => d.label);
                currentChart.data.datasets[0].data = filteredData.map(d => d.value);
                currentChart.update();
            });
        });
    });
</script>

