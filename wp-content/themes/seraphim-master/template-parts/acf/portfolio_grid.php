<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
   

<div class="main-grid row">
			        
	<?php
		
		$args = array(  
	        'post_type' => 'wporg_portfolio',
	        'post_status' => 'publish',
	        'posts_per_page' => -1, 
	        'orderby' => 'title', 
	        'order' => 'ASC', 
	    );
	
	    $loop = new WP_Query( $args ); 
	        
	    while ( $loop->have_posts() ) : $loop->the_post(); 
	    
	    $postID = $post->ID;    
	    $background_attachment_id = get_field('background_image', $postID);
		$size = "medium_large";
		$background_image = wp_get_attachment_image_src( $background_attachment_id, $size );
		
		$logo_attachment_id = get_field('company_logo', $postID);
		$logo_image = wp_get_attachment_image_src( $logo_attachment_id, $size );	
		
		$preview_text = get_field('preview_text', $postID);
		$ipo_status = get_field('ipo_status', $postID);
		
		$fund = "";
		
		$seraphim_brand_attributions = get_field('seraphim_brand_attribution', $postID);
		if( $seraphim_brand_attributions ): 
		foreach( $seraphim_brand_attributions as $seraphim_brand_attribution ):
		       $fund .= $seraphim_brand_attribution . " ";
		    endforeach;
		endif; 
				
		if ($seraphim_brand_attribution == "seraphim_fund") {
			$outline = "terrain";
		} else if ($seraphim_brand_attribution == "space_camp") {
			$outline = "solar";
		} else {
			$outline = "main";
		}
		
		$website_link_only = get_field('website_link_only', $postID);
		
		if ($website_link_only == "yes") {
			$website_link = get_field('website_link', $postID);
		}
		
	    ?>
		
		      <div class="col-12 col-md-4 <?= $fund; ?>">
			    <a href="<?php if ($website_link_only == "yes") { echo $website_link; } else { echo get_permalink(); } ?>" <?php if ($website_link_only == "yes") { echo 'target="_blank"'; } ?>>
			        <div class="item <?= $outline; ?>-stroke">
			          <div class="brand-holder">
			            <img class="ontop" src="<?= $logo_image[0]; ?>" />
			            <img src="<?= $background_image[0]; ?>" />
			            
			            <?php if ($ipo_status == 'yes') { 
				            echo '<div class="ipo_badge">IPO</div>';
			            } ?>
			            
			          </div>
			          <div class="info">
			            <p>
			              <?= $preview_text; ?>
			            </p>
			            <button class="<?= $outline; ?>-grad-button">
						<div class="button-fill">  </div>
							<p>Learn More</p>
							<span><img src="/wp-content/themes/seraphimvc/src/images/arrow-right.svg"/></span>
						</button>
			          </div>
			        </div>
			    </a>
		      </div>
	
	
	
	<?php 
	    endwhile;
	    wp_reset_postdata();  
	?>

</div>


<script>
	
		$(document).ready(function(){
			$('.space_camp').show();
			$('.seraphim_fund').hide();
		});
	
	</script>



