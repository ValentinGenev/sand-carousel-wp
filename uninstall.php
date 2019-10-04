<?php
/**
 * @package Sand_Carousel_WP
 * @version 1.0.0
 */

defined('WP_UNINSTALL_PLUGIN') or die('Hey! Get out!');

$slides = get_posts(array(
	'post_type'		=> 'slide',
	'numberposts'	=> -1,
));
if (!empty($slides)) {
	foreach ($slides as $slide) {
		wp_delete_post($slide->ID, true);
	}
}

delete_custom_taxonomy('slides_group');
 
/***********************************************************
 * Clears the database from the custom
 * taxonomy on plugin uninstall:
 ***********************************************************/
function delete_custom_taxonomy($taxonomy){
    global $wpdb;
 
    // SQL magic — gets the terms based on taxomony instead of get_terms()
    $terms_query =      "SELECT t.name, t.term_id 
                        FROM " . $wpdb->terms . " AS t
                        INNER JOIN " . $wpdb->term_taxonomy . " AS tt
                        ON t.term_id = tt.term_id
                        WHERE tt.taxonomy = '" . $taxonomy . "'";
 
    // Cleans the terms and the terms relationship DB emtries
    $terms = $wpdb->get_results($terms_query);
    foreach ($terms as $term) {
        wp_delete_term($term->term_id, $taxonomy);
        $wpdb->query("DELETE from wp_term_relationships WHERE term_taxonomy_id =" . $term->term_id);
    }
 
    // Cleans the taxonomy DB entry
    $wpdb->query("DELETE from wp_term_taxonomy WHERE taxonomy = '" . $taxonomy . "'");
}