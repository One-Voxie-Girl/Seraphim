<?php
/**
 * Updates Card Component
 * Fields:
 * - title_text (Text)
 * - main_text (Textarea/WYSIWYG)
 * - link (Link Array)
 * - background_image (Image Array)
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$title_text = get_sub_field('title_text');
$main_text = get_sub_field('main_text');
$link = get_sub_field('link');
$background_image = get_sub_field('background_image');

$bg_url = $background_image ? esc_url($background_image['url']) : '';
?>

<div class="updates-card" <?php if ($bg_url) : ?>style="background-image: url('<?php echo $bg_url; ?>');"<?php endif; ?>>
    <div class="updates-card-overlay"></div>
    <div class="updates-card-content">
        <?php if ($title_text) : ?>
            <h2 class="updates-card-title"><?php echo esc_html($title_text); ?></h2>
        <?php endif; ?>

        <?php if ($main_text) : ?>
            <div class="updates-card-text">
                <?php echo wp_kses_post($main_text); ?>
            </div>
        <?php endif; ?>

        <?php if ($link) : ?>
            <div class="updates-card-action">
                <a href="<?php echo esc_url($link['url']); ?>" 
                   target="<?php echo esc_attr($link['target'] ?: '_self'); ?>" 
                   class="updates-card-btn">
                    <?php echo esc_html($link['title']); ?> <span class="btn-arrow">&raquo;</span>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    .updates-card {
        position: relative;
        width: 100%;
        min-height: 400px;
        background-color: #020714;
        background-size: cover;
        background-position: center;
        border-radius: 12px;
        overflow: hidden;
        display: flex;
        align-items: center;
        padding: 60px;
        box-sizing: border-box;
        border: 1px solid rgba(255, 255, 255, 0.1);
        margin-bottom: 30px;
    }

    .updates-card-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(90deg, rgba(2, 7, 20, 0.9) 0%, rgba(2, 7, 20, 0.4) 100%);
        z-index: 1;
    }

    .updates-card-content {
        position: relative;
        z-index: 2;
        max-width: 600px;
    }

    .updates-card-title {
        font-family: "Osiris", sans-serif;
        font-size: 32px;
        color: #FFFFFF;
        text-transform: uppercase;
        margin-bottom: 24px;
        letter-spacing: 1px;
    }

    .updates-card-text {
        font-family: "Neuro", sans-serif;
        font-size: 18px;
        line-height: 1.6;
        color: #A6A7AB;
        margin-bottom: 40px;
    }

    .updates-card-btn {
        display: inline-flex;
        align-items: center;
        padding: 12px 28px;
        font-family: "Neuro", sans-serif;
        font-size: 16px;
        color: #FFFFFF;
        text-decoration: none;
        border-radius: 50px;
        position: relative;
        background: rgba(255, 255, 255, 0.05);
        transition: all 0.3s ease;
        border: 1px solid transparent;
        background-clip: padding-box;
    }

    .updates-card-btn::before {
        content: "";
        position: absolute;
        top: -1px;
        bottom: -1px;
        left: -1px;
        right: -1px;
        background: linear-gradient(to right, #70D0D9, #FFBA4A);
        border-radius: 50px;
        z-index: -1;
        opacity: 0.8;
    }

    .updates-card-btn:hover {
        background: rgba(255, 255, 255, 0.1);
        transform: translateY(-2px);
    }

    .btn-arrow {
        margin-left: 10px;
        font-size: 14px;
        transition: transform 0.3s ease;
    }

    .updates-card-btn:hover .btn-arrow {
        transform: translateX(4px);
    }

    @media (max-width: 768px) {
        .updates-card {
            padding: 40px 30px;
        }
        .updates-card-title {
            font-size: 26px;
        }
        .updates-card-text {
            font-size: 16px;
        }
    }
</style>
