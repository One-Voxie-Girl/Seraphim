<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}	
	
// Vars
$quote = get_sub_field('quote');
$quote_attribution = get_sub_field('quote_attribution');

?>
          
          

<div class="quoteCon">
  <div class="quote">
    <?= $quote; ?>
  </div>
   <div class="marks">“”</div>
  <div class="attribution">
    <?= $quote_attribution; ?>  
  </div>
</div>

          
 

