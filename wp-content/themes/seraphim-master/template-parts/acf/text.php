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

$content_alignment = get_sub_field('content_alignment'); // 0 : Left, 1 : Centre, 2 : Right

$alignment_class = '';
if ( $content_alignment == 1 ) {
	$alignment_class = ' text-center';
} elseif ( $content_alignment == 2 ) {
	$alignment_class = ' text-end';
}
?>

<style>
.copyCon.text-center {
    text-align: center;
}
.copyCon.text-end {
    text-align: right;
}
</style>

<div class="container">

    <div class="copyCon <?php echo $main_copy_size; if ($contained_block == "yes"){echo" containedText";} if ($background_colour){echo " bg" . $background_colour;} if ($block_height == "short"){echo" shortHeight";} echo $alignment_class; ?>">
      <?php if ($header) { ?><h2 class="<?php echo $header_size; ?>"><?= $header; ?></h2><?php } ?>
      <?= $main_copy; ?>
      <?php
        get_template_part('template-parts/acf/call_to_action', 'none');
      ?>

    </div>
</div>

          
 

