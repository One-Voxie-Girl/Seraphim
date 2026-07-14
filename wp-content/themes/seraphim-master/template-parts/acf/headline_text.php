<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}	
	
// Vars
$tagline = get_sub_field('tagline');
$tagline_size = get_sub_field('tagline_size');

$headline = get_sub_field('headline');
$headline_size = get_sub_field('headline_size');

$subtext = get_sub_field('subtext');
$subtext_size = get_sub_field('subtext_size');

$contained_block = get_sub_field('contained_block');
$background_colour = get_sub_field('background_colour');
$block_height = get_sub_field('block_height');
?>


<div class="copyCon <?php echo $subtext_size;
if ($contained_block == "yes") {
    echo " containedText";
}
if ($background_colour) {
    echo " bg" . $background_colour;
}
if ($block_height == "short") {
    echo " shortHeight";
} ?>">
    <div class="container">
        <div class="row">
            <div class="col-7">
                <?php if ($tagline) { ?><h2
                    class="title prefix <?php echo $tagline_size; ?>"><?= $tagline; ?></h2><?php } ?>
                <?php if ($headline) { ?><h1 class="<?php echo $headline_size; ?>"><?= $headline; ?></h1><?php } ?>
                <?php if ($subtext) { ?><p class="<?php echo $subtext_size; ?>"><?= $subtext; ?></p><?php } ?>
            </div>
        </div>
    </div>
    <?php
    get_template_part('template-parts/acf/call_to_action', 'none');
    ?>

</div>
 

