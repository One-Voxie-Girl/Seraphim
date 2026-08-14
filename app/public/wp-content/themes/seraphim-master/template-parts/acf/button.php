<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}	
	
$button_text = get_sub_field('button_text');
$button_link = get_sub_field('button_link');
$cta_align = get_sub_field('cta_align');
$button_type = get_sub_field('button_type');

?>

<?php if ($button_link != "") { ?>

    <div class="container">
        <div class="row">
            <div class="col-7">
                <div class=" <?php echo $cta_align . "Align"; ?>">

                    <a href="<?= $button_link['url']; ?>" rel="bookmark">
                        <button type="button" class="btn-<?php echo $button_type; ?>">
                            <?= $button_text; ?> &gt;&gt;
                        </button>
                    </a>

                </div>
            </div>
        </div>
    </div>



<?php } ?>
