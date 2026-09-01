<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/*TODO
 * fix issue with horizontal layout grabbing parent page as insight post
 * if categorisation left blank hide brackets
 * ensure view all links redirect properly
 * fix alignment/ padding amounts  */

$layout        = get_sub_field( 'layout' ); // radio button returning 0, 1 or 2
$insight_type  = get_sub_field( 'insight_type' ); // radio button returning string of insight type lowercase
$insight_count = get_sub_field( 'insight_count' ) ?: 3; // number of insights to display

// Podcast specific fields (only if type is podcast)
$podcast_title      = get_sub_field( 'podcast_title' );
$podcast_description = get_sub_field( 'podcast_description' );
$podcast_link       = get_sub_field( 'podcast_link' );
$podcast_platforms  = get_sub_field( 'podcast_platforms' ); // Expecting repeater or gallery of platform logos

// News specific fields (only if type is news)
$news_title       = get_sub_field( 'news_intro_title' );
$news_description = get_sub_field( 'news_intro_description' );
$news_link        = get_sub_field( 'news_intro_link' );

$args = array(
    'post_type'      => 'insight',
    'posts_per_page' => $insight_count,
    'orderby'        => 'date',
    'order'          => 'DESC',
);

if ( $insight_type && $insight_type !== 'all' ) {
    $args['tax_query'] = array(
        array(
            'taxonomy' => 'insight-type',
            'field'    => 'slug',
            'terms'    => $insight_type,
        ),
    );
}

$query = new WP_Query( $args );

if ( ! $query->have_posts() ) {
    return;
}

$insights = $query->posts;
$total    = count( $insights );

// Get taxonomy name for section title if needed
$title = '';
if ( $insight_type && $insight_type !== 'all' ) {
    $term = get_term_by( 'slug', $insight_type, 'insight-type' );
    if ( $term ) {
        $title = $term->name;
    }
} else {
    $title = 'Insights';
}

?>

