<?php
/**
 * ACF Template Part: Team Spotlight
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$team_member_id = get_sub_field('team_member');

if ( $team_member_id ) :
    $member_post = get_post( $team_member_id );
    if ( $member_post && $member_post->post_type === 'team-members' ) :
        $main_photo = get_field('main_photo', $team_member_id);
        $bio_text = get_field('bio_text', $team_member_id);
        $linkedin_profile = get_field('linkedin_profile', $team_member_id);
        $job_title = get_field('job_title', $team_member_id);

        $team_tags = get_the_terms( $team_member_id, 'team-tag' );
        $positions = [];
        if ( ! is_wp_error( $team_tags ) && ! empty( $team_tags ) ) {
            foreach ( $team_tags as $term ) {
                $positions[] = $term->name;
            }
        }

        $photo_url = '';
        if ( $main_photo ) {
            $photo_url = is_array($main_photo) ? $main_photo['url'] : (is_numeric($main_photo) ? wp_get_attachment_url($main_photo) : $main_photo);
        }
        ?>
        <div class="row">
            <div class="col-12">
                <div class="card team-card big-card noHover">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="imageCon" style="background-image: url('<?php echo esc_url( $photo_url ); ?>');"></div>
                        </div>
                        <div class="col-12 col-md-6 contentSection">
                           
                            <div class="contentCon">
                                <h4 class="title"><?php echo get_the_title($team_member_id); ?></h4>
                            </div>

                            <div class="detailsCon">                            
                                <span class="caption"><?php echo esc_html( !empty($positions) ? implode(', ', $positions) : $job_title ); ?></span>
                            </div>

                            <div class="contentCon">
                                <?php if ( $bio_text ) : ?>
                                    <span class="white65"><?php echo wp_kses_post( $bio_text ); ?></span>
                                <?php endif; ?>
                                
                                <?php if ( $linkedin_profile ) : ?>
                                    <a href="<?php echo esc_url( $linkedin_profile ); ?>" class="iconLink featuredTeamLinkedin" target="_blank" rel="noopener">
                                        <div class="iconCon">
                                            <div class="circleIcon linkedin"></div>
                                            View LinkedIn profile
                                        </div>
                                    </a>
                                <?php endif; ?>

                            </div>
                                
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>
