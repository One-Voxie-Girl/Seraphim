<?php
/**
 * Feature Card Component
 * Fields:
 * - small_image (Image Array)
 * - title_text (Text)
 * - main_text (Textarea/WYSIWYG)
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$small_image = get_sub_field('image');
$title_text  = get_sub_field('title_text');
$main_text   = get_sub_field('main_text');

if ($small_image || $title_text || $main_text) :
?>

<div class="feature-card">
    <div class="feature-card-inner">
        <?php if ($small_image) : ?>
            <div class="feature-card-icon-wrapper">
                <img src="<?php echo esc_url($small_image['url']); ?>" alt="<?php echo esc_attr($small_image['alt']); ?>" class="feature-card-icon">
            </div>
        <?php endif; ?>
        
        <div class="feature-card-content">
            <?php if ($title_text) : ?>
                <h3 class="feature-card-title"><?php echo esc_html($title_text); ?></h3>
            <?php endif; ?>
            
            <?php if ($main_text) : ?>
                <div class="feature-card-text">
                    <?php echo wp_kses_post($main_text); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .feature-card {
        width: 100%;
        padding: 10px;
    }

    .feature-card-inner {
        position: relative;
        background: #020714;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 40px;
        height: 360px;
        display: flex;
        flex-direction: column;
        transition: all 0.4s ease;
        overflow: hidden;
        z-index: 1;
        box-sizing: border-box;
    }

    /* Hover effect - subtle glow/gradient */
    .feature-card:hover .feature-card-inner {
        border-color: rgba(255, 255, 255, 0.2);
    }

    .feature-card-inner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 60% 30%, rgba(112, 208, 217, 0.25) 0%, transparent 60%),
                    radial-gradient(circle at 40% 70%, rgba(255, 186, 74, 0.2) 0%, transparent 60%),
                    radial-gradient(circle at 80% 50%, rgba(0, 170, 80, 0.15) 0%, transparent 50%);
        opacity: 0;
        transition: opacity 0.6s ease;
        z-index: -1;
        filter: blur(40px);
    }

    .feature-card:hover .feature-card-inner::before {
        opacity: 1;
    }

    .feature-card-icon-wrapper {
        width: 64px;
        height: 64px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 32px;
        position: relative;
        overflow: hidden;
    }
    
    .feature-card-icon-wrapper::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 50%;
        transform: translateX(-50%);
        width: 80%;
        height: 15px;
        background: radial-gradient(circle, rgba(255, 186, 74, 0.5), transparent 70%);
        filter: blur(5px);
        opacity: 0.8;
    }

    .feature-card-icon {
        width: 24px;
        height: 24px;
        object-fit: contain;
        filter: brightness(0) invert(1); /* Assuming white icons */
    }

    .feature-card-title {
        font-family: "Osiris", sans-serif;
        font-size: 24px;
        font-weight: 400;
        color: #FFFFFF;
        margin-bottom: 16px;
        line-height: 1.3;
    }

    .feature-card-text {
        font-family: "Neuro", sans-serif;
        font-size: 16px;
        line-height: 1.6;
        color: #A6A7AB;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: rgba(255, 255, 255, 0.2) transparent;
    }

    .feature-card-text::-webkit-scrollbar {
        width: 4px;
    }

    .feature-card-text::-webkit-scrollbar-track {
        background: transparent;
    }

    .feature-card-text::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 2px;
    }

    /* For 2x2 layout when used in a row */
    @media (min-width: 992px) {
        .col-lg-6 .feature-card {
            margin-bottom: 0;
        }
    }
</style>

<?php endif; ?>
