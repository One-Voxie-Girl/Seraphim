<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}	
	
// Vars
$email = get_field('email_address', 'option');
$phone1 = get_field('phone_number_part_1', 'option');
$phone2 = get_field('phone_number_part_2', 'option');
$phone3 = get_field('phone_number_part_3', 'option');

$linkedin = get_field('linkedin', 'option');
$instagram = get_field('instagram', 'option');

// Vars
$header = get_sub_field('header');
$main_copy = get_sub_field('main_copy');

$show_socials = get_sub_field('show_socials');


?>
          
          

<div>

    <h2><?= $header; ?></h2>
    <?= $main_copy; ?>

    <h3 id="contactEmail"></h3>
    <h3 id="contactPhone"></h3>
    
    <?php if ($show_socials == 'yes') { ?>
   
      <a href="<?= $instagram; ?>" target="_blank" rel="noreferrer">
        <img src="<?=get_template_directory_uri();?>/src/img/instagram_black.svg" alt="instagram" />
      </a>
      
      <a href="<?= $linkedin; ?>" target="_blank" rel="noreferrer">
        <img src="<?=get_template_directory_uri();?>/src/img/linkedin_black.svg" alt="linkedin" />
      </a>

    <?php } ?>

</div>



          
 

