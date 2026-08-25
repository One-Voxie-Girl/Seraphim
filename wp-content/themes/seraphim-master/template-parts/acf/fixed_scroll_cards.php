<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Fixed Scroll Cards Component
 * 
 * Fields:
 * - tagline_text (text)
 * - headline_text (text)
 * - card_repeater (repeater)
 *   - title_text (text)
 *   - preview_text (text)
 *   - main_text (textarea)
 */

$tagline_text  = get_sub_field( 'tagline_text' );
$headline_text = get_sub_field( 'headline_text' );
?>

<section class="fixedScrollCards py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-4 mb-4 mb-lg-0">
                <div class="sticky-side">
                    <?php if ( $tagline_text ) : ?>
                        <div class="tagline mb-3">// <?php echo esc_html( $tagline_text ); ?></div>
                    <?php endif; ?>
                    
                    <?php if ( $headline_text ) : ?>
                        <h2 class="headline text-uppercase"><?php echo esc_html( $headline_text ); ?></h2>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-12 col-lg-8">
                <?php if ( have_rows( 'card' ) ) : ?>
                    <div class="cards-list">
                        <?php 
                        $i = 0;
                        while ( have_rows( 'card' ) ) : the_row();
                            $i++;
                            $title_text   = get_sub_field( 'title_text' );
                            $preview_text = get_sub_field( 'preview_text' );
                            $main_text    = get_sub_field( 'main_text' );
                        ?>
                            <div class="scroll-card mb-4" data-index="<?php echo $i; ?>">
                                <div class="activeCorners">
                                    <div class="top"></div>
                                    <div class="bottom"></div>
                                </div>
                                
                                <div class="card-content p-4">
                                    <div class="card-number mb-2"><?php echo sprintf('%02d', $i); ?></div>
                                    
                                    <?php if ( $title_text ) : ?>
                                        <h3 class="card-title mb-4"><?php echo esc_html( $title_text ); ?></h3>
                                    <?php endif; ?>

                                    <div class="card-body-text">
                                        <?php if ( $preview_text ) : ?>
                                            <div class="preview-text mb-0">
                                                <?php echo esc_html( $preview_text ); ?>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ( $main_text ) : ?>
                                            <div class="main-text mt-3" style="display: none;">
                                                <?php echo wp_kses_post( $main_text ); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <button class="card-toggle-btn" aria-label="Toggle content">
                                        <svg width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M10 4V16M4 10H16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<style>
.fixedScrollCards {
    background-color: #01060e;
    color: #ffffff;
}

.fixedScrollCards .sticky-side {
    position: sticky;
    top: 140px; 
}

.fixedScrollCards .tagline {
    color: #63d693;
    font-size: 14px;
    font-weight: 500;
}

.fixedScrollCards .headline {
    font-family: inherit;
    font-weight: 700;
    line-height: 1.1;
    font-size: clamp(32px, 4vw, 54px);
}

.scroll-card {
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    position: relative;
    cursor: pointer;
    transition: background 0.3s ease;
}

.scroll-card:hover {
    background: rgba(255, 255, 255, 0.05);
}

.scroll-card .card-number {
    color: #63d693;
    font-size: 14px;
    opacity: 0.8;
}

.scroll-card .card-title {
    font-weight: 500;
    font-size: clamp(18px, 2vw, 24px);
    margin-bottom: 20px !important;
}

.scroll-card .preview-text {
    font-size: 18px;
    color: rgba(255, 255, 255, 0.7);
}

.scroll-card .card-toggle-btn {
    position: absolute;
    bottom: 20px;
    right: 20px;
    background: none;
    border: none;
    color: #63d693;
    padding: 0;
    cursor: pointer;
    transition: transform 0.3s ease;
}

.scroll-card.is-active .card-toggle-btn {
    transform: rotate(45deg);
}

/* activeCorners Integration */
.scroll-card .activeCorners .top::before,
.scroll-card .activeCorners .top::after,
.scroll-card .activeCorners .bottom::before,
.scroll-card .activeCorners .bottom::after {
    opacity: 0.1;
}

.scroll-card:hover .activeCorners .top::before,
.scroll-card:hover .activeCorners .top::after,
.scroll-card:hover .activeCorners .bottom::before,
.scroll-card:hover .activeCorners .bottom::after {
    opacity: 0.5;
}

@media (max-width: 991.98px) {
    .fixedScrollCards .sticky-side {
        position: static;
        margin-bottom: 40px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.scroll-card');
    
    cards.forEach(card => {
        card.addEventListener('click', function() {
            const mainText = this.querySelector('.main-text');
            const previewText = this.querySelector('.preview-text');
            const isActive = this.classList.contains('is-active');
            
            // Optional: Close other cards
            // cards.forEach(c => {
            //     c.classList.remove('is-active');
            //     const mt = c.querySelector('.main-text');
            //     const pt = c.querySelector('.preview-text');
            //     if (mt) mt.style.display = 'none';
            //     if (pt) pt.style.display = 'block';
            // });

            if (!isActive) {
                this.classList.add('is-active');
                if (mainText) mainText.style.display = 'block';
                if (previewText) previewText.style.display = 'none';
            } else {
                this.classList.remove('is-active');
                if (mainText) mainText.style.display = 'none';
                if (previewText) previewText.style.display = 'block';
            }
        });
    });
});
</script>

