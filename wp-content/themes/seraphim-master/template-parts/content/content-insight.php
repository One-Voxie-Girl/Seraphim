<?php

/**
 * Template part for displaying portfolio items
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 */


$title = get_the_title();
$tagline = get_field( 'tagline' );
$insight_file = get_field( 'insight_file' );
$download_link = $insight_file['url'];
$associated_companies = get_field( 'associated_companies' );
$insight_type = get_the_terms( get_the_ID(), 'insight-type' );

?>



