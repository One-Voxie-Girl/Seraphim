<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}	
	
// Vars
$portal_id = get_sub_field('portal_id');
$form_id = get_sub_field('form_id');

?>

<div>

	<script charset="utf-8" type="text/javascript" src="//js.hsforms.net/forms/embed/v2.js"></script>
	<script>
	hbspt.forms.create({
		region: "na1",
		formId: "<?= $form_id; ?>",
		portalId: "<?= $portal_id; ?>"
	});
	</script>
	
	
	
</div>
 