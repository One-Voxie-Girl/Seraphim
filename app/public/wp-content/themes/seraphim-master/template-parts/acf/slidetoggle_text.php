<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}	
	
// Vars
$header = get_sub_field('header');
$preview_copy = get_sub_field('preview_copy');
$main_copy = get_sub_field('main_copy');

$slide_id = rand(999, 9999999);

?>
          
          

<div>
  <h2><?= $header; ?></h2>

  <?= $preview_copy; ?>
  
  <span id="show-<?= $slide_id; ?>">Read more </span>
  <span id="hide-<?= $slide_id; ?>"style="display: none; opacity:0.5">Show less</span>

  <div id="slide-<?= $slide_id; ?>">
    <?= $main_copy; ?>
     <?php include('call_to_action.php'); ?>
  </div>

 
  
</div>

          
 

