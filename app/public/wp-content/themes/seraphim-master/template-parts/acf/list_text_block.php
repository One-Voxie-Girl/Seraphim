<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}	
	
// Vars
$colour = get_sub_field('colour');

?>


<?php
$x = 0;
$count = 1;
// Check rows existexists.
if( have_rows('list_text') ):

    // Loop through rows.
    while( have_rows('list_text') ) : the_row();

    $header = get_sub_field('header');
    $main_copy = get_sub_field('main_copy');
  
    ?>
    <div class="col-12 col-md-8">
      <div>
        
        

        <div>
          <h3><?= $header; ?></h3>
          <?= $main_copy; ?>
          <?php include('call_to_action.php'); ?>
        </div>

        <div>
          <div><?= $count; $count++; ?>.</div>
          <div></div>
        </div>

      </div>
    </div>
    <?php
    // End loop.

    if ($x == 1 ) { $x = 0; } else {$x++;}
    endwhile;

// No value.
else :
    // Do something...
endif;

?>


            
