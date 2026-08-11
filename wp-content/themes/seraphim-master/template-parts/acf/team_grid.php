<?php
/**
 * ACF Template Part: Team Grid
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$selected_tags = get_sub_field('team_tags');

$args = array(
    'post_type'      => 'team-members',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
);

if ( ! empty( $selected_tags ) ) {
    $args['tax_query'] = array(
        array(
            'taxonomy'         => 'team-tag',
            'field'            => 'term_id',
            'terms'            => (array) $selected_tags,
            'include_children' => true,
        ),
    );
}

$query = new WP_Query( $args );
?>

<section class="team-grid-section py-5">
    <div class="container">
        <?php if ( $query->have_posts() ) : ?>
            <div class="row g-4 team-grid-container">
                <?php while ( $query->have_posts() ) : $query->the_post();
                    $main_photo = get_field('main_photo');
                    $bio_text = get_field('bio_text');
                    $linkedin_profile = get_field('linkedin_profile');
                    $job_title = get_field('job_title');
                    
                    $team_tags = get_the_terms( get_the_ID(), 'team-tag' );
                    $positions = [];
                    if ( ! is_wp_error( $team_tags ) && ! empty( $team_tags ) ) {
                        foreach ( $team_tags as $term ) {
                            // Check if this term is a child tag (has a parent)
                            // or more specifically, if it's NOT a parent tag (doesn't have children)
                            // The instruction says "any parent tags... should not be shown".
                            // In WP, we can check if a term has children.
                            $children = get_term_children( $term->term_id, 'team-tag' );
                            if ( empty( $children ) ) {
                                $positions[] = $term->name;
                            }
                        }
                    }
                    
                    $photo_url = '';
                    if ( $main_photo ) {
                        $photo_url = is_array($main_photo) ? $main_photo['url'] : (is_numeric($main_photo) ? wp_get_attachment_url($main_photo) : $main_photo);
                    }
                    ?>
                    <div class="col-12 col-md-4 mb-4">
                        <div class="notch-card team-card">
                            <span class="notch-card__tab" aria-hidden="true"></span>
                            <div class="imageCon" style="background-image: url('<?php echo esc_url( $photo_url ); ?>');"></div>
                            <div class="contentCon">
                                <h4 class="title small"><?php the_title(); ?></h4>
                            </div>

                            <div class="detailsCon">                            
                                <?php if ( !empty($positions) ) : ?>
                                    <span class="caption d-block mb-1"><?php echo esc_html( implode(', ', $positions) ); ?></span>
                                <?php endif; ?>
                                <?php if ( $job_title ) : ?>
                                    <span class="caption"><?php echo esc_html( $job_title ); ?></span>
                                <?php endif; ?>
                            </div>
                            
                            <?php if ( $bio_text || $linkedin_profile ) : ?>
                                <div class="textCtaCon">
                                    <a href="<?php the_permalink(); ?>" class="openDrawer">Read more  <i class="ci-expand"></i></a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        <?php else : ?>
            <div class="text-center py-5">
                <p>No team members found.</p>
            </div>
        <?php endif; ?>
    </div>
</section>