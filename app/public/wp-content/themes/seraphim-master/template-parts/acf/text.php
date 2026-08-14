<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}	
	
// Vars
$header = get_sub_field('header');
$header_size = get_sub_field('header_size');
$main_copy = get_sub_field('main_copy');
$main_copy_size = get_sub_field('main_copy_size');
$contained_block = get_sub_field('contained_block');
$background_colour = get_sub_field('background_colour');
$block_height = get_sub_field('block_height');
?>
          
          

<div class="copyCon <?php echo $main_copy_size; if ($contained_block == "yes"){echo" containedText";} if ($background_colour){echo " bg" . $background_colour;} if ($block_height == "short"){echo" shortHeight";}?>">
  <?php if ($header) { ?><h2 class="<?php echo $header_size; ?>"><?= $header; ?></h2><?php } ?>
  <?= $main_copy; ?>
  <?php
    get_template_part('template-parts/acf/call_to_action', 'none');
  ?>
  
</div>

          
 

