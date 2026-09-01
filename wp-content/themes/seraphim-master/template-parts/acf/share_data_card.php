<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$title_text = get_sub_field( 'title_text' );
$lse_text = get_sub_field( 'lse_text' );
$share_price_text = get_sub_field( 'share_price_text' );
$nav_text = get_sub_field( 'nav_text' );
?>
<div class="container">
    <div class="share-data-card p-4 rounded-4 border border-secondary text-white" style="max-width: 250px;">
        <?php if ( $title_text ) : ?>
            <div class="card-title mb-1 opacity-50"><?php echo esc_html( $title_text ); ?></div>
            <hr class="border-secondary mt-0 mb-4 opacity-50">
        <?php endif; ?>

        <div class="share-data-content">
            <?php if ( $lse_text ) : ?>
                <div class="data-row mb-2">
                    <span class="label">LSE: </span>
                    <span class="value text-terrain"><?php echo esc_html( $lse_text ); ?></span>
                </div>
            <?php endif; ?>

            <?php if ( $share_price_text ) : ?>
                <div class="data-row mb-2">
                    <span class="label">Share price: </span>
                    <span class="value text-terrain"><?php echo esc_html( $share_price_text ); ?></span>
                </div>
            <?php endif; ?>

            <?php if ( $nav_text ) : ?>
                <div class="data-row">
                    <span class="label">NAV per share: </span>
                    <span class="value text-terrain"><?php echo esc_html( $nav_text ); ?></span>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .share-data-card {
        background-color: rgba(14, 20, 32, 0.25) !important;
        -webkit-backdrop-filter: blur(5px);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.16) !important;
        border-radius: 20px !important;
    }
    .text-terrain {
        color: #89DEAB !important;
    }
    .share-data-card .label {
        color: #FFFFFF;
        opacity: 0.9;
    }
    .share-data-card .card-title {
        font-size: 1.1rem;
        font-weight: 300;
    }
    .share-data-card .data-row {
        font-size: 1.2rem;
        font-weight: 400;
    }
    .share-data-card hr {
        border-top: 1px solid #353943 !important;
    }
</style>
