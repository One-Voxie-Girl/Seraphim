<div class="thirdPartyResearchCon">
    <div class="thirdPartyResearchHeader">
        <h2><?php echo esc_html( $title ); ?></h2>
        <?php if ( $all_insights_link ) : ?>
            <a href="<?php echo esc_url( $all_insights_link['url'] ); ?>" target="<?php echo esc_attr( $all_insights_link['target'] ?: '_self' ); ?>">
                View All <?php echo esc_html( $all_insights_link['title'] ); ?>
            </a>
        <?php endif; ?>
    </div>

    <?php if ( $query->have_posts() ) : ?>
        <div class="InsightsShortList insightsShortlist">
            <?php
            $count = 0;
            while ( $query->have_posts() ) : $query->the_post();
                $count++;
                $insight_types = get_the_terms( get_the_ID(), 'insight-type' );
                $insight_tag = '';
                if ( ! is_wp_error( $insight_types ) && ! empty( $insight_types ) ) {
                    $insight_tag = $insight_types[0]->name;
                }

                $thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'large' ) ?: 'src/img/card-background-ssit-v1.jpg';

                // Get video runtime if available
                $runtime = '';
                $insight_file = get_field('insight_file');
                if ($insight_file && isset($insight_file['mime_type']) && str_contains($insight_file['mime_type'], 'video')) {
//                  $runtime = get_field('duration') ?: get_field('video_runtime') ?: ($insight_file['duration'] ?? '');
                    $runtime = wp_read_video_metadata($insight_file['url']);
                }
                debug_to_console($runtime);
                ?>



                <a href="<?php the_permalink(); ?>" class="thirdPartyResearchItem<?php echo $count === 1 ? ' featured' : ''; ?>">
                    <div class="thirdPartyResearchItem__image" style="background-image: url('<?php echo esc_url( $thumbnail_url ); ?>');"></div>
                    <div class="thirdPartyResearchItem__content">
                        <div class="thirdPartyResearchItem__meta">
                            <?php if ( $insight_tag ) : ?>
                                <span class="caption"><?php echo esc_html( $insight_tag ); ?></span>
                            <?php endif; ?>
                            <p><?php echo esc_html( $runtime ); ?></p>
                            <?php if ( $runtime ) : ?>
                                <span class="caption runtime"><?php echo esc_html( $runtime ); ?></span>
                            <?php endif; ?>
                            <span class="caption"><?php echo get_the_date( 'd M Y' ); ?></span>
                        </div>
                        <h4><?php the_title(); ?></h4>
                    </div>
                </a>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    <?php endif; ?>
</div>

