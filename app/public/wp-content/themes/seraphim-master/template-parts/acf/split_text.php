<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}	
	
// Vars
$header = get_sub_field('header');
$header_colour = get_sub_field('header_colour');
$main_copy = get_sub_field('main_copy');

?>
          
          

<div>
  
  <div class="row">
    <div class="col-12 col-md-5">
      <h3><?= $header; ?></h3>
    </div>
  </div>

  <div>
    <?= $main_copy; ?>
  </div>

  <?php
    get_template_part('template-parts/acf/call_to_action', 'none');
  ?>
  
  
</div>

          
 

