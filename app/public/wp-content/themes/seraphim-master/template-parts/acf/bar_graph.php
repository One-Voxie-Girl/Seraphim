<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Get ACF sub-fields
$data_type = get_sub_field('data_type'); // "true" for percentage, "false" for absolute
$graph_data_raw = get_sub_field('graph_data');
$background_colour = get_sub_field('background_colour');

if (!$graph_data_raw) {
    return;
}

// Parse graph data
// Format: "Tag:Data" with new data each new line
$lines = explode("\n", str_replace("\r", "", trim($graph_data_raw)));
$labels = [];
$data_values = [];

foreach ($lines as $line) {
    if (strpos($line, ':') !== false) {
        list($tag, $data) = explode(':', $line, 2);
        $labels[] = trim($tag);
        $data_values[] = (float) trim($data);
    }
}

if (empty($data_values)) {
    return;
}

// Generate a unique ID for the chart to allow multiple charts on one page
$chart_id = 'bar-chart-' . uniqid();
?>

<div class="bar-graph-section<?php if ($background_colour) echo ' bg' . strtolower($background_colour); ?>" style="padding: 60px 0;">
    <div class="container">
        <div class="doughnut-graph-wrapper" style="border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 20px; padding: 40px; overflow: hidden;">
            <div class="row align-items-center">
                <div class="col-md-5 order-2 order-md-1">
                    <div id="<?= $chart_id; ?>-legend" class="chart-legend" style="padding-right: 20px;">
                        <?php 
                        $colors = [
                            '#70D0D9', '#318AA0', '#1A5C80', 
                            '#89DEAB', '#00AA50', '#006E4A', 
                            '#A3C2FF', '#464DF9', '#0D2EA6', 
                            '#FFBA4A', '#FF7A1C', '#C74254'
                        ];
                        foreach ($labels as $index => $label) : 
                            $color = $colors[$index % count($colors)];
                        ?>
                            <div class="legend-item d-flex align-items-center mb-2" style="font-family: 'F37 Neuro', sans-serif; color: #FFFFFF; font-size: 16px;">
                                <span class="legend-color" style="display: inline-block; width: 12px; height: 12px; background-color: <?= $color; ?>; border-radius: 2px; margin-right: 10px; flex-shrink: 0;"></span>
                                <span class="legend-text"><?= $label; ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="col-md-7 order-1 order-md-2 mb-4 mb-md-0">
                    <div class="doughnut-graph-container" style="position: relative; height:350px; width:100%; max-width: 500px; margin: auto;">
                        <canvas id="<?= $chart_id; ?>"></canvas>
                        <?php if (get_sub_field('headline')) : ?>
                            <div class="chart-headline" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; pointer-events: none;">
                                <h4 style="margin: 0; font-family: Osiris, sans-serif;"><?= get_sub_field('headline'); ?></h4>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('<?= $chart_id; ?>').getContext('2d');
        const isPercentage = <?= json_encode($data_type === "true" || $data_type === true); ?>;
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($labels); ?>,
                datasets: [{
                    data: <?= json_encode($data_values); ?>,
                    backgroundColor: [
                        '#70D0D9', // $aqua100
                        '#318AA0', // $aqua300
                        '#1A5C80', // $aqua500
                        '#89DEAB', // $terrain100
                        '#00AA50', // $terrain300
                        '#006E4A', // $terrain500
                        '#A3C2FF', // $space100
                        '#464DF9', // $space300
                        '#0D2EA6', // $space500
                        '#FFBA4A', // $solar100
                        '#FF7A1C', // $solar300
                        '#C74254'  // $solar500
                    ],
                    borderColor: '#020714', // $black
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false // Use custom HTML legend on the left
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    label += context.parsed + (isPercentage ? '%' : '');
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
