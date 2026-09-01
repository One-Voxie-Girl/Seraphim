<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$title_text = get_sub_field('title_text');
$main_text = get_sub_field('main_text');
$image = get_sub_field('image');

if ($title_text || $main_text || $image) :
?>

<div class="info-card-item">
    <div class="info-card-inner">
        <div class="info-card-image-wrapper">
            <?php if ($image) : ?>
                <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" class="info-card-image">
            <?php endif; ?>
        </div>
        
        <div class="info-card-content">
            <?php if ($title_text) : ?>
                <h3 class="info-card-title"><?php echo esc_html($title_text); ?></h3>
            <?php endif; ?>
            
            <div class="info-card-main-text">
                <?php echo wp_kses_post($main_text); ?>
            </div>
        </div>

        <div class="info-card-corner top-left"></div>
        <div class="info-card-corner top-right"></div>
        <div class="info-card-corner bottom-left"></div>
        <div class="info-card-corner bottom-right"></div>
        
        <div class="info-card-close">
            <svg width="10" height="10" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M1 1L13 13M1 13L13 1" stroke="currentColor" stroke-width="2"/>
            </svg>
        </div>
    </div>
</div>

<style>
    .info-card-item {
        position: relative;
        width: 100%;
        margin-bottom: 20px;
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .info-card-inner {
        position: relative;
        background: #020714;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 24px;
        height: 400px; /* Fixed height */
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }

    /* Gradient Border on Active */
    .info-card-item.active .info-card-inner {
        border-color: transparent;
        background: linear-gradient(#020714, #020714) padding-box,
                    linear-gradient(135deg, #70D0D9, #FFBA4A) border-box;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }

    .info-card-image-wrapper {
        width: 100%;
        flex: 1; /* Take remaining space */
        min-height: 100px; /* Minimum height when text is expanded */
        margin-bottom: 24px;
        border-radius: 8px;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.05);
        transition: flex 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .info-card-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.6;
        transition: opacity 0.5s ease;
    }

    .info-card-item.active .info-card-image {
        opacity: 0.8;
    }

    .info-card-content {
        flex: 0 0 auto; /* Height based on content */
        display: flex;
        flex-direction: column;
    }

    .info-card-title {
        font-family: "Osiris", sans-serif;
        font-size: 24px;
        line-height: 1.2;
        text-transform: uppercase;
        color: #FFFFFF;
        margin: 0 0 16px 0;
        transition: all 0.5s ease;
        flex: 0 0 auto;
    }

    .info-card-main-text {
        font-family: "Neuro", sans-serif;
        font-size: 16px;
        line-height: 1.5;
        color: #A6A7AB;
        max-height: 0;
        opacity: 0;
        overflow-y: auto; /* Allow scroll if text is very long */
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        scrollbar-width: none; /* Hide scrollbar for Firefox */
    }

    .info-card-main-text::-webkit-scrollbar {
        display: none; /* Hide scrollbar for Chrome/Safari */
    }

    .info-card-item.active .info-card-main-text {
        max-height: 250px; /* Adjusted to fit within fixed card height */
        opacity: 1;
        margin-top: 8px;
    }

    .info-card-item.active .info-card-image-wrapper {
        flex: 0 0 150px; /* Shrink image when active */
    }

    /* Corner Marks */
    .info-card-corner {
        position: absolute;
        width: 12px;
        height: 12px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        pointer-events: none;
        transition: border-color 0.5s ease;
    }
    
    .info-card-item.active .info-card-corner {
        border-color: rgba(112, 208, 217, 0.5);
    }

    .info-card-corner.top-left { top: 12px; left: 12px; border-right: 0; border-bottom: 0; border-top-left-radius: 4px; }
    .info-card-corner.top-right { top: 12px; right: 12px; border-left: 0; border-bottom: 0; border-top-right-radius: 4px; }
    .info-card-corner.bottom-left { bottom: 12px; left: 12px; border-right: 0; border-top: 0; border-bottom-left-radius: 4px; }
    .info-card-corner.bottom-right { bottom: 12px; right: 12px; border-left: 0; border-top: 0; border-bottom-right-radius: 4px; }

    /* Close Icon */
    .info-card-close {
        position: absolute;
        bottom: 12px;
        right: 12px;
        color: #FFBA4A;
        opacity: 0.6;
        transition: opacity 0.3s ease;
    }

    .info-card-item.active .info-card-close {
        opacity: 1;
    }

    /* Parent Layout Support - assuming a row of these */
    @media (min-width: 992px) {
        .row:has(> .col-lg-4 > .info-card-item) {
            display: flex;
            align-items: stretch;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.info-card-item');
        
        cards.forEach(card => {
            card.addEventListener('click', function(e) {
                // If this card is already active, we might want to toggle it or do nothing
                // The image shows one expanded, so let's make it exclusive if they are siblings
                const container = card.closest('.row') || card.parentElement;
                const siblings = container.querySelectorAll('.info-card-item');
                
                siblings.forEach(s => {
                    if (s !== card) s.classList.remove('active');
                });
                
                card.classList.toggle('active');
            });
        });
    });
</script>

<?php endif; ?>