<section class="insights-shortlist-section py-5">
    <div class="container">
        <?php if ( $layout == 0 ) : 
            $is_podcast = ( $insight_type === 'podcast' );
            $is_news    = ( $insight_type === 'news' );
            $has_intro  = ( $is_podcast || $is_news ) && ( $podcast_title || $news_title );
            ?>
            <?php /* Vertical with Left Spotlight (News Feed Style) */ ?>

            <div class="row">
                <div class="col-12 insightSectionDivide">
                    <div class="insightTitleLink">
                        <h3><?php echo esc_html( $title ); ?></h3>
                        <a href="<?php echo get_post_type_archive_link( 'insight' ); ?>">View all</a>
                    </div>
                </div>
            </div>

            <div class="newsFeedCon splitFeedCon <?php echo $is_podcast ? 'podcastFeedCon' : ''; ?> <?php echo $is_news ? 'newsSplitFeedCon' : ''; ?> <?php echo ! $has_intro ? 'latestSplitFeedCon' : ''; ?>">
                
                <?php if ( $has_intro ) : ?>
                    <div class="row">
                        <div class="col-12 insightSectionDivide">
                            <div class="insightTitleLink">
                                <h3><?php echo esc_html( $title ); ?></h3>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <?php if ( $has_intro ) : ?>
                        <div class="col-12 col-lg-7">
                            <?php if ( $is_podcast ) : ?>
                                <div class="podcastIntroCard">
                                    <div class="activeCorners gradientCorners">
                                        <div class="top"></div>
                                        <div class="bottom"></div>
                                    </div>
                                    <div class="iconCon">
                                        <div class="circleIcon"><i class="ci-Headphones"></i></div>
                                    </div>
                                    <div class="podcastIntroCard__content">
                                        <h4><?php echo esc_html( $podcast_title ); ?></h4>
                                        <p><?php echo esc_html( $podcast_description ); ?></p>
                                    </div>
                                    <div class="podcastIntroCard__footer">
                                        <?php if ( $podcast_link ) : ?>
                                            <a href="<?php echo esc_url( $podcast_link['url'] ); ?>" <?php echo $podcast_link['target'] ? 'target="' . esc_attr( $podcast_link['target'] ) . '"' : ''; ?>>
                                                <?php echo esc_html( $podcast_link['title'] ); ?> <i class="ci-External_Link"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if ( $podcast_platforms ) : ?>
                                            <div class="podcastIntroCard__platforms">
                                                <?php foreach ( $podcast_platforms as $platform ) : 
                                                    $img = $platform['logo'];
                                                    $url = $platform['url'];
                                                    ?>
                                                    <a href="<?php echo esc_url( $url ); ?>" target="_blank"><img src="<?php echo esc_url( $img['url'] ); ?>" alt="<?php echo esc_attr( $img['alt'] ); ?>"></a>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php elseif ( $is_news ) : ?>
                                <div class="splitFeedIntroCard">
                                    <div class="activeCorners gradientCorners">
                                        <div class="top"></div>
                                        <div class="bottom"></div>
                                    </div>
                                    <div class="iconCon">
                                        <div class="circleIcon"><i class="ci-Note"></i></div>
                                    </div>
                                    <div class="splitFeedIntroCard__content">
                                        <h4><?php echo esc_html( $news_title ); ?></h4>
                                        <p><?php echo esc_html( $news_description ); ?></p>
                                    </div>
                                    <div class="splitFeedIntroCard__footer">
                                        <?php if ( $news_link ) : ?>
                                            <a href="<?php echo esc_url( $news_link['url'] ); ?>" <?php echo $news_link['target'] ? 'target="' . esc_attr( $news_link['target'] ) . '"' : ''; ?>>
                                                <?php echo esc_html( $news_link['title'] ); ?> <i class="ci-External_Link"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php elseif ( isset( $insights[0] ) ) :
                        $post = $insights[0];
                        setup_postdata( $post );
                        $thumbnail_url = get_the_post_thumbnail_url( $post->ID, 'large' );
                        $terms = get_the_terms( $post->ID, 'insight-type' );
                        $term_name = ! empty( $terms ) && ! is_wp_error( $terms ) ? $terms[0]->name : '';
                        ?>
                        <div class="col-12 col-lg-7">
                            <a href="<?php echo get_permalink( $post->ID ); ?>" class="contentCard contentCard--feature splitFeedFeature">
                                <div class="activeCorners gradientCorners">
                                    <div class="top"></div>
                                    <div class="bottom"></div>
                                </div>
                                <div class="contentCard__image" style="background-image: url('<?php echo esc_url( $thumbnail_url ); ?>');">
                                    <?php if ( has_term( 'video', 'insight-type', $post->ID ) ) : ?>
                                        <span class="videoPlayButton" aria-hidden="true"></span>
                                    <?php endif; ?>
                                </div>
                                <div class="contentCard__meta">
                                    <span class="caption"><?php echo esc_html( $term_name ); ?></span>
                                    <span class="caption"><?php echo get_the_date( 'd M Y', $post->ID ); ?></span>
                                </div>
                                <h4><?php echo get_the_title( $post->ID ); ?></h4>
                                <p><?php echo wp_trim_words( get_the_excerpt( $post->ID ), 20 ); ?></p>
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="col-12 col-lg-5">
                        <div class="contentList splitFeedList <?php echo $is_podcast ? 'podcastEpisodeList' : ''; ?>">
                            <?php 
                            $start_index = $has_intro ? 0 : 1;
                            for ( $i = $start_index; $i < $total; $i ++ ) :
                                $post = $insights[ $i ];
                                setup_postdata( $post );
                                $thumbnail_url = get_the_post_thumbnail_url( $post->ID, 'medium' );
                                $terms = get_the_terms( $post->ID, 'insight-type' );
                                $term_name = ! empty( $terms ) && ! is_wp_error( $terms ) ? $terms[0]->name : '';
                                $episode   = get_field( 'episode_number', $post->ID );
                                ?>
                                <a href="<?php echo get_permalink( $post->ID ); ?>" class="contentListItem splitFeedItem <?php echo $is_podcast ? 'podcastEpisodeItem' : ''; ?>">
                                    <div class="activeCorners gradientCorners">
                                        <div class="top"></div>
                                        <div class="bottom"></div>
                                    </div>
                                    <div class="contentListItem__image" style="background-image: url('<?php echo esc_url( $thumbnail_url ); ?>');">
                                        <?php if ( has_term( 'video', 'insight-type', $post->ID ) ) : ?>
                                            <span class="contentListItem__play" aria-hidden="true"></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="contentListItem__content">
                                        <div class="contentListItem__meta">
                                            <span class="caption"><?php echo esc_html( $term_name ); ?></span>
                                            <span class="caption"><?php echo get_the_date( 'd M Y', $post->ID ); ?></span>
                                        </div>
                                        <h4><?php echo get_the_title( $post->ID ); ?></h4>
                                        <?php if ( $is_podcast && $episode ) : ?>
                                            <p>Episode <?php echo esc_html( $episode ); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </a>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
            </div>

        <?php elseif ( $layout == 1 ) : ?>
            <?php /* Vertical (Standard List) */ ?>
            <div class="row">
                <div class="insightTitleLink">
                    <h3><?php echo esc_html( $title ); ?></h3>
                    <a href="<?php echo get_post_type_archive_link( 'insight' ); ?>">View all</a>
                </div>
            </div>
            <div class="row">
                <div class="col-12 ">
                    <div class="contentList">
                        <?php foreach ( $insights as $post ) :
                            setup_postdata( $post );
                            $thumbnail_url = get_the_post_thumbnail_url( $post->ID, 'medium' );
                            $terms = get_the_terms( $post->ID, 'insight-type' );
                            $term_name = ! empty( $terms ) && ! is_wp_error( $terms ) ? $terms[0]->name : '';
                            ?>
                            <a href="<?php echo get_permalink( $post->ID ); ?>" class="contentListItem mb-4">
                                <div class="activeCorners gradientCorners">
                                    <div class="top"></div>
                                    <div class="bottom"></div>
                                </div>
                                <div class="contentListItem__image" style="background-image: url('<?php echo esc_url( $thumbnail_url ); ?>');">
                                    <?php if ( has_term( 'video', 'insight-type', $post->ID ) ) : ?>
                                        <span class="contentListItem__play" aria-hidden="true"></span>
                                    <?php endif; ?>
                                </div>
                                <div class="contentListItem__content">
                                    <div class="contentListItem__meta">
                                        <span class="caption"><?php echo esc_html( $term_name ); ?></span>
                                        <span class="caption"><?php echo get_the_date( 'd M Y', $post->ID ); ?></span>
                                    </div>
                                    <h4><?php echo get_the_title( $post->ID ); ?></h4>
                                    <p><?php echo wp_trim_words( get_the_excerpt( $post->ID ), 25 ); ?></p>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

        <?php elseif ( $layout == 2 ) : ?>
            <?php /* Horizontal (Grid Style) */ ?>
            <div class="row">
                <div class="col-12 insightSectionDivide">
                    <div class="insightTitleLink">
                        <h3><?php echo esc_html( $title ); ?></h3>
                        <a href="<?php echo get_post_type_archive_link( 'insight' ); ?>">View all</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php foreach ( $insights as $post ) :
                    setup_postdata( $post );
                    $thumbnail_url = get_the_post_thumbnail_url( $post->ID, 'large' );
                    $terms = get_the_terms( $post->ID, 'insight-type' );
                    $term_name = ! empty( $terms ) && ! is_wp_error( $terms ) ? $terms[0]->name : '';
                    $is_video = has_term( 'video', 'insight-type', $post->ID );
                    $duration = get_field( 'duration', $post->ID );
                    ?>
                    <div class="col-12 col-md-6 col-lg-4 mb-4">
                        <a href="<?php echo get_permalink( $post->ID ); ?>" class="contentCard contentCard--grid <?php echo $is_video ? 'contentCard--video' : ''; ?>">
                            <div class="activeCorners gradientCorners">
                                <div class="top"></div>
                                <div class="bottom"></div>
                            </div>
                            <div class="contentCard__image" style="background-image: url('<?php echo esc_url( $thumbnail_url ); ?>');">
                                <?php if ( $is_video ) : ?>
                                    <span class="videoPlayButton" aria-hidden="true"></span>
                                <?php endif; ?>
                            </div>
                            <div class="contentCard__meta">
                                <span class="caption"><?php echo esc_html( $term_name ); ?></span>
                                <?php if ( $is_video && $duration ) : ?>
                                    <span class="caption"><?php echo esc_html( $duration ); ?></span>
                                <?php endif; ?>
                                <span class="caption"><?php echo get_the_date( 'd M Y', $post->ID ); ?></span>
                            </div>
                            <h4><?php echo get_the_title( $post->ID ); ?></h4>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php 
wp_reset_postdata(); 
?>
