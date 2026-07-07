<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}	
	
$button_text = get_sub_field('button_text');
$button_link = get_sub_field('button_link');
$cta_align = get_sub_field('cta_align');

?>
                    
<?php if ($button_link != "") { ?>
	
<div class="call_to_action <?php echo $cta_align . "Align"; ?>">

  <a href="<?= $button_link['url']; ?>" rel="bookmark" class="cta-line">
    <span><?= $button_text; ?></span>
  </a>

</div>

		
<?php } ?>

