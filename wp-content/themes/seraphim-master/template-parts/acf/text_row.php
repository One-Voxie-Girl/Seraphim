<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Vars
$background_colour = get_sub_field('background_colour');

$columns = [];
for ($i = 1; $i <= 4; $i++) {
    $headline = get_sub_field('column_' . $i . '_headline');
    $subtext = get_sub_field('column_' . $i . '_subtext');
    if ($headline || $subtext) {
        $columns[] = [
            'headline' => $headline,
            'subtext' => $subtext
        ];
    }
}

// Count columns with content
$column_count = count($columns);

// Calculate column class based on number of columns
$col_class = 'col-12';
if ($column_count == 4) {
    $col_class = 'col-3';
} elseif ($column_count == 3) {
    $col_class = 'col-4';
} elseif ($column_count == 2) {
    $col_class = 'col-6';
}
?>

<div class="textRowCon<?php
if ($background_colour) {
    echo " bg" . strtolower($background_colour);
} ?>">
    <div class="container container-text-row">
        <div class="row text-columns-row">
            <?php
            $current_index = 0;
            foreach ($columns as $column) {
                $current_index++;
                $has_divider = ($current_index < $column_count) ? ' has-divider' : '';
                ?>
                <div class="<?php echo $col_class . $has_divider; ?>">
                    <div class="column-content">
                        <?php if ($column['headline']) : ?>
                            <h3><?= $column['headline']; ?></h3>
                        <?php endif; ?>
                        <?php if ($column['subtext']) : ?>
                            <div class="subtext"><?= $column['subtext']; ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <?php
    get_template_part('template-parts/acf/call_to_action', 'none');
    ?>
</div>
