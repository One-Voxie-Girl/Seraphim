<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
$text=get_sub_field('text');
$content_alignment = get_sub_field('content_alignment'); // 0 : Left, 1 : Centre, 2 : Right

$alignment_class = '';
if ($content_alignment == 1) {
    $alignment_class = ' text-center';
} elseif ($content_alignment == 2) {
    $alignment_class = ' text-end';
}
?>
<style>
.info-tag-wrapper {
    display: flex;
}
.info-tag-wrapper.text-center {
    justify-content: center;
}
.info-tag-wrapper.text-end {
    justify-content: flex-end;
}
.info-tag {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #FFFFFF;
    border: 1px solid #00AA50;
    padding: 6px 16px;
    border-radius: 100px;
    line-height: 1;
}
.info-tag::before {
    content: "";
    width: 4px;
    height: 4px;
    background: #00AA50;
    border-radius: 50%;
}
</style>
<div class="info-tag-wrapper<?php echo $alignment_class; ?>">
    <div class="info-tag"><?php echo esc_html($text); ?></div>
</div>

