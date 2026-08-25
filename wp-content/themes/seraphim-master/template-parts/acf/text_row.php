<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Text Row ACF Component
 * 
 * Repeater Field: text_column
 * - Sub Field: main_text
 * - Sub Field: subtext
 */

if ( have_rows( 'text_column' ) ) : ?>
    <section class="textRowCon py-5">
        <div class="container">
            <div class="row statsRowCon smallStats g-0">
                <div class="activeCorners">
                    <div class="top"></div>
                    <div class="bottom"></div>
                </div>

                <?php while ( have_rows( 'text_column' ) ) : the_row(); 
                    $main_text = get_sub_field( 'main_text' );
                    $subtext = get_sub_field( 'subtext' );
                ?>
                    <div class="col-12 col-md">
                        <?php if ( $main_text ) : ?>
                            <h2 class="mb-2"><?php echo esc_html( $main_text ); ?></h2>
                        <?php endif; ?>
                        <?php if ( $subtext ) : ?>
                            <p class="mb-0"><?php echo wp_kses_post( $subtext ); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
<?php endif; ?>
