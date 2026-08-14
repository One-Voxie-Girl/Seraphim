<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}	
	
$hand_position = get_sub_field('hand_position');
$header_hand = get_sub_field('header_hand');

?>

<div>

    <div>

    <div>

        <?php if ($hand_position == "right") { ?>
            
            <span value="-15">
            <img src="/wp-content/themes/muc23/src/img/header_hand_top.svg" />
            </span>

        <?php } else if ($hand_position == "left") { ?>

            <span value="15">
            <img src="/wp-content/themes/muc23/src/img/single_hand_bottom_left.svg" />
            </span>

        <?php } ?>

    </div>

    </div>

</div>




