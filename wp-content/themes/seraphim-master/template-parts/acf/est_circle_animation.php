<?php
/**
 * ACF Template Part: Evolution of Space Technology Circle Animation
 * Slug: est_circle_animation
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// ACF Fields (Assuming these exist or will be added to the ACF field group)
$headline = get_sub_field('headline') ?: 'THE EVOLUTION OF SPACE TECHNOLOGY';

// State 1 (1980's)
$state1_year = get_sub_field('state1_year') ?: "1980's";
$state1_top_image = get_sub_field('state1_top_image');
$state1_top_label = get_sub_field('state1_top_label') ?: '[ LAUNCH COST ]';
$state1_top_value = get_sub_field('state1_top_value') ?: '$85,000/KG';
$state1_top_desc = get_sub_field('state1_top_desc') ?: 'Space Shuttle<br>Launch: 1980\'s';

$state1_bottom_image = get_sub_field('state1_bottom_image');
$state1_bottom_label = get_sub_field('state1_bottom_label') ?: '[ SATELLITE SIZE ]';
$state1_bottom_value = get_sub_field('state1_bottom_value') ?: '2,800KG';
$state1_bottom_desc = get_sub_field('state1_bottom_desc') ?: 'Over $1 billion to build<br>and launch';
$state1_bottom_extra = get_sub_field('state1_bottom_extra') ?: '14 M TALL';

// State 2 (2020's)
$state2_year = get_sub_field('state2_year') ?: "2020's";
$state2_top_image = get_sub_field('state2_top_image');
$state2_top_label = get_sub_field('state2_top_label') ?: '[ LAUNCH COST ]';
$state2_top_value = get_sub_field('state2_top_value') ?: '$1,500/KG';
$state2_top_desc = get_sub_field('state2_top_desc') ?: 'SpaceX Falcon Heavy<br>Launch: 2020\'s';

$state2_bottom_image = get_sub_field('state2_bottom_image');
$state2_bottom_label = get_sub_field('state2_bottom_label') ?: '[ SATELLITE SIZE ]';
$state2_bottom_value = get_sub_field('state2_bottom_value') ?: '250KG';
$state2_bottom_desc = get_sub_field('state2_bottom_desc') ?: 'Starlink Satellite<br>Mass production';
$state2_bottom_extra = get_sub_field('state2_bottom_extra') ?: 'VARIES';

// Placeholder images if not provided
$placeholder = 'https://via.placeholder.com/200';
$s1_t_img = $state1_top_image ? $state1_top_image['url'] : $placeholder;
$s1_b_img = $state1_bottom_image ? $state1_bottom_image['url'] : $placeholder;
$s2_t_img = $state2_top_image ? $state2_top_image['url'] : $placeholder;
$s2_b_img = $state2_bottom_image ? $state2_bottom_image['url'] : $placeholder;

?>

<style>
    .est-container {
        position: relative;
        background-color: #020714;
        color: #FFFFFF;
        padding: 80px 0;
        overflow: hidden;
        cursor: pointer;
        user-select: none;
        min-height: 600px;
        display: flex;
        align-items: center;
        font-family: 'Neuro', sans-serif;
    }

    .est-inner {
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        position: relative;
        z-index: 2;
    }

    /* Left Side & Circle Area */
    .est-left-wrap {
        flex: 0 0 60%;
        position: relative;
        height: 600px;
        display: flex;
        align-items: center;
        padding-left: 80px;
    }

    .est-left-content {
        position: relative;
        z-index: 5;
    }

    .est-left-content h2 {
        font-family: 'Osiris', sans-serif;
        font-size: 56px;
        line-height: 1.1;
        text-transform: uppercase;
        margin: 0;
        max-width: 450px;
    }

    .est-circle-wrap {
        position: absolute;
        left: -100px; /* Positioned relative to the left wrap to encircle title */
        top: 50%;
        margin-top: -350px; /* Center vertically (700px height) */
        width: 700px;
        height: 700px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 1s cubic-bezier(0.65, 0, 0.35, 1);
        z-index: 1;
    }

    .est-container.state-2 .est-circle-wrap {
        transform: rotate(-45deg);
    }

    .est-dashed-circle {
        position: absolute;
        width: 110%;
        height: 110%;
        border: 1px dashed rgba(137, 222, 171, 0.3); /* terrain100 */
        border-radius: 50%;
    }

    .est-year-marker {
        position: absolute;
        font-size: 18px;
        color: rgba(255, 255, 255, 0.5);
        transition: color 0.5s, font-weight 0.5s;
    }

    .est-year-marker.year-1 {
        right: 0;
        top: 50%;
        transform: translateY(-50%) translateX(50%);
    }

    .est-year-marker.year-2 {
        right: 15%;
        bottom: 15%;
        transform: rotate(45deg);
    }

    .est-container.state-1 .year-1,
    .est-container.state-2 .year-2 {
        color: #FFFFFF;
        font-weight: bold;
    }

    /* Indicator Line */
    .est-indicator {
        position: absolute;
        right: 0;
        top: 50%;
        width: 150px;
        height: 1px;
        background: #FFFFFF;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        z-index: 3;
    }

    .est-indicator::after {
        content: '';
        width: 6px;
        height: 6px;
        background: #FFFFFF;
        border-radius: 50%;
        margin-right: -3px;
    }

    /* Right Side Content */
    .est-right {
        flex: 0 0 40%;
        display: flex;
        flex-direction: column;
        gap: 60px;
        padding-right: 80px;
        z-index: 5;
    }

    .est-block {
        display: flex;
        align-items: center;
        gap: 30px;
        position: relative;
    }

    .est-img-box {
        width: 180px;
        height: 180px;
        position: relative;
        flex-shrink: 0;
    }

    .est-img-box img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: contain;
        opacity: 0;
        transition: opacity 0.8s ease;
    }

    .est-container.state-1 .img-s1,
    .est-container.state-2 .img-s2 {
        opacity: 1;
    }

    .est-details {
        flex-grow: 1;
        transition: opacity 0.5s ease;
    }

    .est-label {
        font-size: 12px;
        letter-spacing: 2px;
        color: rgba(255, 255, 255, 0.5);
        margin-bottom: 10px;
    }

    .est-value {
        font-family: 'Osiris', sans-serif;
        font-size: 32px;
        margin-bottom: 5px;
    }

    .est-desc {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.7);
        line-height: 1.4;
    }

    /* Bottom specific styling */
    .est-bottom-meta {
        position: absolute;
        left: -80px;
        top: 50%;
        transform: translateY(-50%);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .est-extra-text {
        font-size: 10px;
        white-space: nowrap;
        color: #89DEAB; /* terrain100 */
    }

    .est-bracket {
        height: 100px;
        width: 10px;
        border-left: 1px dashed rgba(255, 255, 255, 0.3);
        border-top: 1px dashed rgba(255, 255, 255, 0.3);
        border-bottom: 1px dashed rgba(255, 255, 255, 0.3);
        position: absolute;
        left: -20px;
        top: 50%;
        transform: translateY(-50%);
    }

    /* Animating the text content */
    .est-text-swap {
        position: relative;
    }
    
    .est-text-swap > div {
        position: absolute;
        top: 0;
        left: 0;
        opacity: 0;
        transition: opacity 0.8s ease, transform 0.8s ease;
        transform: translateY(10px);
        pointer-events: none;
        width: 100%;
    }

    .est-container.state-1 .text-s1,
    .est-container.state-2 .text-s2 {
        opacity: 1;
        transform: translateY(0);
        position: relative;
        pointer-events: auto;
    }

    @media (max-width: 991px) {
        .est-inner {
            flex-direction: column;
            text-align: center;
        }
        .est-left-wrap {
            width: 100%;
            height: 400px;
            padding: 0;
            justify-content: center;
        }
        .est-left-content h2 {
            font-size: 36px;
            max-width: 100%;
        }
        .est-circle-wrap {
            left: 50%;
            transform: translateX(-50%) scale(0.6);
            margin-top: -350px;
        }
        .est-container.state-2 .est-circle-wrap {
            transform: translateX(-50%) scale(0.6) rotate(-45deg);
        }
        .est-indicator {
            display: none;
        }
        .est-right {
            padding: 0;
            width: 100%;
        }
        .est-block {
            flex-direction: column;
        }
        .est-bracket {
            display: none;
        }
    }
</style>

<section class="est-container state-1" id="est-circle-animation">
    <div class="est-inner">
        
        <div class="est-left-wrap">
            <div class="est-left-content">
                <h2><?php echo $headline; ?></h2>
            </div>
            
            <div class="est-circle-wrap">
                <div class="est-dashed-circle"></div>
                <div class="est-year-marker year-1"><?php echo $state1_year; ?></div>
                <div class="est-year-marker year-2"><?php echo $state2_year; ?></div>
            </div>
            <div class="est-indicator"></div>
        </div>

        <div class="est-right">
            <!-- Top Block -->
            <div class="est-block">
                <div class="est-img-box">
                    <img src="<?php echo $s1_t_img; ?>" class="img-s1" alt="1980s Rocket">
                    <img src="<?php echo $s2_t_img; ?>" class="img-s2" alt="2020s Rocket">
                </div>
                <div class="est-details est-text-swap">
                    <div class="text-s1">
                        <div class="est-label"><?php echo $state1_top_label; ?></div>
                        <div class="est-value"><?php echo $state1_top_value; ?></div>
                        <div class="est-desc"><?php echo $state1_top_desc; ?></div>
                    </div>
                    <div class="text-s2">
                        <div class="est-label"><?php echo $state2_top_label; ?></div>
                        <div class="est-value"><?php echo $state2_top_value; ?></div>
                        <div class="est-desc"><?php echo $state2_top_desc; ?></div>
                    </div>
                </div>
            </div>

            <!-- Bottom Block -->
            <div class="est-block">
                <div class="est-bracket"></div>
                <div class="est-img-box">
                    <img src="<?php echo $s1_b_img; ?>" class="img-s1" alt="1980s Satellite">
                    <img src="<?php echo $s2_b_img; ?>" class="img-s2" alt="2020s Satellite">
                </div>
                <div class="est-bottom-meta">
                     <div class="est-extra-text"><?php echo $state1_bottom_extra; ?></div>
                </div>

                <div class="est-details est-text-swap">
                    <div class="text-s1">
                        <div class="est-label"><?php echo $state1_bottom_label; ?></div>
                        <div class="est-value"><?php echo $state1_bottom_value; ?></div>
                        <div class="est-desc"><?php echo $state1_bottom_desc; ?></div>
                    </div>
                    <div class="text-s2">
                        <div class="est-label"><?php echo $state2_bottom_label; ?></div>
                        <div class="est-value"><?php echo $state2_bottom_value; ?></div>
                        <div class="est-desc"><?php echo $state2_bottom_desc; ?></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('est-circle-animation');
    if (!container) return;

    container.addEventListener('click', function() {
        if (container.classList.contains('state-1')) {
            container.classList.remove('state-1');
            container.classList.add('state-2');
        } else {
            container.classList.remove('state-2');
            container.classList.add('state-1');
        }
    });
});
</script>
