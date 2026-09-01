<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$headline = get_sub_field('headline');
$globe_image = get_sub_field('globe_image');

if (have_rows('industry')) :
    $industries = [];
    while (have_rows('industry')) : the_row();
        $industries[] = [
            'name'  => get_sub_field('industry_name'),
            'title' => get_sub_field('industry_title'),
            'text'  => get_sub_field('industry_text'),
        ];
    endwhile;

    $total_count = count($industries);
    
    // Split industries into two rings. 
    // The mock had 5 in outer and 4 in inner. 
    // We can distribute them roughly half-half or based on a fixed logic.
    // Let's try to keep the outer ring slightly larger if odd.
    $outer_ring_count = ceil($total_count / 2);
    if ($total_count > 5) {
        $outer_ring_count = 5; // Match mock's distribution if possible
    }
    
    $outer_ring = array_slice($industries, 0, $outer_ring_count);
    $inner_ring = array_slice($industries, $outer_ring_count);
    
    $outer_total = count($outer_ring);
    $inner_total = count($inner_ring);
?>

<section class="industry-globe-section">
    <?php if ($headline) : ?>
        <h2 class="section-title"><?php echo $headline; ?></h2>
    <?php else : ?>
        <h2 class="section-title">Every Industry Is<br>Looking to the Sky</h2>
    <?php endif; ?>

    <div class="industry-globe-container">
        <?php if ($globe_image) : ?>
            <img src="<?php echo esc_url($globe_image['url']); ?>" alt="<?php echo esc_attr($globe_image['alt']); ?>" class="globe-background-image">
        <?php endif; ?>
        <div class="labels-container">
            <!-- Inner Ring -->
            <?php foreach ($inner_ring as $i => $industry) : ?>
                <div class="industry-label inner-ring" style="--i: <?php echo $i; ?>; --total: <?php echo $inner_total; ?>;" onclick="toggleLabel(this)">
                    <div class="label-pill"><?php echo esc_html($industry['name']); ?></div>
                    <div class="label-dropdown">
                        <span class="dropdown-title"><?php echo esc_html($industry['title']); ?></span>
                        <div class="dropdown-content"><?php echo wp_kses_post($industry['text']); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Outer Ring -->
            <?php foreach ($outer_ring as $i => $industry) : ?>
                <div class="industry-label" style="--i: <?php echo $i; ?>; --total: <?php echo $outer_total; ?>;" onclick="toggleLabel(this)">
                    <div class="label-pill"><?php echo esc_html($industry['name']); ?></div>
                    <div class="label-dropdown">
                        <span class="dropdown-title"><?php echo esc_html($industry['title']); ?></span>
                        <div class="dropdown-content"><?php echo wp_kses_post($industry['text']); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>


        </div>
    </div>
</section>

<style>
    .industry-globe-section {
        width: 100%;
        max-width: 1440px;
        padding: 20px 20px;
        text-align: center;
        position: relative;
        margin: 0 auto;
        --bg-color: #020714;
        --text-color: #FFFFFF;
        --text-muted: #A6A7AB;
        --border-color: rgba(255, 255, 255, 0.2);
        --accent-color: #00AA50;
        --font-main: "Neuro", sans-serif;
        --font-heading: "Osiris", sans-serif;
    }

    .industry-globe-section .section-title {
        font-family: var(--font-heading);
        font-size: 3rem;
        text-transform: uppercase;
        margin-bottom: 60px;
        letter-spacing: 2px;
        line-height: 1.1;
        color: var(--text-color);
    }

    .industry-globe-section .industry-globe-container {
        position: relative;
        height: 600px;
        margin: 0 auto;
        max-width: 1400px;
        display: flex;
        justify-content: center;
        align-items: flex-end;
        overflow: hidden;
    }

    .industry-globe-section .globe-background-image {
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100%;
        height: auto;
        z-index: 1;
        pointer-events: none;
        opacity: 0.8;
    }

    .industry-globe-section .labels-container {
        position: absolute;
        width: 100%;
        height: 100%;
        bottom: 0;
        left: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        pointer-events: none;
        z-index: 5;
    }

    .industry-globe-section .industry-label {
        position: absolute;
        display: inline-block;
        cursor: pointer;
        z-index: 10;
        pointer-events: auto;
        --radius: 70vw;
        --angle: calc(-110deg + (40deg * var(--i) / (max(var(--total) - 1, 1))));
        transform: rotate(var(--angle)) translate(var(--radius)) rotate(calc(-1 * var(--angle))) translateY(calc(var(--radius) - 200px));
    }

    .industry-globe-section .industry-label.inner-ring {
        --radius: 70vw;
        --angle: calc(-112deg + (45deg * var(--i) / (max(var(--total) - 1, 1))));
        transform: rotate(var(--angle)) translate(var(--radius)) rotate(calc(-1 * var(--angle))) translateY(calc(var(--radius) - 100px) );
    }

    .industry-globe-section .label-pill {
        background: rgba(2, 7, 20, 0.8);
        backdrop-filter: blur(4px);
        border: 1px solid var(--border-color);
        padding: 8px 16px;
        border-radius: 4px;
        font-size: 14px;
        color: var(--text-color);
        transition: all 0.3s ease;
        white-space: nowrap;
        transform: translate(-50%, -50%);
    }

    .industry-globe-section .industry-label:hover .label-pill,
    .industry-globe-section .industry-label.active .label-pill {
        border-color: var(--accent-color);
        background: rgba(0, 170, 80, 0.2);
    }

    .industry-globe-section .industry-label .label-dropdown {
        position: absolute;
        top: 25px;
        left: 50%;
        width: 240px;
        background: rgba(14, 20, 32, 0.95);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 20px;
        text-align: left;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        z-index: 20;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        transform: translateX(-50%) translateY(10px);
    }

    .industry-globe-section .industry-label.active .label-dropdown {
        opacity: 1;
        visibility: visible;
        transform: translateX(-50%) translateY(0);
    }

    .industry-globe-section .industry-label.active::after {
        content: '';
        position: absolute;
        top: 10px;
        left: 50%;
        width: 1px;
        height: 15px;
        background: var(--accent-color);
        border-left: 1px dashed var(--accent-color);
        transform: translateX(-50%);
    }

    .industry-globe-section .dropdown-title {
        font-family: var(--font-heading);
        font-size: 14px;
        text-transform: uppercase;
        margin-bottom: 10px;
        display: block;
        letter-spacing: 1px;
        color: var(--text-color);
    }

    .industry-globe-section .dropdown-content {
        font-size: 13px;
        color: var(--text-muted);
        line-height: 1.5;
    }
    
    @media (max-width: 768px) {
        .industry-globe-section .industry-label {
            --radius: 100vw;
        }
        .industry-globe-section .industry-label.inner-ring {
            --radius: 100vw;
        }
    }
</style>

<script>
    if (typeof toggleLabel !== 'function') {
        function toggleLabel(element) {
            const activeLabels = document.querySelectorAll('.industry-label.active');
            activeLabels.forEach(label => {
                if (label !== element) {
                    label.classList.remove('active');
                }
            });
            element.classList.toggle('active');
        }

        document.addEventListener('click', function(event) {
            if (!event.target.closest('.industry-label')) {
                const activeLabels = document.querySelectorAll('.industry-label.active');
                activeLabels.forEach(label => {
                    label.classList.remove('active');
                });
            }
        });
    }
</script>

<?php endif; ?>
